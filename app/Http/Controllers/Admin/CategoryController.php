<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;


class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::all();
        return view('admin-pages.category.index', compact('categories'));
    }


    public function create()
    {
        return view('admin-pages.category.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Require a valid image
        ]);

        // Save the image in the public folder with the original name
        $imagePath = $request->file('image')->storePublicly('images/category', 'public');

        // Get the original image name
        $imageName = $request->file('image')->getClientOriginalName();

        // Create the category record in the database
        $category = Category::create([
            'name' => $validatedData['name'],
            'content' => $validatedData['content'],
            'image' => $imagePath,
            'original_image_name' => $imageName, // Store the original image name separately
        ]);

        return redirect()->route('categories.index')->with('success', 'Subject created successfully.');
    }



    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('admin-pages.category.show', compact('category'));
    }


    public function edit($id)
{
    $category = Category::findOrFail($id);
    return view('admin-pages.category.edit', compact('category'));
}


public function update(Request $request, Category $category)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'content' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Add image validation rule
    ]);

    if ($request->hasFile('image')) {
        // Handle image upload
        $imagePath = $request->file('image')->storePublicly('images/category', 'public');
        $imageName = $request->file('image')->getClientOriginalName();
        $validatedData['image'] = $imagePath;
    }

    $category->update($validatedData);

    return redirect()->route('categories.index')->with('success', 'Subject updated successfully.');
}

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Subject deleted successfully.');
    }
}
