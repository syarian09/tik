<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PDFController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Guest
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('ceklogin', [AuthController::class, 'ceklogin'])->name('ceklogin');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth']], function () {
    Route::group(['prefix' => 'laravel-filemanager', 'ceklevel:9'], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });
    Route::get('beranda', 'App\Http\Livewire\Beranda\Index')->name('beranda');
    Route::get('profil', 'App\Http\Livewire\User\Profil')->name('profil');
    Route::get('materi', 'App\Http\Livewire\Materi\Index')->name('materi');
    Route::get('materi/baca/{id}', [PDFController::class, 'index'])->name('materi.baca');
    //Administrator
    Route::group(['middleware' => ['ceklevel:9']], function () {
        Route::get('user', 'App\Http\Livewire\User\Index')->name('user');
        Route::get('materi/add', 'App\Http\Livewire\Materi\Add')->name('materi.add');
        Route::get('materi/edit/{id}', 'App\Http\Livewire\Materi\Add')->name('materi.edit');
    });
});