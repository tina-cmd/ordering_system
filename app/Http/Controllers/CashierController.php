<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class CashierController extends Controller
{
    public function cashier()
    {
        $orders = DB::table('restaurant.orders')
            ->where('paid', 'Unpaid')
            ->where('status', 'Pending')
            ->get();
        // dd($orders);


        return Inertia::render('Cashier', ['orders' => $orders]);
    }


    public function pay(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'order_num' => 'required|exists:restaurant.orders,order_number',
        ]);

        try {
            // Update the 'paid' status for the order with the given order number
            $updated = DB::table('restaurant.orders')
                ->where('order_number', $request->order_num)
                ->update([
                    'paid' => 'Paid', // Change 'Paid' to a boolean or a constant if needed
                ]);

            // If no rows were updated, return an error (order might not exist or other issues)
            if ($updated === 0) {
                return back()->with('error', 'Order not found or already paid.');
            }

            // Return success and redirect back to cashier page with flash data
            return Inertia::render(route('cashier'))->with('success', 'Order marked as paid successfully.');

        } catch (QueryException $e) {

            // Return with an error message
            return back()->with('error', 'An error occurred while marking the order as paid.');
        }
    }

}
