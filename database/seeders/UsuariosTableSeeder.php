<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;

class UsuariosTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            User::create([
                'nombre' => $faker->firstName,
                'apellidos' => $faker->lastName,
                'sexo' => $faker->randomElement(['Hombre', 'Mujer']),
                'email' => $faker->unique()->safeEmail,
                'user' => $faker->userName,
                'password' => bcrypt('password'),
                'fecha_nac' => $faker->date,
                'foto_perfil' => null,
                'fecha_registro' => now(),
            ]);
        }
    }
}

