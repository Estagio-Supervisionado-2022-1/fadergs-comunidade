<?php

namespace App\Classes;

use App\Models\Service;
use Illuminate\Validation\Rule;

class ServiceData {

    public function getServiceData ($pagination){
        $services = Service::all()->isEmpty() ?
                            ['service_error' => 'Não existem serviços cadastrados'] :
                            Service::with('departaments')->paginate($pagination);

        return $services;  
    }

    public function getCountServices (){
        $services = Service::count() == 0 ?
                                ['service_error' => 'Não existem serviços cadastrados'] :
                                ['service_count' => Service::count()];
        return $services;
    }

    //===================================VALIDAÇÕES=====================================

    public function getIndexRulesToValidate(){
        return [
            'pagination' => [
                'integer',
                Rule::in([10, 25, 50, 100])
            ]
        ];
    }

    public function getStoreRulesToValidate () {
        return [
            'name'          => [
                'required',
                'string',
                'min:3',
                'max:50'
            ],
            'departament_id' => [
                'required',
                'integer'
            ]
        ];
    }

    public function getErrorMessagesToValidate(){
        return [
            'required' => 'O campo é obrigatório',
            'name.string' => 'O campo precisa ser uma string',
            'min' => 'O campo precisa conter no mínimo 3 carateres',
            'max' => 'O campo excedeu 50 caracteres',
            'integer' => 'O campo precisa ser um número inteiro',
            'passwordReset.accepted' => 'Os parâmetros fornecidos não estão corretos'
        ];
    }
    

}
