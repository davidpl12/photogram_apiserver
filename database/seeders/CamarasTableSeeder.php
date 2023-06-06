<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Camara;


class CamarasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $camaras = [
            [
                'marca' => 'Canon',
                'modelo' => 'EOS 5D Mark IV',
                'descripcion' => 'Cámara DSLR profesional con sensor full-frame.',
                'valoracion' => 4.8,
            ],
            [
                'marca' => 'Nikon',
                'modelo' => 'D850',
                'descripcion' => 'Cámara DSLR de alta resolución con excelentes capacidades de video.',
                'valoracion' => 4.7,
            ],
            [
                'marca' => 'Sony',
                'modelo' => 'Alpha a7 III',
                'descripcion' => 'Cámara sin espejo de fotograma completo con enfoque automático avanzado.',
                'valoracion' => 4.9,
            ],
            // Agrega más registros de cámaras según sea necesario
        ];

        foreach ($camaras as $camara) {
            Camara::create($camara);
        }
    }
}
