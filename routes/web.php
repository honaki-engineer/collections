<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\TechnologyTagController;
use App\Http\Controllers\Admin\FeatureTagController;

use App\Http\Controllers\Admin\CollectionController as AdminCollectionController;
use App\Http\Controllers\PublicSite\CollectionController as PublicCollectionController;

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // 管理者側コレクション
    Route::resource('collections', AdminCollectionController::class);
    Route::post('/remove-session-image', [AdminCollectionController::class, 'removeSessionImage'])->name('remove.session.image');
    Route::post('/clear-session-images', [AdminCollectionController::class, 'clearSessionImages'])->name('session.clear.images'); // セッション画像を全削除(create画面から離れる時など)
    Route::post('/collections/storeSessionWithImage', [AdminCollectionController::class, 'storeSessionWithImage'])->name('collections.storeSessionWithImage'); // タグ遷移のセッション保存

    Route::resource('technology-tags', TechnologyTagController::class);
    Route::resource('feature-tags', FeatureTagController::class);
});

Route::get('/admin', function () { // 「/admin」
    return view('admin.welcome');
})->name('admin.welcome');

// 一般
Route::get('/', [PublicCollectionController::class, 'index'])->name('collections.index'); // indexのみ
Route::prefix('')->group(function () {
    Route::resource('collections', PublicCollectionController::class)->except(['index']); // indexだけ除外
});
Route::get('/collections', function () { return redirect('/'); }); // 「/collections」の場合のリダイレクト

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
