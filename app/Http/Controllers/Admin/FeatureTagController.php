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
        $user = Auth::user();
        $featureTags = $user
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

        // 🔹 主な機能タグstore
        TagService::storeRequestFeatureTag($names);

        // ✅ editから遷移してきた場合
        if(session()->has('collection_return_url')) {
            $redirectUrl = session('collection_return_url');
            session()->forget('collection_return_url'); // 一度きりの使用
            return redirect($redirectUrl)->with('success', '主な機能タグを登録しました');
        }

        // ✅ createから遷移してきた場合
        return to_route('admin.collections.create')->with('success', '主な機能タグを登録しました');;
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
