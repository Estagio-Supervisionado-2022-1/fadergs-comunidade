<?php

namespace App\Http\Controllers;

use App\Classes\AppointmentData;
use App\Mail\AppointmentModifiedMessage;
use App\Models\Appointment;
use App\Models\SecondaryAddress;
use App\Models\Service;
use App\Models\User;
use Dingo\Api\Auth\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;


class AppointmentController extends Controller
{
    private $modelAppointment;
    
    public function __construct(Appointment $appointment)
    {
        $this->modelAppointment = $appointment;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $modelAppointment = $this->modelAppointment;

        if ($request->has('name')) {
            $modelAppointment = $modelAppointment->where('name', 'like', '%'. trim($request->input('name') . '%'));
        }

        if (
            $request->has('orderBy') 
            && in_array($request->input('orderBy'), ['id', 'name'])
        ) {
            $modelAppointment = $modelAppointment->orderBy($request->input('orderBy'));
        }
        
        if (
            $request->has('paginateRows') 
            && in_array($request->input('paginateRows'), [10, 25, 50, 100])
        ) {
            $paginateRows = $request->input('paginateRows');
        } else {
            $paginateRows = 10;
        }

        
        $appointments = $modelAppointment->paginate($paginateRows);
        return response()->json([
            'appointments' => $appointments
        ]);
        
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
    
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
