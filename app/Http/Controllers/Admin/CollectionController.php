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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

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
    // 🔹 画像以外のデータを保存
    $collection = CollectionService::storeRequest($request);

    // 🔹 画像を保存（通常アップロード & セッション画像）
    if ($request->hasFile('image_path') || !empty($request->input('session_image_src'))) {
        CollectionService::storeRequestImage($request, $collection);
    }

    // 🔹 バリデーションエラーがなければセッション画像を削除
    Session::forget('image_src');
    Session::forget('file_names');
    Session::forget('image_order');

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
        // ログインユーザーの(コレクション&コレクション画像)テーブルを取得
        $collection = CollectionService::getCollectionImage($id);

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
        // ログインユーザーの(コレクション&コレクション画像)テーブルを取得
        $collection = CollectionService::getCollectionImage($id);

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
        ->collections()
        ->with('collection_image')
        ->findOrFail($id);

        // save(画像以外)
        CollectionService::updateRequest($collection, $request);

        // 削除リクエストがある場合、該当画像を削除
        CollectionService::deleteRequestImage($request);

        // 追加画像保存、既存画像position変更
        CollectionService::updateRequestImage($request, $collection);

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


    // ✅ 特定のセッション画像を削除するメソッド
    // public function removeSessionImage(Request $request)
    // {
    //     $imageSrc = $request->input('image_src');

    //     // セッションから現在の画像データを取得
    //     $sessionImages = Session::get('image_src', []);
    //     $sessionFileNames = Session::get('file_names', []);

    //     // 削除対象のインデックスを検索
    //     $index = array_search($imageSrc, $sessionImages);

    //     if ($index !== false) {
    //         // 配列から削除
    //         unset($sessionImages[$index]);
    //         unset($sessionFileNames[$index]);

    //         // 配列のインデックスをリセットしてセッションを更新
    //         Session::put('image_src', array_values($sessionImages));
    //         Session::put('file_names', array_values($sessionFileNames));
    //     }

    //     return response()->json(["message" => "セッション画像が削除されました"]);
    // }
}