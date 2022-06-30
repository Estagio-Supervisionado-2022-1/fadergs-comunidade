<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FullDataAppointments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('create view full_data_appointments as select appointments.id appointment_id, appointments.status appointment_status, appointments.datetime appointment_datetime, appointments.compareceu appointment_compareceu, 
        users.name user_name, users.email user_email, users.cpf user_cpf, users.telphone user_telphone,
        secondary_addresses.building_number address_room, secondary_addresses.floor address_floor,secondary_addresses.description address_description,
        operators.name operator_name, operators.email operator_email,
        services.name service_name, services.duration service_duration, services.description service_description,
        departaments.name service_departament_name, departaments.description service_departament_description, departaments.id service_departament_id,
        addresses.zipcode address_zipcode, addresses."streetName"  address_street_name, addresses.district address_district, addresses.city address_city, addresses."stateAbbr"  address_state
        from appointments
        left join users on appointments.user_id = users.id
        left join secondary_addresses on appointments.room_id = secondary_addresses.id
        left join addresses on secondary_addresses.address_id = addresses.id 
        left join operators on appointments.operator_id = operators.id 
        left join services on appointments.service_id = services.id
        left join departaments on services.departament_id = departaments.id 
        order by appointments.id DESC');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW full_data_appointments');
    }
}
