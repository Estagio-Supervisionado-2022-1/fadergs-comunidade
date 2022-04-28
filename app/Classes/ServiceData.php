<?php

namespace App\Classes;

use App\Models\Service;

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

}
