<x-app-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
            <!-- Encabezado con título y acciones -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <a href="{{ route('proyectos.show', $proyecto->id_proyecto) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 mr-4">
                            Volver al proyecto
                        </a>
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Editar Proyecto</h1>
                    </div>
                    <h2 class="text-xl text-gray-700 dark:text-gray-300 mt-2">{{ $proyecto->nombre_corto }} ({{ $proyecto->codigo }})</h2>
                </div>
            </div>
            
            <!-- Mensajes de alerta -->
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

            <!-- Errores de validación -->
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <h4 class="font-medium mb-2">Por favor corrige los siguientes errores:</h4>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- Formulario de edición -->
            <form action="{{ route('proyectos.update', $proyecto->id_proyecto) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-600">
                        Información del Proyecto
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <label for="titulo" class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Título</label>
                                <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $proyecto->titulo) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800"
                                       required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="codigo" class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Código</label>
                                <input type="text" name="codigo" id="codigo" value="{{ old('codigo', $proyecto->codigo) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800"
                                       required maxlength="32">
                            </div>
                            
                            <div class="mb-4">
                                <label for="nombre_corto" class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Nombre Corto</label>
                                <input type="text" name="nombre_corto" id="nombre_corto" value="{{ old('nombre_corto', $proyecto->nombre_corto) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800"
                                       required maxlength="128">
                            </div>
                            
                            <div class="mb-4">
                                <label for="financiacion" class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Tipo de Financiación</label>
                                <input type="text" name="financiacion" id="financiacion" value="{{ old('financiacion', $proyecto->financiacion) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800"
                                       maxlength="16">
                            </div>
                        </div>
                        
                        <div>
                            <div class="mb-4">
                                <label for="id_responsable" class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Investigador Principal</label>
                                <select name="id_responsable" id="id_responsable" 
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800"
                                        required>
                                    <option value="">Seleccionar responsable...</option>
                                    @foreach($responsables as $responsable)
                                        <option value="{{ $responsable->id_usuario }}" {{ old('id_responsable', $proyecto->id_responsable) == $responsable->id_usuario ? 'selected' : '' }}>
                                            {{ $responsable->apellidos }}, {{ $responsable->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="fecha_inicio" class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Fecha de Inicio</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" 
                                       value="{{ old('fecha_inicio', $proyecto->fecha_inicio ? $proyecto->fecha_inicio->format('Y-m-d') : '') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800"
                                       required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="fecha_fin" class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Fecha de Fin</label>
                                <input type="date" name="fecha_fin" id="fecha_fin" 
                                       value="{{ old('fecha_fin', $proyecto->fecha_fin ? $proyecto->fecha_fin->format('Y-m-d') : '') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800">
                            </div>
                            
                            <div class="mb-4">
                                <label for="activo" class="flex items-center">
                                    <input type="checkbox" name="activo" id="activo" value="1" 
                                           {{ old('activo', $proyecto->activo) ? 'checked' : '' }}
                                           class="h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                    <span class="ml-2 text-sm font-semibold text-gray-600 dark:text-gray-400">Proyecto Activo</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="web" class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Sitio Web</label>
                        <input type="url" name="web" id="web" value="{{ old('web', $proyecto->web) }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800"
                               maxlength="256" placeholder="https://ejemplo.com">
                    </div>
                    
                    <div class="mt-4">
                        <label for="creditos_compensacion_proyecto" class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Créditos de Compensación</label>
                        <input type="number" name="creditos_compensacion_proyecto" id="creditos_compensacion_proyecto" 
                               value="{{ old('creditos_compensacion_proyecto', $proyecto->creditos_compensacion_proyecto) }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800"
                               step="0.1" min="0" max="100">
                    </div>
                </div>
                
                <!-- Botones de acción -->
                <div class="flex justify-end space-x-3 mt-6">
                    <a href="{{ route('proyectos.show', $proyecto->id_proyecto) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded transition duration-300">
                        Cancelar
                    </a>
                    
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition duration-300">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>