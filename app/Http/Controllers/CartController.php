<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $size = $request->input('size');
        $cart = $request->session()->get('cart', []);

        // Find variant by product and size
        $variant = $product->variants()
            ->whereHas('size', function ($q) use ($size) {
                $q->where('name', $size);
            })
            ->first();

        if (!$variant) {
            return redirect()->back()->with('error', 'Varian tidak ditemukan');
        }

        if ($variant->stock <= 0) {
            return redirect()->back()->with('error', 'Stok habis untuk ukuran ini');
        }

        // Use composite key so same product with different sizes are distinct
        $key = $product->id . ($size ? ':' . $size : '');

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += 1;
        } else {
            $cart[$key] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $variant->price,
                'quantity' => 1,
                'size' => $size,
            ];
        }

        $request->session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang');
    }

    public function remove(Request $request, $id)
    {
        $cart = $request->session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            $request->session()->put('cart', $cart);
        }
        return redirect()->back();
    }
}
