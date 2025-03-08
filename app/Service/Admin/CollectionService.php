<?php 
namespace App\Service\Admin;

use App\Models\Collection;
use App\Models\CollectionImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
    ->with(['collection_image' => function ($query) { // collection_imageの取得時に追加のクエリを実行(カスタマイズ可能)
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

  // public static function storeRequestImage($request, $collection) {
  //   if($request->hasFile('image_path')) {
  //       $uploadedFiles = $request->file('image_path');
  //       $orderData = json_decode($request->input('image_order'), true);

  //       foreach($uploadedFiles as $index => $imagePath) {
  //           // 画像positionを設定する準備
  //           $fileName = trim($imagePath->getClientOriginalName()); // ファイル名
  //           $order = collect($orderData)->first(fn($item) => str_starts_with($item['uniqueId'], $fileName)); // first() = 条件に合致する最初の要素を返す | str_starts_with($item['uniqueId'], $fileName) = uniqueIdがfileNameで始まるかどうかをチェック

  //           // 画像を保存
  //           $imageName = time() . '_' . uniqid() . '.' . $imagePath->getClientOriginalExtension();
  //           $imagePath->storeAs('public/collection_images', $imageName);

  //           // データベースに保存
  //           $image = CollectionImage::create([
  //               'collection_id' => $collection->id,
  //               'image_path' => $imageName,
  //               'position' => $order ? $order['position'] : 0
  //           ]);
  //       }
  //   }
  // }
  // public static function storeRequestImage($request, $collection) {
  //   if($request->hasFile('image_path')) {
  //       $uploadedFiles = $request->file('image_path');
  //       $orderData = json_decode($request->input('image_order'), true);

  //       foreach($uploadedFiles as $index => $imagePath) {
  //           // 画像のpositionを取得
  //           $fileName = trim($imagePath->getClientOriginalName());
  //           $order = collect($orderData)->first(fn($item) => str_starts_with($item['uniqueId'], $fileName));

  //           // 画像を `storage` に保存
  //           $imageName = time() . '_' . uniqid() . '.' . $imagePath->getClientOriginalExtension();
  //           $imagePath->storeAs('public/collection_images', $imageName);

  //           // DB に保存
  //           CollectionImage::create([
  //               'collection_id' => $collection->id,
  //               'image_path' => $imageName,
  //               'position' => $order ? $order['position'] : 0
  //           ]);
  //       }
  //   }
  // }
  public static function storeRequestImage($request, $collection)
  {
      $orderData = json_decode($request->input('image_order'), true) ?? [];
      $sessionImageSrc = json_decode($request->input('session_image_src'), true) ?? [];
  
      // dd(empty(json_decode($request->input('session_image_src'))));
      // 🔹 通常アップロードされた画像の保存
      // if($request->hasFile('image_path') && empty($request->input('session_image_src'))) {
      if($request->hasFile('image_path') && empty($sessionImageSrc)) {
          $uploadedFiles = $request->file('image_path');
  
          foreach ($uploadedFiles as $imagePath) {
              $fileName = trim($imagePath->getClientOriginalName());
              $order = collect($orderData)->first(fn($item) => str_starts_with($item['uniqueId'], $fileName));
  
              $imageName = time() . '_' . uniqid() . '.' . $imagePath->getClientOriginalExtension();
              $imagePath->storeAs('public/collection_images', $imageName);
  
              CollectionImage::create([
                  'collection_id' => $collection->id,
                  'image_path' => $imageName,
                  'position' => $order ? $order['position'] : 0
              ]);
          }
      }
  
      // 🔹 セッション画像の保存（通常アップロード時はスキップ）
      if($sessionImageSrc) {
          $sessionFileNames = json_decode($request->input('session_file_names'), true) ?? [];
  
          foreach($sessionImageSrc as $index => $base64Image) {
              $imageData = explode(',', $base64Image); // 「メタ情報」と「画像データ部分」に分割
              if (count($imageData) === 2) { // 正しく2つに分割されているかチェック
                  $decodedImage = base64_decode($imageData[1]);
                  
                  $fileName = $sessionFileNames[$index] ?? 'unknown';
                  $order = collect($orderData)->first(fn($item) => str_starts_with($item['uniqueId'], $fileName));

                  $extension = explode(';', explode('/', $imageData[0])[1])[0] ?? 'jpg'; // 拡張子の取得 | $imageData[0] = メタ情報部分(data:image/png;base64)
                  $imageName = time() . '_' . uniqid() . '.' . $extension;
                  Storage::disk('public')->put('collection_images/' . $imageName, $decodedImage);
                  
                  CollectionImage::create([
                      'collection_id' => $collection->id,
                      'image_path' => $imageName,
                      'position' => $order ? $order['position'] : 0
                  ]);
              }
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
    // --- 追加画像あり
    if($request->hasFile('image_path')) {
      // 初期設定
      $uploadedFiles = $request->file('image_path');
      $orderData = json_decode($request->input('image_order'), true);
      $imageIdMap = [];
      
      // 追加画像のループ
      foreach($uploadedFiles as $index => $imagePath) {
        // 追加画像のファイル名を生成、publicに保存
        $imageName = time() . '_' . uniqid() . '.' . $imagePath->getClientOriginalExtension();
        $imagePath->storeAs('public/collection_images', $imageName);

        // 追加画像のposition確定
        $fileName = trim($imagePath->getClientOriginalName()); // ファイル名
        $order = collect($orderData)->first(fn($item) => str_starts_with($item['uniqueId'], $fileName));
        
        // データベースに保存
        $image = CollectionImage::create([
          'collection_id' => $collection->id,
          'image_path' => $imageName,
          'position' => $order ? $order['position'] : 0
        ]);
      }

      // 既存画像position更新
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


    // --- 追加画像なし、既存position更新
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