<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\VideoView;
use App\Events\VideoViewed;
use App\Models\Category;
use App\Models\Content;

class ContentUpController extends Controller
{
    // Show the content upload form
    public function showContentUploadForm()
    {
        $categories = Category::all();
        return view('admin-pages.upload_content', compact('categories'));
    }

    // Handle the content upload
    public function uploadContent(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Save the content details to the database
        Content::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'category_id' => $request->input('category_id'),
        ]);

        return redirect()->route('admin.upload.content.form')->with('success', 'Content uploaded successfully.');
    }

    public function showContent()
    {
        $contents = Content::all();
        return view('admin-pages.view-content', compact('contents'));
    }

    public function edit(Content $content)
    {
        $categories = Category::all();
        return view('admin-pages.content-edit', compact('content', 'categories'));
    }

    public function update(Request $request, Content $content)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);

        $content->title = $request->input('title');
        $content->description = $request->input('description');
        $content->category_id = $request->input('category_id');
        $content->save();

        return redirect()->route('admin.content')->with('success', 'Content updated successfully.');
    }

    public function destroy(Content $content)
    {
        $content->delete();

        return redirect()->route('admin.content')->with('success', 'Content deleted successfully.');
    }
}

