<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\MusicController;
use App\Http\Controllers\MusicManagementController;
use App\Http\Controllers\DashboardController;


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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    //Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/music/search', [DashboardController::class, 'search'])->name('music.search');


    //get mp 3 file
    Route::get('/ms/{filename}', [DashboardController::class, 'streamMusic'])->name('music.stream');
    //get img file
    Route::get('/is/{filename}', [DashboardController::class, 'showimg'])->name('music.img');


    //Upload music
    //Route::get('/music', [MusicController::class, 'index'])->name('music.index');
    Route::post('/music', [MusicController::class, 'store'])->name('music.store');
    Route::get('/music/create', [MusicController::class, 'create'])->name('music.create');


    //view, delete, edit song
    Route::get('/music/list', [MusicManagementController::class, 'list'])->name('music.list');
    Route::get('/music/edit/{id}', [MusicManagementController::class, 'edit'])->name('music.edit');
    Route::get('/is1/{filename}', [MusicManagementController::class, 'showimg'])->name('musicm.img');



    Route::put('/music/update/{id}', [MusicManagementController::class, 'update'])->name('music.update');
    Route::delete('/music/{id}', [MusicManagementController::class, 'destroy'])->name('music.destroy');


    //profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
