<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\VideoView;
use App\Events\VideoViewed;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->input('date');
        $query = VideoView::with('video', 'user')->orderByDesc('created_at');

        if ($selectedDate) {
            $query->whereDate('created_at', $selectedDate);
        }

        $videoViews = $query->paginate(10);

        return view('admin-pages.analytics', compact('videoViews', 'selectedDate'));
    }

    public function view(Request $request, $videoId)
    {
        // Logic to retrieve the video and user ID
        $userId = $request->user()->id;

        // Dispatch the VideoViewed event
        event(new VideoViewed($videoId, $userId));

        // Rest of the code to display the video view
        $videoView = VideoView::with('video', 'user')->find($videoId);

        if (!$videoView) {
            return redirect()->back()->with('status', 'Video view not found.');
        }

        return view('admin-pages.analytics', compact('videoView'));
    }
}
