<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Spatie\Permission\Traits\HasRoles;

class UserSeeder extends Seeder
{
    use HasRoles;
    /**
     * 
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    $NUMBER_OF_RECORDS = 10;
    $faker = Faker::create();

        for ($i = 0; $i < $NUMBER_OF_RECORDS ; $i++){

            $user = User::insert([
                'name'              => $faker->name,
                'email'             => $faker->safeEmail,
                'password'          => bcrypt('Brasil@10a'),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            User::find($i+1)->assignRole('user');
        }

    }
}
