<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Consts\paginateConsts;
use App\Models\Comment;
use App\Models\Follower;
use App\Models\Tweet;
use App\Models\User;
use App\Http\Requests\Tweet\TweetRequest;

class TweetsController extends Controller
{
    public function index(Tweet $tweet, Follower $follower, User $user)
    {
        $user = auth()->user();
        // フォローしているユーザーID
        $FollowIds = $follower->followingIds($user->id);
        // followed_idだけ抜き出す
        $FollowingIds =  $FollowIds->pluck('followed_id')->toArray();
        $timeLines = $tweet->getTimelines($user->id, $FollowingIds);
        $tweetCount = $tweet->getTweetCount($user->id);
        $followCount = $follower->getFollowCount($user->id);
        $followerCount = $follower->getFollowerCount($user->id);
        $all_users = $user->getAllUsers(auth()->user()->id);
        $followNames = auth()->user()->follows()->get();
        $followerNames = auth()->user()->followers()->get();

        return view('tweets.index', [
            'user'      => $user,
            'timeLines' => $timeLines,
            'tweetCount'     => $tweetCount,
            'followCount'    => $followCount,
            'followerCount'   => $followerCount,
            'all_users'  => $all_users,
            'followNames'  => $followNames,
            'followerNames' =>  $followerNames,
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
        $filename = $request->imgpath->getClientOriginalName();
        $img = $request->imgpath->storeAs('', $filename, 'public');
        $user_id = auth()->id();
        $data = $request->all();
        $tweet->store($user_id, $data, $img);

        $tweet->save();
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
        $tweet_id = $tweet->id;
        $tweet = Tweet::find($tweet_id);
        $tweet->fill($data);
        $tweet->save();
        return redirect('tweets');
    }

    public function destroy(Tweet $tweet)
    {
        $tweet_id = $tweet->id;
        $tweet = Tweet::find($tweet_id);
        $tweet->delete();
        return back();
    }

    public function search(Request $request, Tweet $tweet, Follower $follower, User $user)
    {
        $tweets = Tweet::where('text', 'like', "%{$request->search}%")->paginate(paginateConsts::DISPLAY_PER_PAGE_TWEET);
        $user = auth()->user();
        // フォローしているユーザーID
        $FollowIds = $follower->followingIds($user->id);
        // followed_idだけ抜き出す
        $FollowingIds =  $FollowIds->pluck('followed_id')->toArray();
        $timeLines = $tweet->getTimelines($user->id, $FollowingIds);
        $tweetCount = $tweet->getTweetCount($user->id);
        $followCount = $follower->getFollowCount($user->id);
        $followerCount = $follower->getFollowerCount($user->id);
        $all_users = $user->getAllUsers(auth()->user()->id);
        $followNames = auth()->user()->follows()->get();
        $followerNames = auth()->user()->followers()->get();

        return view('tweets.search', [
            'user'      => $user,
            'timeLines' => $timeLines,
            'tweetCount'     => $tweetCount,
            'followCount'    => $followCount,
            'followerCount'   => $followerCount,
            'all_users'  => $all_users,
            'followNames'  => $followNames,
            'followerNames' =>  $followerNames,
            'tweets' => $tweets,
        ]);
    }
}
