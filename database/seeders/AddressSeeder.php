<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $NUMBER_OF_RECORDS = 10;

        for ($i = 0; $i < $NUMBER_OF_RECORDS ; $i++){

            Address::insert([
                'cep'           => Str::random(8),
                'logradouro'    => Str::random(16),
                'bairro'        => Str::random(2),
                'cidade'        => Str::random(4),
                'uf'            => Str::random(2),
                'complemento'   => Str::random(7),
                'numero'        => Str::random(2),


            ]);
        }
    }
}
