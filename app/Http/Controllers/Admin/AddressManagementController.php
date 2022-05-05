<?php

namespace App\Http\Controllers\Admin;

use App\Classes\AddressData;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;
use Canducci\ZipCode\Facades\ZipCode;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;

class AddressManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        
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
            $addresses = $addressData->getAddressData ($request->pagination);
        }
        else {
            $addresses = $addressData->getAddressData(10);
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
    public function store(Request $request){
        $addressData = new addressData();

        $validatorReturn = Validator::make(
            $request->all(), 
            $addressData->getStoreRulesToValidate(), 
            $addressData->getErrorMessagesToValidate()
        );

        if ($validatorReturn->fails()){
            return response()->json([
                'validation errors' => $validatorReturn->errors()
            ]);
        }

        try {

            $zipCodeInfo = ZipCode::find($request->zipcode, true)->getObject();

            Address::withTrashed()->firstOrCreate([
                'zipcode'              => $zipCodeInfo->cep,
                'streetName'    => $zipCodeInfo->logradouro,
                'district'      => $zipCodeInfo->bairro,
                'city'          => $zipCodeInfo->localidade,
                'stateAbbr'     => $zipCodeInfo->uf,
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
    public function show($id){
        if (! $address = Address::where('id', $id)->with('secondary_addresses')->first()) {
            throw new NotFoundHttpException('Endereço não encontrado com o id = ' . $id);
        }

        return response()->json($address)->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        return response()->json(['message' => 'Não autorizado']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! $address = Address::find($id)) {
            throw new NotFoundHttpException('Endereço não encontrado com o id = ' . $id);
        }
        try {
                $address->delete();
                return response()->json(['message' => 'Endereço desativado com sucesso']);
                        
        } catch (HttpException $e) {
            throw $e;
        }
    }
}
