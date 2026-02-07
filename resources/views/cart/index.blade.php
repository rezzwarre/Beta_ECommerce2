<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Keranjang Belanja') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(empty($cart))
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Keranjang kosong</h3>
                    <p class="mt-2 text-gray-600">Mulai belanja dengan mengunjungi koleksi produk kami.</p>
                    <a href="{{ route('products.index') }}" class="mt-6 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Lihat Produk
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <table class="w-full">
                                <thead class="bg-gray-100 border-b">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-sm font-semibold">Produk</th>
                                        <th class="px-6 py-3 text-center text-sm font-semibold">Harga</th>
                                        <th class="px-6 py-3 text-center text-sm font-semibold">Jumlah</th>
                                        <th class="px-6 py-3 text-right text-sm font-semibold">Subtotal</th>
                                        <th class="px-6 py-3 text-center text-sm font-semibold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $item)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item['name'] }} @if(!empty($item['size']))<span class="text-sm text-gray-500"> ({{ $item['size'] }})</span>@endif</td>
                                            <td class="px-6 py-4 text-center text-sm">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 text-center text-sm">{{ $item['quantity'] }}</td>
                                            <td class="px-6 py-4 text-right text-sm font-semibold">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 text-center">
                                                <form action="{{ route('cart.remove', $item['id']) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus item ini?');">
                                                    @csrf
                                                    <button class="text-red-600 hover:text-red-900 text-sm">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Cart Summary -->
                    <div>
                        <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                            <h3 class="text-lg font-semibold mb-4">Ringkasan Pesanan</h3>
                            
                            <div class="space-y-3 mb-4 pb-4 border-b">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-medium" id="subtotal">Rp {{ number_format(array_reduce($cart, function($t, $i) { return $t + ($i['price'] * $i['quantity']); }, 0), 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Biaya Pengiriman</span>
                                    <span class="font-medium" id="shipping">Rp 20.000</span>
                                </div>
                            </div>

                            <div class="flex justify-between mb-6">
                                <span class="text-lg font-bold">Total</span>
                                <span class="text-xl font-bold text-blue-600" id="total">Rp {{ number_format(array_reduce($cart, function($t, $i) { return $t + ($i['price'] * $i['quantity']); }, 0) + 20000, 0, ',', '.') }}</span>
                            </div>

                            @auth
                                <a href="{{ route('checkout.show') }}" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg font-semibold hover:bg-blue-700 text-center block">
                                    Checkout
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg font-semibold hover:bg-blue-700 text-center block">
                                    Login untuk Checkout
                                </a>
                            @endauth

                            <a href="{{ route('products.index') }}" class="block mt-3 text-center text-blue-600 hover:text-blue-900 font-medium">
                                Lanjut Belanja
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
