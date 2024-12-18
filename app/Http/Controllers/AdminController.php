<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    // Render the Dashboard page
    public function dashboard()
    {   
        $menu = DB::table('restaurant.menu')
            ->get();
        $order = DB::table('restaurant.orders')
            ->get();
        $inventory = DB::table('admin.inventory')
            ->get();

        return Inertia::render('Dashboard', [
            'menu' => $menu,
            'order' => $order,
            'inventory' => $inventory,
        ]);
    }

    // Render the Menu page
    public function menu()
    {
        $response = Menu::all();
        
    
        return Inertia::render('Menu', ['data' => $response]);
    }

    // Render the Analytics page
    public function analytics()
    {
        return Inertia::render('Analytics');
    }
}