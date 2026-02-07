<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Size;
use App\Models\ProductVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $products = [
            [
                'name' => 'Kaos Putih Basic',
                'slug' => 'kaos-putih-basic',
                'description' => 'Kaos putih bahan katun, nyaman dipakai sehari-hari.',
                'price' => 100000,
                'stock' => 50,
                'size' => 'S,M,L,XL',
                'image' => null, // Will show placeholder
                'images' => json_encode([]),
            ],
            [
                'name' => 'Kaos Hitam Logo',
                'slug' => 'kaos-hitam-logo',
                'description' => 'Kaos hitam dengan logo sablon berkualitas.',
                'price' => 120000,
                'stock' => 40,
                'size' => 'M,L,XL',
                'image' => null, // Will show placeholder
                'images' => json_encode([]),
            ],
        ];

        foreach ($products as $p) {
            $product = Product::firstOrCreate(
                ['slug' => $p['slug']],
                [
                    'name' => $p['name'],
                    'description' => $p['description'],
                    'price' => $p['price'],
                    'base_price' => $p['price'],
                    'stock' => $p['stock'],
                    'image' => $p['image'],
                    'images' => $p['images'],
                ]
            );

            $sizeNames = array_map('trim', explode(',', $p['size']));
            $count = count($sizeNames) ?: 1;
            $perVariantStock = (int) floor($p['stock'] / $count);

            foreach ($sizeNames as $name) {
                $size = Size::where('name', $name)->first();
                if (! $size) {
                    continue;
                }

                ProductVariant::firstOrCreate([
                    'product_id' => $product->id,
                    'size_id' => $size->id,
                ], [
                    'price' => $p['price'],
                    'stock' => $perVariantStock,
                ]);
            }
        }
    }
}
