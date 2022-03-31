<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\TweetsController;
use App\Http\Controllers\CommentsController;

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

    // ユーザー関連
    Route::resource('users', UsersController::class)->only([
        'index', 'show', 'edit', 'update'
    ]);

    Route::get('users/{user}/follow', [App\Http\Controllers\UsersController::class, 'follow'])->name('follow');
    Route::post('users/{user}/follow', [App\Http\Controllers\UsersController::class, 'follow'])->name('follow');
    Route::delete('users/{user}/unfollow', [App\Http\Controllers\UsersController::class, 'unfollow'])->name('unfollow');

    // ツイート関連
    Route::resource('tweets', TweetsController::class);

    // コメント関連
    Route::resource('comments', CommentsController::class);

});