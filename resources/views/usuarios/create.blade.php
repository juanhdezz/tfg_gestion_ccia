<x-app-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full">
            <h1 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Crear Asignatura</h1>

            @if (session('error'))
                <div class="bg-red-500 text-white p-4 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 p-4 rounded mb-4">
                    <div class="font-bold">Por favor corrige los siguientes errores:</div>
                    <ul class="list-disc ml-5 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('asignaturas.store') }}" method="POST" id="createAsignaturaForm">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Primera columna -->
                    <div class="space-y-4">
                        <div class="mb-4">
                            <label for="id_asignatura" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ID asignatura:</label>
                            <input type="text" id="id_asignatura" name="id_asignatura" value="{{ old('id_asignatura') }}" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            @error('id_asignatura')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="nombre_asignatura" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre:</label>
                            <input type="text" id="nombre_asignatura" name="nombre_asignatura" value="{{ old('nombre_asignatura') }}" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            @error('nombre_asignatura')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="siglas_asignatura" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Siglas:</label>
                            <input type="text" id="siglas_asignatura" name="siglas_asignatura" value="{{ old('siglas_asignatura') }}" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            @error('siglas_asignatura')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="especialidad" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Especialidad:</label>
                            <input type="text" id="especialidad" name="especialidad" value="{{ old('especialidad') }}" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        </div>

                        <div class="mb-4">
                            <label for="id_coordinador" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ID Coordinador:</label>
                            <input type="text" id="id_coordinador" name="id_coordinador" value="{{ old('id_coordinador') }}" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            @error('id_coordinador')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="curso" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Curso:</label>
                            <input type="number" id="curso" name="curso" value="{{ old('curso', 1) }}" required min="1" max="4" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            @error('curso')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="creditos_teoria" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Créditos Teoría:</label>
                            <input type="number" id="creditos_teoria" name="creditos_teoria" value="{{ old('creditos_teoria', 0) }}" required step="0.1" min="0" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            @error('creditos_teoria')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="creditos_practicas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Créditos Prácticas:</label>
                            <input type="number" id="creditos_practicas" name="creditos_practicas" value="{{ old('creditos_practicas', 0) }}" required step="0.1" min="0" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            @error('creditos_practicas')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Segunda columna -->
                    <div class="space-y-4">
                        <div class="mb-4">
                            <label for="ects_teoria" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ECTS Teoría:</label>
                            <input type="number" id="ects_teoria" name="ects_teoria" value="{{ old('ects_teoria', 0) }}" required step="0.1" min="0" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            @error('ects_teoria')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="ects_practicas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ECTS Prácticas:</label>
                            <input type="number" id="ects_practicas" name="ects_practicas" value="{{ old('ects_practicas', 0) }}" required step="0.1" min="0" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            @error('ects_practicas')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="grupos_teoria" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Grupos de Teoría:</label>
                            <input type="number" id="grupos_teoria" name="grupos_teoria" value="{{ old('grupos_teoria', 1) }}" required min="1" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Número de grupos de teoría para esta asignatura</p>
                            @error('grupos_teoria')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="grupos_practicas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Grupos de Prácticas:</label>
                            <input type="number" id="grupos_practicas" name="grupos_practicas" value="{{ old('grupos_practicas', 1) }}" required min="0" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Total de grupos de prácticas que serán distribuidos automáticamente</p>
                            @error('grupos_practicas')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Asignatura:</label>
                            <select id="tipo" name="tipo" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                                <option value="Asignatura" {{ old('tipo') == 'Asignatura' ? 'selected' : '' }}>Asignatura</option>
                                <option value="Proyecto Fin de Carrera" {{ old('tipo') == 'Proyecto Fin de Carrera' ? 'selected' : '' }}>Proyecto Fin de Carrera</option>
                            </select>
                            @error('tipo')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="cuatrimestre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cuatrimestre:</label>
                            <select id="cuatrimestre" name="cuatrimestre" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                                <option value="Primero" {{ old('cuatrimestre') == 'Primero' ? 'selected' : '' }}>Primer Cuatrimestre</option>
                                <option value="Segundo" {{ old('cuatrimestre') == 'Segundo' ? 'selected' : '' }}>Segundo Cuatrimestre</option>
                                <option value="Anual" {{ old('cuatrimestre') == 'Anual' ? 'selected' : '' }}>Anual</option>
                            </select>
                            @error('cuatrimestre')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado:</label>
                            <select id="estado" name="estado" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                                <option value="Activa" {{ old('estado') == 'Activa' ? 'selected' : 'selected' }}>Activa</option>
                                <option value="A extinguir" {{ old('estado') == 'A extinguir' ? 'selected' : '' }}>A extinguir</option>
                                <option value="Extinta" {{ old('estado') == 'Extinta' ? 'selected' : '' }}>Extinta</option>
                            </select>
                            @error('estado')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-4 mt-4">
                    <label for="web_asignatura" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Web Asignatura:</label>
                    <input type="url" id="web_asignatura" name="web_asignatura" value="{{ old('web_asignatura') }}" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white" placeholder="https://...">
                </div>

                <div class="mb-4">
                    <label for="id_titulacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Titulación:</label>
                    <select id="id_titulacion" name="id_titulacion" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        <option value="">Seleccione una titulación</option>
                        @foreach($titulaciones as $titulacion)
                            <option value="{{ $titulacion->id_titulacion }}" {{ old('id_titulacion') == $titulacion->id_titulacion ? 'selected' : '' }}>{{ $titulacion->nombre_titulacion }}</option>
                        @endforeach
                    </select>
                    @error('id_titulacion')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4 flex items-center">
                    <input type="checkbox" id="fraccionable" name="fraccionable" value="1" {{ old('fraccionable') ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <label for="fraccionable" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Asignatura Fraccionable</label>
                </div>

                <div class="mt-6 flex space-x-3">
                    <button type="submit" id="submitBtn" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 transition">Crear Asignatura</button>
                    <a href="{{ route('asignaturas.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-700 transition">Cancelar</a>
                </div>

                <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-800 rounded-md">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">Información sobre la distribución de grupos</h3>
                    <p class="mt-1 text-xs text-blue-700 dark:text-blue-400">
                        Al guardar la asignatura, el sistema distribuirá automáticamente el número total de grupos de prácticas 
                        entre los grupos de teoría de la manera más equilibrada posible.
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('createAsignaturaForm').addEventListener('submit', function(event) {
            // Desactivar el botón para evitar múltiples envíos
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').innerText = 'Procesando...';
            
            // Validaciones adicionales del lado del cliente
            const idTitulacion = document.getElementById('id_titulacion').value;
            if (!idTitulacion) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error de validación',
                    text: 'Debe seleccionar una titulación'
                });
                document.getElementById('submitBtn').disabled = false;
                document.getElementById('submitBtn').innerText = 'Crear Asignatura';
                return false;
            }
            
            // Verificar que los campos numéricos son válidos
            const gruposTeoria = parseInt(document.getElementById('grupos_teoria').value);
            const gruposPracticas = parseInt(document.getElementById('grupos_practicas').value);
            
            if (isNaN(gruposTeoria) || gruposTeoria < 1) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error de validación',
                    text: 'Debe especificar al menos un grupo de teoría'
                });
                document.getElementById('submitBtn').disabled = false;
                document.getElementById('submitBtn').innerText = 'Crear Asignatura';
                return false;
            }
            
            if (isNaN(gruposPracticas) || gruposPracticas < 0) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error de validación',
                    text: 'El número de grupos de prácticas no puede ser negativo'
                });
                document.getElementById('submitBtn').disabled = false;
                document.getElementById('submitBtn').innerText = 'Crear Asignatura';
                return false;
            }
        });
    </script>
</x-app-layout>