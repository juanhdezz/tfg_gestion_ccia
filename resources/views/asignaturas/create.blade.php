<x-app-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full">
            <h1 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Crear Asignatura</h1>

            @if (session('error'))
                <div class="bg-red-500 text-white p-2 rounded mb-4">{{ session('error') }}</div>
            @endif

            <form action="{{ route('asignaturas.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Primera columna -->
                    <div class="space-y-4">
                        <div class="mb-4">
                            <label for="id_asignatura" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ID asignatura:</label>
                            <input type="text" id="id_asignatura" name="id_asignatura" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        </div>

                        <div class="mb-4">
                            <label for="nombre_asignatura" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre:</label>
                            <input type="text" id="nombre_asignatura" name="nombre_asignatura" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        </div>

                        <div class="mb-4">
                            <label for="siglas_asignatura" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Siglas:</label>
                            <input type="text" id="siglas_asignatura" name="siglas_asignatura" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        </div>

                        <div class="mb-4">
                            <label for="especialidad" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Especialidad:</label>
                            <input type="text" id="especialidad" name="especialidad" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        </div>                        <div class="mb-4">
                            <label for="id_coordinador" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Coordinador:</label>
                            <select id="id_coordinador" name="id_coordinador" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                                <option value="">Seleccionar coordinador...</option>
                                @foreach ($usuarios as $usuario)
                                    <option value="{{ $usuario->id_usuario }}" {{ old('id_coordinador') == $usuario->id_usuario ? 'selected' : '' }}>
                                        {{ $usuario->apellidos }}, {{ $usuario->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="curso" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Curso:</label>
                            <input type="number" id="curso" name="curso" required min="1" max="4" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        </div>

                        <div class="mb-4">
                            <label for="creditos_teoria" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Créditos Teoría:</label>
                            <input type="number" id="creditos_teoria" name="creditos_teoria" required step="0.1" min="0" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        </div>

                        <div class="mb-4">
                            <label for="creditos_practicas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Créditos Prácticas:</label>
                            <input type="number" id="creditos_practicas" name="creditos_practicas" required step="0.1" min="0" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        </div>
                    </div>

                    <!-- Segunda columna -->
                    <div class="space-y-4">
                        <div class="mb-4">
                            <label for="ects_teoria" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ECTS Teoría:</label>
                            <input type="number" id="ects_teoria" name="ects_teoria" required step="0.1" min="0" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        </div>

                        <div class="mb-4">
                            <label for="ects_practicas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ECTS Prácticas:</label>
                            <input type="number" id="ects_practicas" name="ects_practicas" required step="0.1" min="0" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        </div>

                        <div class="mb-4">
                            <label for="grupos_teoria" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Grupos de Teoría:</label>
                            <input type="number" id="grupos_teoria" name="grupos_teoria" required min="1" value="1" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Número de grupos de teoría para esta asignatura</p>
                        </div>

                        <div class="mb-4">
                            <label for="grupos_practicas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Grupos de Prácticas:</label>
                            <input type="number" id="grupos_practicas" name="grupos_practicas" required min="0" value="1" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Total de grupos de prácticas que serán distribuidos automáticamente</p>
                        </div>

                        <div class="mb-4">
                            <label for="tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Asignatura:</label>
                            <select id="tipo" name="tipo" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                                <option value="Asignatura">Asignatura</option>
                                <option value="Proyecto Fin de Carrera">Proyecto Fin de Carrera</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="cuatrimestre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cuatrimestre:</label>
                            <select id="cuatrimestre" name="cuatrimestre" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                                <option value="Primero">Primer Cuatrimestre</option>
                                <option value="Segundo">Segundo Cuatrimestre</option>
                                <option value="Anual">Anual</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado:</label>
                            <select id="estado" name="estado" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                                <option value="Activa">Activa</option>
                                <option value="A extinguir">A extinguir</option>
                                <option value="Extinta">Extinta</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="id_titulacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Titulación:</label>
                            <select id="id_titulacion" name="id_titulacion" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                                <option value="">Seleccione una titulación</option>
                                @foreach($titulaciones as $titulacion)
                                    <option value="{{ $titulacion->id_titulacion }}">{{ $titulacion->nombre_titulacion }}</option>
                                @endforeach
                            </select>
                            @error('id_titulacion')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-4 mt-4">
                    <label for="web_asignatura" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Web Asignatura:</label>
                    <input type="url" id="web_asignatura" name="web_asignatura" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white" placeholder="https://...">
                </div>

                <div class="mb-4 flex items-center">
                    <input type="checkbox" id="fraccionable" name="fraccionable" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <label for="fraccionable" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Asignatura Fraccionable</label>
                </div>

                <div class="mt-6 flex space-x-3">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 transition">Crear Asignatura</button>
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
</x-app-layout>