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
                'post_title' => $faker->sentence,
                'brand' => $faker->company,
                'platform' => $faker->word,
                'due_date' => $faker->date(),
                'payment' => $faker->randomFloat(2, 1000, 10000), // Angka acak dengan 2 desimal
                'status' => $faker->numberBetween(0, 2), // Status antara 0 dan 2
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
