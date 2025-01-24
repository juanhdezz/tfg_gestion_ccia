<!-- filepath: /c:/xampp/htdocs/laravel/tfg_gestion_ccia/resources/views/usuarios/show.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Detalle del Usuario</title>
</head>
<body>
    <h1>Detalle del Usuario</h1>
    @if (session('error'))
        <div>{{ session('error') }}</div>
    @endif
    <p>Nombre Abreviado: {{ $usuario->nombre_abreviado }}</p>
    <p>DNI/Pasaporte: {{ $usuario->dni_pasaporte }}</p>
    <p>Correo: {{ $usuario->correo }}</p>
    <!-- Agrega más campos según sea necesario -->
    <a href="{{ route('usuarios.index') }}">Volver a la lista de usuarios</a>
</body>
</html>