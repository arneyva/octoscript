<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DataPostinganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $brands = ['Nike', 'Adidas', 'Puma', 'Reebok'];

        foreach ($brands as $brand) {
            DB::table('brands')->insert([
                'name' => $brand,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $platforms = ['Instagram', 'Twitter', 'Facebook'];

        foreach ($platforms as $platform) {
            DB::table('platforms')->insert([
                'name' => $platform,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $faker = Faker::create('id_ID'); // Gunakan bahasa Indonesia

        // Ambil semua ID dari tabel brands & platforms
        $brandIds = DB::table('brands')->pluck('id')->toArray();
        $platformIds = DB::table('platforms')->pluck('id')->toArray();

        foreach (range(1, 10) as $index) {
            DB::table('posts')->insert([
                'post_title' => $faker->sentence(),
                'brand_id' => $faker->randomElement($brandIds),
                'platform_id' => $faker->randomElement($platformIds),
                'due_date' => $faker->date(),
                'payment' => $faker->numberBetween(1000, 10000), // Tanpa koma
                'status' => $faker->randomElement(['pending', 'approved', 'rejected']), // ENUM
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
