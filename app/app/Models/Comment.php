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
}