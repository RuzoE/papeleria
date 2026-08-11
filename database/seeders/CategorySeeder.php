<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Escolar', 'description' => 'Útiles y materiales escolares para estudiantes'],
            ['name' => 'Oficina', 'description' => 'Artículos de escritorio y suministros de oficina'],
            ['name' => 'Arte y Dibujo', 'description' => 'Materiales de pintura, dibujo técnico y manualidades'],
            ['name' => 'Papelería y Hojas', 'description' => 'Resmas, cartulinas, carpetas y papeles especiales'],
            ['name' => 'Tecnología y Accesorios', 'description' => 'Calculadoras, memorias USB, cables y accesorios'],
        ];

        foreach ($categories as $catData) {
            $parent = Category::create($catData);

            if ($catData['name'] === 'Escolar') {
                Category::create(['name' => 'Cuadernos y Libretas', 'parent_id' => $parent->id]);
                Category::create(['name' => 'Lápices y Colores', 'parent_id' => $parent->id]);
                Category::create(['name' => 'Reglas y Geometría', 'parent_id' => $parent->id]);
            } elseif ($catData['name'] === 'Oficina') {
                Category::create(['name' => 'Archivadores y Carpetas', 'parent_id' => $parent->id]);
                Category::create(['name' => 'Abrochadoras y Cosedoras', 'parent_id' => $parent->id]);
                Category::create(['name' => 'Bolígrafos y Resaltadores', 'parent_id' => $parent->id]);
            }
        }
    }
}
