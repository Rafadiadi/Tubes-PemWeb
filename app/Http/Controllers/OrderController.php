<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of user's orders.
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['orderItems.product']);

        return view('orders.show', compact('order'));
    }

    /**
     * Cancel an order.
     */
    public function cancel(Order $order)
    {
        $this->authorize('update', $order);

        if ($order->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be cancelled.');
        }

        // Restore product stock
        foreach ($order->orderItems as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        $order->update([
            'status' => 'cancelled',
        ]);

        return back()->with('success', 'Order cancelled successfully!');
    }
}
