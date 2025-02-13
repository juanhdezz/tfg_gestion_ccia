<x-app-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full">
            <h1 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Detalles del Usuario: {{ $usuario->nombre }} {{ $usuario->apellidos }}</h1>
            
            @if (session('error'))
                <div class="bg-red-500 text-white p-2 rounded mb-4">{{ session('error') }}</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Nombre Abreviado -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Nombre Abreviado</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $usuario->nombre_abreviado }}</p>
                </div>

                <!-- DNI/Pasaporte -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">DNI/Pasaporte</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $usuario->dni_pasaporte }}</p>
                </div>

                <!-- Correo Electrónico -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Correo Electrónico</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $usuario->correo }}</p>
                </div>

                <!-- Foto de perfil -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md flex justify-center">
                    @if ($usuario->foto)
                        <img src="{{ $usuario->foto }}" alt="Foto de {{ $usuario->nombre }}" class="rounded-full w-24 h-24">
                    @else
                        <p class="text-gray-900 dark:text-white">Sin foto</p>
                    @endif
                </div>

                <!-- Teléfono Personal -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Teléfono</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $usuario->telefono ?? 'No disponible' }}</p>
                </div>

                <!-- Teléfono del Despacho -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Teléfono del Despacho</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $usuario->telefono_despacho ?? 'No disponible' }}</p>
                </div>

                <!-- Despacho -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Despacho</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">
                        {{ $usuario->despacho ? $usuario->despacho->nombre_despacho : 'No asignado' }}
                    </p>
                </div>

                <!-- IP Asociada -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">IP Asociada</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $usuario->ip_asociada ?? 'No disponible' }}</p>
                </div>

                <!-- Toma de Red -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Toma de Red</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $usuario->toma_red ?? 'No disponible' }}</p>
                </div>

                <!-- Tipo de Usuario -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Usuario</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $usuario->tipo_usuario }}</p>
                </div>

                <!-- Último Inicio de Sesión -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Último Inicio de Sesión</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">
                        {{ $usuario->user_last_login ? $usuario->user_last_login->format('d/m/Y H:i') : 'Nunca' }}
                    </p>
                </div>

                <!-- Imparte Docencia -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Imparte Docencia</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">
                        {{ $usuario->imparte_docencia ? 'Sí' : 'No' }}
                    </p>
                </div>

                <!-- Es miembro actual -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Miembro Actual</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">
                        {{ $usuario->miembro_actual ? 'Sí' : 'No' }}
                    </p>
                </div>

                <!-- Es miembro del consejo -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Miembro del Consejo</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">
                        {{ $usuario->miembro_consejo ? 'Sí' : 'No' }}
                    </p>
                </div>
            </div>

            <div class="mt-6 flex justify-between">
                <a href="{{ route('usuarios.index') }}" class="text-blue-600 hover:underline">Volver a la lista de usuarios</a>
                <a href="{{ route('usuarios.edit', $usuario->id_usuario) }}" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">Editar Usuario</a>
            </div>
        </div>
    </div>
</x-app-layout>
