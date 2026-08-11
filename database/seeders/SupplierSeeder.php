<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Distribuidora Carvajal (Norma)',
                'document_type' => 'NIT',
                'document_number' => '890.300.045-1',
                'phone' => '601 484 2000',
                'email' => 'ventas@carvajal.com',
                'address' => 'Zona Industrial Cazuca, Bogotá',
                'status' => true,
            ],
            [
                'name' => 'Faber-Castell Colombia S.A.',
                'document_type' => 'NIT',
                'document_number' => '860.005.123-9',
                'phone' => '601 742 5500',
                'email' => 'contacto@faber-castell.com.co',
                'address' => 'Autopista Norte Km 19, Chía',
                'status' => true,
            ],
            [
                'name' => 'Distribuidora Pelikan S.A.S.',
                'document_type' => 'NIT',
                'document_number' => '900.412.789-3',
                'phone' => '601 518 9090',
                'email' => 'servicioalcliente@pelikan.com.co',
                'address' => 'Calle 80 # 69-40, Bogotá',
                'status' => true,
            ],
        ];

        foreach ($suppliers as $sup) {
            Supplier::create($sup);
        }
    }
}
