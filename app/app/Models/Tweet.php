<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Consts\paginateConsts;
use Illuminate\Support\Facades\Auth;

class Tweet extends Model
{
    protected $fillable = [
        'text',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // 一つの投稿に良いね複数つけるから多
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    // 一つの投稿にコメント複数つけるから多
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getUserTimeLine(Int $user_id)
    {
        return $this->where('user_id', $user_id)->orderBy('created_at', 'DESC')->paginate(paginateConsts::DISPLAY_PER_PAGE_TWEET);
    }

    // ツイート数
    public function getTweetCount(Int $user_id)
    {
        return $this->where('user_id', $user_id)->count();
    }

    // 一覧画面                    自分とフォローしているユーザーID
    public function getTimeLines(Int $user_id, array $follow_ids)
    {
        // 自身とフォローしているユーザIDを結合する
        $follow_ids[] = $user_id;
        return $this->whereIn('user_id', $follow_ids)->orderBy('created_at', 'DESC')->paginate(paginateConsts::DISPLAY_PER_PAGE_TWEET);
    }

    public function Tweet(Int $tweet_id)
    {
        return $this->with('user')->where('id', $tweet_id)->first();
    }

    public function store(Int $user_id, array $data, $img)
    {
        $this->user_id = $user_id;
        $this->text = $data['text'];
        $this->image = $img;

        $this->save();

        return;
    }

    public function getTweetByUserIdAndTweetId(Int $user_id, Int $tweet_id)
    {
        return $this->where('user_id', $user_id)->where('id', $tweet_id)->first();
    }

    public function getTweetByUserIdAndAuthId()
    {
        return $this->user_id === Auth::id();
    }
}
