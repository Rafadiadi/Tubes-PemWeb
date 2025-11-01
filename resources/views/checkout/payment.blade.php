<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Order Placed Successfully!</h3>
                    <p class="text-gray-600">Order #{{ $order->order_number }}</p>
                </div>

                <!-- Order Summary -->
                <div class="border-t border-b py-6 mb-6">
                    <h4 class="font-semibold mb-4">Order Details</h4>
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order Number:</span>
                            <span class="font-medium">{{ $order->order_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order Date:</span>
                            <span class="font-medium">{{ $order->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Amount:</span>
                            <span class="font-medium text-lg text-indigo-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Payment Method:</span>
                            <span class="font-medium capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Simulation -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <h5 class="font-semibold text-yellow-800 mb-1">Payment Simulation</h5>
                            <p class="text-sm text-yellow-700">
                                This is a simulated payment. Click the button below to simulate a successful payment.
                            </p>
                        </div>
                    </div>
                </div>

                @if($order->payment_method == 'bank_transfer')
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <h5 class="font-semibold mb-2">Bank Transfer Instructions</h5>
                        <div class="space-y-1 text-sm">
                            <p><strong>Bank:</strong> Bank Central Asia (BCA)</p>
                            <p><strong>Account Number:</strong> 1234567890</p>
                            <p><strong>Account Name:</strong> E-Commerce Store</p>
                            <p><strong>Amount:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endif

                @if($order->payment_method == 'credit_card')
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <h5 class="font-semibold mb-3">Credit Card Payment (Simulated)</h5>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium mb-1">Card Number</label>
                                <input type="text" value="4111 1111 1111 1111" readonly 
                                       class="w-full rounded-md border-gray-300 bg-white">
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Expiry</label>
                                    <input type="text" value="12/25" readonly 
                                           class="w-full rounded-md border-gray-300 bg-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">CVV</label>
                                    <input type="text" value="123" readonly 
                                           class="w-full rounded-md border-gray-300 bg-white">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Complete Payment -->
                <form method="POST" action="{{ route('checkout.payment.process', $order) }}">
                    @csrf
                    <input type="hidden" name="payment_method" value="{{ $order->payment_method }}">
                    
                    <button type="submit" 
                            class="w-full px-6 py-3 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700 mb-3">
                        Complete Payment (Simulation)
                    </button>
                </form>

                <a href="{{ route('orders.show', $order) }}" 
                   class="block w-full px-6 py-3 bg-gray-200 text-gray-800 text-center font-semibold rounded-md hover:bg-gray-300">
                    View Order Details
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
