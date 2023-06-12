<?php

namespace Database\Seeders;

use App\Models\Publicacion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;



class PublicacionesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $camaras = [1, 2, 3];

        for ($i = 0; $i < 10; $i++) {
            $fechaPublicacion = $faker->dateTimeBetween('2023-01-01', 'now');

            Publicacion::create([
                'autor' => $faker->numberBetween(3, 23),
                'descripcion' => $faker->text,
                'lugar_realizacion' => $faker->address,
                'licencia' => $faker->randomElement(['Creative Commons', 'Public Domain']),
                'camara' => $faker->randomElement($camaras),
                'imagen' => $faker->imageUrl,
                'num_reacciones' => 0,
                'album' => $faker->numberBetween(1, 3),
                'fecha_public' => $fechaPublicacion->format('Y-m-d H:i:s'),
            ]);
        }
    }
}
