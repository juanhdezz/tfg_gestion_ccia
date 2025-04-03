{{-- filepath: c:\xampp\htdocs\laravel\tfg_gestion_ccia\resources\views\libros\solicitar.blade.php --}}
<x-app-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
            <div class="flex items-center mb-6">
                <a href="{{ route('libros.show', $libro->id_libro) }}" class="flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 mr-4">
                    <i class="fas fa-arrow-left mr-2"></i> Volver al detalle
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Solicitar Libro</h1>
            </div>

            {{-- Mensajes de alerta --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            {{-- Información del libro --}}
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md mb-6">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/4 mb-4 md:mb-0">
                        @if($libro->portada)
                            <img src="{{ Storage::url($libro->portada) }}" alt="{{ $libro->titulo }}" class="w-full h-auto rounded-md shadow-md">
                        @else
                            <div class="w-full h-48 bg-gray-200 dark:bg-gray-600 rounded-md flex items-center justify-center">
                                <i class="fas fa-book text-4xl text-gray-400 dark:text-gray-500"></i>
                            </div>
                        @endif
                    </div>
                    <div class="md:w-3/4 md:pl-6">
                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-2">{{ $libro->titulo }}</h2>
                        <p class="text-gray-700 dark:text-gray-300 mb-1"><span class="font-semibold">Autor:</span> {{ $libro->autor }}</p>
                        @if($libro->isbn)
                            <p class="text-gray-700 dark:text-gray-300 mb-1"><span class="font-semibold">ISBN:</span> {{ $libro->isbn }}</p>
                        @endif
                        <p class="text-gray-700 dark:text-gray-300 mb-1"><span class="font-semibold">Año:</span> {{ $libro->year }}</p>
                        @if($libro->editorial)
                            <p class="text-gray-700 dark:text-gray-300 mb-1"><span class="font-semibold">Editorial:</span> {{ $libro->editorial }}</p>
                        @endif
                        @if($libro->edicion)
                            <p class="text-gray-700 dark:text-gray-300 mb-1"><span class="font-semibold">Edición:</span> {{ $libro->edicion }}</p>
                        @endif
                        @if($libro->num_paginas)
                            <p class="text-gray-700 dark:text-gray-300 mb-1"><span class="font-semibold">Páginas:</span> {{ $libro->num_paginas }}</p>
                        @endif
                        @if($libro->website)
                            <p class="text-gray-700 dark:text-gray-300 mb-1">
                                <span class="font-semibold">Web:</span> 
                                <a href="{{ $libro->website }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    {{ $libro->website }}
                                </a>
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Formulario de solicitud --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Formulario de Solicitud</h3>
                
                {{-- Instrucciones --}}
                <div class="bg-blue-50 dark:bg-blue-900/30 p-4 rounded-md mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-blue-800 dark:text-blue-300">Información sobre el proceso de solicitud:</h4>
                            <div class="mt-2 text-sm text-blue-700 dark:text-blue-400">
                                <p class="mb-1">1. Al enviar esta solicitud, el libro entrará en estado "Pendiente de aceptación".</p>
                                <p class="mb-1">2. Dependiendo del cargo que elijas, tu solicitud será autorizada por diferentes personas:</p>
                                <ul class="list-disc pl-5 mb-1">
                                    <li>Asignatura: La dirección del departamento</li>
                                    <li>Proyecto: El responsable del proyecto (IP)</li>
                                    <li>Grupo: El director del grupo de investigación</li>
                                    <li>Posgrado: El coordinador del máster</li>
                                    <li>Otros conceptos: La dirección del departamento</li>
                                </ul>
                                <p>3. Recibirás notificaciones por correo en cada cambio de estado de tu solicitud.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('solicitudes.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="id_libro" value="{{ $libro->id_libro }}">
                    
                    {{-- Tipo de solicitud --}}
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="tipo_solicitud" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Tipo de solicitud <span class="text-red-600">*</span>
                            </label>
                            <select id="tipo_solicitud" name="tipo_solicitud" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800"
                                onchange="mostrarCamposEspecificos()">
                                <option value="">Selecciona el tipo de solicitud</option>
                                @if(count($asignaturas) > 0)
                                    <option value="asignatura" {{ old('tipo_solicitud') == 'asignatura' ? 'selected' : '' }}>Asignatura</option>
                                @endif
                                <option value="proyecto" {{ old('tipo_solicitud') == 'proyecto' ? 'selected' : '' }}>Proyecto</option>
                                @if(count($grupos) > 0)
                                    <option value="grupo" {{ old('tipo_solicitud') == 'grupo' ? 'selected' : '' }}>Grupo de Investigación</option>
                                @endif
                                <option value="posgrado" {{ old('tipo_solicitud') == 'posgrado' ? 'selected' : '' }}>Posgrado (Máster)</option>
                                <option value="otro" {{ old('tipo_solicitud') == 'otro' ? 'selected' : '' }}>Otros conceptos</option>
                            </select>
                            @error('tipo_solicitud')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Campos específicos por tipo --}}
                    <div id="campos_asignatura" class="hidden space-y-6">
                        <div>
                            <label for="id_asignatura" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Asignatura <span class="text-red-600">*</span>
                            </label>
                            <select id="id_asignatura" name="id_asignatura" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800">
                                <option value="">Selecciona una asignatura</option>
                                @foreach($asignaturas as $asignatura)
                                    <option value="{{ $asignatura->id_asignatura }}" {{ old('id_asignatura') == $asignatura->id_asignatura ? 'selected' : '' }}>
                                        {{ $asignatura->nombre_asignatura }} ({{ $asignatura->titulacion }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_asignatura')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                La solicitud será autorizada por la dirección del departamento.
                            </p>
                        </div>
                    </div>

                    <div id="campos_proyecto" class="hidden space-y-6">
                        <div>
                            <label for="id_proyecto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Proyecto <span class="text-red-600">*</span>
                            </label>
                            <select id="id_proyecto" name="id_proyecto" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800">
                                <option value="">Selecciona un proyecto</option>
                                @foreach($proyectos as $proyecto)
                                    <option value="{{ $proyecto->id_proyecto }}" {{ old('id_proyecto') == $proyecto->id_proyecto ? 'selected' : '' }}>
                                        {{ $proyecto->nombre_proyecto }} (IP: {{ $proyecto->ip_nombre }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_proyecto')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                La solicitud será autorizada por el IP del proyecto.
                            </p>
                        </div>
                    </div>

                    <div id="campos_grupo" class="hidden space-y-6">
                        <div>
                            <label for="id_grupo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Grupo de Investigación <span class="text-red-600">*</span>
                            </label>
                            <select id="id_grupo" name="id_grupo" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800">
                                <option value="">Selecciona un grupo</option>
                                @foreach($grupos as $grupo)
                                    <option value="{{ $grupo->id_grupo }}" {{ old('id_grupo') == $grupo->id_grupo ? 'selected' : '' }}>
                                        {{ $grupo->nombre_grupo }} ({{ $grupo->codigo }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_grupo')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                La solicitud será autorizada por el director del grupo de investigación.
                            </p>
                        </div>
                    </div>

                    <div id="campos_posgrado" class="hidden space-y-6">
                        <div>
                            <label for="id_posgrado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Posgrado (Máster) <span class="text-red-600">*</span>
                            </label>
                            <select id="id_posgrado" name="id_posgrado" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800">
                                <option value="">Selecciona un posgrado</option>
                                @foreach($posgrados as $posgrado)
                                    <option value="{{ $posgrado->id_posgrado }}" {{ old('id_posgrado') == $posgrado->id_posgrado ? 'selected' : '' }}>
                                        {{ $posgrado->nombre_posgrado }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_posgrado')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                La solicitud será autorizada por el coordinador del posgrado.
                            </p>
                        </div>
                    </div>

                    <div id="campos_otro" class="hidden space-y-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                La solicitud para otros conceptos será autorizada por la dirección del departamento.
                            </p>
                        </div>
                    </div>

                    {{-- Campos comunes --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="precio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Precio estimado (€)
                            </label>
                            <input type="text" id="precio" name="precio" value="{{ old('precio') }}" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800"
                                placeholder="Ej: 45.99">
                            @error('precio')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="num_ejemplares" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Número de ejemplares <span class="text-red-600">*</span>
                            </label>
                            <input type="number" id="num_ejemplares" name="num_ejemplares" value="{{ old('num_ejemplares', 1) }}" min="1" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800">
                            @error('num_ejemplares')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="justificacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Justificación <span class="text-red-600">*</span>
                        </label>
                        <textarea id="justificacion" name="justificacion" rows="4" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800"
                            placeholder="Explica por qué es necesario este libro">{{ old('justificacion') }}</textarea>
                        @error('justificacion')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="observaciones" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Observaciones adicionales
                        </label>
                        <textarea id="observaciones" name="observaciones" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800"
                            placeholder="Información adicional sobre tu solicitud">{{ old('observaciones') }}</textarea>
                        @error('observaciones')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('libros.show', $libro->id_libro) }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
                            Cancelar
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            Enviar Solicitud
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Función para mostrar u ocultar campos según el tipo de solicitud
        function mostrarCamposEspecificos() {
            // Ocultar todos los campos específicos
            document.getElementById('campos_asignatura').classList.add('hidden');
            document.getElementById('campos_proyecto').classList.add('hidden');
            document.getElementById('campos_grupo').classList.add('hidden');
            document.getElementById('campos_posgrado').classList.add('hidden');
            document.getElementById('campos_otro').classList.add('hidden');
            
            // Desactivar validación en todos los campos específicos
            document.getElementById('id_asignatura').required = false;
            document.getElementById('id_proyecto').required = false;
            document.getElementById('id_grupo').required = false;
            document.getElementById('id_posgrado').required = false;
            
            // Obtener el tipo de solicitud seleccionado
            const tipoSolicitud = document.getElementById('tipo_solicitud').value;
            
            // Mostrar los campos correspondientes al tipo seleccionado
            if (tipoSolicitud) {
                const camposDiv = document.getElementById(`campos_${tipoSolicitud}`);
                if (camposDiv) {
                    camposDiv.classList.remove('hidden');
                    
                    // Activar validación en el campo específico requerido
                    if (tipoSolicitud === 'asignatura') {
                        document.getElementById('id_asignatura').required = true;
                    } else if (tipoSolicitud === 'proyecto') {
                        document.getElementById('id_proyecto').required = true;
                    } else if (tipoSolicitud === 'grupo') {
                        document.getElementById('id_grupo').required = true;
                    } else if (tipoSolicitud === 'posgrado') {
                        document.getElementById('id_posgrado').required = true;
                    }
                }
            }
        }
        
        // Ejecutar al cargar la página para mantener los campos seleccionados si hay errores de validación
        document.addEventListener('DOMContentLoaded', mostrarCamposEspecificos);
    </script>
    @endpush
</x-app-layout>