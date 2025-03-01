<?php 
namespace App\Service\Admin;

use App\Models\Collection;
use App\Models\CollectionImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
    if ($request->hasFile('image_path')) {
      foreach ($request->file('image_path') as $imagePath) {
          $imageName = null;

          $imageName = time() . '_' . uniqid() . '.' . $imagePath->getClientOriginalExtension();
          $imagePath->storeAs('public/collection_images', $imageName);

          // データベースに保存
          CollectionImage::create([
              'collection_id' => $collection->id, // 作成したコレクションのIDを設定
              'image_path' => $imageName, // 公開URL用に変換
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

  public static function updateRequestImageOrder($request) {
    if($request->image_order) {
      $imageOrders = json_decode($request->image_order, true); // JSONを配列に変換
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