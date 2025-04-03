{{-- filepath: c:\xampp\htdocs\laravel\tfg_gestion_ccia\resources\views\libros\mis_solicitudes_asignatura.blade.php --}}
<x-app-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Mis Solicitudes de Libros para Asignaturas</h1>
                
                <a href="{{ route('libros.index') }}" class="mt-3 md:mt-0 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 flex items-center">
                    <i class="fas fa-book mr-2"></i> Ver catálogo de libros
                </a>
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

            {{-- Tabla de solicitudes --}}
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Libro</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Asignatura</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Fecha Solicitud</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
                        @forelse($solicitudes as $solicitud)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-200">
                                    <a href="{{ route('libros.show', $solicitud->id_libro) }}" class="hover:text-blue-600 dark:hover:text-blue-400 font-medium">
                                        {{ Str::limit($solicitud->titulo, 40) }}
                                    </a>
                                    <div class="text-gray-500 dark:text-gray-400 text-sm">
                                        {{ Str::limit($solicitud->autor, 30) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-200">
                                    {{ $solicitud->nombre_asignatura }}
                                    <div class="text-gray-500 dark:text-gray-400 text-sm">
                                        {{ $solicitud->titulacion }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-200">
                                    {{ \Carbon\Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    @switch($solicitud->estado)
                                        @case('Pendiente Aceptación')
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">
                                                Pendiente Aceptación
                                            </span>
                                            @break
                                        @case('Aceptado')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                                Aceptado
                                            </span>
                                            @break
                                        @case('Denegado')
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">
                                                Denegado
                                            </span>
                                            @break
                                        @case('Pedido')
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                                Pedido
                                            </span>
                                            @break
                                        @case('Recibido')
                                            <span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs">
                                                Recibido
                                            </span>
                                            @break
                                        @case('Biblioteca')
                                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">
                                                En Biblioteca
                                            </span>
                                            @break
                                        @case('Agotado/Descatalogado')
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                                Agotado/Descatalogado
                                            </span>
                                            @break
                                        @default
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                                {{ $solicitud->estado }}
                                            </span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-4">
                                    <button onclick="verDetalleSolicitud('{{ $solicitud->id_libro }}', '{{ $solicitud->id_usuario }}', '{{ $solicitud->fecha_solicitud }}')" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center py-6">
                                        <i class="fas fa-inbox text-4xl mb-3 text-gray-400 dark:text-gray-600"></i>
                                        <p class="text-lg font-medium">No tienes solicitudes de libros para asignaturas</p>
                                        <p class="text-sm">Cuando solicites un libro para una asignatura, aparecerá aquí</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if($solicitudes->count() > 0)
                <div class="mt-6">
                    {{ $solicitudes->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Modal para ver detalles --}}
    <div id="detalleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white" id="modalTitle">Detalles de la Solicitud</h3>
                <button onclick="cerrarModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="modalContent" class="space-y-4">
                <p class="text-gray-600 dark:text-gray-400">Cargando...</p>
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="cerrarModal()" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function verDetalleSolicitud(idLibro, idUsuario, fechaSolicitud) {
            // Mostrar el modal
            document.getElementById('detalleModal').classList.remove('hidden');
            document.getElementById('modalContent').innerHTML = '<p class="text-center"><i class="fas fa-spinner fa-spin mr-2"></i> Cargando detalles...</p>';
            
            // Realizar la petición AJAX para obtener los detalles
            fetch(`/libros/solicitudes/asignatura/detalle/${idLibro}/${idUsuario}/${fechaSolicitud}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al cargar los detalles');
                    }
                    return response.text();
                })
                .then(html => {
                    document.getElementById('modalContent').innerHTML = html;
                })
                .catch(error => {
                    document.getElementById('modalContent').innerHTML = `
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                            <p>${error.message}</p>
                        </div>
                    `;
                });
        }
        
        function cerrarModal() {
            document.getElementById('detalleModal').classList.add('hidden');
        }
        
        // Cerrar el modal si se hace clic fuera de él
        document.getElementById('detalleModal').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModal();
            }
        });
    </script>
    @endpush
</x-app-layout>