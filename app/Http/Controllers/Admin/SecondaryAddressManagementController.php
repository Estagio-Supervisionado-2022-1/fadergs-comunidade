<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\AddressData;
use Illuminate\Support\Facades\Validator;

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
            $addressData->getStoreRulesToValidate(), 
            $addressData->getErrorMessagesToValidate()
        );

        if ($validatorReturn->fails()){
            return response()->json([
                'validation errors' => $validatorReturn->errors()
            ]);
        }

        try {

            $zipCodeInfo = ZipCode::find($request->zipcode)->getObject();

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
