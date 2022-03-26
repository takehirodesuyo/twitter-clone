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
}