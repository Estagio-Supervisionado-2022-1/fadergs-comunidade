<?php

namespace App\Classes;

use App\Models\Address;
use Illuminate\Validation\Rule;

class AddressData {

    public function getAddressData ($pagination){
        $addresses = Address::all()->isEmpty() ?
                            ['address_error' => 'Não existem Endereços cadastrados'] :
                            Address::paginate($pagination);

        return $addresses;  
    }

    public function getCountAddresses (){
        $addresses = Address::count() == 0 ?
                                ['address_error' => 'Não existem Endereços cadastrados'] :
                                ['address_count' => Address::count()];
        return $addresses;
    }

    //===============================VALIDATORS=========================
    public function getIndexRulesToValidate(){
        return [
            'pagination' => [
                'integer',
                Rule::in([10, 25, 50, 100])
            ],
        ];
    }

    public function getStoreRulesToValidate(){
        return [
            'zipcode' => [
                'required',
                'min:8',
                'max:9'
            ],
        ];
    }

    

    public function getErrorMessagesToValidate(){
        return [
            'required' => 'O campo é obrigatório',
            'name.string' => 'O campo precisa ser uma string',
            'min' => 'O campo precisa conter no mínimo 8 carateres',
            'max' => 'O campo excedeu 9 caracteres',
            'integer' => 'O campo precisa ser um número inteiro',
            'passwordReset.accepted' => 'Os parâmetros fornecidos não estão corretos'
        ];
    }
}