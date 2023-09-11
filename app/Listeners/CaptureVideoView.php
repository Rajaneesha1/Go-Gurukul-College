<?php

namespace App\Listeners;

use App\Events\VideoViewed;
use App\Models\VideoView;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CaptureVideoView implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  VideoViewed  $event
     * @return void
     */

     /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    public function handle(VideoViewed $event)
    {
        // Retrieve the video by ID
        $video = Video::find($event->videoId);

        if ($video) {
            // Increment the view count
            $video->view_count++;
            $video->save();
        }
    }
}







