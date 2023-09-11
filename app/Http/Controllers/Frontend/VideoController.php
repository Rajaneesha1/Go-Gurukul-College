<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Video;
use App\Models\Comment;
use App\Models\VideoView;


class VideoController extends Controller
{
    public function index(Category $category)
    {
        $videos = Video::where('category_id', $category->id)->get();
        return view('videos.index', compact('category', 'videos'));
    }

    public function showVideo($id)
{
    $video = Video::findOrFail($id);
        $comments = $video->comments;

        $user = auth()->user();
        $videoView = VideoView::where('user_id', $user->id)
        ->where('video_id', $video->id)
        ->where('category_id', $video->category_id)
        ->first();

        // If the video view doesn't exist, create a new entry
        if (!$videoView) {
            $videoView = new VideoView([
                'user_id' => $user->id,
                'video_id' => $video->id,
                'category_id' => $video->category_id,
                'watched' => true // Set to true since the user is viewing the video now
            ]);
            $videoView->save();
        }

        return view('videos.show', compact('video', 'comments'));
    }

    public function getProgress($categoryId)
{
    // Retrieve progress data from the database
    $category = VideoView::find($categoryId);
    $totalVideos = $category->videos->count();
    $watchedVideos = $category->videos->where('watched', 1)->count();

    // Calculate the percentage of videos watched
    $percentageWatched = ($watchedVideos / $totalVideos) * 100;

    // Return the progress data as JSON
    return response()->json(['percentageWatched' => $percentageWatched]);
}




}

