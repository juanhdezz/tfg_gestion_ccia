{{-- filepath: c:\xampp\htdocs\laravel\tfg_gestion_ccia\resources\views\libros\index.blade.php --}}
<x-app-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestión de Libros</h1>
                
                {{-- @can('crear libros') --}}
                <a href="{{ route('libros.create') }}" class="mt-3 md:mt-0 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition flex items-center">
                    <i class="fas fa-plus mr-2"></i> Nuevo Libro
                </a>
                {{-- @endcan --}}
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

            {{-- Filtros de búsqueda --}}
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3">Filtros de búsqueda</h2>
                
                <form action="{{ route('libros.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="titulo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Título</label>
                        <input type="text" name="titulo" id="titulo" value="{{ request('titulo') }}" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800"
                            placeholder="Buscar por título">
                    </div>
                    
                    <div>
                        <label for="autor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Autor</label>
                        <input type="text" name="autor" id="autor" value="{{ request('autor') }}" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800"
                            placeholder="Buscar por autor">
                    </div>
                    
                    <div>
                        <label for="isbn" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ISBN</label>
                        <input type="text" name="isbn" id="isbn" value="{{ request('isbn') }}" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800"
                            placeholder="Buscar por ISBN">
                    </div>
                    
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Año</label>
                        <input type="text" name="year" id="year" value="{{ request('year') }}" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800"
                            placeholder="Año de publicación" maxlength="4">
                    </div>
                    
                    <div class="md:col-span-2 lg:col-span-4 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            <i class="fas fa-search mr-2"></i> Buscar
                        </button>
                        
                        <a href="{{ route('libros.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition text-center">
                            <i class="fas fa-times mr-2"></i> Limpiar filtros
                        </a>
                    </div>
                </form>
            </div>

            {{-- Ordenamiento --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                <p class="text-gray-600 dark:text-gray-400 mb-2 sm:mb-0">
                    <span class="font-semibold">{{ $libros->total() }}</span> libros encontrados
                </p>
                
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Ordenar por:</span>
                    <select id="orderSelector" onchange="reorderPage()" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md px-3 py-1 text-sm">
                        <option value="titulo-asc" {{ request('order_by') == 'titulo' && request('order_direction') == 'asc' ? 'selected' : '' }}>Título (A-Z)</option>
                        <option value="titulo-desc" {{ request('order_by') == 'titulo' && request('order_direction') == 'desc' ? 'selected' : '' }}>Título (Z-A)</option>
                        <option value="autor-asc" {{ request('order_by') == 'autor' && request('order_direction') == 'asc' ? 'selected' : '' }}>Autor (A-Z)</option>
                        <option value="autor-desc" {{ request('order_by') == 'autor' && request('order_direction') == 'desc' ? 'selected' : '' }}>Autor (Z-A)</option>
                        <option value="year-desc" {{ request('order_by') == 'year' && request('order_direction') == 'desc' ? 'selected' : '' }}>Año (reciente primero)</option>
                        <option value="year-asc" {{ request('order_by') == 'year' && request('order_direction') == 'asc' ? 'selected' : '' }}>Año (antiguo primero)</option>
                    </select>
                </div>
            </div>

            {{-- Tabla de libros --}}
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Título</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Autor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">ISBN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Año</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Editorial</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
                        @forelse($libros as $libro)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-200">
                                    <a href="{{ route('libros.show', $libro->id_libro) }}" class="hover:text-blue-600 dark:hover:text-blue-400 font-medium">
                                        {{ Str::limit($libro->titulo, 50) }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-200">{{ Str::limit($libro->autor, 40) }}</td>
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-200">{{ $libro->isbn ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-200">{{ $libro->year }}</td>
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-200">{{ $libro->editorial ?? 'N/A' }}</td>
                                <td class="px-6 py-4 flex space-x-2">
                                    <a href="{{ route('libros.show', $libro->id_libro) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @can('editar libros')
                                    <a href="{{ route('libros.edit', $libro->id_libro) }}" class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan
                                    
                                    {{-- @can('solicitar libros') --}}
                                    <a href="{{ route('libros.solicitarForm', $libro->id_libro) }}" 
                                       class="inline-flex items-center px-2 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 transition-colors" 
                                       title="Solicitar libro">
                                        <i class="fas fa-shopping-cart mr-1"></i> Solicitar
                                    </a>
                                    {{-- @endcan --}}
                                    
                                    @can('eliminar libros')
                                    <button onclick="confirmDelete('{{ $libro->id_libro }}', '{{ addslashes($libro->titulo) }}')" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center py-6">
                                        <i class="fas fa-book-open text-4xl mb-3 text-gray-400 dark:text-gray-600"></i>
                                        <p class="text-lg font-medium">No se encontraron libros</p>
                                        <p class="text-sm">Intenta con otros criterios de búsqueda o crea un nuevo libro</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="mt-6">
                {{ $libros->links() }}
            </div>
        </div>
    </div>

    {{-- Formulario para eliminar (oculto) --}}
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    {{-- Scripts --}}
    @push('scripts')
    <script>
        function confirmDelete(id, titulo) {
            Swal.fire({
                title: '¿Estás seguro?',
                html: `Vas a eliminar el libro:<br><strong>${titulo}</strong>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-form');
                    form.action = `/libros/${id}`;
                    form.submit();
                }
            });
        }

        function reorderPage() {
            const selector = document.getElementById('orderSelector');
            const selectedOption = selector.options[selector.selectedIndex].value;
            const [orderBy, orderDirection] = selectedOption.split('-');
            
            // Obtener los parámetros actuales
            const urlParams = new URLSearchParams(window.location.search);
            
            // Actualizar los parámetros de ordenación
            urlParams.set('order_by', orderBy);
            urlParams.set('order_direction', orderDirection);
            
            // Redirigir a la nueva URL
            window.location.href = `${window.location.pathname}?${urlParams.toString()}`;
        }
    </script>
    @endpush
</x-app-layout>