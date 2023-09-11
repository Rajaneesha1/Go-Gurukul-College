<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Video;
use App\Models\Comment;
use Illuminate\Http\Request;


class CommentController extends Controller
{
    public function store(Request $request, Video $video)
    {
        $request->validate([
            'content' => 'required|string|max:500', // Adjust the validation rules as needed
        ]);

        $comment = new Comment([
            'content' => $request->input('content'),
            'user_id' => Auth::id(), // Set the user_id to the authenticated user's ID
        ]);

        $video->comments()->save($comment);

        return back()->with('success', 'Comment added successfully.'); // Redirect back to the video show page
    }

    public function storeReply(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $reply = new Comment([
            'content' => $request->input('content'),
            'user_id' => auth()->user()->id,
            'parent_id' => $comment->id,
            'video_id' => $comment->video_id,
        ]);

        $comment->replies()->save($reply);

        return back()->with('success', 'Reply added successfully.');
    }

    public function destroyReply(Comment $comment)
    {
        // Add logic to delete the reply if needed

        return back()->with('success', 'Reply deleted successfully.');
    }

}
