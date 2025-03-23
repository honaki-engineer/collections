<?php 
namespace App\Service\Admin;

use App\Models\Collection;
use App\Models\CollectionImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Drivers\Gd\Driver;


class CollectionService
{
  // ------ 共通 ------
  public static function isPublicLabel($collection) {
    $collection->is_public_label = 
    $collection->is_public ? '公開' : '非公開'; // trueが１、falseが0

    return $collection;
  }

  public static function positionLabel($collection) {
    $collection->position_label =
    match($collection->position) {
        0 => 'デフォルト',
        1 => '1ページ目',
        2 => 'topページ',
    };

    return $collection;
  }

  public static function getCollectionImage($id) {
    $collection = Auth::user()
    ->collections()
    ->with(['collectionImages' => function ($query) { // collection_imageの取得時に追加のクエリを実行(カスタマイズ可能)
        $query->orderBy('position', 'asc'); // `position` 昇順でソート
    }])
    ->findOrFail($id);

    return $collection;
  }

  // ------ store ------
  public static function storeRequest($request) {
    $collection = Collection::create([
      'title' => $request->title,
      'description' => $request->description,
      'url_qiita' => $request->url_qiita,
      'url_webapp' => $request->url_webapp,
      'url_github' => $request->url_github,
      'is_public' => $request->is_public,
      'position' => $request->position,
      'user_id' => Auth::id(),
    ]);

    return $collection;
  }

  public static function storeRequestImage($request, $collection)
  {
      $orderData = json_decode($request->input('image_order'), true) ?? [];
      $sessionTmpImages = $request->input('tmp_images');
      $sessionFileNames = $request->input('session_file_names');

      // ✅ `ImageManager`を`gd`ドライバー指定で作成
      $manager = new ImageManager(new Driver());

      // 🔹 `image_order` にある画像の最大 position を取得
      $existingPositions = array_column($orderData, 'position');
      $maxPosition = !empty($existingPositions) ? max($existingPositions) + 1 : 0;

      // 🔹 セッション画像の保存（追加画像がある場合も処理）
      if($sessionTmpImages) {
        foreach($sessionTmpImages as $index => $tmpImage) {
            // ✅ 'image_path'保存準備
            $imageName = str_replace('tmp/', '', $tmpImage);

            // ファイル名取得
            $parts = explode("_", $imageName);
            $fileName = end($parts);

            // ✅ 'position'取得
            $order = collect($orderData)->first(fn($item) => str_ends_with($item['uniqueId'], $fileName));
            $position = $order ? $order['position'] : $maxPosition++;

            // ✅ Storage画像保存
            $newPath = str_replace('tmp/', 'collection_images/', $tmpImage);
            Storage::disk('public')->move($tmpImage, $newPath);

            // ✅ DB に保存
            CollectionImage::create([
                'collection_id' => $collection->id,
                'image_path' => $imageName,
                'position' => $position
            ]);
        }
    }

    // 🔹 アップロード画像の保存（セッション画像の有無を問わず処理する）
    if($request->hasFile('image_path')) {
      $uploadedFiles = $request->file('image_path');

      foreach($uploadedFiles as $imagePath) {
          $fileName = trim($imagePath->getClientOriginalName()); // アップロードファイル名取得
          $order = collect($orderData)->first(fn($item) => str_ends_with($item['uniqueId'], $fileName));
          $position = $order ? $order['position'] : $maxPosition++;
          // $imageName = time() . '_' . uniqid() . '.' . $imagePath->getClientOriginalExtension(); // テーブル保存用
          $baseName = pathinfo($fileName, PATHINFO_FILENAME); // 拡張子を除いたファイル名
          $extension = strtolower($imagePath->extension()); // 元の拡張子
          $imageName = time() . uniqid() . '_' . $baseName . '.' . $extension;

          // ✅ 画像に合わせた拡張子選択
          switch($extension){
            case 'jpg':
            case 'jpeg':
                $encoder = new JpegEncoder(75); // JPG / JPEG → 非可逆圧縮
                break;
            case 'png':
                $encoder = new PngEncoder(9); // PNG → 可逆圧縮(高圧縮率)
                break;
            case 'webp':
                $encoder = new WebpEncoder(80); // WebP → 高圧縮率
                break;
            case 'avif':
                $encoder = new JpegEncoder(75); // AVIF → JPEGへ変換(互換性のため)
                break;
            default:
                throw new \Exception("対応していない画像フォーマットです: " . $extension); // 未対応形式はエラー
          }

          // ✅ 画像を圧縮
          $compressedImage = $manager->read($imagePath->getRealPath())->encode($encoder);

          // ✅ 圧縮画像を保存
          Storage::disk('public')->put('collection_images/' . $imageName, (string)$compressedImage);

          // ✅ DB に保存
          CollectionImage::create([
              'collection_id' => $collection->id,
              'image_path' => $imageName,
              'position' => $position
          ]);
      }
    }
  }
  
