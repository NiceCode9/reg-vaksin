<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/propinsi', [App\Http\Controllers\Api\WilayahController::class, 'getPropinsi']);
Route::get('/kabupaten/{prop_id}', [App\Http\Controllers\Api\WilayahController::class, 'getKabupaten']);
Route::get('/kecamatan/{kab_id}', [App\Http\Controllers\Api\WilayahController::class, 'getKecamatan']);
Route::get('/kelurahan/{kec_id}', [App\Http\Controllers\Api\WilayahController::class, 'getKelurahan']);

Route::post('/register', [App\Http\Controllers\Api\PendaftaranController::class, 'store']);
