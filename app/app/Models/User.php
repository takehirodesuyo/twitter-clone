<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Consts\paginateConsts;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // 可変項目
    protected $fillable = [
        'name',
        'profile_image',
        'email',
        'password'
    ];

    /**
     * フォローするメソッド
     * 
     * @params int $user_id
     * 
     * @return int
     */
    public function follow(Int $user_id)
    {
        return $this->follows()->attach($user_id);
    }

    /**
     * フォロー解除するメソッド
     * 
     * @params int $user_id
     * 
     * @return int
     */
    public function unfollow(Int $user_id)
    {
        return $this->follows()->detach($user_id);
    }
    /**
     * フォローしているか判定するメソッド
     * 
     * @params int $user_id
     * 
     * @return int
     */
    public function isFollowing(Int $user_id)
    {
        return $this->follows()->where('followed_id', $user_id)->exists();
    }
    /**
     * フォローされているか判定するメソッド
     * 
     * @params int $user_id
     * 
     * @return int
     */
    public function isFollowed(Int $user_id)
    {
        return $this->followers()->where('following_id', $user_id)->exists();
    }
    /**
     * ログインユーザー以外のユーザーIDを取得するメソッド
     * 
     * @params int $user_id
     * 
     * @return int
     */
    public function getAllUsers(Int $user_id)
    {
        return $this->Where('id', '<>', $user_id)->paginate(paginateConsts::DISPLAY_PER_PAGE_USER);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 多対多
    public function followers()
    {
        return $this->belongsToMany(self::class, 'followers', 'followed_id', 'following_id');
    }

    public function follows()
    {
        return $this->belongsToMany(self::class, 'followers', 'following_id', 'followed_id');
    }

    public function updateProfile(array $params)
    {
        if (isset($params['profile_image'])) {
            $file_name = $params['profile_image']->store('public/images');

            $this::where('id', $this->id)
                ->update([
                    'name'          => $params['name'],
                    'profile_image' => basename($file_name),
                    'email'         => $params['email'],
                ]);
        } else {
            $this::where('id', $this->id)
                ->update([
                    'name'          => $params['name'],
                    'email'         => $params['email'],
                ]);
        }

        return;
    }
}
