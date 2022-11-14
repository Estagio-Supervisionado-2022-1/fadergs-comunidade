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



class UserAppointmentController extends Controller
{
    use HasRoles;

    private $request;
    private $user;
    private const GUARD = 'api_users';

    public function __construct(Request $request)
    {
        if (!auth(self::GUARD)->check()) {
            abort(Response::HTTP_UNAUTHORIZED);
        }
        $this->request = $request;
        $this->user = auth(self::GUARD)->user();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $request = $this->request;
        // PEGAR OS AGENDAMENTOS DO USUÁRIO
        $user = $this->user;
        $user->userRole = $user->getRoleNames()[0];

        $appointmentData = new AppointmentData();

        $validatorReturn = Validator::make(
            $request->all(), 
            $appointmentData->getIndexRulesToValidate()
        );

        if ($validatorReturn->fails()){
            return response()->json([
                'validation errors' => $validatorReturn->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($request->has('pagination') && $request->pagination > 10) {
            $pagination = $request->pagination;
        } else {
            $pagination = 10;
        }
        
        $appointments = $appointmentData->getAppointmentsDataByUser($pagination, $user);

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
        if (!auth('api_users')->check()){
            abort(400, 'usuario nao possui permissao');
        }
        $appointmentData = new AppointmentData();
        $data = [];

        foreach ($request->all() as $request){
            $validationReturn = Validator::make(
                $request,
                $appointmentData->getStoreRulesToValidate()
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
                'compareceu' => false,
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
        $appointmentData = new AppointmentData();
    
        $appointment = $appointmentData->getAppointmentData($id);

        $user = $this->user;

        if ($user->id != $appointment->user_id) {
            return response()->json(['error' => 'Você não pode visualizar esse agendamento'], Response::HTTP_UNAUTHORIZED);
        }

        return $appointment;
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
        $request = $this->request;

        $appointmentData = new AppointmentData();

        if(empty($id)) {
            return response()->json(['error' => 'O id do agendamento deve ser informado'], Response::HTTP_UNAUTHORIZED);
        }

        $appointment = $appointmentData->getAppointmentDataWithoutWhere($id);
        
        $user = $this->user;

        if(empty($appointment)) {
            return response()->json(['error' => 'Agendamento não encontrado'], Response::HTTP_UNAUTHORIZED);
        }
    
        if ($appointment->user_id && $user->id != $appointment->user_id) {
            return response()->json(['error' => 'Você não pode alterar esse agendamento'], Response::HTTP_UNAUTHORIZED);
        }

        if(!isset($request->user_id)) {
            return response()->json(['error' => 'O id do usuário deve ser informado'], Response::HTTP_UNAUTHORIZED);
        }
       
        if (!empty($request->status)) {
            $validatorReturn = Validator::make($request->all(), [
                'status'          => [
                    'required',
                    Rule::in(['Aguardando Confirmação','Cancelado'])
                ]
            ]);

            if ($validatorReturn->fails()){
                return response()->json(['errors' => $validatorReturn->errors()], Response::HTTP_BAD_REQUEST);
            }

            if ($appointment->status == 'Atendido') {
                return response()->json(['error' => 'Não é possível alterar o status'], Response::HTTP_UNAUTHORIZED);
            }

            $appointmentUpdate = Appointment::find($id);

            if (empty($appointmentUpdate)) {
                return abort(404);
            }

            $appointmentUpdate->update([
                'status'     => $request->status,
                'user_id'    => $request->user_id,
                'updated_at' => now()
            ]);
        }

        $response = [
            'message' => 'Agendamento atualizado com sucesso',
            'id' => $id
        ];

        return response()->json($response);

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
