<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    protected $fillable = [
        'text'
    ];
    // tweetに対しユーザーは1
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
        // whereでDBから値取る。第一引数にカラム名、第二引数に比較演算子、またはSQLで使うオペレータ、第三引数に比較する値を指定する。
        // 第二引数がイコールの場合省略可能。なのでこの場合省略されている。oerderByでカラム並び替え。この場合、created_at カラムでDESCで降順という意味。
        return $this->where('user_id', $user_id)->orderBy('created_at', 'DESC')->paginate(50);
    }

    public function getTweetCount(Int $user_id)
    {
        return $this->where('user_id', $user_id)->count();
    }
    
    // 一覧画面
    public function getTimeLines(Int $user_id, Array $follow_ids)
    {
        // 自身とフォローしているユーザIDを結合する
        $follow_ids[] = $user_id;
        return $this->whereIn('user_id', $follow_ids)->orderBy('created_at', 'DESC')->paginate(50);
    }

    public function getTweet(Int $tweet_id)
    {
        return $this->with('user')->where('id', $tweet_id)->first();
    }

    public function tweetStore(Int $user_id, Array $data)
    {
        $this->user_id = $user_id;
        $this->text = $data['text'];
        $this->save();

        return;
    }

    public function getEditTweet(Int $user_id, Int $tweet_id)
    {
        return $this->where('user_id', $user_id)->where('id', $tweet_id)->first();
    }
}