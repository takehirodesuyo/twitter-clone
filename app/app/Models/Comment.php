<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// 大本のModelが準備してあってそれを使っている。
use Illuminate\Database\Eloquent\Model;

// Modelから伝承
class Comment extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'text'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getComments(Int $tweet_id)
    {
        return $this->with('user')->where('tweet_id', $tweet_id)->get();
    }

    public function commentStore(Int $user_id, Array $data)
    {
        $this->user_id = $user_id;
        $this->tweet_id = $data['tweet_id'];
        $this->text = $data['text'];
        $this->save();

        return;
    }
    
}