<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\Admin\TagService;
use Illuminate\Support\Facades\Auth;
use App\Models\FeatureTag;

class FeatureTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 🔹 検索結果 & orderBy & ページネーション → 値が入る
        /** @var \App\Models\User $user */
        $featureTags = Auth::user()
        ->featureTags()
        ->get();

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
    public function store(Request $request)
    {
        // 🔹 初期設定
        $names = explode(',', $request->input('names')); // カンマで値を分割

        // 🔹 機能タグstore
        TagService::storeRequestFeatureTag($names);

        // 機能タグ取得
        $featureTags = Auth::user()
        ->featureTags()
        ->get();

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
