<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PostinganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        // Membuat 10 data dummy untuk tabel posts
        foreach (range(1, 10) as $index) {
            DB::table('postingan')->insert([
                'post_title' => $faker->sentence, // Kalimat dalam bahasa Indonesia
                'brand' => $faker->randomElement(['Nike', 'Adidas', 'Puma', 'Reebok']),
                'platform' => $faker->randomElement(['Instagram', 'Twitter', 'Facebook']),
                'due_date' => $faker->date(),
                'payment' => $faker->numberBetween(1000, 10000), // Tanpa koma
                'status' => $faker->randomElement(['draft', 'published', 'archived']), // ENUM
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
