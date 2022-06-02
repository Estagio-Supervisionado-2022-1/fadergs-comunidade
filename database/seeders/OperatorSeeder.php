<?php

namespace Database\Seeders;

use App\Models\Operator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class OperatorSeeder extends Seeder
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

            Operator::insert([
                'name'              => $faker->name,
                'email'             => $faker->safeEmail,
                'password'          => bcrypt('Brasil@10a'),
                'departament_id'    => random_int(1,4),
                'created_at'        => now(),
                'updated_at'        => now(),

            ]);
        }

        Operator::find(1)->assignRole('admin');
        Operator::find(2)->assignRole('manager');   
        Operator::find(3)->assignRole('student');
}
}
