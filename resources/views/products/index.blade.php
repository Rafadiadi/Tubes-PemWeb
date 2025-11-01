<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <form method="GET" action="{{ route('products.index') }}" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search products..." 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="min-w-[200px]">
                        <select name="category" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="min-w-[150px]">
                        <select name="sort" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Newest</option>
                            <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Filter
                    </button>
                </form>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow">
                        <a href="{{ route('products.show', $product->slug) }}">
                            <div class="aspect-square bg-gray-200">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </a>
                        <div class="p-4">
                            <p class="text-xs text-gray-500 mb-1">{{ $product->category->name }}</p>
                            <h3 class="font-semibold text-lg mb-2">
                                <a href="{{ route('products.show', $product->slug) }}" class="hover:text-indigo-600">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ Str::limit($product->description, 80) }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xl font-bold text-indigo-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="text-sm text-gray-500">Stock: {{ $product->stock }}</span>
                            </div>
                            @auth
                                <form method="POST" action="{{ route('cart.add', $product) }}" class="mt-3">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" 
                                            class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:bg-gray-400"
                                            {{ $product->stock < 1 ? 'disabled' : '' }}>
                                        {{ $product->stock < 1 ? 'Out of Stock' : 'Add to Cart' }}
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="mt-3 block w-full px-4 py-2 bg-gray-600 text-white text-center rounded-md hover:bg-gray-700">
                                    Login to Buy
                                </a>
                            @endauth
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">No products found.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
