<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserRequest;
use App\Models\User;
use App\Models\Tweet;
use App\Models\Follower;


class UsersController extends Controller
{
    /**
     * ユーザー一覧を表示するメソッド
     * 
     * @params int $user
     * 
     * @return Illuminate\Http\Response;
     */
    public function index(User $user)
    {
        $all_users = $user->getAllUsers(auth()->user()->id);

        return view('users.index', [
            'all_users'  => $all_users
        ]);
    }
    /**
     * ユーザープロフィール編集画面表示するメソッド
     * 
     * @params int $user
     * 
     * @return Illuminate\Http\Response;
     */
    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }
    /**
     * ユーザープロフィール画面表示するメソッド
     * 
     * @params int $user,
     * @params int $tweet,
     * @params int $follower,
     * 
     * @return Illuminate\Http\Response;
     */
    public function show(User $user, Tweet $tweet, Follower $follower)
    {
        $loginUser = auth()->user();
        $isFollowing = $loginUser->isFollowing($user->id);
        $isFollowed = $loginUser->isFollowed($user->id);
        $timeLines = $tweet->getUserTimeLine($user->id);
        $tweetCount = $tweet->getTweetCount($user->id);
        $followCount = $follower->getFollowCount($user->id);
        $followerCount = $follower->getFollowerCount($user->id);

        return view('users.show', [
            'user'           => $user,
            'isFollowing'    => $isFollowing,
            'isFollowed'     => $isFollowed,
            'timeLines'      => $timeLines,
            'tweetCount'     => $tweetCount,
            'followCount'    => $followCount,
            'followerCount'  => $followerCount
        ]);
    }
    /**
     * ユーザープロフィールを更新するメソッド
     * 
     * @params int $request
     * @params int $user
     * 
     * @return Illuminate\Http\Response;
     */
    public function update(UserRequest $request, User $user)
    {
        $data = $request->all();
        $user->updateProfile($data);

        return redirect()->route('users.update', $user->id);
    }
    /**
     * ユーザーをフォローするメソッド
     * 
     * @params int $user
     * 
     * @return Illuminate\Http\Response;
     */
    public function follow(User $user)
    {
        $follower = auth()->user();
        $isFollowing = $follower->isFollowing($user->id);

        if (!$isFollowing) {
            $follower->follow($user->id);
            return back();
        }
    }
    /**
     * ユーザーをフォロー解除するメソッド
     * 
     * @params int $user
     * 
     * @return Illuminate\Http\Response;
     */
    public function unfollow(User $user)
    {
        $follower = auth()->user();
        $isFollowing = $follower->isFollowing($user->id);

        if ($isFollowing) {
            $follower->unfollow($user->id);
            return back();
        }
    }
}
