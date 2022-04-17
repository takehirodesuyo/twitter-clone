<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Consts\paginateConsts;
use App\Models\Comment;
use App\Models\Follower;
use App\Models\Tweet;
use App\Models\User;
use App\Http\Requests\Tweet\TweetRequest;

class TweetsController extends Controller
{
    /**
     * 投稿一覧を表示するメソッド
     * 
     * @params int $tweet,
     * @params int $follower,
     * @params int $user,
     * 
     * @return Illuminate\Http\Response;
     */
    public function index(Tweet $tweet, Follower $follower, User $user)
    {
        $user = auth()->user();
        // フォローしているユーザーID
        $followIds = $follower->followingIds($user->id);
        // followed_idだけ抜き出す
        $followingIds =  $followIds->pluck('followed_id')->toArray();
        $timeLines = $tweet->getTimelines($user->id, $followingIds);
        $tweetCount = $tweet->getTweetCount($user->id);
        $followCount = $follower->getFollowCount($user->id);
        $followerCount = $follower->getFollowerCount($user->id);
        $all_users = $user->getAllUsers(auth()->user()->id);
        $followNames = auth()->user()->follows()->get();
        $followerNames = auth()->user()->followers()->get();
        return view('tweets.index', [
            'user'           => $user,
            'timeLines'      => $timeLines,
            'tweetCount'     => $tweetCount,
            'followCount'    => $followCount,
            'followerCount'  => $followerCount,
            'all_users'      => $all_users,
            'followNames'    => $followNames,
            'followerNames'  =>  $followerNames,
        ]);
    }
    /**
     * 新規投稿画面を表示するメソッド
     * 
     * @return Illuminate\Http\Response;
     */

    public function create()
    {
        $user = auth()->user();

        return view('tweets.create', [
            'user' => $user
        ]);
    }
    /**
     * 投稿を保存するメソッド
     * 
     * @params int $request,
     * @params int $tweet,
     * 
     * @return Illuminate\Http\Response;
     */

    public function store(TweetRequest $request, Tweet $tweet)
    {
        $user_id = auth()->id();
        $data = $request->all();

        if (isset($request->imgpath)) {
            $filename = $request->imgpath->getClientOriginalName();
            $img = $request->imgpath->storeAs('', $filename, 'public');
        } else {
            $img = null;
        }

        $tweet->store($user_id, $data, $img);
        $tweet->save();
        return redirect('tweets')->with('flash_message', '投稿されました！');
    }
    /**
     * 投稿詳細を表示するメソッド
     * 
     * @params int $tweet,
     * @params int $comment,
     * 
     * @return Illuminate\Http\Response;
     */

    public function show(Tweet $tweet, Comment $comment)
    {
        $userId = auth()->id();
        $tweet = $tweet->Tweet($tweet->id);
        $comments = $comment->Comments($tweet->id);

        return view('tweets.show', [
            'user'     => $userId,
            'tweet' => $tweet,
            'comments' => $comments
        ]);
    }
    /**
     * 投稿編集画面を表示するメソッド
     * 
     * @params int $tweet
     * 
     * @return Illuminate\Http\Response;
     */
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
    /**
     * 投稿を更新するメソッド
     * 
     * @params int $request
     * @params int $tweet
     * 
     * @return Illuminate\Http\Response;
     */

    public function update(TweetRequest $request, Tweet $tweet)
    {
        $data = $request->all();
        $tweet_id = $tweet->id;
        $tweet = Tweet::find($tweet_id);
        $tweet->fill($data);
        $tweet->save();
        return redirect('tweets');
    }
    /**
     * 投稿を削除するメソッド
     * 
     * @params int $tweet
     * 
     * @return Illuminate\Http\Response;
     */
    public function destroy(Tweet $tweet)
    {
        $tweet_id = $tweet->id;
        $tweet = Tweet::find($tweet_id);
        $tweet->delete();
        return back();
    }
    /**
     * 検索結果を表示するメソッド
     * 
     * @params int $request
     * @params int $tweet,
     * @params int $follower,
     * @params int $user,
     * 
     * @return Illuminate\Http\Response;
     */

    public function search(Request $request, Tweet $tweet, Follower $follower, User $user)
    {
        $tweets = Tweet::where('text', 'like', "%{$request->search}%")->paginate(paginateConsts::DISPLAY_PER_PAGE_TWEET);
        $user = auth()->user();
        // フォローしているユーザーID
        $followIds = $follower->followingIds($user->id);
        // followed_idだけ抜き出す
        $followingIds =  $followIds->pluck('followed_id')->toArray();
        $timeLines = $tweet->getTimelines($user->id, $followingIds);
        $tweetCount = $tweet->getTweetCount($user->id);
        $followCount = $follower->getFollowCount($user->id);
        $followerCount = $follower->getFollowerCount($user->id);
        $all_users = $user->getAllUsers(auth()->user()->id);
        $followNames = auth()->user()->follows()->get();
        $followerNames = auth()->user()->followers()->get();

        return view('tweets.search', [
            'user'           => $user,
            'timeLines'      => $timeLines,
            'tweetCount'     => $tweetCount,
            'followCount'    => $followCount,
            'followerCount'  => $followerCount,
            'all_users'      => $all_users,
            'followNames'    => $followNames,
            'followerNames'  => $followerNames,
            'tweets'         => $tweets,
        ])->with('test', '投稿されました！');;
    }
}
