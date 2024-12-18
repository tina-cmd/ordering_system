<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function get_order()
    {
        $orders = DB::table('restaurant.orders')->get();

        return Inertia::render('Order', ['orders' => $orders]);
    }


    public function update_order(Request $request)
    {
        try {
            // Validate input
            $validate = $request->validate([
                'order_number' => 'required',
                'status' => 'required',
            ]);

            // Update the status in the database
            DB::table('restaurant.orders')
                ->where('order_number', $validate['order_number'])
                ->update([
                    'status' => $validate['status']
                ]);

            // Return success response
            return Inertia::location(route('order'))->with('success', 'Order updated successfully!');

        } catch (\Exception $e) {
            // Log the error (optional)
            \Log::error('Error updating order: ' . $e->getMessage());

            // Return an error response
            return Inertia::location(route('order'))->with('error', 'Failed to update the order. Please try again.');
        }
    }
}
