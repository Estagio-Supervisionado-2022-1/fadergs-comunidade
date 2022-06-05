<?php

namespace App\Classes;

use App\Models\Departament;

class DepartamentData {

    public function getDepartamentData ($pagination){
        $departaments = Departament::all() == null ?
                            ['departament_error' => 'Não existem departamentos cadastrados'] :
                            Departament::all();

        return $departaments;  
    }

    public function getCountDepartaments (){
        $departaments = Departament::count() == 0 ?
                                ['departament_error' => 'Não existem departamentos cadastrados'] :
                                ['departament_count' => Departament::count()];
        return $departaments;
    }
}