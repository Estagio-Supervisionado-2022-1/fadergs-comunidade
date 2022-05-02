<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\AddressData;
use App\Models\Address;
use App\Models\SecondaryAddress;
use Canducci\ZipCode\Facades\ZipCode;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SecondaryAddressManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $addressData = new AddressData();

        $validatorReturn = Validator::make(
            $request->all(), 
            $addressData->getIndexRulesToValidate(), 
            $addressData->getErrorMessagesToValidate()
        );

        if ($validatorReturn->fails()){
            return response()->json([
                'validation errors' => $validatorReturn->errors()
            ]);
        }

        if ( $request->pagination) {
            $addresses = $addressData->getSecondaryAddressesData ($request->pagination);
        }
        else {
            $addresses = $addressData->getSecondaryAddressesData(10);
        }

        return response()->json([
            'addresses' => $addresses,
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
        $addressData = new addressData();

        $validatorReturn = Validator::make(
            $request->all(), 
            $addressData->getStoreSecondaryRulesToValidate(), 
            $addressData->getErrorMessagesToValidate()
        );

        if ($validatorReturn->fails()){
            return response()->json([
                'validation errors' => $validatorReturn->errors()
            ]);
        }

        try {

            $zipCodeInfo = ZipCode::find($request->zipcode)->getObject();
            $address = Address::where('zipcode', $zipCodeInfo->cep)->first();

            $secondary = SecondaryAddress::withTrashed()->firstOrCreate([
                'building_number'              => $request->building_number,
                'floor'    => $request->floor,
                'room'      => $request->room,
                'description'          => $request->description,
                'address_id'     => $address->id,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

        } catch (NotFoundHttpException $h) {
            throw $h;
        }

        return response()->json(['message_success' => 'Endereço criado com sucesso!'])
                            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! $secondary_address = SecondaryAddress::where('id', $id)->with('addresses')->first()) {
            throw new NotFoundHttpException('Endereço não encontrado com o id = ' . $id);
        }

        return response()->json($secondary_address)->setStatusCode(200);
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
        $addressData = new AddressData();

        if (! $secondaryAddress = SecondaryAddress::where('id', $id)->with('addresses')->first()) {
            throw new NotFoundHttpException('Serviço não encontrado com o id = ' . $id);
        }
            if (!empty($request->building_number)){
                $validatorReturn = Validator::make($request->all(), [
                    'building_number' => [
                        'required',
                        'integer',
                        'min:1',
                        'max:99999999'
                        ],
                    ], $addressData->getErrorMessagesToValidate());
            

                if ($validatorReturn->fails()){
                    return response()->json(['errors' => $validatorReturn->errors()]);
                }

                $secondaryAddress->updateOrCreate(['id' => $secondaryAddress->id], [
                    'building_number' => $request->building_number,
                ]);

            }

            if (!empty($request->floor)){
                $validatorReturn = Validator::make($request->all(), [
                    'floor' => [
                        'required',
                        'string',
                        'min:1',
                        'max:2'
                    ],
                ], $addressData->getErrorMessagesToValidate());
            
            if ($validatorReturn->fails()){
                return response()->json(['errors' => $validatorReturn->errors()]);
            }

            
            $secondaryAddress->updateOrCreate(['id' => $id], [
                'floor' => $request->floor,
            ]);

        }

            if (!empty($request->room)){
                $validatorReturn = Validator::make($request->all(), [
                    'room' => [
                        'string',
                        'required',
                        'min:1',
                        'max:50'
                    ],
                ], $addressData->getErrorMessagesToValidate());
            

                if ($validatorReturn->fails()){
                    return response()->json(['errors' => $validatorReturn->errors()]);
                }
                
                $secondaryAddress->updateOrCreate(['id' => $id], [
                    'room' => $request->room,
                ]);
            }
        
            if (!empty($request->description)){
                $validatorReturn = Validator::make($request->all(), [
                    'description' => [
                        'required',
                        'string',
                        'min:3',
                        'max:100'
                    ],
                ], $addressData->getErrorMessagesToValidate());
            

                if ($validatorReturn->fails()){
                    return response()->json(['errors' => $validatorReturn->errors()]);
                }

                
                $secondaryAddress->updateOrCreate(['id' => $id], [
                    'description' => $request->description,
                ]);
            }
            if (!empty($request->address_id)){
                $validatorReturn = Validator::make($request->all(), [
                    'address_id' => [
                        'required',
                        'integer',
                    ],
                ], $addressData->getErrorMessagesToValidate());
            

                if ($validatorReturn->fails()){
                    return response()->json(['errors' => $validatorReturn->errors()]);
                }

                if (! $address = Address::find($request->address_id)){
                    throw new NotFoundHttpException('Endereço não encontrado com o id = ' . $request->departament_id);
                }


                $secondaryAddress->updateOrCreate(['id' => $id], [
                    'address_id' => $request->address_id,
                ]);
            
        }
        $response = [
            'message' => 'Serviço atualizado com sucesso',
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
    public function destroy($id){
        if (! $secondaryAddress = SecondaryAddress::find($id)) {
            throw new NotFoundHttpException('Sala não encontrada com o id = ' . $id);
        }
        try {
                $secondaryAddress->delete();
                return response()->json(['message' => 'Serviço desativado com sucesso']);
                        
        } catch (HttpException $e) {
            throw $e;
        }
    }
}
