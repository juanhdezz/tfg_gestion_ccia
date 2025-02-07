<!-- filepath: /c:/xampp/htdocs/laravel/tfg_gestion_ccia/resources/views/usuarios/create.blade.php -->
 <x-app-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full">
            <h1 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Crear Usuario</h1>
            @if (session('error'))
                <div class="bg-red-500 text-white p-2 rounded mb-4">{{ session('error') }}</div>
            @endif
            <form action="{{ route('usuarios.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="apellidos" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="nombre_abreviado" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre Abreviado:</label>
                    <input type="text" id="nombre_abreviado" name="nombre_abreviado" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="dni_pasaporte" class="block text-sm font-medium text-gray-700 dark:text-gray-300">DNI/Pasaporte:</label>
                    <input type="text" id="dni_pasaporte" name="dni_pasaporte" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="correo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo:</label>
                    <input type="email" id="correo" name="correo" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="foto" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Foto:</label>
                    <input type="text" id="foto" name="foto" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="telefono_despacho" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teléfono Despacho:</label>
                    <input type="text" id="telefono_despacho" name="telefono_despacho" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="ip_asociada" class="block text-sm font-medium text-gray-700 dark:text-gray-300">IP Asociada:</label>
                    <input type="text" id="ip_asociada" name="ip_asociada" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="toma_red" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Toma de Red:</label>
                    <input type="text" id="toma_red" name="toma_red" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="mantiene_numero" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mantiene Número:</label>
                    <input type="text" id="mantiene_numero" name="mantiene_numero" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="uid_fotocopy" class="block text-sm font-medium text-gray-700 dark:text-gray-300">UID Fotocopy:</label>
                    <input type="text" id="uid_fotocopy" name="uid_fotocopy" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="clave_fotocopy" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Clave Fotocopy:</label>
                    <input type="text" id="clave_fotocopy" name="clave_fotocopy" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="login" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Login:</label>
                    <input type="text" id="login" name="login" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="passwd" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contraseña:</label>
                    <input type="password" id="passwd" name="passwd" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="imparte_docencia" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Imparte Docencia:</label>
                    <select id="imparte_docencia" name="imparte_docencia" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="miembro_actual" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Miembro Actual:</label>
                    <select id="miembro_actual" name="miembro_actual" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="miembro_total" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Miembro Total:</label>
                    <select id="miembro_total" name="miembro_total" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="miembro_consejo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Miembro Consejo:</label>
                    <select id="miembro_consejo" name="miembro_consejo" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="clave_acceso" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Clave de Acceso:</label>
                    <input type="text" id="clave_acceso" name="clave_acceso" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">Crear Usuario</button>
            </form>
        </div>
    </div>
</x-app-layout> 



