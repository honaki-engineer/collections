<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Collection;
use App\Models\CollectionImage;
use App\Models\TechnologyTag;
use App\Http\Controllers\Controller;
use App\Service\Admin\CollectionService;
use App\Service\Admin\TagService;
use App\Http\Requests\StoreCollectionRequest;
use App\Http\Requests\UpdateCollectionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;



use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\WebpEncoder;

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

        // 🔹 タグ一覧/新規作成遷移の際に、作ったセッションを削除
        TagService::forgetCollectionFormInput();

        return view('admin.collections.index', compact('collections'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 🔹 ログインユーザーの技術タグをtech_type昇順で取得してadmin.collections.createに渡す処理
        $technologyTags = TagService::getTechnologyTagsSorted();

        // 🔹 技術タグのセレクトボックス内テーマ
        $technologyTags->typeLabels = TagService::appendTypeLabelsToTechnologyTags();

        return view('admin.collections.create', compact('technologyTags'));
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
        // ログインユーザーの(コレクション&画像&技術タグ)テーブルを取得
        $collection = CollectionService::getCollectionWithRelations($id);

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
        // ログインユーザーの(コレクション&画像&技術タグ)テーブルを取得
        $collection = CollectionService::getCollectionWithRelations($id);

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


    // ✅ フォームの入力内容をセッションに保存して、技術タグ一覧ページへリダイレクトする処理
    public function storeSessionWithImage(Request $request)
    {
        // 🔹 初期値
        $formInput = $request->except('image_path');
        $imageOrderInput = [];
        // 🔹 セッション初期値
        $tmpImagePaths = session('tmp_images', []);
        $fileNames = session('file_names', []);
        $imageOrder = [];

        // 🔹 image_orderをマッピング形式に変換
        if($request->filled('image_order')) {
            $decoded = json_decode($request->input('image_order'), true);
            if(is_array($decoded)) {
                $imageOrderInput = collect($decoded)->keyBy('uniqueId')->toArray(); // $decodedをLaravelのCollectionに変換。keyBy('uniqueId')によって、各画像のデータを'uniqueId'をキーにしたマップ形式に変換。
            }
        }

        // 🔹 画像の読み込み、圧縮処理をするライブラリ(Intervention Image)の新しいインスタンス作成
        $manager = new ImageManager(new Driver());

        // 🔹 アップロード画像処理
        if($request->hasFile('image_path')) {
            foreach ($request->file('image_path') as $image) {
                $fileName = $image->getClientOriginalName();
                // 🔸 すでに同じファイル名がセッションに存在してたら、この画像はスキップ
                if(in_array($fileName, $fileNames)) {
                    continue;
                }

                // 🔸 画像の拡張子に応じて、最適な圧縮エンコーダー(Jpeg/Png/Webp)を選ぶ処理
                $extension = strtolower($image->getClientOriginalExtension());
                $encoder = match($extension) {
                    'jpg', 'jpeg' => new JpegEncoder(75),
                    'png'        => new PngEncoder(9),
                    'webp'       => new WebpEncoder(80),
                    default      => new JpegEncoder(75),
                };

                // 🔸 アップロードされた画像を圧縮して一時保存
                $compressedImage = $manager->read($image->getRealPath())->encode($encoder); // 圧縮
                $tmpImageName = time() . uniqid() . '_' . $fileName; // ファイル名
                Storage::disk('public')->put("tmp/{$tmpImageName}", (string)$compressedImage); // 一時保存

                // 🔸 あとでセッションに保存するためのデータを配列に追加
                $tmpImagePaths[] = "tmp/{$tmpImageName}";
                $fileNames[] = $fileName;

                // 🔸 JSの順序情報とマッチング(uniqueIdのsuffix一致)
                $matched = collect($imageOrderInput)->first(fn($item) => str_ends_with($item['uniqueId'], $fileName));

                $imageOrder[] = [
                    'fileName' => $fileName,
                    'src' => "tmp/{$tmpImageName}",
                    'position' => $matched['position'] ?? count($imageOrder),
                    'uniqueId' => $matched['uniqueId'] ?? (uniqid() . '_' . $fileName),
                ];
            }
        }

        // 🔹 既存のセッション画像情報を `imageOrderInput` から復元
        foreach($tmpImagePaths as $index => $path) {
            // 🔸 セッションから復元した画像のファイル名を取得して、無効なデータはスキップ
            $fileName = $fileNames[$index] ?? null;
            if(!$fileName) continue; // nullや空文字はスキップ

            $matched = collect($imageOrderInput)->first(fn($item) => str_ends_with($item['uniqueId'], $fileName));
            if($matched) {
                $imageOrder[] = [
                    'fileName' => $fileName,
                    'src' => $path,
                    'position' => $matched['position'],
                    'uniqueId' => $matched['uniqueId'],
                ];
            }
        }

        // 🔹 並び順でソート & uniqueId で重複排除
        $imageOrder = collect($imageOrder)
        ->keyBy('uniqueId') // 重複したuniqueIdを排除（後勝ち）
        ->sortBy('position') // position順に並び替え
        ->values()
        ->all();

        Session::put('tmp_images', $tmpImagePaths);
        Session::put('file_names', $fileNames);
        Session::put('image_order', $imageOrder);
        Session::put('collection.form_input', $formInput);

        return response()->json(['message' => 'セッション保存完了']);
}


    
}