<?php

use App\Http\Controllers\Api\PsController;
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

$proxy_url    = env('PROXY_URL');
$proxy_schema = env('PROXY_SCHEMA');

if(!empty($proxy_url)) {
    url()->forceRootUrl($proxy_url);
}
if(!empty($proxy_schema)) {
    url()->forceScheme($proxy_schema);
}

Route::get('/ps', [PsController::class, 'index']);
Route::get('/ps/{ps}', [PsController::class, 'show']);
Route::put('/ps/{ps}', [PsController::class, 'update']);
