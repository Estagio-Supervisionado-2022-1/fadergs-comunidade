<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Seeder;
use Canducci\ZipCode\Facades\ZipCode;


class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $zipcodes = ['90020-061', '90010-273', '90020-090', '90010-170', '90020-023'];

        foreach ($zipcodes as $zipcode){
            $zipCodeInfo = ZipCode::find($zipcode)->getObject();
            Address::insert([
                'zipcode'              => $zipCodeInfo->cep,
                'streetName'    => $zipCodeInfo->logradouro,
                'district'      => $zipCodeInfo->bairro,
                'city'          => $zipCodeInfo->localidade,
                'stateAbbr'     => $zipCodeInfo->uf,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

        }

    }
}
