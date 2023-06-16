@if (isset($pedido->id))
    <form action="{{ route('pedido.update', ['pedido' => $pedido->id]) }}" method="post">
        @csrf
        @method('PATCH')
    @else
        <form action="{{ route('pedido.store') }}" method="post">
            @csrf
@endif

<select name="cliente_id">
    <option>
        -- Selecione um Cliente --
    </option>
    @foreach ($clientes as $cliente)
        <option value="{{ $cliente->id }}"
            {{ ($pedido->cliente_id ?? old('cliente_id')) == $cliente->nome ? 'selected' : '' }}>
            {{ $cliente->nome }}
        </option>
    @endforeach
</select>
{{ $errors->has('cliente_id') ? $errors->first('cliente_id') : '' }}

<button type="submit" class="borda-preta">
    Cadastrar
</button>
</form>
