<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Size;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $sizes = Size::all();
        return view('admin.products.create', compact('sizes'));
    }

    public function store(Request $request)
    {
        Log::info('Store request received', $request->all());
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'variants' => 'required|array|min:1',
            'variants.*.size_id' => 'required|exists:sizes,id',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
        ]);

        Log::info('Validation passed', $validated);

        try {
            DB::beginTransaction();

            // Handle main image upload
            $mainImage = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('products', $filename, 'public');
                $mainImage = 'storage/' . $path;
            }

            // Handle gallery images upload
            $galleryImages = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('products', $filename, 'public');
                    $galleryImages[] = 'storage/' . $path;
                }
            }

            // Create product with base_price from first variant or default
            $product = Product::create([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'] ?? '',
                'image' => $mainImage,
                'images' => !empty($galleryImages) ? $galleryImages : null,
                'price' => $validated['variants'][0]['price'] ?? 0,
                'base_price' => $validated['variants'][0]['price'] ?? 0,
                'stock' => array_sum(array_column($validated['variants'], 'stock')),
            ]);

            Log::info('Product created', ['id' => $product->id, 'name' => $product->name]);

            // Create variants
            foreach ($validated['variants'] as $variant) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size_id' => $variant['size_id'],
                    'price' => $variant['price'],
                    'stock' => $variant['stock'],
                ]);
            }

            Log::info('Variants created for product', ['product_id' => $product->id]);

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Produk dibuat dengan variant');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating product', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit(Product $product)
    {
        $sizes = Size::all();
        $variants = $product->variants()->with('size')->get();
        return view('admin.products.edit', compact('product', 'sizes', 'variants'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'variants' => 'required|array|min:1',
            'variants.*.size_id' => 'required|exists:sizes,id',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Handle main image upload
            $mainImage = $product->image; // keep existing if not uploaded
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($product->image && Storage::disk('public')->exists(str_replace('storage/', '', $product->image))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $product->image));
                }
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('products', $filename, 'public');
                $mainImage = 'storage/' . $path;
            }

            // Handle gallery images upload
            $galleryImages = is_array($product->images) ? $product->images : [];
            if ($request->hasFile('images')) {
                // Delete old images
                if (!empty($galleryImages) && is_array($galleryImages)) {
                    foreach ($galleryImages as $img) {
                        if ($img && Storage::disk('public')->exists(str_replace('storage/', '', $img))) {
                            Storage::disk('public')->delete(str_replace('storage/', '', $img));
                        }
                    }
                }
                $galleryImages = [];
                foreach ($request->file('images') as $file) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('products', $filename, 'public');
                    $galleryImages[] = 'storage/' . $path;
                }
            }

            // Update product
            $product->update([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description'],
                'image' => $mainImage,
                'images' => !empty($galleryImages) ? $galleryImages : null,
                'price' => $data['variants'][0]['price'] ?? 0,
                'base_price' => $data['variants'][0]['price'] ?? 0,
                'stock' => array_sum(array_column($data['variants'], 'stock')),
            ]);

            // Delete existing variants
            ProductVariant::where('product_id', $product->id)->delete();

            // Create new variants
            foreach ($data['variants'] as $variant) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size_id' => $variant['size_id'],
                    'price' => $variant['price'],
                    'stock' => $variant['stock'],
                ]);
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Produk diperbarui dengan variant dan gambar');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating product', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk dihapus');
    }
}
