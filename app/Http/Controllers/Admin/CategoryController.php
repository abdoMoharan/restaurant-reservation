<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequstCategoryStore;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Storage;
class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index',compact('categories'));
    }


    public function create()
    {
        return view('admin.categories.create');

    }

    public function store(RequstCategoryStore $request)
    {
            $image = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->store('category', 'attachment');
        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $path,
        ]);
        return to_route('admin.categoreis.index')->with('success', 'Category Ceated successfully.');;

    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('admin.categories.edit',compact('category'));
    }


    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);
        $category = Category::findOrFail($request->id);
        if($request->hasFile('image')){
            Storage::disk('attachment')->delete($category->image);
            $path = $request->file('image')->store('category', 'attachment');
            $category->update([
                'name' => $request->name,
                'description' => $request->description,
                'image' => $path
            ]);
        }
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return to_route('admin.categoreis.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Request $request)
    {
        $category = Category::findOrFail($request->id);
        Storage::disk('attachment')->delete($category->image);
        $category->menus()->detach();
        $category->delete();
        return to_route('admin.categoreis.index')->with('danger', 'Category deleted successfully.');
    }
}
