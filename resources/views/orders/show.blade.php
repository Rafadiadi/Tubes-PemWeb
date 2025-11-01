<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('orders.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Order #{{ $order->order_number }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <x-alert type="success" :message="session('success')" class="mb-6" />
            @endif

            @if(session('error'))
                <x-alert type="error" :message="session('error')" class="mb-6" />
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Order Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold mb-4">Order Items</h3>
                        
                        <div class="space-y-4">
                            @foreach($order->orderItems as $item)
                                <div class="flex gap-4 p-4 border rounded-lg">
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

                                    <div class="flex-1">
                                        <h4 class="font-semibold">
                                            <a href="{{ route('products.show', $item->product->slug) }}" class="hover:text-indigo-600">
                                                {{ $item->product->name }}
                                            </a>
                                        </h4>
                                        <p class="text-sm text-gray-500">{{ $item->product->category->name }}</p>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-600">
                                                Price: Rp {{ number_format($item->price, 0, ',', '.') }} × {{ $item->quantity }}
                                            </p>
                                            <p class="text-lg font-bold text-indigo-600">
                                                Subtotal: Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Shipping Information</h3>
                        
                        <div class="space-y-3 text-sm">
                            <div>
                                <span class="text-gray-600">Address:</span>
                                <p class="font-medium whitespace-pre-line">{{ $order->shipping_address }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Phone:</span>
                                <p class="font-medium">{{ $order->phone }}</p>
                            </div>
                            @if($order->notes)
                                <div>
                                    <span class="text-gray-600">Notes:</span>
                                    <p class="font-medium">{{ $order->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6 sticky top-6">
                        <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
                        
                        <div class="space-y-2 text-sm mb-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Order Number:</span>
                                <span class="font-medium">{{ $order->order_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Order Date:</span>
                                <span class="font-medium">{{ $order->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'processing' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded {{ $statusColors[$order->status] }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Status:</span>
                                @php
                                    $paymentStatusColors = [
                                        'unpaid' => 'bg-red-100 text-red-800',
                                        'paid' => 'bg-green-100 text-green-800',
                                        'failed' => 'bg-gray-100 text-gray-800',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded {{ $paymentStatusColors[$order->payment_status] }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Method:</span>
                                <span class="font-medium capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                            </div>
                            @if($order->paid_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Paid At:</span>
                                    <span class="font-medium">{{ $order->paid_at->format('d M Y, H:i') }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="border-t pt-4 mb-4 space-y-2">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Shipping</span>
                                <span class="text-green-600">Free</span>
                            </div>
                        </div>

                        <div class="border-t pt-4 mb-6">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total</span>
                                <span class="text-indigo-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        @if($order->status === 'pending')
                            @if($order->payment_status === 'unpaid')
                                <a href="{{ route('checkout.payment', $order) }}" 
                                   class="block w-full mb-3 px-6 py-3 bg-green-600 text-white text-center font-semibold rounded-md hover:bg-green-700">
                                    Complete Payment
                                </a>
                            @endif

                            <form method="POST" action="{{ route('orders.cancel', $order) }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full px-6 py-3 bg-red-600 text-white font-semibold rounded-md hover:bg-red-700"
                                        onclick="return confirm('Are you sure you want to cancel this order?')">
                                    Cancel Order
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
