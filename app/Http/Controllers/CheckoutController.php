<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Display the checkout form.
     */
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        // Check stock availability
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return redirect()->route('cart.index')
                    ->with('error', "Insufficient stock for {$item->product->name}");
            }
        }

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return view('checkout.index', compact('cartItems', 'total'));
    }

    /**
     * Process the checkout.
     */
    public function process(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|in:credit_card,bank_transfer,cod',
            'notes' => 'nullable|string|max:500',
        ]);

        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        DB::beginTransaction();

        try {
            // Create order
            $totalAmount = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => Order::generateOrderNumber(),
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_method' => $request->payment_method,
                'shipping_address' => $request->shipping_address,
                'phone' => $request->phone,
                'notes' => $request->notes,
            ]);

            // Create order items and update stock
            foreach ($cartItems as $item) {
                if ($item->product->stock < $item->quantity) {
                    throw new \Exception("Insufficient stock for {$item->product->name}");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'subtotal' => $item->quantity * $item->product->price,
                ]);

                // Update product stock
                $item->product->decrement('stock', $item->quantity);
            }

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('checkout.payment', $order)->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process order: ' . $e->getMessage());
        }
    }

    /**
     * Display payment page (simulation).
     */
    public function payment(Order $order)
    {
        $this->authorize('view', $order);

        return view('checkout.payment', compact('order'));
    }

    /**
     * Process payment (simulation).
     */
    public function processPayment(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $request->validate([
            'payment_method' => 'required|in:credit_card,bank_transfer,cod',
        ]);

        // Simulate payment processing
        $order->update([
            'payment_status' => 'paid',
            'status' => 'processing',
            'paid_at' => now(),
        ]);

        return redirect()->route('orders.show', $order)->with('success', 'Payment successful!');
    }
}
