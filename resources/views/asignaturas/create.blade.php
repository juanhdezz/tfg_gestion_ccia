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
                    </div>

                    <div class="mb-4">
                        <label for="id_coordinador" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ID Coordinador:</label>
                        <input type="text" id="id_coordinador" name="id_coordinador" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
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
                        <label for="creditos_practica" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Créditos Práctica:</label>
                        <input type="number" id="creditos_practica" name="creditos_practica" required step="0.1" min="0" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                    </div>

                    <div class="mb-4">
                        <label for="ects_teoria" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ECTS Teoría:</label>
                        <input type="number" id="ects_teoria" name="ects_teoria" required step="0.1" min="0" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                    </div>

                    <div class="mb-4">
                        <label for="ects_practica" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ECTS Práctica:</label>
                        <input type="number" id="ects_practica" name="ects_practica" required step="0.1" min="0" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                    </div>

                    <div class="mb-4">
                        <label for="grupos_teoria" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Grupos de Teoría:</label>
                        <div id="grupos_teoria_container">
                            <!-- Aquí se añadirán dinámicamente los grupos de teoría -->
                        </div>
                        <button type="button" onclick="agregarGrupoTeoria()" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">
                            Añadir Grupo de Teoría
                        </button>
                    </div>

                    {{-- <div class="mb-4">
                        <label for="grupos_practica" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Grupos Práctica:</label>
                        <input type="number" id="grupos_practica" name="grupos_practica" required min="1" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                    </div> --}}

                    <div class="mb-4">
                        <label for="tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Asignatura:</label>
                        <select id="tipo" name="tipo" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            <option value="Asignatura">Asignatura</option>
                            <option value="Proyecto Fin de Carrera">Proyecto Fin de Carrera</option>
                        </select>
                    </div>


                    <div class="mb-4">
                        <label for="cuatrimestre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cuatrimestre:</label>
                        <select id="cuatrimestre" name="cuatrimestre" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            <option value="1">Primer Cuatrimestre</option>
                            <option value="2">Segundo Cuatrimestre</option>
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

                    <div class="mb-4">
                        <label for="fraccionable" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fraccionable:</label>
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" id="fraccionable" name="fraccionable" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">Sí</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">Crear Asignatura</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let grupoTeoriaIndex = 0;

        function agregarGrupoTeoria() {
            grupoTeoriaIndex++;
            const container = document.getElementById('grupos_teoria_container');

            // Crear un nuevo grupo de teoría con campos para los grupos de práctica asociados
            const grupoTeoriaHTML = `
                <div class="grupo-teoria mb-4" id="grupo_teoria_${grupoTeoriaIndex}">
                    <div class="mb-2">
                        <label for="grupo_teoria_${grupoTeoriaIndex}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Grupo Teoría ${grupoTeoriaIndex}:</label>
                        <input type="text" name="grupo_teoria[${grupoTeoriaIndex}][nombre]" id="grupo_teoria_${grupoTeoriaIndex}" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                    </div>

                    <div class="mb-2">
                        <label for="grupos_practica_${grupoTeoriaIndex}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Grupos de Práctica:</label>
                        <div id="grupos_practica_${grupoTeoriaIndex}_container">
                            <!-- Aquí se añadirán dinámicamente los grupos de práctica para este grupo de teoría -->
                        </div>
                        <button type="button" onclick="agregarGrupoPractica(${grupoTeoriaIndex})" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">
                            Añadir Grupo de Práctica
                        </button>
                    </div>

                    <button type="button" onclick="eliminarGrupoTeoria(${grupoTeoriaIndex})" class="text-red-500 hover:text-red-700 mt-2">Eliminar Grupo de Teoría</button>
                </div>
            `;

            // Añadir el grupo de teoría al contenedor
            container.insertAdjacentHTML('beforeend', grupoTeoriaHTML);
        }

        function agregarGrupoPractica(grupoTeoriaIndex) {
            const practicaContainer = document.getElementById(`grupos_practica_${grupoTeoriaIndex}_container`);

            const grupoPracticaHTML = `
                <div class="grupo-practica mb-2" id="grupo_practica_${grupoTeoriaIndex}_${Date.now()}">
                    <input type="text" name="grupo_teoria[${grupoTeoriaIndex}][practicas][]" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white" placeholder="Nombre del Grupo de Práctica">
                    <button type="button" onclick="eliminarGrupoPractica(${grupoTeoriaIndex}, ${Date.now()})" class="text-red-500 hover:text-red-700 mt-2">Eliminar Grupo de Práctica</button>
                </div>
            `;
            
            practicaContainer.insertAdjacentHTML('beforeend', grupoPracticaHTML);
        }

        function eliminarGrupoTeoria(index) {
            const grupoTeoria = document.getElementById(`grupo_teoria_${index}`);
            grupoTeoria.remove();
        }

        function eliminarGrupoPractica(grupoTeoriaIndex, practicaId) {
            const grupoPractica = document.getElementById(`grupo_practica_${grupoTeoriaIndex}_${practicaId}`);
            grupoPractica.remove();
        }
    </script>
</x-app-layout>