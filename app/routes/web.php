<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\GoogleRegisterController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

use App\Http\Requests\Comment\CommentRequest;
use App\Http\Requests\Tweet\TweetRequest;
use App\Http\Requests\User\UserRequest;

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\TweetsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SearchController;

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
Route::prefix('login')->name('login.')->group(function () {
    Route::get('/{provider}', [LoginController::class, 'redirectToProvider'])->name('{api}');
    Route::get('/{provider}/callback', [LoginController::class, 'handleProviderCallback'])->name('{provider}.callback');
});

// google
Route::prefix('register')->name('register.')->group(function () {
    Route::get('/{provider}', [GoogleRegisterController::class, 'showProviderUserRegistrationForm'])->name('{googleapi}');
    Route::post('/{provider}', [GoogleRegisterController::class, 'registerProviderUser'])->name('{provider}');
});

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

    Route::prefix('tweets')->name('tweets.')->group(function () {
        Route::put('/{tweet}/like', [TweetsController::class, 'like'])->name('like');
        Route::delete('/{tweet}/like', [TweetsController::class, 'unlike'])->name('unlike');
    });

    // 検索機能
    Route::get('tweets/search', [TweetsController::class, 'search'])->name('tweets.get');
    Route::post('tweets/search', [TweetsController::class, 'search'])->name('tweets.search');

    // Route::fallback(function () {
    //     return redirect('/');
    // });
});
