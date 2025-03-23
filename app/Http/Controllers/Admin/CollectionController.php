<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Collection;
use App\Http\Controllers\Controller;
use App\Service\Admin\CollectionService;
use App\Http\Requests\StoreCollectionRequest;
use App\Http\Requests\UpdateCollectionRequest;
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
    public function store(StoreCollectionRequest $request)
    {
        // 🔹 画像以外のデータを保存
        $collection = CollectionService::storeRequest($request);

        // 🔹 画像を保存（通常アップロード & セッション画像）
        if($request->hasFile('image_path') || !empty($request->input('tmp_images'))) {
            CollectionService::storeRequestImage($request, $collection);
        }

        // 🔹 バリデーションエラーがなければセッション画像を削除
        Session::forget('tmp_images');
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
    public function update(UpdateCollectionRequest $request, Collection $collection)
    {
        $collection = Auth::user()
        ->collections()
        ->with('collectionImages')
        ->findOrFail($collection->id);

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
    public function removeSessionImage(Request $request)
    {
        $tmpImage = $request->input('tmp_image');

        // セッションから現在の画像データを取得
        $sessionTmpImages = Session::get('tmp_images', []);
        $sessionFileNames = Session::get('file_names', []);

        // `tmp/`の`/storage/`変換による影響を排除
        $tmpImage = str_replace("/storage/", "", $tmpImage);

        // 削除対象のインデックスを検索
        $index = array_search($tmpImage, $sessionTmpImages);

        if($index !== false) {
            // 配列から削除
            unset($sessionTmpImages[$index]);
            unset($sessionFileNames[$index]);

            // 配列のインデックスをリセットしてセッションを更新
            Session::put('tmp_images', array_values($sessionTmpImages));
            Session::put('file_names', array_values($sessionFileNames));

            // ストレージから物理削除
            if(Storage::disk('public')->exists($tmpImage)) {
                Storage::disk('public')->delete($tmpImage);
            }

            return response()->json(["message" => "セッション画像が削除されました"], 200);
        }

        return response()->json(["message" => "画像が見つかりません"], 400);
    }


    /**
     * セッションに保持している画像データをすべて削除（create画面から離れた時など）
     */
    public function clearSessionImages(Request $request)
    {
        // 一時保存された画像のパスを取得
        $tmpImages = Session::get('tmp_images', []);

        // ストレージ内の物理ファイルを削除
        foreach($tmpImages as $tmpImage) {
            if(Storage::disk('public')->exists($tmpImage)) {
                Storage::disk('public')->delete($tmpImage);
            }
        }

        // 一括でセッションから削除
        Session::forget('tmp_images');
        Session::forget('file_names');
        Session::forget('image_order');

        return response()->json(['message' => 'セッション画像を削除しました']);
    }

}