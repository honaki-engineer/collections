<?php 
namespace App\Service\Admin;

use Illuminate\Support\Facades\Auth;


class TagService
{
  //  ----- 共通 -----
  public static function forgetCollectionFormInput() {
      if(session()->has('collection.form_input')) {
        session()->forget('collection.form_input');
      }
    
    return;
  }

  //  ----- 技術タグ -----
  // 🔹 ログインユーザーの技術タグをtech_type昇順で取得してadmin.collections.createに渡す処理
  public static function getTechnologyTagsSorted() {
      $technologyTags = Auth::user()
          ->technologyTags()
          ->orderBy('tech_type', 'asc')
          ->get();

      return $technologyTags;
  }

  // 🔹 技術タグのセレクトボックス内テーマ
  public static function appendTypeLabelsToTechnologyTags() {
      return [
          0 => '言語',
          1 => 'フレームワーク',
          2 => 'ツール',
      ];
  }

  // 🔹 update
  public static function updateTechnologyTag($technologyTag, $request) {
    $technologyTag->name = $request->name;
    $technologyTag->tech_type = $request->tech_type;
    $technologyTag->save();
    return;
  }
}

?>