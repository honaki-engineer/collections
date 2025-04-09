<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Service\PublicSite\CollectionService;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 🔹 データ取得
        $collections = Collection::where('is_public', 1)
        ->orderBy('created_at', 'desc')
        ->with([
            'collectionImages' => fn($query) => $query->orderBy('position', 'asc'),
          ])
        ->paginate(6);

        // 🔹 image_pathの最初を取得
        foreach($collections as $collection) {
            $collection->firstImage = optional($collection->collectionImages->first())->image_path; // optional(...) = 	nullでも安全にアクセス(エラーにならない)
        }

        return view('public_site.index', compact('collections'));
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
        $collection = Collection::with([
            'collectionImages' => fn($query) => $query->orderBy('position', 'asc'),
            'technologyTags' => fn($query) => $query->orderBy('tech_type', 'asc'),
            'featureTags' => fn($query) => $query,
        ])
        ->findOrFail($id);

        // 「公開種別」日本語化
        $collection->is_public_label = 
        $collection->is_public ? '公開' : '非公開'; // trueが１、falseが0

        // 「表示優先度」日本語化
        $collection->position_label =
        match($collection->position) {
            0 => 'デフォルト',
            1 => '1ページ目',
            2 => 'topページ',
        };

        return view('public_site.show', compact('collection'));
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
