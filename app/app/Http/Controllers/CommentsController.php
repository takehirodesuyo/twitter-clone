<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Tweet;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Comment\CommentRequest;

class CommentsController extends Controller
{
    public function store(CommentRequest $request, Comment $comment)
    {
        $user = auth()->id();
        $data = $request->all();
        $comment->storeComment($user, $data);

        return back();
    }
}
