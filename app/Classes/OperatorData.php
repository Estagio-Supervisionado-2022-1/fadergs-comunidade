<?php

namespace App\Classes;

use App\Models\Operator;
use Spatie\Permission\Traits\HasRoles;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OperatorData {

    use HasRoles;


    //================================DATA FETCH========================================
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

    //=====================================COUNT FUNCTIONS==================================
    
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

    //===================================DATA VALIDATION=====================================

    public function getDataAdminById($id) {
        if( $administrator = Operator::where('id', $id)->with('Departament', 'roles', 'permissions')->first()){
            
             if (! $administrator->hasRole('admin')){
                 throw new NotFoundHttpException('Administrador não encontrado com o id = ' . $id);
             }
             return $administrator;
        }
 
        throw new NotFoundHttpException('Administrador não encontrado com o id = ' . $id);
        
     }
    
    public function getDataManagerById($id) {
       if( $manager = Operator::where('id', $id)->with('Departament', 'roles', 'permissions')->first()){
           
            if (! $manager->hasRole('manager')){
                throw new NotFoundHttpException('Coordenador não encontrado com o id = ' . $id);
            }
            return $manager;
       }

       throw new NotFoundHttpException('Coordenador não encontrado com o id = ' . $id);
       
    }
    public function getDataStudentById($id) {
       if( $student = Operator::where('id', $id)->with('Departament', 'roles', 'permissions')->first()){
           
            if (! $student->hasRole('student')){
                throw new NotFoundHttpException('Aluno não encontrado com o id = ' . $id);
            }
            return $student;
       }

       throw new NotFoundHttpException('Aluno não encontrado com o id = ' . $id);
       
    }
        

}