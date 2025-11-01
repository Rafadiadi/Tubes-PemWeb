<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('products.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $product->name }}
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Product Image -->
                    <div>
                        <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div>
                        <div class="mb-4">
                            <a href="{{ route('products.index', ['category' => $product->category->id]) }}" 
                               class="text-sm text-indigo-600 hover:text-indigo-800">
                                {{ $product->category->name }}
                            </a>
                        </div>

                        <h1 class="text-3xl font-bold mb-4">{{ $product->name }}</h1>

                        <div class="mb-6">
                            <span class="text-4xl font-bold text-indigo-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>

                        <div class="mb-6">
                            <span class="text-sm text-gray-600">Stock Available: </span>
                            <span class="font-semibold {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $product->stock }} units
                            </span>
                        </div>

                        <div class="mb-6">
                            <h3 class="font-semibold mb-2">Description</h3>
                            <p class="text-gray-700 whitespace-pre-line">{{ $product->description }}</p>
                        </div>

                        @auth
                            @if($product->stock > 0)
                                <form method="POST" action="{{ route('cart.add', $product) }}">
                                    @csrf
                                    <div class="flex items-center gap-4 mb-4">
                                        <label for="quantity" class="font-semibold">Quantity:</label>
                                        <input type="number" 
                                               id="quantity" 
                                               name="quantity" 
                                               value="1" 
                                               min="1" 
                                               max="{{ $product->stock }}"
                                               class="w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    <button type="submit" 
                                            class="w-full lg:w-auto px-8 py-3 bg-indigo-600 text-white text-lg font-semibold rounded-md hover:bg-indigo-700">
                                        Add to Cart
                                    </button>
                                </form>
                            @else
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                    Out of Stock
                                </div>
                            @endif
                        @else
                            <a href="{{ route('login') }}" 
                               class="inline-block w-full lg:w-auto px-8 py-3 bg-gray-600 text-white text-lg font-semibold text-center rounded-md hover:bg-gray-700">
                                Login to Purchase
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Related Products -->
                @if($relatedProducts->count() > 0)
                    <div class="mt-12">
                        <h2 class="text-2xl font-bold mb-6">Related Products</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($relatedProducts as $related)
                                <div class="bg-white border rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                                    <a href="{{ route('products.show', $related->slug) }}">
                                        <div class="aspect-square bg-gray-200">
                                            @if($related->image)
                                                <img src="{{ asset('storage/' . $related->image) }}" 
                                                     alt="{{ $related->name }}" 
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                    <div class="p-4">
                                        <h3 class="font-semibold mb-2">
                                            <a href="{{ route('products.show', $related->slug) }}" class="hover:text-indigo-600">
                                                {{ $related->name }}
                                            </a>
                                        </h3>
                                        <span class="text-lg font-bold text-indigo-600">Rp {{ number_format($related->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
