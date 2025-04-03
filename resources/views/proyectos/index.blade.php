<x-app-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Proyectos de Investigación</h1>
                
                <a href="{{ route('proyectos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition duration-300">
                    Nuevo Proyecto
                </a>
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

            <!-- Filtros -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md mb-4">
                <form action="{{ route('proyectos.index') }}" method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-grow min-w-[200px]">
                        <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por título, código..." 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800">
                    </div>
                    
                    <div class="min-w-[150px]">
                        <select name="estado" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800">
                            <option value="">Estado</option>
                            <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                    
                    <div class="min-w-[150px]">
                        <select name="financiacion" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800">
                            <option value="">Financiación</option>
                            @foreach($tiposFinanciacion as $tipo)
                                <option value="{{ $tipo }}" {{ request('financiacion') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="min-w-[120px]">
                        <select name="order_by" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800">
                            <option value="fecha_inicio" {{ request('order_by') == 'fecha_inicio' ? 'selected' : '' }}>Fecha Inicio</option>
                            <option value="titulo" {{ request('order_by') == 'titulo' ? 'selected' : '' }}>Título</option>
                            <option value="codigo" {{ request('order_by') == 'codigo' ? 'selected' : '' }}>Código</option>
                        </select>
                    </div>
                    
                    <div class="min-w-[120px]">
                        <select name="order_direction" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800">
                            <option value="desc" {{ request('order_direction') == 'desc' ? 'selected' : '' }}>Descendente</option>
                            <option value="asc" {{ request('order_direction') == 'asc' ? 'selected' : '' }}>Ascendente</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition duration-300">
                            Filtrar
                        </button>
                        
                        <a href="{{ route('proyectos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded transition duration-300">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tabla de Proyectos -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Código
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Título
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Responsable
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Fechas
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
                        @forelse($proyectos as $proyecto)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-200">
                                    {{ $proyecto->codigo }}
                                </td>
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-200">
                                    <a href="{{ route('proyectos.show', $proyecto->id_proyecto) }}" class="hover:text-blue-600 dark:hover:text-blue-400 font-medium">
                                        {{ Str::limit($proyecto->titulo, 50) }}
                                    </a>
                                    @if($proyecto->nombre_corto)
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $proyecto->nombre_corto }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-200">
                                    @if($proyecto->responsable)
                                        {{ $proyecto->responsable->nombre }} {{ $proyecto->responsable->apellidos }}
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">Sin asignar</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-200">
                                    <div>
                                        <span class="font-medium">Inicio:</span> 
                                        {{ $proyecto->fecha_inicio ? $proyecto->fecha_inicio->format('d/m/Y') : 'N/A' }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Fin:</span> 
                                        {{ $proyecto->fecha_fin ? $proyecto->fecha_fin->format('d/m/Y') : 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($proyecto->activo)
                                        <span class="bg-green-100 text-green-800 py-1 px-2 rounded-full text-xs font-medium">
                                            Activo
                                        </span>
                                    @else
                                        <span class="bg-red-100 text-red-800 py-1 px-2 rounded-full text-xs font-medium">
                                            Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        <a href="{{ route('proyectos.show', $proyecto->id_proyecto) }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-center py-1 px-2 rounded text-xs" title="Ver detalles">
                                            <span>Ver</span>
                                        </a>
                                        
                                        <a href="{{ route('proyectos.edit', $proyecto->id_proyecto) }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-center py-1 px-2 rounded text-xs" title="Editar proyecto">
                                            <span>Editar</span>
                                        </a>
                                        
                                        <button onclick="confirmDelete('{{ $proyecto->id_proyecto }}', '{{ addslashes($proyecto->titulo) }}')" class="inline-block bg-red-600 hover:bg-red-700 text-white text-center py-1 px-2 rounded text-xs" title="Eliminar proyecto">
                                            <span>Eliminar</span>
                                        </button>
                                        
                                        <form action="{{ route('proyectos.cambiarEstado', $proyecto->id_proyecto) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="{{ $proyecto->activo ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }} py-1 px-2 rounded text-xs font-medium" title="{{ $proyecto->activo ? 'Desactivar proyecto' : 'Activar proyecto' }}">
                                                {{ $proyecto->activo ? 'Desactivar' : 'Activar' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No se encontraron proyectos.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-4">
                {{ $proyectos->links() }}
            </div>
        </div>
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