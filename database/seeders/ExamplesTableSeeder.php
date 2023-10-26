<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExamplesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('examples')->truncate();
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            DB::table('examples')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'desc' => $faker->sentence,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
