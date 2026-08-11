<?php

namespace Database\Seeders;

use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Location;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $escolarCat = Category::where('name', 'Cuadernos y Libretas')->first() ?? Category::first();
        $lapicesCat = Category::where('name', 'Lápices y Colores')->first() ?? Category::first();
        $hojasCat = Category::where('name', 'Papelería y Hojas')->first() ?? Category::first();
        $oficinaCat = Category::where('name', 'Bolígrafos y Resaltadores')->first() ?? Category::first();

        $loc1 = Location::first();
        $loc2 = Location::skip(1)->first() ?? $loc1;

        $supNorma = Supplier::where('name', 'like', '%Norma%')->first() ?? Supplier::first();
        $supFaber = Supplier::where('name', 'like', '%Faber%')->first() ?? Supplier::first();
        $supPelikan = Supplier::where('name', 'like', '%Pelikan%')->first() ?? Supplier::first();

        $products = [
            [
                'barcode' => '7701234567890',
                'internal_code' => 'PROD-00001',
                'name' => 'Cuaderno Cosido Norma 100 Hojas Rayado',
                'description' => 'Cuaderno cosido pasta dura 100 hojas con margen rojo',
                'brand' => 'Norma',
                'purchase_price' => 3800.00,
                'sale_price' => 5500.00,
                'stock' => 45,
                'minimum_stock' => 10,
                'unit' => 'unidad',
                'status' => ProductStatus::Active,
                'category_id' => $escolarCat->id,
                'location_id' => $loc1?->id,
                'supplier_id' => $supNorma?->id,
            ],
            [
                'barcode' => '7709876543210',
                'internal_code' => 'PROD-00002',
                'name' => 'Caja Colores Faber-Castell 12 Unidades + 2 Lápices',
                'description' => 'Ecolápices de color triangulares mina super suave',
                'brand' => 'Faber-Castell',
                'purchase_price' => 12500.00,
                'sale_price' => 18000.00,
                'stock' => 18,
                'minimum_stock' => 5,
                'unit' => 'caja',
                'status' => ProductStatus::Active,
                'category_id' => $lapicesCat->id,
                'location_id' => $loc2?->id,
                'supplier_id' => $supFaber?->id,
            ],
            [
                'barcode' => '7701112223334',
                'internal_code' => 'PROD-00003',
                'name' => 'Resma Papel Letter Reprograf 75g (500 Hojas)',
                'description' => 'Papel multipropósito de alta blancura para fotocopia e impresión',
                'brand' => 'Reprograf',
                'purchase_price' => 16500.00,
                'sale_price' => 22000.00,
                'stock' => 4,
                'minimum_stock' => 10,
                'unit' => 'resma',
                'status' => ProductStatus::Active,
                'category_id' => $hojasCat->id,
                'location_id' => $loc2?->id,
                'supplier_id' => $supNorma?->id,
            ],
            [
                'barcode' => '7705556667778',
                'internal_code' => 'PROD-00004',
                'name' => 'Bolígrafo Pelikan Kilométrico Negro 1.0mm',
                'description' => 'Tinta de escritura suave de secado rápido',
                'brand' => 'Pelikan',
                'purchase_price' => 800.00,
                'sale_price' => 1500.00,
                'stock' => 0,
                'minimum_stock' => 20,
                'unit' => 'unidad',
                'status' => ProductStatus::Active,
                'category_id' => $oficinaCat->id,
                'location_id' => $loc1?->id,
                'supplier_id' => $supPelikan?->id,
            ],
        ];

        foreach ($products as $p) {
            Product::create($p);
        }
    }
}
