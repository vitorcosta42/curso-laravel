<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    private $marca;

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $marcas = $this->marca->all();
        return $marcas;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $marca = $this->marca->create($request->all());
        return $marca;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $marca = $this->marca->find($id);
        if ($marca === null) {
            return ['erro' => 'Recurso pesquisado
            não existe'];
        }
        return $marca;
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);
        if ($marca === null) {
            return ['erro' => 'Impossível realizar
            a atualização. O recurso solicitado
            não existe'];
        }
        $marca->update($request->all());
        return $marca;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        if ($marca === null) {
            return ['erro' => 'Impossível realizar
            a exclusão. O recurso solicitado
            não existe'];
        }
        $marca->delete();
        return ['msg' => 'A marca foi removida com sucesso!'];
    }
}
