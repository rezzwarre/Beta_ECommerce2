<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- User Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-sm font-semibold text-gray-600 mb-2">Nama</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->name }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-sm font-semibold text-gray-600 mb-2">Email</h3>
                    <p class="text-lg text-gray-900">{{ auth()->user()->email }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-sm font-semibold text-gray-600 mb-2">Role</h3>
                    <p class="text-lg font-bold {{ auth()->user()->is_admin ? 'text-blue-600' : 'text-green-600' }}">
                        {{ auth()->user()->is_admin ? 'Admin' : 'Pelanggan' }}
                    </p>
                </div>
            </div>

            <!-- Orders -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Pesanan Saya</h3>

                    @if(auth()->user()->orders->count() === 0)
                        <p class="text-gray-500">Anda belum memiliki pesanan. <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-900">Mulai belanja sekarang</a></p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100 border-b">
                                    <tr>
                                        <th class="px-4 py-3 text-left">No. Pesanan</th>
                                        <th class="px-4 py-3 text-left">Total</th>
                                        <th class="px-4 py-3 text-left">Status</th>
                                        <th class="px-4 py-3 text-left">Pembayaran</th>
                                        <th class="px-4 py-3 text-left">Tanggal</th>
                                        <th class="px-4 py-3 text-left">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(auth()->user()->orders->sortByDesc('created_at') as $order)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="px-4 py-3 font-mono font-semibold">{{ $order->order_number }}</td>
                                            <td class="px-4 py-3 font-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3">
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'processing' => 'bg-blue-100 text-blue-800',
                                                        'shipped' => 'bg-purple-100 text-purple-800',
                                                        'delivered' => 'bg-green-100 text-green-800',
                                                        'cancelled' => 'bg-red-100 text-red-800',
                                                    ];
                                                @endphp
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ ucfirst($order->payment_status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-xs text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                                            <td class="px-4 py-3">
                                                <a href="{{ route('checkout.payment', $order) }}" class="text-blue-600 hover:text-blue-900 font-semibold">Lihat</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
