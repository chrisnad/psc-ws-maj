<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PsController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

//Auth::routes();

Route::get('/home', [HomeController::class, 'index'])
    ->name('home');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');


Route::get('/ps/{nationalId}', [PsController::class, 'show'])
    ->name('ps.show');
Route::put('/ps/{nationalId}', [PsController::class, 'update'])
    ->name('ps.update');
Route::get('/ps/{nationalId}/edit', [PsController::class, 'edit']);


Route::get('/auth/{provider}/redirect', [LoginController::class, 'redirectToProvider'])
    ->name('auth.redirect');

Route::get('/auth/{provider}/callback', [LoginController::class, 'handleProviderCallback']);
