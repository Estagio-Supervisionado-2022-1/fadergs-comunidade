<?php

namespace App\Classes;

use App\Models\Address;

class AddressData {

    public function getAddressData ($pagination){
        $addressess = Address::all() == null ?
                            ['address_error' => 'Não existem Endereços cadastrados'] :
                            Address::all()->paginate($pagination);

        return $addressess;  
    }

    public function getCountAddresses (){
        $addressess = Address::count() == 0 ?
                                ['address_error' => 'Não existem Endereços cadastrados'] :
                                ['address_count' => Address::count()];
        return $addressess;
    }
}