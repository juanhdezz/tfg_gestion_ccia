<!-- filepath: c:\xampp\htdocs\laravel\tfg_gestion_ccia\resources\views\proyectos\index.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                        @if($esAdminOGestor)
                            Gestión de Proyectos
                        @else
                            Mis Proyectos
                        @endif
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        @if($esAdminOGestor)
                            Lista completa de todos los proyectos del sistema
                        @else
                            Proyectos donde eres responsable
                        @endif
                    </p>
                </div>
                
                @if($esAdminOGestor)
                    <a href="{{ route('proyectos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition duration-300">
                        Crear Proyecto
                    </a>
                @endif
            </div>

            <!-- Mensajes de alerta -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @if(session('info'))
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4">
                    <p>{{ session('info') }}</p>
                </div>
            @endif

            <!-- Verificar si hay proyectos -->
            @if($proyectos->count() > 0)
                <!-- Filtros -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md mb-4">
                    <form method="GET" action="{{ route('proyectos.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Buscador -->
                        <div>
                            <label for="buscar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                            <input type="text" name="buscar" id="buscar" value="{{ request('buscar') }}" 
                                   placeholder="Título, código o nombre corto..." 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800">
                        </div>

                        <!-- Estado -->
                        <div>
                            <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                            <select name="estado" id="estado" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800">
                                <option value="">Todos</option>
                                <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activos</option>
                                <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                            </select>
                        </div>

                        <!-- Financiación -->
                        <div>
                            <label for="financiacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Financiación</label>
                            <select name="financiacion" id="financiacion" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800">
                                <option value="">Todas</option>
                                @foreach($tiposFinanciacion as $tipo)
                                    <option value="{{ $tipo }}" {{ request('financiacion') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-end gap-2">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                Filtrar
                            </button>
                            <a href="{{ route('proyectos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                                Limpiar
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Tabla de Proyectos -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg">
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
                            @foreach($proyectos as $proyecto)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 text-gray-800 dark:text-gray-200 font-medium">
                                        {{ $proyecto->codigo }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-800 dark:text-gray-200">
                                        <div class="max-w-xs truncate" title="{{ $proyecto->titulo }}">
                                            {{ $proyecto->titulo }}
                                        </div>
                                        @if($proyecto->nombre_corto)
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $proyecto->nombre_corto }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-800 dark:text-gray-200">
                                        {{ $proyecto->responsable ? $proyecto->responsable->nombre . ' ' . $proyecto->responsable->apellidos : 'Sin asignar' }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-800 dark:text-gray-200 text-sm">
                                        <div>{{ $proyecto->fecha_inicio ? $proyecto->fecha_inicio->format('d/m/Y') : 'No definida' }}</div>
                                        @if($proyecto->fecha_fin)
                                            <div class="text-gray-500 dark:text-gray-400">{{ $proyecto->fecha_fin->format('d/m/Y') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $proyecto->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $proyecto->activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
    <div class="flex flex-wrap gap-1">
        <a href="{{ route('proyectos.show', $proyecto->id_proyecto) }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-center py-1 px-2 rounded text-xs" title="Ver detalles">
            <span>Ver</span>
        </a>
        
        @if($esAdminOGestor)
            <a href="{{ route('proyectos.edit', $proyecto->id_proyecto) }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-center py-1 px-2 rounded text-xs" title="Editar proyecto">
                <span>Editar</span>
            </a>
        @endif
        
        <!-- Estado de compensación - visible para todos los usuarios -->
        @if($proyecto->responsableTieneCompensacion())
            <span class="inline-block bg-green-500 text-white text-center py-1 px-2 rounded text-xs cursor-default" title="Responsable ya compensado">
                Compensado
            </span>
        @else
            @if($puedeAsignarCompensaciones)
                <!-- Solo los gestores pueden compensar -->
                <form action="{{ route('proyectos.asignarCompensacion', $proyecto->id_proyecto) }}" method="POST" class="inline-block">
                    @csrf
                    <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white text-center py-1 px-2 rounded text-xs" title="Compensar al responsable">
                        Compensar
                    </button>
                </form>
            @else
                <!-- Usuarios normales solo ven el estado sin poder actuar -->
                <span class="inline-block bg-gray-400 text-white text-center py-1 px-2 rounded text-xs cursor-default" title="Pendiente de compensación">
                    Sin compensar
                </span>
            @endif
        @endif
        
        @if($esAdminOGestor)
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
        @endif
    </div>
</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="mt-4">
                    {{ $proyectos->links() }}
                </div>

            @else
                <!-- Mensaje cuando no hay proyectos -->
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">
                        @if($esAdminOGestor)
                            No hay proyectos registrados
                        @else
                            Aún no tienes proyectos asignados
                        @endif
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">
                        @if($esAdminOGestor)
                            No se han encontrado proyectos en el sistema. Puedes crear el primero.
                        @else
                            No tienes ningún proyecto donde seas responsable. Contacta con la administración si crees que esto es un error.
                        @endif
                    </p>
                    
                    @if($esAdminOGestor)
                        <a href="{{ route('proyectos.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Crear Primer Proyecto
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Modal de confirmación de eliminación (solo para admin/gestores) -->
    @if($esAdminOGestor)
        <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center hidden">
            <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Confirmar eliminación</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    ¿Estás seguro de que deseas eliminar el proyecto "<span id="projectTitle" class="font-medium"></span>"?
                    Esta acción no se puede deshacer.
                </p>
                <div class="flex justify-end gap-3">
                    <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-400 dark:hover:bg-gray-500">
                        Cancelar
                    </button>
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
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
                document.getElementById('projectTitle').textContent = title;
                document.getElementById('deleteForm').action = `/proyectos/${id}`;
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
    @endif
</x-app-layout>