<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Konfirmasi Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-md p-8">
                <h3 class="text-2xl font-bold mb-6">No. Pesanan: {{ $order->order_number }}</h3>

                <div class="grid grid-cols-2 gap-6 mb-8 pb-8 border-b">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-600 mb-2">Alamat Pengiriman</h4>
                        <p class="text-gray-900">{{ $order->shipping_address }}</p>
                        <p class="text-gray-900">{{ $order->shipping_city }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-600 mb-2">Metode Pembayaran</h4>
                        <p class="text-gray-900 font-medium">{{ $order->payment_method === 'transfer' ? 'Transfer Bank' : 'Bayar di Tempat' }}</p>
                        <p class="text-sm text-gray-600 mt-2">Status: <span class="text-yellow-600 font-semibold">{{ ucfirst($order->payment_status) }}</span></p>
                    </div>
                </div>

                <div class="mb-8">
                    <h4 class="text-lg font-semibold mb-4">Detail Pesanan</h4>
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="text-left px-4 py-2">Produk</th>
                                <th class="text-center px-4 py-2">Jumlah</th>
                                <th class="text-right px-4 py-2">Harga</th>
                                <th class="text-right px-4 py-2">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3">{{ $item->product->name }}</td>
                                    <td class="text-center px-4 py-3">{{ $item->quantity }}</td>
                                    <td class="text-right px-4 py-3">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-right px-4 py-3 font-semibold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="bg-gray-50 rounded-lg p-6 mb-8 space-y-3">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span class="font-semibold">Rp {{ number_format($order->total_price - $order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Biaya Pengiriman</span>
                        <span class="font-semibold">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between text-lg font-bold">
                        <span>Total Pembayaran</span>
                        <span class="text-blue-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if($order->payment_status === 'unpaid')
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                        <h4 class="font-bold text-blue-900 mb-3">🏦 Instruksi Pembayaran</h4>
                        @if($order->payment_method === 'transfer')
                            <p class="text-sm text-blue-800 mb-3">Transfer ke rekening berikut:</p>
                            <div class="bg-white rounded p-4 border border-blue-200 mb-3">
                                <p><strong>Bank BCA</strong></p>
                                <p class="text-xl font-mono font-bold">123 456 7891</p>
                                <p class="text-sm text-gray-600">Atas Nama: Shop Kaos Indonesia</p>
                            </div>
                            <p class="text-sm text-blue-800">Jumlah transfer: <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></p>
                            <p class="text-sm text-blue-800 mt-2">Gunakan nomor pesanan sebagai referensi transfer: <strong>{{ $order->order_number }}</strong></p>
                        @else
                            <p class="text-sm text-blue-800">Anda dapat membayar saat paket tiba. Siapkan uang sebesar <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></p>
                        @endif
                    </div>

                    <form action="{{ route('checkout.confirmPayment', $order) }}" method="POST" class="space-y-4">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 font-semibold text-lg">
                            ✓ Konfirmasi Pembayaran
                        </button>
                    </form>
                @else
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-green-800 text-center">
                        <p class="font-semibold">✓ Pembayaran Telah Diterima</p>
                        <p class="text-sm">Pesanan Anda sedang diproses</p>
                    </div>
                @endif

                <div class="mt-6 text-center">
                    <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-900 font-medium">
                        ← Kembali ke Belanja
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
