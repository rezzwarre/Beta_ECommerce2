<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Checkout Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-6">Data Pengiriman</h3>

                        <form action="{{ route('checkout.store') }}" method="POST" class="space-y-6">
                            @csrf

                            <div>
                                <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-2">Alamat Pengiriman</label>
                                <textarea id="shipping_address" name="shipping_address" rows="4" required class="w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500">{{ old('shipping_address') }}</textarea>
                                @error('shipping_address') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="shipping_city" class="block text-sm font-medium text-gray-700 mb-2">Kota</label>
                                <input type="text" id="shipping_city" name="shipping_city" required placeholder="Jakarta, Bandung, dll" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500" value="{{ old('shipping_city') }}">
                                @error('shipping_city') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                                <select id="payment_method" name="payment_method" required class="w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Metode --</option>
                                    <option value="transfer" {{ old('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                    <option value="cod" {{ old('payment_method') === 'cod' ? 'selected' : '' }}>Bayar di Tempat</option>
                                </select>
                                @error('payment_method') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                                <textarea id="notes" name="notes" rows="2" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500" placeholder="Catatan untuk penjual atau kurir...">{{ old('notes') }}</textarea>
                            </div>

                            <div class="flex gap-4">
                                <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-semibold">
                                    Lanjut ke Pembayaran
                                </button>
                                <a href="{{ route('cart.index') }}" class="flex-1 border-2 border-gray-300 text-gray-700 py-3 rounded-lg hover:bg-gray-100 font-semibold text-center">
                                    Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Order Summary -->
                <div>
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                        <h3 class="text-lg font-semibold mb-4">Ringkasan Pesanan</h3>

                        <div class="space-y-3 mb-6 pb-6 border-b max-h-80 overflow-y-auto">
                            @php
                                $cart = session('cart', []);
                                $subtotal = 0;
                            @endphp
                            @foreach($cart as $item)
                                @php $subtotal += $item['price'] * $item['quantity']; @endphp
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-700">{{ $item['name'] }} (x{{ $item['quantity'] }})</span>
                                    <span class="font-medium">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="space-y-3 mb-6 pb-6 border-b">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Biaya Pengiriman</span>
                                <span class="font-medium">Rp {{ number_format($shipping_cost, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="flex justify-between mb-6">
                            <span class="text-lg font-bold">Total</span>
                            <span class="text-xl font-bold text-blue-600">Rp {{ number_format($total + $shipping_cost, 0, ',', '.') }}</span>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-800">
                            <p class="font-semibold mb-2">💡 Informasi</p>
                            <p>Pesanan Anda akan diproses setelah pembayaran dikonfirmasi. Estimasi pengiriman 3-5 hari kerja.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
