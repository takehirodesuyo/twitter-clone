<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;

class LikeController extends Controller
{
    public function Like(Request $request, Tweet $tweet)
    {
        dd($request);
    }
}
