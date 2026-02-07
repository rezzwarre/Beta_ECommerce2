<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Product Image & Gallery -->
                <div>
                    <!-- Main Image -->
                    <div class="bg-gray-200 rounded-lg h-96 flex items-center justify-center overflow-hidden mb-4">
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-gray-400 text-lg">Tidak ada gambar</span>
                        @endif
                    </div>

                    <!-- Gallery Carousel -->
                    @if($product->images && count($product->images) > 0)
                        <div class="relative">
                            <div id="gallery-container" class="overflow-x-auto flex gap-2 pb-2">
                                @if($product->image)
                                    <button onclick="setMainImage('{{ asset($product->image) }}')" class="flex-shrink-0 w-20 h-20 border-2 border-blue-500 rounded-lg overflow-hidden hover:border-blue-700">
                                        <img src="{{ asset($product->image) }}" alt="Main" class="w-full h-full object-cover">
                                    </button>
                                @endif
                                @foreach($product->images as $img)
                                    <button onclick="setMainImage('{{ asset($img) }}')" class="flex-shrink-0 w-20 h-20 border-2 border-gray-300 rounded-lg overflow-hidden hover:border-blue-500">
                                        <img src="{{ asset($img) }}" alt="Gallery" class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Product Details -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
                    
                    <p class="mt-6 text-gray-700 text-lg">{{ $product->description }}</p>

                    <div class="mt-8">
                        <p class="text-sm text-gray-600">Harga</p>
                        <p class="text-4xl font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>

                    <div class="mt-6">
                        <p class="text-sm text-gray-600 mb-3">Ukuran & Stok</p>
                        @php
                            $hasAvailableVariant = $product->variants->sum('stock') > 0;
                        @endphp
                        <div class="space-y-2">
                            @forelse($product->variants as $variant)
                                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-md bg-gray-50">
                                    <div class="flex items-center gap-3">
                                        <span class="font-semibold text-gray-900 min-w-12">{{ $variant->size->name }}</span>
                                        <span class="text-gray-600">Rp {{ number_format($variant->price, 0, ',', '.') }}</span>
                                    </div>
                                    <span class="text-sm font-semibold {{ $variant->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $variant->stock > 0 ? 'Stok: ' . $variant->stock : 'Habis' }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">Tidak ada varian ukuran</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-8 flex gap-4">
                        @if($hasAvailableVariant)
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="block text-sm text-gray-700 mb-2">Pilih Ukuran</label>
                                        <select name="size" required class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                                            <option value="">-- Pilih Ukuran --</option>
                                            @foreach($product->variants as $variant)
                                                @if($variant->stock > 0)
                                                    <option value="{{ $variant->size->name }}">
                                                        {{ $variant->size->name }} (Rp {{ number_format($variant->price, 0, ',', '.') }}) - Stok: {{ $variant->stock }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <button class="w-full bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 font-semibold text-lg transition">
                                        Tambah ke Keranjang
                                    </button>
                                </form>
                        @else
                            <button disabled class="flex-1 bg-gray-400 text-white px-6 py-3 rounded-lg cursor-not-allowed font-semibold text-lg">
                                Stok Habis
                            </button>
                        @endif
                        <a href="{{ route('products.index') }}" class=" m-5 flex-1 inline-flex items-center justify-center border border-gray-300 text-gray-700 px-5 py-3 rounded-lg hover:bg-gray-50 font-semibold text-lg transition">
                            <svg class="h-5 w-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setMainImage(imagePath) {
            const mainImage = document.querySelector('.bg-gray-200.rounded-lg.h-96 img');
            if (mainImage) {
                mainImage.src = imagePath;
            }
        }
    </script>
</x-app-layout>
