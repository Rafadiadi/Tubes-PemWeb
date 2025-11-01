<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the cart.
     */
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Add product to cart.
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Insufficient stock available.');
        }

        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            
            if ($product->stock < $newQuantity) {
                return back()->with('error', 'Insufficient stock available.');
            }
            
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
        }

        return back()->with('success', 'Product added to cart successfully!');
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, Cart $cart)
    {
        $this->authorize('update', $cart);

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($cart->product->stock < $request->quantity) {
            return back()->with('error', 'Insufficient stock available.');
        }

        $cart->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated successfully!');
    }

    /**
     * Remove item from cart.
     */
    public function remove(Cart $cart)
    {
        $this->authorize('delete', $cart);

        $cart->delete();

        return back()->with('success', 'Item removed from cart!');
    }

    /**
     * Clear all cart items.
     */
    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        return back()->with('success', 'Cart cleared successfully!');
    }

    /**
     * Get cart count for header.
     */
    public function count()
    {
        $count = Cart::where('user_id', Auth::id())->sum('quantity');
        return response()->json(['count' => $count]);
    }
}
