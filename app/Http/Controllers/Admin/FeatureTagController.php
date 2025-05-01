<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeatureTagRequest;
use App\Http\Requests\UpdateFeatureTagRequest;
use App\Service\TagService;
use App\Models\FeatureTag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FeatureTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 🔹 検索結果
        $searches = $request->search_name;

        // 🔹 検索結果 & orderBy & ページネーション → 値が入る
        /** @var \App\Models\User $user */
        $featureTags = Auth::user()
            ->featureTags()
            ->search($searches) // scope
            ->paginate(10);

        return view('admin.featureTags.index', compact('featureTags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.featureTags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFeatureTagRequest $request)
    {
        // 🔹 初期設定
        $names = explode(',', $request->input('names')); // カンマで値を分割

        // 🔹 機能タグstore
        TagService::storeRequestFeatureTag($names);

        // 🔹 機能タグ取得
        $featureTags = TagService::getFeatureTags();

        // ✅ 技術タグ用
        // 🔹 ログインユーザーの技術タグをtech_type昇順で取得してadmin.collections.createに渡す処理
        $technologyTags = TagService::getTechnologyTagsSorted();
        // 🔹 技術タグのセレクトボックス内テーマ
        $technologyTags->typeLabels = TagService::appendTypeLabelsToTechnologyTags();

        return view('admin.collections.create', compact('featureTags', 'technologyTags'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // 🔹 個別のFeatureTagレコード取得
        $featureTag = FeatureTag::findOrFail($id);

        return view('admin.featureTags.edit', compact('featureTag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFeatureTagRequest $request, $id)
    {
        // 🔹 個別のFeatureTagレコード取得
        $featureTag = FeatureTag::findOrFail($id);

        // 🔹 update
        TagService::updateFeatureTag($featureTag, $request);

        return to_route('admin.feature-tags.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 🔹 個別のFeatureTagレコード取得
        $featureTag = FeatureTag::findOrFail($id);
        // 🔹 削除
        $featureTag->delete();

        return to_route('admin.feature-tags.index');
    }
}
