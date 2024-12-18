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

            // dd($validate); 
            if ($validate['status'] == 'Ready') {
                DB::table('restaurant.orders')
                    ->where('order_id', $validate['order_number'])
                    ->update([
                        'status' => $validate['status']
                    ]);

                // dd('hello'); 
                // Return success response
                session()->flash('success', 'Order is ready');

                // Redirect using Inertia location
                return Inertia::location(route('order'));
            }

            // Update the status in the database
            DB::table('restaurant.orders')
                ->where('order_number', $validate['order_number'])
                ->update([
                    'status' => $validate['status']
                ]);

            // Return success response
            session()->flash('success', 'Order has been completed');

            // Redirect using Inertia location
            return Inertia::location(route('order'));

        } catch (\Exception $e) {
            // Log the error (optional)
            \Log::error('Error updating order: ' . $e->getMessage());

            // Return an error response
            session()->flash('error', 'Error in updating order.');

            // Redirect using Inertia location
            return Inertia::location(route('order'));
        }
    }
}
