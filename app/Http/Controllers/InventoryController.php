<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Inertia\Inertia;

class InventoryController extends Controller
{
    // Render the Inventory page
    public function inventory()
    {   
        $menu = DB::table('restaurant.menu')
            ->get();

        $inventory = DB::table('admin.inventory')
            ->get();
        return Inertia::render('Inventory', [
            'inventory' => $inventory,
            'menu' => $menu
        ]);
    }

    public function inventory_update(Request $request) {
        // Validate the incoming request data
        $validator = $request->validate([
            'item_name' => 'required',
            'quantity' => 'required',
        ]);
        
        try {
            // Update the 'paid' status for the order with the given order number
            $updated = DB::table('admin.inventory')
                ->where('item_name', $validator['item_name'])
                ->update([
                    'quantity_in_stock' => $validator['quantity'], // Change 'Paid' to a boolean or a constant if needed
                ]);

            // If no rows were updated, return an error (order might not exist or other issues)
            if ($updated === 0) {
                return back()->with('error', 'Item not found');
            }
            // dd($updated);

            // Return success and redirect back to cashier page with flash data
            return redirect()->route('inventory')->with('success', 'Inventory item updated successfully.');

        } catch (QueryException $e) {

            // Return with an error message
            return back()->with('error', 'An error occurred while editing the item.');
        }
    }
}
