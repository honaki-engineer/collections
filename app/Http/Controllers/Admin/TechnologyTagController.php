<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTechnologyTagRequest;
use App\Http\Requests\UpdateTechnologyTagRequest;
use App\Models\TechnologyTag;
use App\Service\TagService;
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
        // 🔹 技術タグの種類を日本語化
        $typeLabels = TagService::appendTypeLabelsToTechnologyTags();

        return view('admin.technologyTags.create', compact('typeLabels'));
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

        // ✅ editから遷移した場合
        if(session()->has('collection_return_url')) {
            $redirectUrl = session('collection_return_url');
            session()->forget('collection_return_url'); // 一度きりの使用
            return redirect($redirectUrl)->with('success', '技術タグを登録しました');
        }

        // ✅ createから遷移してきた場合
        return to_route('admin.collections.create')->with('success', '技術タグを登録しました');
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

        // 🔹 技術タグの種類を日本語化
        $typeLabels = TagService::appendTypeLabelsToTechnologyTags();

        return view('admin.technologyTags.edit', compact('technologyTag', 'typeLabels'));
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
