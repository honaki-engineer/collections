<?php 
namespace App\Service\Admin;

use Illuminate\Support\Facades\Auth;
use App\Models\TechnologyTag;


class TagService
{
  // ⭐️ 共通 --------------------------------------------------
  // ✅ タグ一覧/新規作成遷移の際に、作ったセッションを削除
  public static function forgetCollectionFormInput() {
      if(session()->has('collection.form_input')) {
        session()->forget('collection.form_input');
      }
    
    return;
  }

  // ⭐️ 技術タグ - index ---------------------------------------
  // ✅ ログインユーザーの技術タグをtech_type昇順で取得してadmin.collections.createに渡す処理
  public static function getPaginatedTechnologyTags() {
    $technologyTags = Auth::user()
    ->technologyTags()
    ->orderBy('tech_type', 'asc')
    ->paginate(10);

    return $technologyTags;
  }

  // ⭐️ 技術タグ - store ---------------------------------------
  // ✅ 技術タグstore
  public static function storeRequestTechnologyTag($request, $names) {
      foreach($names as $name) {
          // 🔹 スペース削除したタグ名
          $trimmedName = trim($name); // スペース削除したタグ名

          // 🔹 store
          if(!empty($trimmedName)) {
              TechnologyTag::firstOrCreate([ // firstOrCreate = 重複時保存しない
                  'name' => $trimmedName,
              ],
              [ // 新規作成時に入れる値
                  'user_id' => Auth::id(),
                  'tech_type' => $request->tech_type,
              ]);
          }
      }

      return;
  }


  // ✅ ログインユーザーの技術タグをtech_type昇順で取得してadmin.collections.createに渡す処理
  public static function getTechnologyTagsSorted() {
      $technologyTags = Auth::user()
          ->technologyTags()
          ->orderBy('tech_type', 'asc')
          ->get();

      return $technologyTags;
  }

  // ✅ 技術タグのセレクトボックス内テーマ
  public static function appendTypeLabelsToTechnologyTags() {
      return [
          0 => '言語',
          1 => 'フレームワーク',
          2 => 'ツール',
      ];
  }

  // ⭐️ 技術タグ - update --------------------------------------
  public static function updateTechnologyTag($technologyTag, $request) {
    $technologyTag->name = $request->name;
    $technologyTag->tech_type = $request->tech_type;
    $technologyTag->save();
    return;
  }
}

?>