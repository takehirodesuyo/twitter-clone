<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;

class FavoritesController extends Controller
{
    public function store(Request $request, Favorite $favorite)
    {
        $userId = auth()->id();
        $tweetId = $request->tweet_id;
        $isFavorite = $favorite->isFavorite($userId, $tweetId);

        if (!$isFavorite) {
            $favorite->storeFavorite($userId, $tweetId);
        }
        return back();
    }

    public function destroy(Favorite $favorite)
    {
        $userId = $favorite->user_id;
        $tweetId = $favorite->tweet_id;
        $favoriteId = $favorite->id;
        $isFavorite = $favorite->isFavorite($userId, $tweetId);

        if ($isFavorite) {
            $favorite->destroyFavorite($favoriteId);
        }
        return back();
    }
}
