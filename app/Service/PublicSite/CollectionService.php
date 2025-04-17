<?php
namespace App\Service\PublicSite;

use App\Models\Collection;

class CollectionService
{
    // ⭐️ - show ----------------------------------------------
    // コレクション&画像&技術タグのテーブルを取得
    public static function getCollectionWithRelations($id)
    {
        $collection = Collection::with([
            'collectionImages' => fn($query) => $query->orderBy('position', 'asc'),
            'technologyTags' => fn($query) => $query->orderBy('tech_type', 'asc'),
            'featureTags' => fn($query) => $query,
        ])->findOrFail($id);

        return $collection;
    }
}

?>
