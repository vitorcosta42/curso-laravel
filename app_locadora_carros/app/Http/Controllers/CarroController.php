<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Repositories\CarroRepository;
use Illuminate\Http\Request;
class CarroController extends Controller
{
    private $carro;
    /**
     * Display a listing of the resource.
     */
     public function __construct(Carro $carro) {
        $this->carro = $carro;
    }


    public function index(Request $request)
    {
        $carroRepository = new CarroRepository($this->carro);
        if ($request->has('atributos_modelo')) {
            $atributos_modelo = 'modelo:id,' . $request->atributos_modelo;
            $carroRepository->selectAtributosRegistrosRelacionados(
                $atributos_modelo
            );
        } else {
            $carroRepository->selectAtributosRegistrosRelacionados('modelo');
        }
        if ($request->has('filtro')) {
            $carroRepository->filtro($request->filtro);
        }

        if ($request->has('atributos')) {
            $carroRepository->selectAtributos(
                $request->atributos
            );
        }
        if ($request->has('buscar')) {
            return response()->json($carroRepository->getResultado(), 200);
    
            }
            return response()->json($carroRepository->getResultadoPaginado(5), 200);
        }

    public function store(Request $request)
    {
        $request->validate(
            $this->carro->rules()
        );
        $carro = $this->carro->create([
            'modelo_id' => $request->modelo_id,
            'placa' => $request->placa,
            'disponivel' => $request->disponivel,
            'km' => $request->km,
        ]);
        return response()->json($carro, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $carro = $this->carro->with('modelo')->find($id);
        if ($carro === null) {
            return response()->json(
                ['erro' => 'Recurso pesquisado não existe'],
                404
            );
        }
        return response()->json($carro, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $carro = $this->carro->find($id);
        if ($carro === null) {
            return response()->json(
                ['erro' => 'Impossível realizar
                a atualização. O recurso solicitado
                não existe'],
                404
            );
        }

        if ($request->method() === 'PATCH') {
            $dinamicRules = array();
            foreach ($carro->rules() as  $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dinamicRules[$input] = $rule;
                }
            }
            $request->validate($dinamicRules);
        } else {
            $request->validate($carro->rules());
        }

        $carro->fill($request->all());
        $carro->save();

        return response()->json($carro, 200);
    }

    public function destroy($id)
    {
        $carro = $this->carro->find($id);

        if ($carro === null) {
            return response()->json(
                ['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'],
                404
            );
        }

        $carro->delete();
        return response()->json(['msg' => 'O carro foi removido com sucesso'], 200);
    }
}
