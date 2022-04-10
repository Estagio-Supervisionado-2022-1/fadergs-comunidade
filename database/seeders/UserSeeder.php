<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    $NUMBER_OF_RECORDS = 50;

        for ($i = 0; $i < $NUMBER_OF_RECORDS ; $i++){

            User::insert([
                'name'      => Str::random(10),
                'email'     => Str::random(10).'@example.com',
                'password'  => Hash::make('passoword'),
            ]);
        }

    }
}
