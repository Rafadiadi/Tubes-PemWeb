<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <x-alert type="success" :message="session('success')" class="mb-6" />
            @endif

            @if(session('error'))
                <x-alert type="error" :message="session('error')" class="mb-6" />
            @endif

            @if($cartItems->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-12 text-center">
                    <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Your cart is empty</h3>
                    <p class="text-gray-600 mb-6">Add some products to your cart to continue shopping</p>
                    <a href="{{ route('products.index') }}" 
                       class="inline-block px-6 py-3 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700">
                        Browse Products
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold mb-4">Cart Items ({{ $cartItems->count() }})</h3>
                                
                                <div class="space-y-4">
                                    @foreach($cartItems as $item)
                                        <div class="flex gap-4 p-4 border rounded-lg">
                                            <!-- Product Image -->
                                            <a href="{{ route('products.show', $item->product->slug) }}" class="flex-shrink-0">
                                                <div class="w-24 h-24 bg-gray-200 rounded">
                                                    @if($item->product->image)
                                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                             alt="{{ $item->product->name }}" 
                                                             class="w-full h-full object-cover rounded">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                            </a>

                                            <!-- Product Info -->
                                            <div class="flex-1">
                                                <h4 class="font-semibold">
                                                    <a href="{{ route('products.show', $item->product->slug) }}" class="hover:text-indigo-600">
                                                        {{ $item->product->name }}
                                                    </a>
                                                </h4>
                                                <p class="text-sm text-gray-500">{{ $item->product->category->name }}</p>
                                                <p class="text-lg font-bold text-indigo-600 mt-2">
                                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                                </p>
                                            </div>

                                            <!-- Quantity & Actions -->
                                            <div class="flex flex-col items-end justify-between">
                                                <form method="POST" action="{{ route('cart.remove', $item) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>

                                                <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center gap-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="number" 
                                                           name="quantity" 
                                                           value="{{ $item->quantity }}" 
                                                           min="1" 
                                                           max="{{ $item->product->stock }}"
                                                           class="w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                           onchange="this.form.submit()">
                                                </form>

                                                <p class="text-sm font-semibold">
                                                    Subtotal: Rp {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Clear Cart -->
                                <form method="POST" action="{{ route('cart.clear') }}" class="mt-4">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-semibold"
                                            onclick="return confirm('Are you sure you want to clear your cart?')">
                                        Clear Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 sticky top-6">
                            <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
                            
                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between">
                                    <span>Subtotal</span>
                                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Shipping</span>
                                    <span class="text-green-600">Free</span>
                                </div>
                            </div>

                            <div class="border-t pt-4 mb-6">
                                <div class="flex justify-between text-lg font-bold">
                                    <span>Total</span>
                                    <span class="text-indigo-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <a href="{{ route('checkout.index') }}" 
                               class="block w-full px-6 py-3 bg-indigo-600 text-white text-center font-semibold rounded-md hover:bg-indigo-700">
                                Proceed to Checkout
                            </a>

                            <a href="{{ route('products.index') }}" 
                               class="block w-full mt-3 px-6 py-3 bg-gray-200 text-gray-800 text-center font-semibold rounded-md hover:bg-gray-300">
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
