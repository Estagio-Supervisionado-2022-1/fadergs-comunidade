<?php

namespace App\Classes;

use App\Models\Operator;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OperatorData {

    use HasRoles;


    //================================DATA FETCH========================================
    public function getDataAdminOperator($pagination){
        $administrators = Operator::role('admin') == null ? 
                                    ['admin_error' => 'Não existem administradores cadastrados'] : 
                                    Operator::role('admin')->get();
        return $administrators;
               
    }

    public function getDataManagerOperator($pagination){
        $managers = Operator::role('manager') == null ? 
                                ['manager_error' => 'Não existem coordenadores cadastrados'] :
                                Operator::role('manager')->orderBy('name', 'ASC')->get()->groupBy('departament_id');
        return $managers;
        
        
    }

    public function getDataStudentOperator($pagination){
        $students = Operator::role('student') == null ?
                                ['students_error' => 'Não existem alunos cadastrados'] :
                                Operator::role('student')->orderBy('name', 'ASC')->get()->groupBy('departament_id');

        return $students;  
        
    }

    public function getDataStudentOperatorLikeManager($pagination){
        $students = Operator::role('student') == null ?
                                ['students_error' => 'Não existem alunos cadastrados'] :
                                Operator::role('student')->where('departament_id', auth()->user()->departament_id)->orderBy('name', 'ASC')->get()->groupBy('departament_id');

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

    public function getIndexRulesToValidate (){
        return [
            'pagination' => [
                'integer',
                Rule::in([10, 25, 50, 100])
            ],
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
            'email'         => [
                'required',
                'email',
                'max:255',
                'unique:operators,email'
            ],
            'departament_id' => [
                'required',
                'integer'
            ]
        ];
    }

    public function getPasswordResetRulesToValidade(){
        return [
            'passwordReset' => [
                'required',
                'accepted'
            ]
        ];
    }


    public function getErrorMessagesToValidate(){
        return [
            'required' => 'O campo é obrigatório',
            'name.string' => 'O campo precisa ser uma string',
            'min' => 'O campo precisa conter no mínimo 3 carateres',
            'max' => 'O campo excedeu 50 caracteres',
            'email' => 'O campo precisa ser um e-mail válido',
            'email.max' => 'O campo excedeu 255 caracteres',
            'email.unique' => 'O e-mail já está cadastrado, reset a senha ou reative o usuário',
            'integer' => 'O campo precisa ser um número inteiro',
            'passwordReset.accepted' => 'Os parâmetros fornecidos não estão corretos'
        ];
    }

    
}
