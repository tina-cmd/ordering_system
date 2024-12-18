<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function menu_add(Request $request)
    {

        // Validate incoming request data
        $validated = $request->validate([
            'id' => 'required|integer',
            'item_name' => 'required|string',
            'category_name' => 'required',
            'price' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:10240'
        ]);
        // dd($validated);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('uploads', 'public');
        }

        // Create a new menu item using Menu::create() method
        DB::table('restaurant.menu')->insert([
            'category_id' => $validated['id'],
            'item_name' => $validated['item_name'],
            'category_name' => $validated['category_name'],
            'price' => $validated['price'],
            'image' => $imagePath, // Store image path in the database
        ]);

        return Inertia::location(route('menu'));

    }

    public function menu_update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'item_name' => 'required|string',
            'category_name' => 'required',
            'price' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:10240'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('uploads', 'public');
        }

        DB::table('restaurant.menu')
            ->where('category_id', $validated['id'])
            ->update([
                'item_name' => $validated['item_name'],
                'category_name' => $validated['category_name'],
                'price' => $validated['price'],
                'image' => $imagePath, // Store image path in the database
            ]);

        return Inertia::location(route('menu'));
    }

    public function destroy(Request $request)
    {
        $item = DB::table('restaurant.menu')
            ->where('category_id', $request->id)
            ->delete();


        return Inertia::location(route('menu'));
    }
}
