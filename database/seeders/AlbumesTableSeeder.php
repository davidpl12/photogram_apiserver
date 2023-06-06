<?php

namespace Database\Seeders;

use App\Models\Album;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlbumesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $albumes = [
            ['nombre_album' => 'Vacaciones', 'descripcion' => 'Álbum de fotos de vacaciones'],
            ['nombre_album' => 'Familia', 'descripcion' => 'Álbum de fotos familiares'],
            ['nombre_album' => 'Naturaleza', 'descripcion' => 'Álbum de fotos de la naturaleza'],
        ];

        Album::insert($albumes);
    }
}
