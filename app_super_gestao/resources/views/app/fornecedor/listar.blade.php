@extends('app.layouts.basico')
@section('titulo', 'Fornecedor')

@section('conteudo')

    <div class="conteudo-pagina">
        <div class="titulo-pagina-2">
            <p>
                Fornecedor - Adicionar
            </p>
        </div>
        <div class="menu">
            <ul>
                <li>
                    <a href="{{ route('app.fornecedor.adicionar') }}">
                        Novo
                    </a>
                </li>
                <li>
                    <a href="{{ route('app.fornecedor') }}">Consulta</a>
                </li>
            </ul>
        </div>
        <div class="informacao-pagina">
            <div >
                <table border="1" style = 'margin: auto;' width="80%;  " >
                    <thead>
                        <tr>
                            <th>
                                Nome
                            </th>

                            <th>
                                Site
                            </th>

                            <th>
                                UF
                            </th>

                            <th>
                                E-mail
                            </th>
                            <th colspan="2">
                                Ações
                            </th>
                        </tr>
                    </thead>

                    @foreach ($fornecedores as $fornecedor)
                        <tr>
                            <td>{{ $fornecedor->nome }}</td>
                            <td>{{ $fornecedor->site }}</td>
                            <td>{{ $fornecedor->uf }}</td>
                            <td>{{ $fornecedor->email }}</td>
                            <td>Excluir</td>
                            <td> 
                                <a href="{{ route('app.fornecedor.editar',$fornecedor->id) }}">
                                    Editar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </table>

            </div>
        </div>
    </div>

@endsection
