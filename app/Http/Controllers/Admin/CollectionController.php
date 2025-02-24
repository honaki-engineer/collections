<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Collection;
use App\Http\Controllers\Controller;
use App\Service\Admin\CollectionService;
use App\Http\Requests\CollectionRequest;
use App\Models\CollectionImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 検索準備
        $searches = [
            'is_public' => $request->search_is_public,
            'position'  => $request->search_position
        ];

        /** @var \App\Models\User $user */
        $collections = Auth::user()
        ->collections()
        ->search($searches)
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        $collections->setCollection( // ②`Paginator`に戻す
            $collections->getCollection() // ①Collectionだけ取得し、変換する
            ->transform(function($collection) {
                // 「公開種別」日本語化
                CollectionService::isPublicLabel($collection);
                // 「表示優先度」日本語化
                CollectionService::positionLabel($collection);
        
                return $collection;
            })
        );

        return view('admin.collections.index', compact('collections'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.collections.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CollectionRequest $request)
    {
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

        // 画像を保存
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

        return to_route('collections.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $collection = Auth::user()
        ->collections()
        ->with('collection_image')
        ->findOrFail($id);

        // 「公開種別」日本語化
        CollectionService::isPublicLabel($collection);
        // 「表示優先度」日本語化
        CollectionService::positionLabel($collection);

        return view('admin.collections.show', compact('collection'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $collection = Auth::user()
        ->collections()->findOrFail($id);

        return view('admin.collections.edit', compact('collection'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CollectionRequest $request, $id)
    {
        $collection = Auth::user()
        ->collections()->findOrFail($id);

        $collection->title = $request->title;
        $collection->description = $request->description;
        $collection->url_qiita = $request->url_qiita;
        $collection->url_webapp = $request->url_webapp;
        $collection->url_github = $request->url_github;
        $collection->is_public = $request->is_public;
        $collection->position = $request->position;
        $collection->save();

        return to_route('collections.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $collection = Auth::user()
        ->collections()->findOrFail($id);

        $collection->delete();

        return to_route('collections.index');
    }
}
