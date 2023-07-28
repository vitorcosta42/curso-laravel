<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use App\Repositories\LocacaoRepository;
use Illuminate\Http\Request;

class LocacaoController extends Controller
{
    private $locacao;
    /**
     * Display a listing of the resource.
     */
    public function __construct(Locacao $locacao)
    {
        $this->locacao = $locacao;
    }

    public function index(Request $request)
    {
        $locacaoRepository = new LocacaoRepository($this->locacao);

        if ($request->has('atributos_cliente')) {
            $atributos_cliente = 'cliente:id,' . $request->atributos_cliente;
            $locacaoRepository->selectAtributosRegistrosRelacionados(
                $atributos_cliente
            );
        } else {
            $locacaoRepository->selectAtributosRegistrosRelacionados('cliente');
        }
        if ($request->has('atributos_carro')) {
            $atributos_carro = 'carro:id,' . $request->atributos_carro;
            $locacaoRepository->selectAtributosRegistrosRelacionados(
                $atributos_carro
            );
        } else {
            $locacaoRepository->selectAtributosRegistrosRelacionados('carro');
        }


        if ($request->has('filtro')) {
            $locacaoRepository->filtro($request->filtro);
        }

        if ($request->has('atributos')) {
            $locacaoRepository->selectAtributos(
                $request->atributos
            );
        }
        if ($request->has('buscar')) {
            return response()->json($locacaoRepository->getResultado(), 200);
        }
        return response()->json($locacaoRepository->getResultadoPaginado(5), 200);
    }


    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate(
            $this->locacao->rules()
        );
        $locacao = $this->locacao->create([
            'cliente_id' => $request->cliente_id,
            'carro_id' => $request->carro_id,
            'data_inicio_periodo' => $request->data_inicio_periodo,
            'data_final_previsto_periodo' => $request->data_final_previsto_periodo,
            'data_final_realizado_periodo' => $request->data_final_realizado_periodo,
            'valor_diaria' => $request->valor_diaria,
            'km_inicial' => $request->km_inicial,
            'km_final' => $request->km_final,
        ]);
        return response()->json($locacao, 201);
    }

    public function show($id)
    {
        $locacao = $this->locacao->find($id);
        if ($locacao === null) {
            return response()->json(
                ['erro' => 'Recurso pesquisado não existe'],
                404
            );
        }
        return response()->json($locacao, 200);
    }

    public function update(Request $request, $id)
    {
        $locacao = $this->locacao->find($id);
        if ($locacao === null) {
            return response()->json(
                ['erro' => 'Impossível realizar
                a atualização. O recurso solicitado
                não existe'],
                404
            );
        }

        if ($request->method() === 'PATCH') {
            $dinamicRules = array();
            foreach ($locacao->rules() as  $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dinamicRules[$input] = $rule;
                }
            }
            $request->validate($dinamicRules);
        } else {
            $request->validate($locacao->rules());
        }

        $locacao->fill($request->all());
        $locacao->save();

        return response()->json($locacao, 200);
    }


    public function destroy($id)
    {
        $locacao = $this->locacao->find($id);

        if ($locacao === null) {
            return response()->json(
                ['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'],
                404
            );
        }

        $locacao->delete();
        return response()->json(['msg' => 'A locação foi removida com sucesso'], 200);
    }
}
