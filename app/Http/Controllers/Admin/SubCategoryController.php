<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Storage;


class SubCategoryController extends Controller
{

    public function index()
    {
        $subcategories = SubCategory::all();
        return view('admin-pages.subcategory.index', compact('subcategories'));
    }


    public function create()
    {
        return view('admin-pages.subcategory.create');
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
        $category = SubCategory::create([
            'name' => $validatedData['name'],
            'content' => $validatedData['content'],
            'image' => $imagePath,
            'original_image_name' => $imageName, // Store the original image name separately
        ]);

        return redirect()->route('subcategories.index')->with('success', 'Category created successfully.');
    }



    public function subshow($id)
    {
        $category = SubCategory::findOrFail($id);
        return view('admin-pages.subcategory.show', compact('category'));
    }


    public function edit($id)
{
    $subcategory = SubCategory::findOrFail($id);
    return view('admin-pages.subcategory.edit', compact('subcategory'));
}


public function update(Request $request, SubCategory $subcategory)
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

    $subcategory->update($validatedData);

    return redirect()->route('subcategories.index')->with('success', 'Category updated successfully.');
}


    public function destroy(SubCategory $subcategory)
    {
        $subcategory->delete();

        return redirect()->route('subcategories.index')->with('success', 'Category deleted successfully.');
    }
}
