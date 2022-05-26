<?php

namespace App\Http\Controllers;

use App\Classes\AppointmentData;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class AppointmentManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $DEFAULT_PAGINATION = 10;
        $appointmentData = new AppointmentData();
        $validationReturn = Validator::make(
            $request->all(),
            $appointmentData->getIndexRulesToValidate()
        );

        if ($validationReturn->fails()){
            return response()->json([
                'appointment_errors' => $validationReturn->errors()
            ]);
        }

        //=======================USER==========================
        if (auth()->guard('api_users')->check()){
            if (! $request->pagination) {
                $appointments = $appointmentData->getAppointmentsDataByUser($DEFAULT_PAGINATION, auth()->user());
                return response()->json(['appointments' => $appointments]);
            }
            $appointments = $appointmentData->getAppointmentsDataByUser($request->pagination, auth()->user());
            return response()->json(['appointments' => $appointments]);
        } 


        //======================ADMIN==========================
        elseif (auth()->guard('api')->check()){
            if (! $request->pagination) {
                $appointments = $appointmentData->getAppointmentsData($DEFAULT_PAGINATION);
                return response()->json(['appointments' => $appointments]);
            }
            $appointments = $appointmentData->getAppointmentsData($request->pagination);
            return response()->json(['appointments' => $appointments]);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
