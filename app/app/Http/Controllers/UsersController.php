<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
// 下記のModelを使用する
use App\Models\User;
use App\Models\Tweet;
use App\Models\Follower;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        //インジェクションって何？
        $all_users = $user->getAllUsers(auth()->user()->id);

        return view('users.index', [
            'all_users'  => $all_users
        ]);
    }

    // フォロー
    public function follow(User $user)
    {   
        // 変数にログインユーザーを代入
        $follower = auth()->user();
        // フォローしているか
        $is_following = $follower->isFollowing($user->id);
        // ！でフォローしてなければフォローできる
        if(!$is_following) {
            $follower->follow($user->id);
            return back();
        }
    }

    // フォロー解除
    public function unfollow(User $user)
    {
        // 変数にログインユーザー代入
        $follower = auth()->user();
        // フォローしているか
        $is_following = $follower->isFollowing($user->id);
        // フォローしてたらフォロー解除できる
        if($is_following) {
            $follower->unfollow($user->id);
            return back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Tweet $tweet, Follower $follower)
    {
        $login_user = auth()->user();
        $is_following = $login_user->isFollowing($user->id);
        $is_followed = $login_user->isFollowed($user->id);
        $timelines = $tweet->getUserTimeLine($user->id);
        $tweet_count = $tweet->getTweetCount($user->id);
        $follow_count = $follower->getFollowCount($user->id);
        $follower_count = $follower->getFollowerCount($user->id);
        
        // user.indexにこいつら渡す
        return view('users.show', [
            'user'           => $user,
            'is_following'   => $is_following,
            'is_followed'    => $is_followed,
            'timelines'      => $timelines,
            'tweet_count'    => $tweet_count,
            'follow_count'   => $follow_count,
            'follower_count' => $follower_count
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
