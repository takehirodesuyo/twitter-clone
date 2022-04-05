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
        $user = auth()->user();
        $comment->commentStore($user->id, $data);

        return back();
    }
}
