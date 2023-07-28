<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use App\Repositories\ModeloRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{

    private $modelo;

    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
    }

    public function index(Request $request)
    {
        $modeloRepository = new ModeloRepository($this->modelo);
        if ($request->has('atributos_marca')) {
            $atributos_marca = 'marca:id,' . $request->atributos_marca;
            $modeloRepository->selectAtributosRegistrosRelacionados(
                $atributos_marca
            );
        } else {
            $modeloRepository->selectAtributosRegistrosRelacionados('marca');
        }
        if ($request->has('filtro')) {
            $modeloRepository->filtro($request->filtro);
        }

        if ($request->has('atributos')) {
            $modeloRepository->selectAtributos(
                $request->atributos 
            );
        }
        if ($request->has('buscar')) {
            return response()->json($modeloRepository->getResultado(), 200);
    
            }
            return response()->json($modeloRepository->getResultadoPaginado(5), 200);
    
    }

    public function store(Request $request)
    {
        $request->validate(
            $this->modelo->rules(),
        );
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/modelos', 'public');

        $modelo = $this->modelo->create([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs
        ]);
        return response()->json($modelo, 201);
    }

    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id);
        if ($modelo === null) {
            return response()->json(
                ['erro' => 'Recurso pesquisado não existe'],
                404
            );
        }
        return response()->json($modelo, 200);
    }

    public function update(Request $request, Modelo $modelo)
    {
        if ($modelo === null) {
            return response()->json(
                ['erro' => 'Impossível realizar
                a atualização. O recurso solicitado
                não existe'],
                404
            );
        }

        if ($request->method() === 'PATCH') {
            $dinamicRules = array();
            foreach ($modelo->rules() as  $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dinamicRules[$input] = $rule;
                }
            }
            $request->validate($dinamicRules);
        } else {
            $request->validate($modelo->rules());
        }
        $modelo->fill($request->all());

        if ($request->file('imagem')) {
            Storage::disk('public')->delete($modelo->imagem);
            $imagem = $request->file('imagem');
            $imagem_urn = $imagem->store('imagens/modelos', 'public');
            $modelo->imagem = $imagem_urn;

        }

        $modelo->save();
        return response()->json($modelo, 200);
    }

    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);
        if ($modelo === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }
        Storage::disk('public')->delete($modelo->imagem);
        $modelo->delete();
        return response()->json(['msg' => 'O modelo foi removida com sucesso!'], 200);
    }
}
