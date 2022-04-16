<?php

namespace Database\Seeders;

use App\Models\Operator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OperatorSeeder extends Seeder
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

            Operator::insert([
                'name'              => Str::random(10),
                'email'             => Str::random(10).'@fadergs.edu.br',
                'password'          => Hash::make('passoword'),
                'id_departament'    => random_int(0,9),
            ]);
        }
    }
}
