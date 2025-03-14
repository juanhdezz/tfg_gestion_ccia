<!-- filepath: /c:/xampp/htdocs/laravel/tfg_gestion_ccia/resources/views/usuario_asignatura/create.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full">
            <h1 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Asignar Usuario a Asignatura</h1>

            @if (session('error'))
                <div class="bg-red-500 text-white p-2 rounded mb-4">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <strong>¡Atención!</strong> Hay errores en el formulario:<br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('usuario_asignatura.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Usuario -->
                    <div class="mb-4">
                        <label for="id_usuario" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Usuario:</label>
                        <select id="id_usuario" name="id_usuario" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            <option value="">Seleccione un usuario</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id_usuario }}" {{ ($preseleccion['id_usuario'] ?? '') == $usuario->id_usuario ? 'selected' : '' }}>
                                    {{ $usuario->apellidos }}, {{ $usuario->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Asignatura -->
                    <div class="mb-4">
                        <label for="id_asignatura" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Asignatura:</label>
                        <select id="id_asignatura" name="id_asignatura" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            <option value="">Seleccione una asignatura</option>
                            @foreach($asignaturas as $asignatura)
                                <option value="{{ $asignatura->id_asignatura }}" 
                                    data-grupos="{{ json_encode($asignatura->grupos->toArray()) }}"
                                    {{ ($preseleccion['id_asignatura'] ?? '') == $asignatura->id_asignatura ? 'selected' : '' }}>
                                    {{ $asignatura->nombre_asignatura }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tipo de grupo -->
                    <div class="mb-4">
                        <label for="tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de grupo:</label>
                        <select id="tipo" name="tipo" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            <option value="">Seleccione un tipo</option>
                            <option value="Teoría" {{ ($preseleccion['tipo'] ?? '') == 'Teoría' ? 'selected' : '' }}>Teoría</option>
                            <option value="Prácticas" {{ ($preseleccion['tipo'] ?? '') == 'Prácticas' ? 'selected' : '' }}>Prácticas</option>
                        </select>
                    </div>

                    <!-- Grupo (se rellena dinámicamente) -->
                    <div class="mb-4">
                        <label for="grupo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Número de grupo:</label>
                        <select id="grupo" name="grupo" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            <option value="">Seleccione primero una asignatura y tipo</option>
                        </select>
                    </div>

                    

                    <!-- Antigüedad -->
                    <div class="mb-4">
                        <label for="antiguedad" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Antigüedad:</label>
                        <input type="number" min="0" id="antiguedad" name="antiguedad" value="{{ old('antiguedad', 0) }}" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                    </div>

                    <!-- En primera fase -->
                    <div class="mb-4 col-span-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="en_primera_fase" class="form-checkbox h-5 w-5 text-blue-600" {{ old('en_primera_fase') ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">En primera fase</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex justify-between">
                    <a href="{{ route('usuario_asignatura.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-700">Cancelar</a>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">Asignar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const asignaturaSelect = document.getElementById('id_asignatura');
            const tipoSelect = document.getElementById('tipo');
            const grupoSelect = document.getElementById('grupo');
            
            // Función para actualizar los grupos disponibles
            function actualizarGrupos() {
                // Limpiar el selector de grupos
                grupoSelect.innerHTML = '<option value="">Seleccione un grupo</option>';
                
                const asignaturaId = asignaturaSelect.value;
                const tipo = tipoSelect.value;
                
                if (!asignaturaId || !tipo) return;
                
                // Obtener la opción seleccionada para acceder a los datos
                const selectedOption = asignaturaSelect.options[asignaturaSelect.selectedIndex];
                const grupos = JSON.parse(selectedOption.dataset.grupos);
                
                if (!grupos || !grupos.length) return;
                
                // Filtrar grupos únicos según el tipo seleccionado
                const gruposUnicos = new Set();
                grupos.forEach(grupo => {
                    if (tipo === 'Teoría' && grupo.grupo_teoria) {
                        gruposUnicos.add(grupo.grupo_teoria);
                    } else if (tipo === 'Prácticas' && grupo.grupo_practica) {
                        gruposUnicos.add(grupo.grupo_practica);
                    }
                });
                
                // Añadir opciones al selector de grupos
                Array.from(gruposUnicos).sort().forEach(grupoValor => {
                    const option = document.createElement('option');
                    option.value = grupoValor;
                    option.textContent = grupoValor;
                    
                    // Si hay una preselección, seleccionar automáticamente
                    @if(isset($preseleccion) && isset($preseleccion['grupo']))
                        if (grupoValor === '{{ $preseleccion['grupo'] }}') {
                            option.selected = true;
                        }
                    @endif
                    
                    grupoSelect.appendChild(option);
                });
            }
            
            // Eventos para actualizar los grupos cuando cambia la asignatura o el tipo
            asignaturaSelect.addEventListener('change', actualizarGrupos);
            tipoSelect.addEventListener('change', actualizarGrupos);
            
            // Inicializar grupos si hay valores preseleccionados
            if (asignaturaSelect.value && tipoSelect.value) {
                actualizarGrupos();
            }
        });
    </script>
</x-app-layout>