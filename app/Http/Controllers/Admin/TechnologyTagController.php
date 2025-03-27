<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
    public function index()
    {
        // 🔹 ログインユーザーの技術タグをtech_type昇順で取得してadmin.collections.createに渡す処理
        $technologyTags = Auth::user()
        ->technologyTags()
        ->orderBy('tech_type', 'asc')
        ->paginate(10);

        // 🔹 技術タグのセレクトボックス内テーマ
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
    public function store(Request $request)
    {
        // 🔹 初期設定
        $names = explode(',', $request->input('names')); // カンマで値を分割

        // 🔹 技術タグの場合
        foreach($names as $name) {
            $trimmedName = trim($name); // スペース削除したタグ名
            if(!empty($trimmedName)) {
                TechnologyTag::firstOrCreate([ // firstOrCreate = 重複時保存しない
                    'name' => $trimmedName,
                ],
                [ // 新規作成時に入れる値
                    'user_id' => Auth::id(),
                    'tech_type' => $request->tech_type,
                ]);
            }
        }

        // 🔹 ログインユーザーの技術タグをtech_type昇順で取得してadmin.collections.createに渡す処理
        $technologyTags = TagService::getTechnologyTagsSorted();

        // 🔹 技術タグのセレクトボックス内テーマ
        $technologyTags->typeLabels = TagService::appendTypeLabelsToTechnologyTags();

        return view('admin.collections.create', compact('technologyTags'));
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
        //
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
