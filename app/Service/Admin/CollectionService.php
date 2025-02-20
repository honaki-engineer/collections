<?php 
namespace App\Service\Admin;

class CollectionService
{
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
}
?>