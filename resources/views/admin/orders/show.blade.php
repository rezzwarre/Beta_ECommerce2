<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pesanan: ') . $order->order_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="grid grid-cols-2 gap-6 mb-6 pb-6 border-b">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-600 mb-2">Informasi Pelanggan</h4>
                        <p class="text-gray-900 font-semibold">{{ $order->user->name }}</p>
                        <p class="text-sm text-gray-600">{{ $order->user->email }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-600 mb-2">Informasi Pesanan</h4>
                        <p><strong>No. Pesanan:</strong> {{ $order->order_number }}</p>
                        <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-6 pb-6 border-b">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-600 mb-2">Alamat Pengiriman</h4>
                        <p class="text-gray-900">{{ $order->shipping_address }}</p>
                        <p class="text-gray-900">{{ $order->shipping_city }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-600 mb-2">Metode Pembayaran</h4>
                        <p class="text-gray-900">{{ $order->payment_method === 'transfer' ? 'Transfer Bank' : 'Bayar di Tempat' }}</p>
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="text-lg font-semibold mb-4">Daftar Produk</h4>
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

                <div class="bg-gray-50 rounded-lg p-6 mb-6 space-y-3">
                    <div class="flex justify-between">
                        <span>Subtotal Produk</span>
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

                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-900 mb-2">Status Pesanan</h4>
                        <p class="text-2xl font-bold text-blue-600">{{ ucfirst($order->status) }}</p>
                    </div>
                    <div class="bg-{{ $order->payment_status === 'paid' ? 'green' : 'red' }}-50 border border-{{ $order->payment_status === 'paid' ? 'green' : 'red' }}-200 rounded-lg p-4">
                        <h4 class="font-semibold text-{{ $order->payment_status === 'paid' ? 'green' : 'red' }}-900 mb-2">Status Pembayaran</h4>
                        <p class="text-2xl font-bold text-{{ $order->payment_status === 'paid' ? 'green' : 'red' }}-600">{{ ucfirst($order->payment_status) }}</p>
                    </div>
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <h4 class="font-semibold text-purple-900 mb-2">Metode Pembayaran</h4>
                        <p class="text-lg font-bold text-purple-600">{{ $order->payment_method === 'transfer' ? 'Transfer' : 'COD' }}</p>
                    </div>
                </div>
            </div>

            <!-- Update Status Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Perbarui Status Pesanan</h3>
                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="flex gap-3">
                    @csrf
                    <select name="status" required class="flex-1 border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Status --</option>
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-semibold">
                        Perbarui
                    </button>
                </form>
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-900 font-medium">
                    ← Kembali ke Daftar Pesanan
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
