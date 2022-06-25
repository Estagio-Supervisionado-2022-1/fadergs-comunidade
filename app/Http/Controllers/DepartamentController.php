<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departament;
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
    public function index(Request $request)
    {   
        $modelDepartament = $this->modelDepartament;

        if ($request->has('name')) {
            $modelDepartament = $modelDepartament->where('name', 'like', '%'. trim($request->input('name') . '%'));
        }

        if (
            $request->has('orderBy') 
            && in_array($request->input('orderBy'), ['id', 'name'])
        ) {
            $modelDepartament = $modelDepartament->orderBy($request->input('orderBy'));
        }
        
        if (
            $request->has('paginateRows') 
            && in_array($request->input('paginateRows'), [10, 25, 50, 100])
        ) {
            $paginateRows = $request->input('paginateRows');
        } else {
            $paginateRows = 10;
        }

        $departaments = $modelDepartament->paginate($paginateRows);

        return response()->json(
            $departaments, 
            $departaments->count() ? Response::HTTP_OK : Response::HTTP_NO_CONTENT
        );
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
        if (!auth('api')->check()){
            abort(400, 'usuario nao possui permissao');
        }

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
        
        if (empty($departament)) {
            return response(null, Response::HTTP_NOT_FOUND);
        }

        $departament->load('history');
        
        return response()->json($departament, Response::HTTP_OK);
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
        if (!auth('api')->check()){
            abort(400, 'usuario nao possui permissao');
        }

        $data = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:10|max:255'
        ]);
        
        $departament = $this->modelDepartament->find($id);
        
        if (empty($departament)) {
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
        if (!auth('api')->check()){
            abort(400, 'usuario nao possui permissao');
        }
        
        $departament = $this->modelDepartament->find($id);
        
        if (empty($departament)) {
            return response(null, Response::HTTP_NOT_FOUND);
        }

        $departament->delete();
        
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
