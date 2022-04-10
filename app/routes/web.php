<?php

use Illuminate\Support\Facades\Route;

use App\Http\Requests\Comment\CommentRequest;
use App\Http\Requests\Tweet\TweetRequest;
use App\Http\Requests\User\UserRequest;

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\TweetsController;
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
    return view('auth.login');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    // ユーザー関連
    Route::resource('users', UsersController::class)->only([
        'index', 'show', 'edit', 'update'
    ]);

    // フォロー関連
    Route::post('users/{user}/follow', [UsersController::class, 'follow'])->name('follow');
    Route::delete('users/{user}/unfollow', [UsersController::class, 'unfollow'])->name('unfollow');

    // ツイート関連
    Route::resource('tweets', TweetsController::class);

    // コメント関連
    Route::resource('comments', CommentsController::class);

    // いいね関連
    Route::resource('favorites', FavoritesController::class);
});
