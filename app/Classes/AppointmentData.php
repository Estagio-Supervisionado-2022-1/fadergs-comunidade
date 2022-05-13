<?php

namespace App\Classes;

use App\Models\Appointment;
use App\Models\Departament;
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


    public function getIndexRulesToValidate (){
        return [
            'pagination' => [
                'integer',
                Rule::in([10, 25, 50, 100])
            ]
        ];
    }
}