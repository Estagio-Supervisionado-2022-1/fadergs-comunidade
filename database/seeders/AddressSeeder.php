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
        $zipcodes = ['90020061', '90010273', '90020090', '90010170', '90020023'];

        foreach ($zipcodes as $zipcode){
            $zipCodeInfo = ZipCode::find($zipcode, true)->getObject();
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
