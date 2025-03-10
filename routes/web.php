<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CollectionController;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Http\Requests\CollectionRequest;

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

Route::resource('collections', CollectionController::class)->middleware('auth');

Route::post('/remove-session-image', [CollectionController::class, 'removeSessionImage'])->name('remove.session.image');



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
