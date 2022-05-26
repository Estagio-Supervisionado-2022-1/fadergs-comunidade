<?php

namespace App\Http\Controllers;

use App\Models\Departament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 

class DepartamentController extends Controller
{
    private $modelDepartament;

    public function __construct(Departament $departament)
    {
        $this->modelDepartament = $departament;   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departaments = $this->modelDepartament->get();
        return response()->json(
            $departaments,
            $departaments->count() ? Response::HTTP_OK : Response::HTTP_NO_CONTENT
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|min:3|max:255'
        ]);

        $departament = $this->modelDepartament->create($data);

        return response()->json($departament, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $departament = $this->modelDepartament->find($id);

        if (empty($departament)){
            return response(null, Response::HTTP_NOT_FOUND);
        }

        $departament->load("history");

        return response()->json($departament, Response::HTTP_OK);
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
        $data = $request->validate([
            'name' => 'required|string|min:3|max:255'
        ]);

        $departament = $this->modelDepartament->find($id);
        
        if (empty($departament)){
            return response(null, Response::HTTP_NOT_FOUND);
        }

        $departament->update($data);

        return response()->json($departament, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $departament = $this->modelDepartament->find($id);

        if (empty($departament)){
            return response(null, Response::HTTP_NOT_FOUND);
        }

        $departament->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
