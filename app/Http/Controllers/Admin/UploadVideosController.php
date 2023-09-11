<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;

class UploadVideosController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('admin-pages.upload-videos', compact('categories'));
    }

    public function store(Request $request)
{
    $request->validate([
        'title' => 'required',
        'link' => 'required|url',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'category_id' => 'required|exists:categories,id',
    ]);

    $video = new Video();
    $video->title = $request->input('title');
    $video->link = $request->input('link');
    $video->category_id = $request->input('category_id');

    // Handle the uploaded image
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('images/video'), $imageName);
        $video->image = 'images/video/' . $imageName;
    }

    $video->save();

    return redirect()->route('admin-pages.upload-videos.create')->with('success', 'Video uploaded successfully.');
}


    public function showVideoList()
    {
        $videos = Video::all();
        return view('admin-pages.video-list', compact('videos'));
    }

    public function edit(Video $video)
    {
        $categories = Category::all();
        return view('admin-pages.video-edit', compact('video', 'categories'));
    }

    public function update(Request $request, Video $video)
    {
        $request->validate([
            'title' => 'required',
            'link' => 'required|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        $video->title = $request->input('title');
        $video->link = $request->input('link');
        $video->category_id = $request->input('category_id');

        // Handle the updated image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/video'), $imageName);
            $video->image = 'images/video/' . $imageName;
        }

        $video->save();

        return redirect()->route('admin-pages.video-list')->with('success', 'Video updated successfully.');
    }

    public function destroy(Video $video)
    {
        // Delete the video's image from storage if it exists
        if ($video->image) {
            Storage::delete($video->image);
        }

        $video->delete();

        return redirect()->route('admin-pages.video-list')->with('success', 'Video deleted successfully.');
    }
}
