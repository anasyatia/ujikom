<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); 

        for ($i = 0; $i < 10; $i++) {
            Member::create([
                'nama' => $faker->name,
                'telp' => $this->generateIndonesianPhoneNumber(),
                'poin' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Generate a random Indonesian phone number.
     *
     * @return string
     */
    private function generateIndonesianPhoneNumber(): string
    {
        $faker = Faker::create('id_ID');
        $prefix = $faker->randomElement(['081', '082', '085', '087', '089']); // Common Indonesian prefixes
        $number = $faker->numerify('########'); // Generate 8 random digits
        return $prefix . $number;
    }
}