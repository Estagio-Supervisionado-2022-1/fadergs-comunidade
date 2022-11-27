<?php

namespace App\Classes;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\Address;
use App\Models\Departament;
use Doctrine\Common\Annotations\Annotation\Enum;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class AppointmentData
{

    public function getAppointmentsDataByUser($pagination, $user)
    {
        if (!auth('api_users')->check()){
            abort(400, 'Operador nao eh do tipo usuario');
        }
        $appointments = Appointment::where('user_id', $user->id)
            ->join('operators', 'appointments.operator_id', '=', 'operators.id')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->join('secondary_addresses', 'appointments.room_id', '=', 'secondary_addresses.id')
            ->join('addresses', 'secondary_addresses.address_id', '=', 'addresses.id')
            ->orderBy('datetime', 'desc')
            ->get([
                'appointments.id as id',
                'appointments.status as appointment_status',
                'appointments.datetime as appointment_datetime',
                'operators.name as operator_name',
                'secondary_addresses.description as address_description',
                'secondary_addresses.floor as address_floor',
                'secondary_addresses.room as address_room',
                'secondary_addresses.building_number as address_number',
                'addresses.zipcode as address_zipcode',
                'addresses.streetName as address_street',
                'addresses.district as address_district',
                'addresses.city as address_city',
                'addresses.stateAbbr as address_state',
                'services.name as service_name'
            ]);

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

    public function getAppointmentDataGroupedByStatusAndDepartament($pagination)
    {
        $appointments = Appointment::get();

        return $appointments;
    }

    public function getAppointmentDataGroupedByStatusAndService($pagination)
    {
        $appointments = Appointment::get();
        $userDepartament = auth()->user()->departament_id;
        $data = collect();
        
        foreach ($appointments as $appointment){
            if (Service::where('departament_id', $userDepartament)->where('id', $appointment->service_id)->exists()) {
                $data->push($appointment);
                
            }
        }

        if ($data->count() > 0) {
            return $data;
        }
           
        return response()->json(['error' => 'Não foi possível encontrar nenhum agendamento'], 400);
    }

    public function getAppointmentDataGroupedByStatusAndServiceAndOperator($pagination, $operator_id)
    {
        $appointments = Appointment::get()->where('operator_id', $operator_id);
        $userDepartament = auth()->user()->departament_id;
        $data = collect();
        
        foreach ($appointments as $appointment){
            if (Service::where('departament_id', $userDepartament)->where('id', $appointment->service_id)->exists()) {
                $data->push($appointment);
                
            }
        }

        if ($data->count() > 0) {
            return $data;
        }
           
        return response()->json(['error' => 'Não foi possível encontrar nenhum agendamento'], 400);
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

    public function getAppointmentDataWithoutWhere($id)
    {
        $appointment = Appointment::find($id);
        if ($appointment) {
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
