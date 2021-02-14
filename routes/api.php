<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('ulangan', [ApiController::class, 'ulangan']);
Route::post('terimanisn', [ApiController::class, 'terimaNISN']);
Route::post('terimatoken', [ApiController::class, 'terimaToken']);
Route::post('terimaselesai', [ApiController::class, 'terimaSelesai']);
Route::post('terimajawaban', [ApiController::class, 'terimaJawaban']);
Route::post('terimanilai', [ApiController::class, 'terimaNilai']);
