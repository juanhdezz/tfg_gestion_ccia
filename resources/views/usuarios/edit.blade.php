<!-- filepath: /c:/xampp/htdocs/laravel/tfg_gestion_ccia/resources/views/usuarios/edit.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Editar Usuario</title>
</head>
<body>
    <h1>Editar Usuario</h1>
    @if (session('error'))
        <div>{{ session('error') }}</div>
    @endif
    <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="nombre_abreviado">Nombre Abreviado:</label>
        <input type="text" id="nombre_abreviado" name="nombre_abreviado" value="{{ $usuario->nombre_abreviado }}" required>
        <br>
        <label for="dni_pasaporte">DNI/Pasaporte:</label>
        <input type="text" id="dni_pasaporte" name="dni_pasaporte" value="{{ $usuario->dni_pasaporte }}" required>
        <br>
        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" value="{{ $usuario->correo }}" required>
        <br>
        <!-- Agrega más campos según sea necesario -->
        <button type="submit">Actualizar</button>
    </form>
    <a href="{{ route('usuarios.index') }}">Volver a la lista de usuarios</a>
</body>
</html>