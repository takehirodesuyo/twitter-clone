<?php

use Illuminate\Support\Facades\Route;
// この宣言しないとUsersControllerにアクセスできない
use App\Http\Controllers\UsersController;

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function() {

    // laravel8 の書き方
    Route::resource('users', UsersController::class)->only([
        'index', 'show', 'edit', 'update'
    ]);
});