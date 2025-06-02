{{-- filepath: c:\xampp\htdocs\laravel\tfg_gestion_ccia\resources\views\salas\index.blade.php --}}
<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-blue-500">
            Gestión de Salas
        </h1>

        <!-- Formulario de búsqueda -->
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <form method="GET" action="{{ route('salas.index') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-64">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Buscar sala:
                    </label>
                    <input type="text" name="search" id="search" value="{{ $search }}" 
                        placeholder="Nombre o localización..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="flex gap-2">
                    <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Buscar
                    </button>
                    <a href="{{ route('salas.index') }}" 
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        <!-- Botones de acción -->
        @role('admin|secretario')
            <div class="mb-6">
                <a href="{{ route('salas.create') }}" 
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Añadir Sala
                </a>
            </div>
        @endrole

        <!-- Tabla de salas -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            @if($salas->count() > 0)
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nombre
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Localización
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Días Anticipación
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($salas as $sala)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $sala->nombre }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-300">
                                        {{ $sala->localizacion }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-300">
                                        {{ $sala->dias_anticipacion_reserva ? $sala->dias_anticipacion_reserva . ' días' : 'Sin límite' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <!-- Ver detalles -->
                                        <a href="{{ route('salas.show', $sala->id_sala) }}" 
                                            class="font-medium text-green-600 dark:text-green-500 hover:underline">
                                            Ver &#128270;
                                        </a>

                                        @role('admin|secretario')
                                            <!-- Editar -->
                                            <a href="{{ route('salas.edit', $sala->id_sala) }}" 
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                                Editar &#9999;
                                            </a>

                                            <!-- Eliminar -->
                                            <form class="delete-form inline" 
                                                action="{{ route('salas.destroy', $sala->id_sala) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    class="font-medium text-red-600 dark:text-red-500 hover:underline">
                                                    Eliminar &#10060;
                                                </button>
                                            </form>
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Paginación -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700">
                    {{ $salas->appends(request()->query())->links() }}
                </div>
            @else
                <div class="p-6 text-center">
                    <div class="text-gray-500 dark:text-gray-400">
                        @if($search)
                            No se encontraron salas que coincidan con la búsqueda "{{ $search }}".
                        @else
                            No hay salas registradas en el sistema.
                        @endif
                    </div>
                    @role('admin|secretario')
                        <div class="mt-4">
                            <a href="{{ route('salas.create') }}" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Crear primera sala
                            </a>
                        </div>
                    @endrole
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        // Confirmación para eliminar sala
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'Esta acción eliminará la sala permanentemente.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
