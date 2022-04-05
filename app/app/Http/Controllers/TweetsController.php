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
        $follow_ids = $follower->followingIds($user->id);
        // followed_idだけ抜き出す
        $following_ids = $follow_ids->pluck('followed_id')->toArray();
        $timelines = $tweet->getTimelines($user->id, $following_ids);
        
        return view('tweets.index', [
            'user'      => $user,
            'timelines' => $timelines
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
        $user_id = auth()->id();
        
        return redirect('tweets');
    }

    public function show(Tweet $tweet, Comment $comment)
    {
        $user_id = auth()->id();
        $tweet = $tweet->getTweet($tweet->id);
        $comments = $comment->getComments($tweet->id);

        return view('tweets.show', [
            'user'     => $user_id,
            'tweet' => $tweet,
            'comments' => $comments
        ]);
    }

    public function edit(Tweet $tweet)
    {
        $user_id = auth()->id();
        $tweets = $tweet->getEditTweet($user_id, $tweet->id);

        if (!isset($tweets)) {
            return redirect('tweets');
        }

        return view('tweets.edit', [
            'user'   => $user_id,
            'tweets' => $tweets
        ]);
    }

    public function update(TweetRequest $request, Tweet $tweet)
    {
        $data = $request->all();
        $tweet->tweetUpdate($tweet->id, $data);

        return redirect('tweets');
    }

    public function destroy(Tweet $tweet)
    {
        $user_id = auth()->id();
        $tweet->tweetDestroy($user_id, $tweet->id);

        return back();
    }
    
}
