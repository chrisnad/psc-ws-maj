<?php

use App\Http\Controllers\Api\ExpertiseController;
use App\Http\Controllers\Api\StructureController;
use App\Http\Controllers\Api\WorkSituationController;
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
Route::get('ps', [PsController::class, 'index']);
Route::post('ps', [PsController::class, 'store']);
Route::put('ps', [PsController::class, 'storeOrUpdate']);
Route::get('ps/{ps}', [PsController::class, 'show'])->where('ps', '^[0-9]+(\/|[0-9]|\-)[0-9]+$');
Route::put('ps/{ps}', [PsController::class, 'update'])->where('ps', '^[0-9]+(\/|[0-9]|\-)[0-9]+$');
Route::delete('ps/{ps}', [PsController::class, 'destroy'])->where('ps', '^[0-9]+(\/|[0-9]|\-)[0-9]+$');

Route::get('ps/{ps}/professions', [ProfessionController::class, 'index'])->where('ps', '^[0-9]+(\/|[0-9]|\-)[0-9]+$');
Route::post('ps/{ps}/professions', [ProfessionController::class, 'store'])->where('ps', '^[0-9]+(\/|[0-9]|\-)[0-9]+$');
Route::get('ps/{ps}/professions/{profession}', [ProfessionController::class, 'show'])->where('ps', '^[0-9]+(\/|[0-9]|\-)[0-9]+$');
Route::put('ps/{ps}/professions/{profession}', [ProfessionController::class, 'update'])->where('ps', '^[0-9]+(\/|[0-9]|\-)[0-9]+$');
Route::delete('ps/{ps}/professions/{profession}', [ProfessionController::class, 'destroy'])->where('ps', '^[0-9]+(\/|[0-9]|\-)[0-9]+$');

Route::get('ps/{ps}/professions/{profession}/expertises', [ExpertiseController::class, 'index'])->where('ps', '^[0-9]+(\/|[0-9]|\-)[0-9]+$');
Route::post('ps/{ps}/professions/{profession}/expertises', [ExpertiseController::class, 'store'])->where('ps', '^[0-9]+(\/|[0-9]|\-)[0-9]+$');
Route::get('ps/{ps}/professions/{profession}/expertises/{expertise}', [ExpertiseController::class, 'show'])->where('ps', '^[0-9]+(\/|[0-9]|\-)[0-9]+$');
Route::put('ps/{ps}/professions/{profession}/expertises/{expertise}', [ExpertiseController::class, 'update'])->where('ps', '^[0-9]+(\/|[0-9]|\-)[0-9]+$');
Route::delete('ps/{ps}/professions/{profession}/expertises/{expertise}', [ExpertiseController::class, 'destroy'])->where('ps', '^[0-9]+(\/|[0-9]|\-)[0-9]+$');

Route::get('ps/{ps}/professions/{profession}/situations', [WorkSituationController::class, 'index'])->where('ps', '^[0-9]+(\/|[0-9]|\-)[0-9]+$');
Route::post('ps/{ps}/professions/{profession}/situations', [WorkSituationController::class, 'store'])->where('ps', '^[0-9]+(\/|[0-9]|\-)[0-9]+$');
Route::get('ps/{ps}/professions/{profession}/situations/{situation}', [WorkSituationController::class, 'show'])->where('ps', '^[0-9]+(\/|[0-9]|\-)[0-9]+$');
Route::put('ps/{ps}/professions/{profession}/situations/{situation}', [WorkSituationController::class, 'update'])->where('ps', '^[0-9]+(\/|[0-9]|\-)[0-9]+$');
Route::delete('ps/{ps}/professions/{profession}/situations/{situation}', [WorkSituationController::class, 'destroy'])->where('ps', '^[0-9]+(\/|[0-9]|\-)[0-9]+$');

Route::resource('structures', StructureController::class,
    ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
