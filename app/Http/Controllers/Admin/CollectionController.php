<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Collection;
use App\Http\Controllers\Controller;
use App\Service\Admin\CollectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $collections = Auth::user()
        ->collections()
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($collection) {
            // 「公開種別」日本語化
            CollectionService::isPublicLabel($collection);
            // 「表示優先度」日本語化
            CollectionService::positionLabel($collection);

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
            'user_id' => Auth::id(),
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
        $collection = Auth::user()
        ->collections()->findOrFail($id);

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
        $collection = Auth::user()
        ->collections()->findOrFail($id);

        return view('admin.collections.edit', compact('collection'));
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
        $collection = Auth::user()
        ->collections()->findOrFail($id);

        $collection->title = $request->title;
        $collection->description = $request->description;
        $collection->url_qiita = $request->url_qiita;
        $collection->url_webapp = $request->url_webapp;
        $collection->url_github = $request->url_github;
        $collection->is_public = $request->is_public;
        $collection->position = $request->position;
        $collection->save();

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
        //
    }
}
