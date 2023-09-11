<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\VideoView;
use App\Events\VideoViewed;
use App\Models\User;

class ViewReportController extends Controller
{
    public function index()
    {
        $videoViews = VideoView::with('video', 'user')->orderByDesc('created_at')->paginate(10);
        return view('admin-pages.view-report', compact('videoViews'));
    }
    public function view(Request $request, $videoId)
    {
        // Logic to retrieve the video and user ID
        $userId = $request->user()->id;

        // Dispatch the VideoViewed event
        event(new VideoViewed($videoId, $userId));

        // Rest of the code to display the video view
    }
}
