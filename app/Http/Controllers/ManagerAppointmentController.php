<?php

namespace App\Http\Controllers;

use App\Classes\AppointmentData;
use App\Mail\AppointmentModifiedMessage;
use App\Models\Appointment;
use App\Models\Operator;
use App\Models\SecondaryAddress;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ManagerAppointmentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!auth('api')->check()){
            abort(400, 'usuario nao possui permissao');
        }
        $appointmentData = new AppointmentData();

        $validatorReturn = Validator::make(
            $request->all(), 
            $appointmentData->getIndexRulesToValidate(), 
        );

        if ($validatorReturn->fails()){
            return response()->json([
                'validation errors' => $validatorReturn->errors()
            ]);
        }

        if ( $request->pagination) {
            $appointments = $appointmentData->getAppointmentDataGroupedByStatusAndService($request->pagination, $request->departamen);
        }
        else {
            $appointments = $appointmentData->getAppointmentDataGroupedByStatusAndService(10, $request->departamen);
        }

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
        if (!auth('api')->check()){
            abort(400, 'usuario nao possui permissao');
        }
        $appointmentData = new AppointmentData();
        $data = [];


        foreach ($request->all() as $request){
            $validationReturn = Validator::make(
                $request,
                $appointmentData->getStoreManagerRulesToValidate()
            );
    
            if ($validationReturn->fails()){
                return response()->json([
                    'validation errors' => $validationReturn->errors(), 
                ])->setStatusCode(400);
            }

            array_push($data, [
                'datetime' => $request['datetime'], 
                'status' => 'Aguardando Confirmação',
                'service_id' => $request['service_id'],
                'operator_id' => isset($request['operator_id']) ? $request['operator_id'] : null,
                'room_id' => isset($request['address_id']) ? $request['address_id'] : null,
                'user_id' => isset($request['user_id']) ? $request['user_id'] : null,
                'compareceu' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        

        try {
            Appointment::insert($data);
        }
        catch (JWTException $e) {
            throw $e;
        }

        return response()->json(array('success' => 'Agendamento criado com sucesso'), 201);
        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!auth('api')->check()){
            abort(400, 'usuario nao possui permissao');
        }
        $appointmentData = new AppointmentData();

        $appointment = $appointmentData->getAppointmentData($id);

        return $appointment;
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
        if (!auth('api')->check()){
            abort(400, 'usuario nao possui permissao');
        }
        $appointmentData = new AppointmentData();

        $appointment = $appointmentData->getAppointmentData($id);

        if (!empty($request->datetime)) {

            $appointment->update([
                'datetime' => $request->datetime
            ]);
        }
        
        if (!empty($request->status)) {
            $validatorReturn = Validator::make($request->all(), [
                'status'          => [
                    'required',
                    Rule::in(['Confirmado','Cancelado','Atendido'])
                ]
            ]);

            if ($validatorReturn->fails()){
                return response()->json(['errors' => $validatorReturn->errors()], 400);
            }

            if ($appointment->status == 'Atendido') {
                return response()->json(['error' => 'Não é possível alterar o status'], 401);
            }

            $appointment->update([
                'status' => $request->status
            ]);
        }

        if (!empty($request->compareceu)) {
            $validatorReturn = Validator::make($request->all(), [
                'compareceu' => [
                    'required',
                    'boolean'
                ]
            ]);

            if ($validatorReturn->fails()){
                return response()->json(['errors' => $validatorReturn->errors()], 400);
            }

            if (!$request->status == 'Atendido') {
                return response()->json(['error' => 'Não é possível confirmar a presença, verifique se o usuário foi atendido'], 401);
            }

            $appointment->update ([
                'compareceu' => $request->compareceu
            ]);

        }

        if (!empty($request->room_id)) {
            $validatorReturn = Validator::make($request->all(), [
                'room_id' => [
                    'required',
                    'integer'
                ]
            ]);

            if ($validatorReturn->fails()){
                return response()->json(['errors' => $validatorReturn->errors()], 400);
            }

            $appointment->update ([
                'room_id' => $request->room_id
            ]);

        }

        if (!empty($request->user_id)) {
            $validatorReturn = Validator::make($request->all(), [
                'user_id' => [
                    'required',
                    'integer',
                    Rule::exists('users', 'id'),
                ]
            ]);

            if ($validatorReturn->fails()){
                return response()->json(['errors' => $validatorReturn->errors()], 400);
            }

            $appointment->update ([
                'user_id' => $request->user_id
            ]);
        }

        if (!empty($request->operator_id)) {
            $validatorReturn = Validator::make($request->all(), [
                'operator_id' => [
                    'required',
                    'integer',
                    Rule::exists('operators', 'id')->where('departament_id', auth()->user()->departament_id),
                ]
            ]);

            if ($validatorReturn->fails()){
                return response()->json(['errors' => $validatorReturn->errors()], 400);
            }

            $operator = Operator::find($request->operator_id)->getRoleNames()[0];

            if ($operator == 'admin' || $operator == null) {
                return response()->json(['errors' => 'Operador não tem o perfil correto para associação'], 400);
            }

            $appointment->update([
                'operator_id' => $request->operator_id
            ]);
        }

        $fullAddress = empty($appointment->room_id) ? null : SecondaryAddress::find($appointment->room_id)->with('addresses')->first();
        $service = empty($appointment->service_id) ? null : Service::find($appointment->service_id)->with('departaments')->first();


        if (!empty($appointment->user_id)){
            $user = User::find($appointment->user_id)->first();
            Mail::to($user->email)->send(new AppointmentModifiedMessage($appointment, $user, $fullAddress, $service));
        }
         
        $response = [
            'message' => 'Agendamento atualizado com sucesso',
            'id' => $id
        ];

        return response()->json($response)->setStatusCode(200);

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