  // ------ update ------
  public static function updateRequest($collection, $request) {
    $collection->title = $request->title;
    $collection->description = $request->description;
    $collection->url_qiita = $request->url_qiita;
    $collection->url_webapp = $request->url_webapp;
    $collection->url_github = $request->url_github;
    $collection->is_public = $request->is_public;
    $collection->position = $request->position;
    $collection->save();
  }

  public static function deleteRequestImage($request) {
    if($request->delete_images) {
      foreach($request->delete_images as $imageId) {
          $image = CollectionImage::find($imageId);
          if($image) {
              Storage::delete('public/collection_images/' . $image->image_path);
              $image->delete();
          }
      }
    }
  }

  public static function updateRequestImage($request, $collection) {
    // ✅ 追加画像あり
    if($request->hasFile('image_path')) {
      // 🔹 初期設定
      $uploadedFiles = $request->file('image_path');
      $orderData = json_decode($request->input('image_order'), true) ?? [];
      $imageIdMap = [];
      $manager = new ImageManager(new Driver()); // ImageManager初期化
      
      // 🔹 圧縮、保存処理
      foreach($uploadedFiles as $index => $imagePath) {
        // 🔸 追加画像のファイル名を生成
        $fileName = trim($imagePath->getClientOriginalName()); // ファイル名
        $baseName = pathinfo($fileName, PATHINFO_FILENAME); // 拡張子を除いたファイル名
        $extension = strtolower($imagePath->extension()); // 元の拡張子
        $imageName = time() . uniqid() . '_' . $baseName . '.' . $extension;

        // 🔸 エンコーダー選択
        switch($extension) {
            case 'jpg':
            case 'jpeg':
                $encoder = new JpegEncoder(75);
                break;
            case 'png':
                $encoder = new PngEncoder(9);
                break;
            case 'webp':
                $encoder = new WebpEncoder(80);
                break;
            case 'avif':
                $encoder = new JpegEncoder(75);
                break;
            default:
                throw new \Exception("対応していない画像フォーマットです: " . $extension);
        }

        // 🔸 画像圧縮処理
        $compressedImage = $manager->read($imagePath->getRealPath())->encode($encoder);

        // 🔸 保存
        \Storage::disk('public')->put("collection_images/{$imageName}", (string) $compressedImage);

        // 🔸 追加画像のposition確定
        $order = (!empty($fileName)) ? collect($orderData)->first(fn($item) => str_starts_with($item['uniqueId'], $fileName)) : null;

        // データベースに保存
        $image = CollectionImage::create([
          'collection_id' => $collection->id,
          'image_path' => $imageName,
          'position' => $order ? $order['position'] : 0
        ]);
      }

      // 🔹 既存画像position更新
      if(isset($orderData)) {
        foreach($orderData as $order) {
          // 既存画像か新規画像かを判、既存画像ならテーブルidがある、新規の場合は'null'が入ってる
          $imageId = ($order['id'] === "null") ? null : $order['id']; // JavaScript側で文字列化のため → hiddenInput.value = JSON.stringify(imageOrder); // オブジェクト配列を文字列化 | valueは文字列しかセットできないので、オブジェクトを文字列にする必要がある
  
            if ($imageId !== null) {
              CollectionImage::where('id', $imageId)
              ->update(['position' => $order['position']]);
            }
        }
      }
    }


    // ✅ 追加画像なし、既存position更新
    if(!$request->hasFile('image_path') && $request->filled('image_order')) {
      $imageOrders = json_decode($request->input('image_order'), true); // JSONを配列に変換
      if(is_array($imageOrders)) {
          foreach ($imageOrders as $order) {
              CollectionImage::where('id', $order['id'])
              ->update(['position' => $order['position']]);
          }
      }
    }
  }
}
?>