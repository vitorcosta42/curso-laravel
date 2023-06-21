@auth
    <h1>Usuário autenticado</h1>
    <p>
        {{ID Auth::user()->id }}
    </p>
<p>
    {{ Auth::user()->name }}
</p>
<p>
    {{ Auth::user()->email }}
</p>
@endauth

@guest
Olá visitante, tudo bem ? 
    

@endguest