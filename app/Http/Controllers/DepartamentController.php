<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departament;

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
        return response()->json($departaments, $departaments->count() ? 200 : 204);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
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
            'name' => 'required|string|min:3|max:255',
        ]);

        $departament = $this->modelDepartament->create($data);
        
        return response()->json($departament, 201);
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
        
        if (empty($departament)) {
            return abort(404);
        }

        $departament->load('history');
        
        return response()->json($departament, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        $data = $request->validate([
            'name' => 'required|string|min:3|max:255',
        ]);
        
        $departament = $this->modelDepartament->find($id);
        
        if (empty($departament)) {
            return abort(404);
        }

        $departament->update($data);
        
        return response()->json($departament, 200);
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
        
        if (empty($departament)) {
            return abort(404);
        }

        $departament->delete();
        
        return abort(204);
    }
}
