<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    protected $primaryKey = [
        'following_id',
        'followed_id'
    ];
    protected $fillable = [
        'following_id',
        'followed_id'
    ];
    public $timestamps = false;
    public $incrementing = false;

    // フォロー数
    public function getFollowCount($user_id)
    {
        return $this->where('following_id', $user_id)->count();
    }

    // フォロワー数
    public function getFollowerCount($user_id)
    {
        return $this->where('followed_id', $user_id)->count();
    }

    // フォローしているユーザー
    public function getFollowName($user_id)
    {
        return $this->where('following_id', $user_id);
    }


    // フォローしているユーザーID取得
    public function followingIds(Int $user_id)
    {
        return $this->where('following_id', $user_id)->get('followed_id');
    }
}
