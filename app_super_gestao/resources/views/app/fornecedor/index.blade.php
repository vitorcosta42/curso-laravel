<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fornecedor</title>
</head>

<body>
    <h3>Fornecedor</h3>
    @php
        
    @endphp

    @isset($fornecedores)
        Fornecedor: {{ $fornecedores[0]['nome'] }}
        <br>
        Status: {{ $fornecedores[0]['status'] }}
        <br>
    @endisset

</body>

</html>
