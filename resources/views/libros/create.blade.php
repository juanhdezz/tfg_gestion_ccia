<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-indigo-800 dark:text-indigo-300 border-b-2 border-indigo-500 pb-2">
            Nueva Solicitud de Libro
        </h1>

        <!-- Mensaje de alerta en caso de errores -->
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                <p class="font-bold">Por favor corrige los siguientes errores:</p>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('libros.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Información básica del libro -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Información del Libro</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="titulo"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Título del libro
                            <span class="text-red-600">*</span></label>
                        <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Introduzca el título completo del libro">
                    </div>

                    <div>
                        <label for="autor"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Autor <span
                                class="text-red-600">*</span></label>
                        <input type="text" name="autor" id="autor" value="{{ old('autor') }}" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Autor o autores separados por comas">
                    </div>

                    <div>
                        <label for="isbn"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ISBN <span
                                class="text-red-600">*</span></label>
                        <input type="text" name="isbn" id="isbn" value="{{ old('isbn') }}" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Código ISBN (10 o 13 dígitos)">
                    </div>

                    <div>
                        <label for="year"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Año de publicación </label>
                        <input type="number" name="year" id="year" value="{{ old('year') }}" 
                            min="1900" max="{{ date('Y') + 1 }}" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Año de publicación del libro">
                    </div>

                    <div>
                        <label for="editorial"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Editorial <span
                                class="text-red-600">*</span></label>
                        <input type="text" name="editorial" id="editorial" value="{{ old('editorial') }}" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Nombre de la editorial">
                    </div>

                    <div>
                        <label for="precio"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Precio unitario (€)
                            <span class="text-red-600">*</span></label>
                        <input type="number" name="precio" id="precio" value="{{ old('precio') }}" step="0.01"
                            min="0" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Precio por unidad">
                    </div>

                    <div>
                        <label for="num_ejemplares"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Número de ejemplares
                            <span class="text-red-600">*</span></label>
                        <input type="number" name="num_ejemplares" id="num_ejemplares"
                            value="{{ old('num_ejemplares', 1) }}" min="1" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div class="md:col-span-2">
                        <label for="enlace"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Enlace de
                            referencia</label>
                        <input type="url" name="enlace" id="enlace" value="{{ old('enlace') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="URL de la página web donde se puede encontrar el libro (opcional)">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Puede incluir un enlace a la web de la
                            editorial o librería donde se puede adquirir el libro.</p>
                    </div>
                </div>
            </div>

            <!-- Selección de tipo de solicitud -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Tipo de Solicitud</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Seleccione el
                            tipo de solicitud <span class="text-red-600">*</span></label>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="flex items-center">
                                <input id="tipo_asignatura" name="tipo_solicitud" type="radio" value="asignatura"
                                    checked class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <label for="tipo_asignatura"
                                    class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Asignatura
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input id="tipo_proyecto" name="tipo_solicitud" type="radio" value="proyecto"
                                    class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <label for="tipo_proyecto"
                                    class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Proyecto
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input id="tipo_grupo" name="tipo_solicitud" type="radio"
                                    value="grupo"
                                    class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <label for="tipo_grupo"
                                    class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Grupo de Investigación
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input id="tipo_posgrado" name="tipo_solicitud" type="radio" value="posgrado"
                                    class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <label for="tipo_posgrado"
                                    class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Posgrado
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input id="tipo_otros" name="tipo_solicitud" type="radio" value="otros"
                                    class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <label for="tipo_otros"
                                    class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Otros
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Sección para Asignatura (visible por defecto) -->
                    <div id="seccion_asignatura" class="border-t pt-4 mt-4">
                        <div class="mb-4">
                            <label for="id_asignatura"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Seleccione la
                                asignatura <span class="text-red-600">*</span></label>
                            <select name="id_asignatura" id="id_asignatura"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">-- Seleccione una asignatura --</option>
                                @foreach ($asignaturas as $asignatura)
                                    <option value="{{ $asignatura->id_asignatura }}"
                                        {{ old('id_asignatura') == $asignatura->id_asignatura ? 'selected' : '' }}>
                                        {{ $asignatura->nombre_asignatura }} ({{ $asignatura->id_asignatura }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="curso_academico"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Curso académico
                                <span class="text-red-600">*</span></label>
                            <select name="curso_academico" id="curso_academico"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="mysql"
                                    {{ old('curso_academico') == 'mysql' ? 'selected' : '' }}>2024-2025</option>
                                <option value="mysql_proximo"
                                    {{ old('curso_academico') == 'mysql_proximo' ? 'selected' : '' }}>2025-2026</option>
                            </select>
                        </div>
                    </div>

                    <!-- Sección para Proyecto (oculta por defecto) -->
                    <!-- Por ejemplo, en la sección de Proyecto -->
                    <div id="seccion_proyecto" class="border-t pt-4 mt-4 hidden">
    

                        <div class="mb-4">
                            <label for="id_proyecto"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Seleccione el
                                proyecto <span class="text-red-600">*</span></label>
                            <select name="id_proyecto" id="id_proyecto"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                data-required>
                                <option value="">-- Seleccione un proyecto --</option>
                                @foreach ($proyectos as $proyecto)
                                    <option value="{{ $proyecto->id_proyecto }}"
                                        {{ old('id_proyecto') == $proyecto->id_proyecto ? 'selected' : '' }}>
                                        {{ $proyecto->titulo }} ({{ $proyecto->codigo }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Ajustar de forma similar para las otras secciones -->
                    <!-- Sección para Grupo de Investigación (oculta por defecto) -->
<div id="seccion_grupo" class="border-t pt-4 mt-4 hidden">
    <div class="mb-4">
        <label for="id_grupo"
            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Seleccione el grupo de investigación
            <span class="text-red-600">*</span></label>
        <select name="id_grupo" id="id_grupo"
            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            data-required>
            <option value="">-- Seleccione un grupo de investigación --</option>
            @isset($grupos)
                @foreach ($grupos as $grupo)
                    <option value="{{ $grupo->id_grupo }}"
                        {{ old('id_grupo') == $grupo->id_grupo ? 'selected' : '' }}>
                        {{ $grupo->nombre_grupo }} ({{ $grupo->siglas_grupo }})
                    </option>
                @endforeach
            @endisset
        </select>
    </div>
    
    <div class="mb-4">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            La solicitud será asociada al grupo de investigación seleccionado y deberá ser aprobada por el responsable del mismo.
        </p>
    </div>
</div>

<!-- Sección para Posgrado (oculta por defecto) -->
<div id="seccion_posgrado" class="border-t pt-4 mt-4 hidden">
    <div class="mb-4">
        <label for="id_posgrado"
            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Seleccione el programa de posgrado
            <span class="text-red-600">*</span></label>
        <select name="id_posgrado" id="id_posgrado"
            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            data-required>
            <option value="">-- Seleccione un programa de posgrado --</option>
            @isset($posgrados)
                @foreach ($posgrados as $posgrado)
                    <option value="{{ $posgrado->id_posgrado }}"
                        {{ old('id_posgrado') == $posgrado->id_posgrado ? 'selected' : '' }}>
                        {{ $posgrado->nombre }} ({{ $posgrado->codigo }})
                    </option>
                @endforeach
            @endisset
        </select>
    </div>
    
    <div class="mb-4">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            La solicitud será asociada al programa de posgrado seleccionado y deberá ser aprobada por el coordinador del mismo.
        </p>
    </div>
</div>
                    <!-- Sección para Otros (oculta por defecto) -->
                    <div id="seccion_otros" class="border-t pt-4 mt-4 hidden">
                        <div class="mb-4">
                            <label for="descripcion_otros"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción
                                <span class="text-red-600">*</span></label>
                            <textarea name="descripcion_otros" id="descripcion_otros" rows="3"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                data-required
                                placeholder="Describa el motivo o fondo al que se cargará la compra del libro">{{ old('descripcion_otros') }}</textarea>
                        </div>
                        
                        
                    </div>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Información Adicional</h2>

                <div class="mb-4">
                    <label for="justificacion"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Justificacion</label>
                    <textarea name="justificacion" id="justificacion" rows="3"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Comentarios adicionales o especificaciones sobre la solicitud">{{ old('justificacion') }}</textarea>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('libros.index') }}"
                    class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-400 dark:hover:bg-gray-600 transition duration-300">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition duration-300">
                    Enviar Solicitud
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <!-- No necesitas cambiar la estructura principal del formulario, pero vamos a mejorar la validación -->

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Referencias a los radio buttons
                const tipoAsignatura = document.getElementById('tipo_asignatura');
                const tipoProyecto = document.getElementById('tipo_proyecto');
                const tipogrupo = document.getElementById('tipo_grupo');
                const tipoPosgrado = document.getElementById('tipo_posgrado');
                const tipoOtros = document.getElementById('tipo_otros');

                // Referencias a las secciones
                const seccionAsignatura = document.getElementById('seccion_asignatura');
                const seccionProyecto = document.getElementById('seccion_proyecto');
                const secciongrupo = document.getElementById('seccion_grupo');
                const seccionPosgrado = document.getElementById('seccion_posgrado');
                const seccionOtros = document.getElementById('seccion_otros');

                // Referencias a los campos requeridos de cada sección
                const camposAsignatura = seccionAsignatura.querySelectorAll('[required]');
                const camposProyecto = seccionProyecto.querySelectorAll('[data-required]');
                const camposgrupo = secciongrupo.querySelectorAll('[data-required]');
                const camposPosgrado = seccionPosgrado.querySelectorAll('[data-required]');
                const camposOtros = seccionOtros.querySelectorAll('[data-required]');

                // Función para mostrar/ocultar secciones según el tipo seleccionado
                function actualizarSecciones() {
                    // Ocultar todas las secciones
                    seccionAsignatura.classList.add('hidden');
                    seccionProyecto.classList.add('hidden');
                    secciongrupo.classList.add('hidden');
                    seccionPosgrado.classList.add('hidden');
                    seccionOtros.classList.add('hidden');

                    // Desactivar todos los campos required que están ocultos
                    toggleRequired(camposAsignatura, false);
                    toggleRequired(camposProyecto, false);
                    toggleRequired(camposgrupo, false);
                    toggleRequired(camposPosgrado, false);
                    toggleRequired(camposOtros, false);

                    // Mostrar la sección correspondiente y activar sus campos required
                    if (tipoAsignatura.checked) {
                        seccionAsignatura.classList.remove('hidden');
                        toggleRequired(camposAsignatura, true);
                    } else if (tipoProyecto.checked) {
                        seccionProyecto.classList.remove('hidden');
                        toggleRequired(camposProyecto, true);
                    } else if (tipogrupo.checked) {
                        secciongrupo.classList.remove('hidden');
                        toggleRequired(camposgrupo, true);
                    } else if (tipoPosgrado.checked) {
                        seccionPosgrado.classList.remove('hidden');
                        toggleRequired(camposPosgrado, true);
                    } else if (tipoOtros.checked) {
                        seccionOtros.classList.remove('hidden');
                        toggleRequired(camposOtros, true);
                    }
                }

                // Función auxiliar para activar/desactivar atributo required
                function toggleRequired(elementos, required) {
                    elementos.forEach(elemento => {
                        if (required) {
                            elemento.setAttribute('required', '');
                        } else {
                            elemento.removeAttribute('required');
                        }
                    });
                }

                // Asignar eventos de cambio a los radio buttons
                tipoAsignatura.addEventListener('change', actualizarSecciones);
                tipoProyecto.addEventListener('change', actualizarSecciones);
                tipogrupo.addEventListener('change', actualizarSecciones);
                tipoPosgrado.addEventListener('change', actualizarSecciones);
                tipoOtros.addEventListener('change', actualizarSecciones);

                // Inicializar con la selección actual
                actualizarSecciones();
            });
        </script>
    @endpush
</x-app-layout>
