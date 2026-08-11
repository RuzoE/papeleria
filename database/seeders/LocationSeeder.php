<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['zone' => 'Pasillo A', 'module' => 'M1', 'shelf' => 'E1', 'level' => '1', 'position' => 'Izquierda', 'description' => 'Cuadernos y libretas escolares'],
            ['zone' => 'Pasillo A', 'module' => 'M1', 'shelf' => 'E2', 'level' => '2', 'position' => 'Centro', 'description' => 'Lápices, borradores y tajalápices'],
            ['zone' => 'Pasillo B', 'module' => 'M2', 'shelf' => 'E1', 'level' => '1', 'position' => 'Derecha', 'description' => 'Resmas de papel carta y oficio'],
            ['zone' => 'Vitrina Principal', 'module' => 'V1', 'shelf' => 'E1', 'level' => '3', 'position' => 'Frontal', 'description' => 'Bolígrafos finos y plumas de regalo'],
            ['zone' => 'Bodega', 'module' => 'B1', 'shelf' => 'E4', 'level' => '1', 'position' => 'Fondo', 'description' => 'Stock de reserva papelería pesada'],
        ];

        foreach ($locations as $loc) {
            Location::create($loc);
        }
    }
}
