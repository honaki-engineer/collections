<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CollectionController;
use App\Http\Controllers\Admin\TagController;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Http\Requests\CollectionRequest;
use Barryvdh\Reflection\DocBlock\Tag;

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

Route::middleware(['auth'])->group(function () {
    Route::resource('collections', CollectionController::class);
    Route::post('/remove-session-image', [CollectionController::class, 'removeSessionImage'])->name('remove.session.image');
    Route::post('/clear-session-images', [CollectionController::class, 'clearSessionImages'])->name('session.clear.images'); // セッション画像を全削除(create画面から離れる時など)
    
    Route::resource('tags', TagController::class);
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
