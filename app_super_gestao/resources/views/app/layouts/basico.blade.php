<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Super Gestão - @yield('titulo')</title>
    <link rel="stylesheet" href="{{ asset('css/estilo_basico.css') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>

<body>
@include('app.layouts._partials.topo')
    @yield('conteudo')
</body>

</html>
