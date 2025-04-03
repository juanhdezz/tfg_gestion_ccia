<x-app-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
            <!-- Encabezado con título y acciones -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <a href="{{ route('proyectos.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 mr-4">
                            Volver al listado
                        </a>
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detalles del Proyecto</h1>
                    </div>
                    <h2 class="text-xl text-gray-700 dark:text-gray-300 mt-2">{{ $proyecto->nombre_corto }} ({{ $proyecto->codigo }})</h2>
                </div>
                
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('proyectos.edit', $proyecto->id_proyecto) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded transition duration-300">
                        Editar
                    </a>
                    
                    <form action="{{ route('proyectos.cambiarEstado', $proyecto->id_proyecto) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="{{ $proyecto->activo ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white font-medium py-2 px-4 rounded transition duration-300">
                            {{ $proyecto->activo ? 'Desactivar' : 'Activar' }}
                        </button>
                    </form>
                    
                    <button onclick="confirmDelete('{{ $proyecto->id_proyecto }}', '{{ addslashes($proyecto->titulo) }}')" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded transition duration-300">
                        Eliminar
                    </button>
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
            
            <!-- Información básica -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-600">
                    Información del Proyecto
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Título</h4>
                            <p class="text-gray-800 dark:text-gray-200">{{ $proyecto->titulo }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Código</h4>
                            <p class="text-gray-800 dark:text-gray-200">{{ $proyecto->codigo }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Nombre Corto</h4>
                            <p class="text-gray-800 dark:text-gray-200">{{ $proyecto->nombre_corto }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Tipo de Financiación</h4>
                            <p class="text-gray-800 dark:text-gray-200">{{ $proyecto->financiacion ?? 'No especificado' }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Investigador Principal</h4>
                            <p class="text-gray-800 dark:text-gray-200">
                                @if($proyecto->responsable)
                                    {{ $proyecto->responsable->nombre }} {{ $proyecto->responsable->apellidos }}
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">Sin asignar</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Fecha de Inicio</h4>
                            <p class="text-gray-800 dark:text-gray-200">
                                {{ $proyecto->fecha_inicio ? $proyecto->fecha_inicio->format('d/m/Y') : 'No especificada' }}
                            </p>
                        </div>
                        
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Fecha de Fin</h4>
                            <p class="text-gray-800 dark:text-gray-200">
                                {{ $proyecto->fecha_fin ? $proyecto->fecha_fin->format('d/m/Y') : 'No especificada' }}
                            </p>
                        </div>
                        
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Estado</h4>
                            <p>
                                @if($proyecto->activo)
                                    <span class="bg-green-100 text-green-800 py-1 px-2 rounded-full text-xs font-medium">
                                        Activo
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-800 py-1 px-2 rounded-full text-xs font-medium">
                                        Inactivo
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                @if($proyecto->web)
                <div class="mt-2">
                    <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Sitio Web</h4>
                    <p class="text-gray-800 dark:text-gray-200">
                        <a href="{{ $proyecto->web }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            {{ $proyecto->web }}
                        </a>
                    </p>
                </div>
                @endif
                
                @if($proyecto->creditos_compensacion_proyecto)
                <div class="mt-4">
                    <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Créditos de Compensación</h4>
                    <p class="text-gray-800 dark:text-gray-200">{{ $proyecto->creditos_compensacion_proyecto }}</p>
                </div>
                @endif
                
                <!-- Duración calculada -->
                @if($proyecto->fecha_inicio && $proyecto->fecha_fin)
                <div class="mt-4">
                    <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Duración</h4>
                    <p class="text-gray-800 dark:text-gray-200">
                        {{ $proyecto->fecha_inicio->diffInMonths($proyecto->fecha_fin) }} meses
                        ({{ $proyecto->fecha_inicio->diffForHumans($proyecto->fecha_fin, ['parts' => 2]) }})
                    </p>
                </div>
                @endif
            </div>
            
            

    <!-- Modal de confirmación de eliminación -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Confirmar eliminación</h3>
            <p class="text-gray-700 dark:text-gray-300 mb-6">¿Estás seguro de que deseas eliminar el proyecto "<span id="deleteProjectTitle"></span>"? Esta acción no se puede deshacer.</p>
            
            <div class="flex justify-end space-x-3">
                <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md transition">
                    Cancelar
                </button>
                
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript para el modal de eliminación -->
    @push('scripts')
    <script>
        function confirmDelete(id, title) {
            document.getElementById('deleteProjectTitle').textContent = title;
            document.getElementById('deleteForm').action = `{{ route('proyectos.destroy', '') }}/${id}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
        
        // Cerrar modal al hacer clic fuera
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
    @endpush
</x-app-layout>