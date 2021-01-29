<?php

use App\Http\Controllers\Api\ProfessionController;
use App\Http\Controllers\Api\PsController;
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

Route::resource('ps', PsController::class,
    ['only' => ['index', 'store', 'show', 'update', 'destroy']])
    ->names(['index' => 'api.ps.index',
        'store' => 'api.ps.store',
        'show' => 'api.ps.show',
        'update' => 'api.ps.update',
        'destroy' => 'api.ps.destroy'
]);

//Route::resource('professions', ProfessionController::class,
//    ['only' => ['index', 'store', 'show', 'update', 'destroy']]);

Route::get('ps/{ps}/professions', [ProfessionController::class, 'index']);
Route::post('ps/{ps}/professions', [ProfessionController::class, 'store']);
Route::get('ps/{ps}/professions/{profession}', [ProfessionController::class, 'show']);
Route::put('ps/{ps}/professions/{profession}', [ProfessionController::class, 'update']);
Route::delete('ps/{ps}/professions/{profession}', [ProfessionController::class, 'destroy']);
