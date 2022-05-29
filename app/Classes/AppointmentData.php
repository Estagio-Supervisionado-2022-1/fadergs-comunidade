<?php

namespace App\Classes;

use App\Models\Appointment;
use App\Models\Departament;
use App\Models\Service;
use Doctrine\Common\Annotations\Annotation\Enum;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class AppointmentData {

    public function getAppointmentsDataByUser($pagination, $user){
        $appointments = Appointment::where('user_id', $user->id)
                                    ->orderBy('datetime', 'desc')
                                    ->paginate($pagination);
        return $appointments;
    }

    public function getAppointmentsData($pagination) {
        $appointments = Appointment::orderBy('datetime', 'desc')
                                        ->paginate($pagination);
        die ($appointments);
    }

    public function getAppointmentLikeAdmin ($id){
        $appointment = Appointment::find($id);

        return $appointment;
    }

    public function getAppointmentLikeManager ($id){
        $appointment = Appointment::find($id);
        $service = Service::where('departament_id', auth()->user()->departament_id)->first() ;

        if (isset($service->id)){
            if ($appointment->service_id == $service->id )
                return $appointment;
        }

        else {
            return response()->json(['error' => 'Não foi possível encontrar este agendamento'], 400);
        }


    }


    public function getIndexRulesToValidate (){
        return [
            'pagination' => [
                'integer',
                Rule::in([10, 25, 50, 100])
            ]
        ];
    }




    public function getStoreRulesToValidate () {
        return [
            'status' => [
                Rule::in(['Aguardando Confirmação','Confirmado','Cancelado','Atendido'])
            ],
            'datetime' => [
                'required',
                'date_format:Y-m-d H:i:s',
                'after:now',
            ],
            'service_id' => [
                'required',
                'integer'
            ],
            'operator_id' => [
                'integer'
            ],
            'address_id' => [
                'integer',
            ]

        ];
    }

    public function getStoreManagerRulesToValidate () {
        return [
            'status' => [
                Rule::in(['Aguardando Confirmação','Confirmado','Cancelado','Atendido'])
            ],
            'datetime' => [
                'required',
                'date_format:Y-m-d H:i:s',
                'after:now',
            ],
            'service_id' => [
                'required',
                'integer',
                Rule::exists('services', 'id')->where('departament_id', auth()->user()->departament_id),
            ],
            'operator_id' => [
                'integer',
                'nullable',
                Rule::exists('operators', 'id')->where('departament_id', auth()->user()->departament_id),
            ],
            'address_id' => [
                'integer',
            ]

        ];
    }
}