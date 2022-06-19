<?php

namespace Database\Seeders;

use App\Models\SecondaryAddress;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SecondaryAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        SecondaryAddress::insert([
            'building_number' => rand(50, 1000),
            'room' => $faker->secondaryAddress,
            'address_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        SecondaryAddress::insert([
            'building_number' => rand(50, 1000),
            'room' => $faker->secondaryAddress,
            'address_id' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        SecondaryAddress::insert([
            'building_number' => rand(50, 1000),
            'room' => $faker->secondaryAddress,
            'address_id' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        SecondaryAddress::insert([
            'building_number' => rand(50, 1000),
            'room' => $faker->secondaryAddress,
            'address_id' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        SecondaryAddress::insert([
            'building_number' => rand(50, 1000),
            'room' => $faker->secondaryAddress,
            'address_id' => 5,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        SecondaryAddress::insert([
            'building_number' => rand(50, 1000),
            'room' => $faker->secondaryAddress,
            'address_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        SecondaryAddress::insert([
            'building_number' => rand(50, 1000),
            'room' => $faker->secondaryAddress,
            'address_id' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        SecondaryAddress::insert([
            'building_number' => rand(50, 1000),
            'room' => $faker->secondaryAddress,
            'address_id' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        SecondaryAddress::insert([
            'building_number' => rand(50, 1000),
            'room' => $faker->secondaryAddress,
            'address_id' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        SecondaryAddress::insert([
            'building_number' => rand(50, 1000),
            'room' => $faker->secondaryAddress,
            'address_id' => 5,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
