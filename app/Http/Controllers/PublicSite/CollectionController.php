<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;
use App\Service\PublicSite\CollectionService;
use App\Models\TechnologyTag;
use App\Models\FeatureTag;


class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 🔹 検索requestデータ
        $searches = [
            'technology_tag_id' => $request->search_technology_tag_id,
            'feature_tag_id' => $request->search_feature_tag_id
        ];

        // 🔹 データ取得
        $collections = Collection::where('is_public', 1)
        ->search($searches)
        ->orderBy('created_at', 'desc')
        ->with([
            'collectionImages' => fn($query) => $query->orderBy('position', 'asc'),
          ])
        ->paginate(6);

        // 🔹 検索フォームの選択肢データ
        $technologyTags = TechnologyTag::get();
        $featureTags = FeatureTag::get();

        // 🔹 image_pathの最初を取得
        foreach($collections as $collection) {
            $collection->firstImage = optional($collection->collectionImages->first())->image_path; // optional(...) = 	nullでも安全にアクセス(エラーにならない)
        }

        return view('public_site.index', compact('collections', 'technologyTags', 'featureTags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // コレクション&画像&技術タグのテーブルを取得
        $collection = CollectionService::getCollectionWithRelations($id);

        // メイン画像
        $firstImage = $collection->collectionImages->first();
        $mainImagePath = $firstImage ? asset('storage/collection_images/' . $firstImage->image_path) : asset('storage/collection_images/noImage.jpg');

        return view('public_site.show', compact('collection', 'mainImagePath'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
