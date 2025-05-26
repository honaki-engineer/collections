<?php

namespace App\Http\Controllers\Admin;

use App\Models\Collection;
use App\Http\Controllers\Controller;
use App\Service\CollectionService;
use App\Service\TagService;
use App\Http\Requests\StoreCollectionRequest;
use App\Http\Requests\UpdateCollectionRequest;
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
            'position' => $request->search_position,
        ];

        /** @var \App\Models\User $user */
        $collections = Auth::user()->collections()->searchAdminIndex($searches)->orderBy('created_at', 'desc')->paginate(10);

        $collections->setCollection(
            // ②`Paginator`に戻す
            $collections
                ->getCollection() // ①Collectionだけ取得し、変換する
                ->transform(function ($collection) {
                    // 「公開種別」日本語化
                    CollectionService::isPublicLabel($collection);
                    // 「表示優先度」日本語化
                    CollectionService::positionLabel($collection);

                    return $collection;
                }),
        );

        // 🔹 タグ一覧/新規作成遷移の際に、作ったセッションを削除
        TagService::forgetCollectionFormInput();

        // 🔹 スマホ時、検索後も検索フォームを表示し続ける
        $isSearching = request()->filled('search_is_public') || request()->filled('search_position'); // filled = 空か否か確認

        return view('admin.collections.index', compact('collections', 'isSearching'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 🔹 古い「戻る先情報」が残っていた場合のクリア(明示的に)
        session()->forget(['collection_return_url', 'collection_return_label']);

        // 🔹 ログインユーザーの技術タグをtech_type昇順で取得してadmin.collections.createに渡す処理
        $technologyTags = TagService::getTechnologyTagsSorted();

        // 🔹 技術タグのセレクトボックス内テーマ
        $technologyTags->typeLabels = TagService::appendTypeLabelsToTechnologyTags();

        // 🔹 ログインユーザーの機能タグを取得してadmin.collections.createに渡す処理
        $featureTags = TagService::getFeatureTags();

        // 🔹 「技術/機能タグのcollections新規登録/編集フォームへ戻る際に使用するaタグのURL」をセッション保存
        session([
            'collection_return_url' => request()->fullUrl(),
            'collection_return_label' => 'ポートフォリオ新規登録へ戻る',
        ]);

        // tech_type の数値 → ラベルに変換
        $typeMap = [
            0 => 'frontend',
            1 => 'backend',
            2 => 'infra',
            3 => 'build',
            4 => 'tool',
            5 => 'db',
        ];

        // tech_type を文字列に変換（JavaScript用マップ）
        $techTypeMapForJS = $technologyTags->pluck('tech_type', 'id')->mapWithKeys(function ($v, $k) use ($typeMap) {
            return [(string)$k => $typeMap[$v] ?? 'default'];
        });

        return view('admin.collections.create', compact('technologyTags', 'featureTags', 'techTypeMapForJS'));
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
        if ($request->hasFile('image_path') || !empty($request->input('tmp_images'))) {
            CollectionService::storeRequestImage($request, $collection);
        }

        // 🔹 バリデーションエラーがなければセッション画像を削除
        CollectionService::forgetImageSessionData();

        return to_route('admin.collections.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // ログインユーザーの(コレクション&画像&技術&機能タグ)テーブルを取得
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
        // 🔹 古い「戻る先情報」が残っていた場合のクリア(明示的に)
        session()->forget(['collection_return_url', 'collection_return_label']);

        // 🔹 ログインユーザーの(コレクション&画像&技術&機能タグ)テーブルを取得
        $collection = CollectionService::getCollectionWithRelations($id);

        // 🔹 ログインユーザーが持つすべての技術タグを取得
        $technologyTags = TagService::getTechnologyTagsSorted();
        // 🔹 ログインユーザーが持つすべての機能タグを取得
        $featureTags = TagService::getFeatureTags();

        // 🔹 タグの種別ラベル
        $technologyTags->typeLabels = TagService::appendTypeLabelsToTechnologyTags();

        // 🔹 $collection->technologyTagsのIDを取得
        $selectedTechTagIds = $collection->technologyTags->pluck('id')->toArray();
        // 🔹 $collection->featureTagsのIDを取得
        $selectedFeatureTagIds = $collection->featureTags->pluck('id')->toArray();

        // 🔹 「技術/機能タグのcollections新規登録/編集フォームへ戻る際に使用するaタグのURL」をセッション保存
        session([
            'collection_return_url' => request()->fullUrl(),
            'collection_return_label' => 'ポートフォリオ編集へ戻る',
        ]);

        // 🔹 tech_type の数値 → ラベルに変換
        $typeMap = [
            0 => 'frontend',
            1 => 'backend',
            2 => 'infra',
            3 => 'build',
            4 => 'tool',
            5 => 'db',
        ];

        // 🔹 tech_type を文字列に変換（JavaScript用マップ）
        $techTypeMapForJS = $technologyTags
            ->pluck('tech_type', 'id') // ←ここで「id => tech_type」になる
            ->mapWithKeys(function($v, $k) use ($typeMap) { // mapWithKeys(ループ処理) = キーと値のペアを返して新しい連想配列（連想コレクション）を作る
                return [(string)$k => $typeMap[$v] ?? 'default'];
            });

        // 🔹 技術タグの「ID順リスト（並び順付き）」を配列として取得する
        $technologyTagOrderFromDB = $collection->technologyTags
            ->sortBy(fn($tag) => $tag->pivot->position) // position カラムでソート
            ->pluck('id') // 並び替えた技術タグから id だけを取り出す
            ->toArray();

        return view('admin.collections.edit', compact('collection', 'technologyTags', 'featureTags', 'selectedTechTagIds', 'selectedFeatureTagIds', 'techTypeMapForJS', 'technologyTagOrderFromDB'));
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
        $collection = Auth::user()->collections()->with('collectionImages')->findOrFail($collection->id);

        // save(画像以外)
        CollectionService::updateRequest($collection, $request);

        // 削除リクエストがある場合、該当画像を削除
        CollectionService::deleteRequestImage($request);

        // 追加画像保存、既存画像position変更
        CollectionService::updateRequestImage($request, $collection);

        return to_route('admin.collections.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $collection = Auth::user()->collections()->findOrFail($id);

        $collection->delete();

        return to_route('admin.collections.index');
    }

    // ✅ 特定のセッション画像を削除するメソッド
    public function removeSessionImage(Request $request)
    {
        $tmpImage = $request->input('tmp_image');

        // 特定のセッション画像を削除
        if (CollectionService::removeSessionImage($tmpImage)) {
            return response()->json(['message' => 'セッション画像が削除されました'], 200);
        }

        return response()->json(['message' => '画像が見つかりません'], 400);
    }

    /**
     * セッションに保持している画像データをすべて削除（create画面から離れた時など）
     */
    public function clearSessionImages(Request $request)
    {
        // 一時保存された画像のパスを取得
        $tmpImages = Session::get('tmp_images', []);

        // ストレージ内の物理ファイルを削除
        foreach ($tmpImages as $tmpImage) {
            if (Storage::disk('public')->exists($tmpImage)) {
                Storage::disk('public')->delete($tmpImage);
            }
        }

        // 一括でセッションから削除
        CollectionService::forgetImageSessionData();

        return response()->json(['message' => 'セッション画像を削除しました']);
    }

    // ✅ フォームの入力内容をセッションに保存して、技術タグ一覧ページへリダイレクトする処理
    public function storeSessionWithImage(Request $request)
    {
        // editから
        if($request->has('return_url')) {
            session(['collection_return_url' => $request->input('return_url')]);
            return response()->json(['message' => '戻るURLのみ保存']);
        }

        // createから(createの場合は途中のフォームをセッションから復元させる)
        // フォームの入力内容をセッションに保存して、技術タグ一覧ページへリダイレクトする処理
        CollectionService::storeSessionWithImage($request);
        return response()->json(['message' => 'セッション保存完了']);
    }
}
