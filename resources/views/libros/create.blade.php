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
                        <label for="titulo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Título del libro <span class="text-red-600">*</span></label>
                        <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Introduzca el título completo del libro">
                    </div>

                    <div>
                        <label for="autor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Autor <span class="text-red-600">*</span></label>
                        <input type="text" name="autor" id="autor" value="{{ old('autor') }}" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Autor o autores separados por comas">
                    </div>

                    <div>
                        <label for="isbn" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ISBN <span class="text-red-600">*</span></label>
                        <input type="text" name="isbn" id="isbn" value="{{ old('isbn') }}" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Código ISBN (10 o 13 dígitos)">
                    </div>

                    <div>
                        <label for="editorial" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Editorial <span class="text-red-600">*</span></label>
                        <input type="text" name="editorial" id="editorial" value="{{ old('editorial') }}" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Nombre de la editorial">
                    </div>

                    <div>
                        <label for="precio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Precio unitario (€) <span class="text-red-600">*</span></label>
                        <input type="number" name="precio" id="precio" value="{{ old('precio') }}" step="0.01" min="0" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Precio por unidad">
                    </div>

                    <div>
                        <label for="num_ejemplares" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Número de ejemplares <span class="text-red-600">*</span></label>
                        <input type="number" name="num_ejemplares" id="num_ejemplares" value="{{ old('num_ejemplares', 1) }}" min="1" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div class="md:col-span-2">
                        <label for="enlace" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Enlace de referencia</label>
                        <input type="url" name="enlace" id="enlace" value="{{ old('enlace') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="URL de la página web donde se puede encontrar el libro (opcional)">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Puede incluir un enlace a la web de la editorial o librería donde se puede adquirir el libro.</p>
                    </div>
                </div>
            </div>

            <!-- Selección de tipo de solicitud -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Tipo de Solicitud</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Seleccione el tipo de solicitud <span class="text-red-600">*</span></label>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="flex items-center">
                                <input id="tipo_asignatura" name="tipo_solicitud" type="radio" value="asignatura" checked
                                    class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <label for="tipo_asignatura" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Asignatura
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input id="tipo_proyecto" name="tipo_solicitud" type="radio" value="proyecto" 
                                    class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <label for="tipo_proyecto" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Proyecto
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input id="tipo_investigacion" name="tipo_solicitud" type="radio" value="investigacion" 
                                    class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <label for="tipo_investigacion" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Grupo de Investigación
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input id="tipo_posgrado" name="tipo_solicitud" type="radio" value="posgrado" 
                                    class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <label for="tipo_posgrado" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Posgrado
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input id="tipo_otros" name="tipo_solicitud" type="radio" value="otros" 
                                    class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <label for="tipo_otros" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Otros
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Sección para Asignatura (visible por defecto) -->
                    <div id="seccion_asignatura" class="border-t pt-4 mt-4">
                        <div class="mb-4">
                            <label for="id_asignatura" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Seleccione la asignatura <span class="text-red-600">*</span></label>
                            <select name="id_asignatura" id="id_asignatura" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">-- Seleccione una asignatura --</option>
                                @foreach($asignaturas as $asignatura)
                                    <option value="{{ $asignatura->id_asignatura }}" {{ old('id_asignatura') == $asignatura->id_asignatura ? 'selected' : '' }}>
                                        {{ $asignatura->nombre_asignatura }} ({{ $asignatura->codigo_asignatura }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="curso_academico" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Curso académico <span class="text-red-600">*</span></label>
                            <select name="curso_academico" id="curso_academico" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="2023-2024" {{ old('curso_academico') == '2023-2024' ? 'selected' : '' }}>2023-2024</option>
                                <option value="2024-2025" {{ old('curso_academico') == '2024-2025' ? 'selected' : '' }}>2024-2025</option>
                            </select>
                        </div>
                    </div>

                    <!-- Sección para Proyecto (oculta por defecto) -->
                    <div id="seccion_proyecto" class="border-t pt-4 mt-4 hidden">
                        <div class="mb-4">
                            <label for="codigo_proyecto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Código del proyecto <span class="text-red-600">*</span></label>
                            <input type="text" name="codigo_proyecto" id="codigo_proyecto" value="{{ old('codigo_proyecto') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Código identificativo del proyecto">
                        </div>

                        <div class="mb-4">
                            <label for="nombre_proyecto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre del proyecto <span class="text-red-600">*</span></label>
                            <input type="text" name="nombre_proyecto" id="nombre_proyecto" value="{{ old('nombre_proyecto') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Nombre completo del proyecto">
                        </div>
                    </div>

                    <!-- Sección para Grupo de Investigación (oculta por defecto) -->
                    <div id="seccion_investigacion" class="border-t pt-4 mt-4 hidden">
                        <div class="mb-4">
                            <label for="codigo_grupo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Código del grupo de investigación <span class="text-red-600">*</span></label>
                            <input type="text" name="codigo_grupo" id="codigo_grupo" value="{{ old('codigo_grupo') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Código identificativo del grupo">
                        </div>

                        <div class="mb-4">
                            <label for="nombre_grupo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre del grupo de investigación <span class="text-red-600">*</span></label>
                            <input type="text" name="nombre_grupo" id="nombre_grupo" value="{{ old('nombre_grupo') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Nombre completo del grupo de investigación">
                        </div>
                    </div>

                    <!-- Sección para Posgrado (oculta por defecto) -->
                    <div id="seccion_posgrado" class="border-t pt-4 mt-4 hidden">
                        <div class="mb-4">
                            <label for="codigo_posgrado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Código del programa de posgrado <span class="text-red-600">*</span></label>
                            <input type="text" name="codigo_posgrado" id="codigo_posgrado" value="{{ old('codigo_posgrado') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Código identificativo del programa">
                        </div>

                        <div class="mb-4">
                            <label for="nombre_posgrado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre del programa de posgrado <span class="text-red-600">*</span></label>
                            <input type="text" name="nombre_posgrado" id="nombre_posgrado" value="{{ old('nombre_posgrado') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Nombre completo del programa de posgrado">
                        </div>
                    </div>

                    <!-- Sección para Otros (oculta por defecto) -->
                    <div id="seccion_otros" class="border-t pt-4 mt-4 hidden">
                        <div class="mb-4">
                            <label for="descripcion_otros" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción <span class="text-red-600">*</span></label>
                            <textarea name="descripcion_otros" id="descripcion_otros" rows="3"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Describa el motivo o fondo al que se cargará la compra del libro">{{ old('descripcion_otros') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Información Adicional</h2>
                
                <div class="mb-4">
                    <label for="justificacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Justificacion</label>
                    <textarea name="justificacion" id="justificacion" rows="3"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Comentarios adicionales o especificaciones sobre la solicitud">{{ old('justificacion') }}</textarea>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('libros.index') }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-400 dark:hover:bg-gray-600 transition duration-300">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition duration-300">
                    Enviar Solicitud
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Referencias a los radio buttons
            const tipoAsignatura = document.getElementById('tipo_asignatura');
            const tipoProyecto = document.getElementById('tipo_proyecto');
            const tipoInvestigacion = document.getElementById('tipo_investigacion');
            const tipoPosgrado = document.getElementById('tipo_posgrado');
            const tipoOtros = document.getElementById('tipo_otros');
            
            // Referencias a las secciones
            const seccionAsignatura = document.getElementById('seccion_asignatura');
            const seccionProyecto = document.getElementById('seccion_proyecto');
            const seccionInvestigacion = document.getElementById('seccion_investigacion');
            const seccionPosgrado = document.getElementById('seccion_posgrado');
            const seccionOtros = document.getElementById('seccion_otros');
            
            // Función para mostrar/ocultar secciones según el tipo seleccionado
            function actualizarSecciones() {
                seccionAsignatura.classList.add('hidden');
                seccionProyecto.classList.add('hidden');
                seccionInvestigacion.classList.add('hidden');
                seccionPosgrado.classList.add('hidden');
                seccionOtros.classList.add('hidden');
                
                if (tipoAsignatura.checked) {
                    seccionAsignatura.classList.remove('hidden');
                } else if (tipoProyecto.checked) {
                    seccionProyecto.classList.remove('hidden');
                } else if (tipoInvestigacion.checked) {
                    seccionInvestigacion.classList.remove('hidden');
                } else if (tipoPosgrado.checked) {
                    seccionPosgrado.classList.remove('hidden');
                } else if (tipoOtros.checked) {
                    seccionOtros.classList.remove('hidden');
                }
            }
            
            // Asignar eventos de cambio a los radio buttons
            tipoAsignatura.addEventListener('change', actualizarSecciones);
            tipoProyecto.addEventListener('change', actualizarSecciones);
            tipoInvestigacion.addEventListener('change', actualizarSecciones);
            tipoPosgrado.addEventListener('change', actualizarSecciones);
            tipoOtros.addEventListener('change', actualizarSecciones);
            
            // Inicializar con la selección actual
            actualizarSecciones();
            
            // Validación del formulario antes de enviar
            const form = document.querySelector('form');
            form.addEventListener('submit', function(event) {
                let valid = true;
                
                // Validar campos comunes
                const titulo = document.getElementById('titulo');
                const autor = document.getElementById('autor');
                const isbn = document.getElementById('isbn');
                const editorial = document.getElementById('editorial');
                const precio = document.getElementById('precio');
                
                if (!titulo.value.trim()) {
                    valid = false;
                    titulo.classList.add('border-red-500');
                } else {
                    titulo.classList.remove('border-red-500');
                }
                
                if (!autor.value.trim()) {
                    valid = false;
                    autor.classList.add('border-red-500');
                } else {
                    autor.classList.remove('border-red-500');
                }
                
                if (!isbn.value.trim()) {
                    valid = false;
                    isbn.classList.add('border-red-500');
                } else {
                    isbn.classList.remove('border-red-500');
                }
                
                if (!editorial.value.trim()) {
                    valid = false;
                    editorial.classList.add('border-red-500');
                } else {
                    editorial.classList.remove('border-red-500');
                }
                
                if (!precio.value.trim() || isNaN(precio.value) || parseFloat(precio.value) <= 0) {
                    valid = false;
                    precio.classList.add('border-red-500');
                } else {
                    precio.classList.remove('border-red-500');
                }
                
                // Validar campos específicos según el tipo de solicitud
                if (tipoAsignatura.checked) {
                    const asignatura = document.getElementById('id_asignatura');
                    if (!asignatura.value) {
                        valid = false;
                        asignatura.classList.add('border-red-500');
                    } else {
                        asignatura.classList.remove('border-red-500');
                    }
                } else if (tipoProyecto.checked) {
                    const codigoProyecto = document.getElementById('codigo_proyecto');
                    const nombreProyecto = document.getElementById('nombre_proyecto');
                    
                    if (!codigoProyecto.value.trim()) {
                        valid = false;
                        codigoProyecto.classList.add('border-red-500');
                    } else {
                        codigoProyecto.classList.remove('border-red-500');
                    }
                    
                    if (!nombreProyecto.value.trim()) {
                        valid = false;
                        nombreProyecto.classList.add('border-red-500');
                    } else {
                        nombreProyecto.classList.remove('border-red-500');
                    }
                } else if (tipoInvestigacion.checked) {
                    const codigoGrupo = document.getElementById('codigo_grupo');
                    const nombreGrupo = document.getElementById('nombre_grupo');
                    
                    if (!codigoGrupo.value.trim()) {
                        valid = false;
                        codigoGrupo.classList.add('border-red-500');
                    } else {
                        codigoGrupo.classList.remove('border-red-500');
                    }
                    
                    if (!nombreGrupo.value.trim()) {
                        valid = false;
                        nombreGrupo.classList.add('border-red-500');
                    } else {
                        nombreGrupo.classList.remove('border-red-500');
                    }
                } else if (tipoPosgrado.checked) {
                    const codigoPosgrado = document.getElementById('codigo_posgrado');
                    const nombrePosgrado = document.getElementById('nombre_posgrado');
                    
                    if (!codigoPosgrado.value.trim()) {
                        valid = false;
                        codigoPosgrado.classList.add('border-red-500');
                    } else {
                        codigoPosgrado.classList.remove('border-red-500');
                    }
                    
                    if (!nombrePosgrado.value.trim()) {
                        valid = false;
                        nombrePosgrado.classList.add('border-red-500');
                    } else {
                        nombrePosgrado.classList.remove('border-red-500');
                    }
                } else if (tipoOtros.checked) {
                    const descripcionOtros = document.getElementById('descripcion_otros');
                    
                    if (!descripcionOtros.value.trim()) {
                        valid = false;
                        descripcionOtros.classList.add('border-red-500');
                    } else {
                        descripcionOtros.classList.remove('border-red-500');
                    }
                }
                
                if (!valid) {
                    event.preventDefault();
                    alert('Por favor, complete todos los campos obligatorios correctamente.');
                }
            });
        });
    </script>
    @endpush
</x-app-layout>