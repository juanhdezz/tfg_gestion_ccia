<!-- filepath: /c:/xampp/htdocs/laravel/tfg_gestion_ccia/resources/views/usuarios/index.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Usuarios</title>
</head>
<body>
    <h1>Lista de Usuarios</h1>
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div>{{ session('error') }}</div>
    @endif
    <ul>
        @foreach ($usuarios as $usuario)
            <li>{{ $usuario->nombre_abreviado }} - <a href="{{ route('usuarios.show', ['usuario' => $usuario->id_usuario]) }}">Ver</a></li>
        @endforeach
    </ul>
    <a href="{{ route('usuarios.create') }}">Crear nuevo usuario</a>
</body>
</html>