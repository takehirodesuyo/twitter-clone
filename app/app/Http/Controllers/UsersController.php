<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Requests\User\UserRequest;
use App\Models\User;
use App\Models\Tweet;
use App\Models\Follower;

class UsersController extends Controller
{
    public function index(User $user)
    {
        $all_users = $user->getAllUsers(auth()->user()->id);

        return view('users.index', [
            'all_users'  => $all_users
        ]);
    }

    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    public function show(User $user, Tweet $tweet, Follower $follower)
    {
        $LoginUser = auth()->user();
        $IsFollowing = $LoginUser->isFollowing($user->id);
        $IsFollowed = $LoginUser->isFollowed($user->id);
        $TimeLines = $tweet->getUserTimeLine($user->id);
        $TweetCount = $tweet->getTweetCount($user->id);
        $FollowCount = $follower->getFollowCount($user->id);
        $FollowerCount = $follower->getFollowerCount($user->id);

        return view('users.show', [
            'user'           => $user,
            'IsFollowing'    => $IsFollowing,
            'IsFollowed'     => $IsFollowed,
            'TimeLines'      => $TimeLines,
            'TweetCount'     => $TweetCount,
            'FollowCount'    => $FollowCount,
            'FollowerCount'   => $FollowerCount
        ]);
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->all();
        $user->updateProfile($data);

        return redirect()->route('users.update', $user->id);
    }

    // フォロー
    public function follow(User $user)
    {
        $follower = auth()->user();
        $is_following = $follower->isFollowing($user->id);

        if (!$is_following) {
            $follower->follow($user->id);
            return back();
        }
    }

    // フォロー解除
    public function unfollow(User $user)
    {
        $follower = auth()->user();
        $is_following = $follower->isFollowing($user->id);

        if ($is_following) {
            $follower->unfollow($user->id);
            return back();
        }
    }
}
