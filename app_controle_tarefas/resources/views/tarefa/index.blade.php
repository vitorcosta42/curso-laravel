@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                Tarefas
                            </div>
                            <div class="col-6">
                                <div class="float-end">
                                    <a href="{{ route('tarefa.create') }}" class="mr-3">Novo</a>
                                    <a href="{{ route('tarefa.exportacao',['extensao'=>'xlsx']) }}">XLSX</a>
                                    <a href="{{ route('tarefa.exportacao',['extensao'=>'csv'])}}">CSV</a>
                                    <a href="{{ route('tarefa.exportacao',['extensao'=>'pdf'])}}">PDF</a>
                                    <a href="{{ route('tarefa.exportar')}}" target="_blank">PDF v2</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table text-center">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Tarefa</th>
                                    <th scope="col">Data limite conclusão</th>
                                    <th colspan="2">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tarefas as $key => $t)
                                    <tr>
                                        <th scope="row">
                                            {{ $t['id'] }}
                                        </th>
                                        <td>{{ $t['tarefa'] }}</td>
                                        <td>{{ date('d/m/Y', strtotime($t['data_limite_conclusao'])) }}</td>
                                        <td>
                                            <a href="{{ route('tarefa.edit', $t['id']) }}">Editar</a>
                                        </td>
                                        <td>
                                            <form id="form_{{ $t['id'] }}" method="post"
                                                action="{{ route('tarefa.destroy', ['tarefa' => $t['id']]) }}">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#"
                                                    onclick="document.getElementById('form_{{ $t['id'] }}').submit()">Excluir</a>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination justify-content-center ">
                                <li class="page-item"><a class="page-link"
                                        href="{{ $tarefas->previousPageUrl() }}">Voltar</a></li>
                                @for ($i = 1; $i <= $tarefas->lastPage(); $i++)
                                    <li class="page-item {{ $tarefas->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $tarefas->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                <li class="page-item"><a class="page-link" href="{{ $tarefas->nextPageUrl() }}">Avançar</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    @endsection
