<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Orders') }}
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

            @if($orders->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-12 text-center">
                    <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No orders yet</h3>
                    <p class="text-gray-600 mb-6">Start shopping to see your orders here</p>
                    <a href="{{ route('products.index') }}" 
                       class="inline-block px-6 py-3 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700">
                        Browse Products
                    </a>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($orders as $order)
                                <div class="border rounded-lg p-6 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="text-lg font-semibold">
                                                <a href="{{ route('orders.show', $order) }}" class="hover:text-indigo-600">
                                                    Order #{{ $order->order_number }}
                                                </a>
                                            </h3>
                                            <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-indigo-600">
                                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                            </p>
                                            <div class="flex gap-2 mt-2">
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'processing' => 'bg-blue-100 text-blue-800',
                                                        'completed' => 'bg-green-100 text-green-800',
                                                        'cancelled' => 'bg-red-100 text-red-800',
                                                    ];
                                                    $paymentStatusColors = [
                                                        'unpaid' => 'bg-red-100 text-red-800',
                                                        'paid' => 'bg-green-100 text-green-800',
                                                        'failed' => 'bg-gray-100 text-gray-800',
                                                    ];
                                                @endphp
                                                <span class="px-2 py-1 text-xs font-semibold rounded {{ $statusColors[$order->status] }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                                <span class="px-2 py-1 text-xs font-semibold rounded {{ $paymentStatusColors[$order->payment_status] }}">
                                                    {{ ucfirst($order->payment_status) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="border-t pt-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                                <span>{{ $order->orderItems->count() }} item(s)</span>
                                            </div>

                                            <div class="flex gap-2">
                                                <a href="{{ route('orders.show', $order) }}" 
                                                   class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-700">
                                                    View Details
                                                </a>

                                                @if($order->status === 'pending')
                                                    <form method="POST" action="{{ route('orders.cancel', $order) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-md hover:bg-red-700"
                                                                onclick="return confirm('Are you sure you want to cancel this order?')">
                                                            Cancel Order
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
