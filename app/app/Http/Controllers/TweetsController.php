<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Consts\paginateConsts;
use App\Models\Comment;
use App\Models\Follower;
use App\Models\Tweet;
use App\Http\Requests\Tweet\TweetRequest;

class TweetsController extends Controller
{
    public function index(Tweet $tweet, Follower $follower)
    {
        $user = auth()->user();
        // フォローしているユーザーID
        $FollowIds = $follower->followingIds($user->id);
        // followed_idだけ抜き出す
        $FollowingIds =  $FollowIds->pluck('followed_id')->toArray();
        $TimeLines = $tweet->getTimelines($user->id, $FollowingIds);

        return view('tweets.index', [
            'user'      => $user,
            'TimeLines' => $TimeLines
        ]);
    }

    public function create()
    {
        $user = auth()->user();

        return view('tweets.create', [
            'user' => $user
        ]);
    }

    public function store(TweetRequest $request, Tweet $tweet)
    {
        $user = auth()->user();
        $data = $request->all();
        $tweet->store($user->id, $data);

        return redirect('tweets');
    }

    public function show(Tweet $tweet, Comment $comment)
    {
        $UserId = auth()->id();
        $Tweet = $tweet->Tweet($tweet->id);
        $Comments = $comment->Comments($tweet->id);

        return view('tweets.show', [
            'User'     => $UserId,
            'Tweet' => $Tweet,
            'Comments' => $Comments
        ]);
    }

    public function edit(Tweet $tweet)
    {
        $user_id = auth()->id();
        $tweets = $tweet->getTweetByUserIdAndTweetId($user_id, $tweet->id);

        if (isset($tweets)) {
            return view('tweets.edit', [
                'user'   => $user_id,
                'tweets' => $tweets
            ]);
        } else {
            return redirect('tweets');
        }
    }

    public function update(TweetRequest $request, Tweet $tweet)
    {
        $data = $request->all();
        $tweet->tweetupdate($tweet->id, $data);

        return redirect('tweets');
    }

    public function destroy(Tweet $tweet)
    {
        $user_id = auth()->id();
        $tweet->tweetdestroy($user_id, $tweet->id);

        return back();
    }
}
