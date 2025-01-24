<!-- filepath: /c:/xampp/htdocs/laravel/tfg_gestion_ccia/resources/views/usuarios/create.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Crear Usuario</title>
</head>
<body>
    <h1>Crear Usuario</h1>
    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf
        <label for="nombre_abreviado">Nombre Abreviado:</label>
        <input type="text" id="nombre_abreviado" name="nombre_abreviado" required>
        <br>
        <label for="dni_pasaporte">DNI/Pasaporte:</label>
        <input type="text" id="dni_pasaporte" name="dni_pasaporte" required>
        <br>
        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" required>
        <br>
        <!-- Agrega más campos según sea necesario -->
        <button type="submit">Crear</button>
    </form>
    <a href="{{ route('usuarios.index') }}">Volver a la lista de usuarios</a>
</body>
</html>