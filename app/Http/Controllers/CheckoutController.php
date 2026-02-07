<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong');
        }

        $total = array_reduce($cart, function ($t, $i) {
            return $t + ($i['price'] * $i['quantity']);
        }, 0);
        $shipping_cost = 20000; // Fixed shipping cost

        return view('checkout.show', compact('cart', 'total', 'shipping_cost'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|min:10',
            'shipping_city' => 'required|string',
            'payment_method' => 'required|in:transfer,cod',
        ]);

        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong');
        }

        try {
            DB::beginTransaction();

            // Calculate total
            $total = array_reduce($cart, function ($t, $i) {
                return $t + ($i['price'] * $i['quantity']);
            }, 0);
            $shipping_cost = 20000;
            $final_total = $total + $shipping_cost;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => Order::generateOrderNumber(),
                'total_price' => $final_total,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_cost' => $shipping_cost,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'notes' => $request->notes,
            ]);

            // Create order items and reduce variant stock
            foreach ($cart as $item) {
                $product = Product::find($item['id']);

                // Find and update variant stock
                $variant = $product->variants()
                    ->whereHas('size', function ($q) use ($item) {
                        $q->where('name', $item['size']);
                    })
                    ->first();

                if ($variant && $variant->stock >= $item['quantity']) {
                    // Reduce variant stock (per size)
                    $variant->decrement('stock', $item['quantity']);
                    
                    // Reduce product stock (total)
                    $product->decrement('stock', $item['quantity']);
                } else {
                    throw new \Exception('Stok tidak cukup untuk ' . $item['name'] . ' ukuran ' . $item['size']);
                }

                $order->items()->create([
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'size' => $item['size'] ?? null,
                ]);
            }

            // Clear cart
            $request->session()->forget('cart');

            DB::commit();

            return redirect()->route('checkout.payment', $order)->with('success', 'Pesanan berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function payment(Order $order)
    {
        // Ensure user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('checkout.payment', compact('order'));
    }

    public function confirmPayment(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Simulate payment success
        $order->update([
            'payment_status' => 'paid',
            'status' => 'processing',
            'paid_at' => now(),
        ]);

        // Simulate automatic shipment after 1 second in real app
        // Here we'll just auto-ship for demo purposes
        $order->update([
            'status' => 'shipped',
            'shipped_at' => now(),
        ]);

        return redirect()->route('checkout.success', $order)->with('success', 'Pembayaran berhasil!');
    }

    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }
}
