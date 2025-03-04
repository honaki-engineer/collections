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

  public static function storeRequestImage($request, $collection) {
    $imageIdMap = []; // 一時ID → DBのIDのマッピング

    if($request->hasFile('image_path')) {
        $uploadedFiles = $request->file('image_path');
        $orderData = json_decode($request->input('image_order'), true);

        foreach($uploadedFiles as $index => $imagePath) {
            $fileName = trim($imagePath->getClientOriginalName()); // ファイル名
            // first() = 条件に合致する最初の要素を返す
            // str_starts_with($item['uniqueId'], $fileName) = uniqueIdがfileNameで始まるかどうかをチェック
            $order = collect($orderData)->first(fn($item) => str_starts_with($item['uniqueId'], $fileName));

            // 画像を保存
            $imageName = time() . '_' . uniqid() . '.' . $imagePath->getClientOriginalExtension();
            $imagePath->storeAs('public/collection_images', $imageName);

            // データベースに保存
            $image = CollectionImage::create([
                'collection_id' => $collection->id,
                'image_path' => $imageName,
                'position' => $order ? $order['position'] : 0
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
    if($request->hasFile('image_path')) {
      // 現在のコレクションの最大position値を取得 ?? 既存の画像の最大 position 値を取得
      $maxPosition = CollectionImage::where('collection_id', $collection->id)->max('position') ?? 0;

      foreach($request->file('image_path') as $imagePath) {
          $imageName = null;

          $imageName = time() . '_' . uniqid() . '.' . $imagePath->getClientOriginalExtension();
          $imagePath->storeAs('public/collection_images', $imageName);

          // データベースに保存
          CollectionImage::create([
              'collection_id' => $collection->id,
              'image_path' => $imageName,
              'position' => ++$maxPosition, // maxPositionを1増やし、新しいpositionを割り当てる(例：既存の最大positionが2なら、新規画像は3)
          ]);
      }
    }
  }

  public static function updateRequestImageOrder($request, $collection) {
    // --- 追加画像あり
    if($request->hasFile('image_path')) {
      $uploadedFiles = $request->file('image_path');
      $orderData = json_decode($request->input('image_order'), true);
      $imageIdMap = [];
      
      foreach($uploadedFiles as $index => $imagePath) {
        // 新しい画像のファイル名を生成
        $imageName = time() . '_' . uniqid() . '.' . $imagePath->getClientOriginalExtension();
        $imagePath->storeAs('public/collection_images', $imageName);

        $fileName = trim($imagePath->getClientOriginalName()); // ファイル名

        $order = collect($orderData)->first(fn($item) => str_starts_with($item['uniqueId'], $fileName));
        
        // データベースに保存
        $image = CollectionImage::create([
          'collection_id' => $collection->id,
          'image_path' => $imageName,
          'position' => $order ? $order['position'] : 0
        ]);
        
        // `uniqueId` を `image_id` にマッピング
        if(isset($orderData[$index]['uniqueId'])) {
          $imageIdMap[$orderData[$index]['uniqueId']] = $image->id;
        }
      }

      // 並び順の更新
      if(isset($orderData)) {
        foreach($orderData as $order) {
          // 既存画像か新規画像かを判断
          $imageId = $order['id']; // 既存画像ならテーブルidがある、ない場合は'null'が入ってる
  
            if ($imageId !== 'null') {
              CollectionImage::where('id', $imageId)
              ->update(['position' => $order['position']]);
            }
          }
      }
    }


    // --- 追加画像なし、既存position変更
    if(!$request->hasFile('image_path') && $request->image_order) {
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