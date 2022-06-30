<?php

namespace App\Classes;

use App\Models\Address;
use App\Models\Appointment;
use App\Models\Departament;
use App\Models\Operator;
use App\Models\SecondaryAddress;
use App\Models\Service;
use App\Models\User;
use Doctrine\Common\Annotations\Annotation\Enum;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class AppointmentData
{

    public function getAppointmentsDataByUser($user)
    {
        if (!auth('api_users')->check()) {
            abort(400, 'Operador nao eh do tipo usuario');
        }
        $appointments = Appointment::where('user_id', $user->id)
            ->orderBy('datetime', 'desc')
            ->get();

        return $appointments;
    }

    public function getAppointmentsData($pagination)
    {
        $appointments = Appointment::where('status', '<>', 'Atendido')
            ->orWhere('status', '<>', 'Cancelado')
            ->orderBy('datetime', 'desc')
            ->get();

        return $appointments;
    }

    public function getAppointmentDataAdmin()
    {
        $appointments = DB::select('SELECT * FROM full_data_appointments');


        return $appointments;
    }

    public function getAppointmentDataByDepartament()
    {
        
        $appointments = DB::select('SELECT * FROM full_data_appointments WHERE service_departament_id = '.auth()->user()->departament_id);
        
        return $appointments;


    }

    public function getAppointmentLikeAdmin($id)
    {
        $appointment = Appointment::find($id);

        if ($appointment == null) {
            return response()->json(['error' => 'Não foi possível encontrar o agendamento'], 400);
        }

        return $appointment;
    }

    public function getAppointmentData($id)
    {
        $appointment = Appointment::find($id);
        $userDepartament = auth()->user()->departament_id;
        if (Service::where('departament_id', $userDepartament)->where('id', $appointment->service_id)->exists()) {
            return $appointment;
        }
        return response()->json(['error' => 'Não foi possível encontrar nenhum agendamento'], 400);
    }

    public function getIndexRulesToValidate()
    {
        return [
            'pagination' => [
                'integer',
                Rule::in([10, 25, 50, 100])
            ]
        ];
    }

    public function getStoreRulesToValidate()
    {
        return [
            'status' => [
                Rule::in(['Aguardando Confirmação', 'Confirmado', 'Cancelado', 'Atendido'])
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

    public function getStoreManagerRulesToValidate()
    {
        return [
            'status' => [
                Rule::in(['Aguardando Confirmação', 'Confirmado', 'Cancelado', 'Atendido'])
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
