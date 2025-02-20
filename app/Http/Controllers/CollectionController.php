<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = 
        Collection::orderBy('created_at', 'desc')
        ->get()
        ->map(function ($collection) {
            // 「公開種別」日本語化
            $collection->is_public_label = 
            $collection->is_public ? '公開' : '非公開'; // trueが１、falseが0

            // 「表示優先度」日本語化
            $collection->position_label =
            match($collection->position) {
                0 => 'デフォルト',
                1 => '1ページ目',
                2 => 'topページ',
            };

            return $collection;
        });

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
    public function store(Request $request)
    {
        Collection::create([
            'title' => $request->title,
            'description' => $request->description,
            'url_qiita' => $request->url_qiita,
            'url_webapp' => $request->url_webapp,
            'url_github' => $request->url_github,
            'is_public' => $request->is_public,
            'position' => $request->position,
        ]);

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
