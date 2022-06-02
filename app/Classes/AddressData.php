<?php

namespace App\Classes;

use App\Models\Address;
use App\Models\SecondaryAddress;
use Illuminate\Validation\Rule;

class AddressData {

    public function getAddressData ($pagination){
        $addresses = Address::all()->isEmpty() ?
                            ['address_error' => 'Não existem endereços cadastrados'] :
                            Address::paginate($pagination);

        return $addresses;  
    }

    public function getCountAddresses (){
        $addresses = Address::count() == 0 ?
                                ['address_error' => 'Não existem endereços cadastrados'] :
                                ['address_count' => Address::count()];
        return $addresses;
    }

    public function getSecondaryAddressesData ($pagination){
        $secondaryAdresses = SecondaryAddress::all()->isEmpty() ?
                                ['address_error' => 'Não existem salas cadastrados'] :
                                SecondaryAddress::with('addresses')->paginate($pagination);
        return $secondaryAdresses;

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

    public function getStoreSecondaryRulesToValidate () {
        return [
            'building_number' => [
                'required',
                'integer',
                'min:1',
                'max:99999999'
            ],
            'floor' => [
                'string',
                'numeric',
                'min:1',
                'max:2'
            ],
            'room' => [
                'string',
                'required',
                'min:1',
                'max:50'
            ],
            'description' => [
                'string',
                'min:3',
                'max:100'
            ],
            'zipcode' => [
                'required',
                'min:8',
                'max:9'
            ]
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
            'building_number.min' => 'O campo precisa conter no mínimo 1 carater',
            'building_number.max' => 'O campo precisa conter no máximo 7 carateres',
            'room.min' => 'O campo precisa conter no mínimo 1 carater',
            'room.max' => 'O campo precisa conter no máximo 50 carateres',
            'string' => 'O campo precisa ser uma string',
            'zipcode.min' => 'O campo precisa conter no mínimo 8 carateres',
            'zipcode.max' => 'O campo excedeu 9 caracteres',
            'numeric' => 'O campo precisa ser um número',
            'floor.min' => 'O campo precisa conter no mínimo 1 caracter',
            'floor.max' => 'O campo precisa conter no máximo 2 caracteres',
            'description.min' => 'O campo precisa conter no mínimo 3 caracteres',
            'description.max' => 'O campo precisa conter no máximo 100 caracteres',
            'integer' => 'O campo precisa ser um número inteiro',
            'passwordReset.accepted' => 'Os parâmetros fornecidos não estão corretos'
        ];
    }
}