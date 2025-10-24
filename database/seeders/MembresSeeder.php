<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MembresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 50) as $i) {
            DB::table('membres')->insert([
                'nom' => $faker->lastName,
                'prenom' => $faker->firstName,
                'email' => $faker->unique()->safeEmail,
                'portable' => $faker->phoneNumber(),
                'portable2' => $faker->phoneNumber(),
                'birthday' => $faker->date(),
                'adresse' => $faker->randomElement(['Adjame', 'Yopougon', 'Bingerville']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
