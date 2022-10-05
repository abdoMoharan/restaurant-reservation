<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequstMenuStore;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = Menu::all();
        return view('admin.menus.index',compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.menus.create',compact('categories'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequstMenuStore $request)
    {
        $image = $request->file('image')->getClientOriginalName();
        $path = $request->file('image')->store('menus', 'attachment');
        $menus=Menu::create([
            'name' => $request->name,
            'image' => $path,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        if($request->has('categories')){
            $menus->categories()->attach($request->categories);
        }
        return to_route('admin.menus.index')->with('success', 'Menu Create successfully.');

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        $categories = Category::all();
        return view('admin.menus.edit',compact('categories', 'menu'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price'=>'required'
        ]);
        $menu = Menu::findOrFail($request->id);
        if ($request->hasFile('image')) {
            Storage::disk('attachment')->delete($menu->image);
            $path = $request->file('image')->store('menus', 'attachment');
            $menu->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'image' => $path
            ]);
        }

        $menu->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);
        if ($request->has('categories')) {
            $menu->categories()->sync($request->categories);
        }

        return to_route('admin.menus.index')->with('success', 'Menu updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $menu = Menu::findOrFail($request->id);
        Storage::disk('attachment')->delete($menu->image);
        $menu->categories()->detach();
        $menu->delete();
        return to_route('admin.menus.index')->with('danger', 'Menu deleted successfully.');
    }
}
