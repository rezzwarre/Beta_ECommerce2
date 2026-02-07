<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pesanan Berhasil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <div class="mb-6">
                    <svg class="mx-auto h-16 w-16 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-2">Pesanan Berhasil Dibuat!</h1>
                <p class="text-gray-600 mb-6">Terima kasih telah berbelanja di Shop Kaos. Pesanan Anda sedang diproses.</p>

                <div class="bg-gray-50 rounded-lg p-6 mb-8 text-left">
                    <h3 class="font-bold text-gray-900 mb-4">Detail Pesanan</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Nomor Pesanan</span>
                            <span class="font-mono font-bold">{{ $order->order_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tanggal</span>
                            <span>{{ $order->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Pembayaran</span>
                            <span class="font-bold text-lg text-blue-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status Pesanan</span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">{{ ucfirst($order->status) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status Pembayaran</span>
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">{{ ucfirst($order->payment_status) }}</span>
                        </div>
                        <div class="flex justify-between pt-3 border-t">
                            <span class="text-gray-600">Pengiriman ke</span>
                            <div class="text-right">
                                <p class="font-semibold">{{ $order->shipping_city }}</p>
                                <p class="text-sm text-gray-600">{{ $order->shipping_address }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8 text-left">
                    <h4 class="font-bold text-green-900 mb-2">📦 Apa Selanjutnya?</h4>
                    <ul class="text-sm text-green-800 space-y-2">
                        <li>✓ Pesanan Anda telah dikonfirmasi dan sedang diproses</li>
                        <li>✓ Kami akan mengirimkan notifikasi saat paket dikirim</li>
                        <li>✓ Estimasi pengiriman 3-5 hari kerja</li>
                        <li>✓ Cek status pesanan di akun Anda</li>
                    </ul>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8 text-left">
                    <h4 class="font-bold text-blue-900 mb-3">💬 Bantuan Customer Service</h4>
                    <p class="text-sm text-blue-800 mb-2">Jika ada pertanyaan atau masalah, hubungi kami:</p>
                    <p class="text-sm font-semibold text-blue-900">📞 Telepon: +62 812-3456-7890</p>
                    <p class="text-sm font-semibold text-blue-900">📧 Email: cs@shopkaos.com</p>
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('products.index') }}" class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-semibold">
                        Lanjut Belanja
                    </a>
                    <a href="{{ route('dashboard') }}" class="flex-1 border-2 border-gray-300 text-gray-700 py-3 rounded-lg hover:bg-gray-100 font-semibold text-center">
                        Lihat Pesanan Anda
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
