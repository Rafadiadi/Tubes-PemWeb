<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <x-alert type="error" :message="session('error')" class="mb-6" />
            @endif

            <form method="POST" action="{{ route('checkout.process') }}">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Shipping Information -->
                    <div class="lg:col-span-2">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold mb-4">Shipping Information</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1">
                                        Shipping Address *
                                    </label>
                                    <textarea id="shipping_address" 
                                              name="shipping_address" 
                                              rows="3" 
                                              required
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('shipping_address') }}</textarea>
                                    @error('shipping_address')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                        Phone Number *
                                    </label>
                                    <input type="tel" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone') }}"
                                           required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('phone')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                        Order Notes (Optional)
                                    </label>
                                    <textarea id="notes" 
                                              name="notes" 
                                              rows="2" 
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-4">Payment Method</h3>
                            
                            <div class="space-y-3">
                                <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" 
                                           name="payment_method" 
                                           value="credit_card" 
                                           {{ old('payment_method', 'credit_card') == 'credit_card' ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-3">
                                        <span class="block font-medium">Credit Card</span>
                                        <span class="text-sm text-gray-500">Pay securely with your credit card</span>
                                    </span>
                                </label>

                                <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" 
                                           name="payment_method" 
                                           value="bank_transfer" 
                                           {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-3">
                                        <span class="block font-medium">Bank Transfer</span>
                                        <span class="text-sm text-gray-500">Transfer to our bank account</span>
                                    </span>
                                </label>

                                <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" 
                                           name="payment_method" 
                                           value="cod" 
                                           {{ old('payment_method') == 'cod' ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-3">
                                        <span class="block font-medium">Cash on Delivery</span>
                                        <span class="text-sm text-gray-500">Pay when you receive your order</span>
                                    </span>
                                </label>
                            </div>

                            @error('payment_method')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 sticky top-6">
                            <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
                            
                            <div class="space-y-3 mb-4">
                                @foreach($cartItems as $item)
                                    <div class="flex justify-between text-sm">
                                        <span>{{ $item->product->name }} ({{ $item->quantity }}x)</span>
                                        <span>Rp {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t pt-4 mb-4 space-y-2">
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

                            <button type="submit" 
                                    class="w-full px-6 py-3 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700">
                                Place Order
                            </button>

                            <a href="{{ route('cart.index') }}" 
                               class="block w-full mt-3 px-6 py-3 bg-gray-200 text-gray-800 text-center font-semibold rounded-md hover:bg-gray-300">
                                Back to Cart
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
