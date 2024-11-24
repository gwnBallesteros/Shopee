<x-app-layout>
    <script src="https://cdn.tailwindcss.com"></script>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-12">
        <div class="absolute top-0 right-0 mt-4 mr-4">
            <a href="{{ route('products.create') }}">
                <button class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Add Product
                </button>
            </a>
        </div>

        <h2 class="text-xl font-semibold mb-6">Products</h2>

        @if($products->isEmpty())
            <p>No products found.</p>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    <div class="bg-white p-4 rounded-lg shadow-lg transition-transform transform hover:scale-105 duration-300 relative">
                        <div class="relative mb-4">
                            @if($product->images->isNotEmpty())
                                <!-- Image Carousel -->
                                <div class="relative w-full h-40">
                                    <div class="overflow-hidden rounded-t-lg">
                                        <div class="flex transition-transform duration-300 ease-in-out" id="carousel-{{ $product->id }}">
                                            @foreach ($product->images as $image)
                                                <div class="w-full flex-shrink-0">
                                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}" class="w-full h-40 object-cover rounded-t-lg">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <!-- Arrow buttons -->
                                    <button onclick="moveSlide({{ $product->id }}, -1)" class="absolute top-1/2 left-0 transform -translate-y-1/2 bg-gray-800 text-white p-2 rounded-full shadow-md hover:bg-gray-700">
                                        &lt;
                                    </button>
                                    <button onclick="moveSlide({{ $product->id }}, 1)" class="absolute top-1/2 right-0 transform -translate-y-1/2 bg-gray-800 text-white p-2 rounded-full shadow-md hover:bg-gray-700">
                                        &gt;
                                    </button>
                                </div>
                            @else
                                <img src="{{ asset('storage/no-image.jpg') }}" alt="No image available" class="w-full h-40 object-cover rounded-t-lg mb-4">
                            @endif
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h3>
                        <p class="text-gray-500 mb-4 text-sm">{{ $product->description }}</p>

                        <!-- Price -->
                        <p class="text-xl font-semibold text-gray-800 mb-4">
                            ${{ number_format($product->price ?? 0, 2) }}
                        </p>

                        <!-- Action buttons -->
                        <div class="flex justify-between">
                            <!-- Update Button -->
                            <a href="{{ route('products.edit', $product) }}">
                                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                    Update
                                </button>
                            </a>

                            <!-- Delete Button -->
                            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-red-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>

                @endforeach
            </div>
        @endif
    </div>

    <script>
        function moveSlide(productId, direction) {
            const carousel = document.getElementById(`carousel-${productId}`);
            const slides = carousel.children;
            const totalSlides = slides.length;
            const currentTransform = parseInt(window.getComputedStyle(carousel).transform.split(',')[4]) || 0;
            const slideWidth = slides[0].offsetWidth;

            let newTransform = currentTransform + direction * slideWidth;

            if (newTransform > 0) {
                newTransform = -(slideWidth * (totalSlides - 1));
            } else if (newTransform < -(slideWidth * (totalSlides - 1))) {
                newTransform = 0;
            }

            carousel.style.transform = `translateX(${newTransform}px)`;
        }
    </script>
</x-app-layout>
