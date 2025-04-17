<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTechnologyTagRequest;
use App\Http\Requests\UpdateTechnologyTagRequest;
use App\Models\TechnologyTag;
use App\Service\Admin\TagService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TechnologyTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 🔹 検索結果
        $searches = [
            'name' => $request->search_name,
            'tech_type' => $request->search_tech_type,
        ];

        // 🔹 検索結果 & orderBy & ページネーション → 値が入る
        /** @var \App\Models\User $user */
        $technologyTags = Auth::user()
            ->technologyTags()
            ->search($searches) // scope
            ->orderBy('tech_type', 'asc')
            ->paginate(10);

        // 🔹 技術タグの種類を日本語化
        $typeLabels = TagService::appendTypeLabelsToTechnologyTags();

        return view('admin.technologyTags.index', compact('technologyTags', 'typeLabels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.technologyTags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTechnologyTagRequest $request)
    {
        // 🔹 初期設定
        $names = explode(',', $request->input('names')); // カンマで値を分割

        // 🔹 技術タグstore
        TagService::storeRequestTechnologyTag($request, $names);

        // 🔹 ログインユーザーの技術タグ → tech_type昇順で取得 → $technologyTagsに渡す処理
        $technologyTags = TagService::getTechnologyTagsSorted();

        // 🔹 技術タグのセレクトボックス内テーマ
        $technologyTags->typeLabels = TagService::appendTypeLabelsToTechnologyTags();

        // ✅ 機能タグ用
        // 🔹 機能タグ取得
        $featureTags = TagService::getFeatureTags();

        return view('admin.collections.create', compact('technologyTags', 'featureTags'));
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
        $technologyTag = TechnologyTag::findOrFail($id);

        return view('admin.technologyTags.edit', compact('technologyTag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTechnologyTagRequest $request, $id)
    {
        // 🔹 個別のTechnologyTagレコード取得
        $technologyTag = TechnologyTag::findOrFail($id);

        // 🔹 update
        TagService::updateTechnologyTag($technologyTag, $request);

        return to_route('admin.technology-tags.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 🔹 個別のTechnologyTagレコード取得
        $technologyTag = TechnologyTag::findOrFail($id);
        // 🔹 削除
        $technologyTag->delete();

        return to_route('admin.technology-tags.index');
    }
}
