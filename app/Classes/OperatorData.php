<?php

namespace App\Classes;

use App\Models\Operator;

class OperatorData {


    public function getDataAdminOperator($pagination){
        $administrators = Operator::role('admin') == null ? 
                                    ['admin_error' => 'Não existem administradores cadastrados'] : 
                                    Operator::role('admin')->paginate($pagination);
        return $administrators;
               
    }

    public function getDataManagerOperator($pagination){
        $managers = Operator::role('manager') == null ? 
                                ['manager_error' => 'Não existem coordenadores cadastrados'] :
                                Operator::role('manager')->paginate($pagination);
        return $managers;
        
        
    }

    public function getDataStudentOperator($pagination){
        $students = Operator::role('student') == null ?
                                ['students_error' => 'Não existem alunos cadastrados'] :
                                Operator::role('student')->paginate($pagination);

        return $students;  
        
    }

    
    public function getCountAdminOperator() {
        $administrators = Operator::role('admin')->count() == 0 ?
                                ['admin_error' => 'Não existem Administradores cadastrados'] :
                                ['admin_count' => Operator::role('admin')->count()];
        return $administrators;
    }

    public function getCountManagerOperator() {
        $managers = Operator::role('manager')->count() == 0 ?
                                ['manager_error' => 'Não existem Coordenadores cadastrados'] :
                                ['manager_count' => Operator::role('manager')->count()];
        return $managers;
    }

    public function getCountStudentOperator() {
        $students = Operator::role('student')->count() == 0 ?
                                ['student_error' => 'Não existem alunos cadastrados'] :
                                ['student_count' => Operator::role('student')->count()];
        return $students;
    }
        

}
