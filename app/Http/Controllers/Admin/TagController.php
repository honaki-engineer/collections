<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TechnologyTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.tags.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tags.create');
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
        $tag_type = $request->type; // タグ種類取得
        $names = explode(',', $request->input('names')); // カンマで値を分割

        // 🔹 技術タグの場合
        if($tag_type == 0) {
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
        }

        // 🔹 admin.collections.createに$technologyTagsデータを送る用
        $technologyTags = Auth::user()
        ->technologyTags()
        ->orderBy('tech_type', 'asc')
        ->get();

        // 🔹 技術タグのセレクトボックス内テーマ
        $technologyTags->typeLabels = [
            0 => '言語',
            1 => 'フレームワーク',
            2 => 'ツール',
        ];

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
