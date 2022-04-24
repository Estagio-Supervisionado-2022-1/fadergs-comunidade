<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    $NUMBER_OF_RECORDS = 10;
    $faker = Faker::create();

        for ($i = 0; $i < $NUMBER_OF_RECORDS ; $i++){

            User::insert([
                'name'              => $faker->name,
                'email'             => $faker->safeEmail,
                'password'          => bcrypt('Brasil@10a'),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }

    }
}
