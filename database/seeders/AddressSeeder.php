<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker::create();
        $NUMBER_OF_RECORDS = 10;

        for ($i = 0; $i < $NUMBER_OF_RECORDS ; $i++){

            Address::create([
                'cep'           => $faker->unique()->postcode,
                'logradouro'    => $faker->unique()->streetName,
                'bairro'        => $faker->cityPrefix,
                'cidade'        => $faker->city,
                'uf'            => $faker->stateAbbr,
                'complemento'   => $faker->secondaryAddress,
                'numero'        => $faker->buildingNumber,
            ]);

        }
    }
}
