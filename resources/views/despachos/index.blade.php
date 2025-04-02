<!-- filepath: /c:/xampp/htdocs/laravel/tfg_gestion_ccia/resources/views/despachos/index.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-indigo-800 dark:text-indigo-300 border-b-2 border-indigo-500 pb-2">
            Gestión de Despachos
        </h1>

        @if (session('success'))
            <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 px-4 py-3 rounded mb-4" role="alert">
                <div class="flex">
                    <div class="py-1">
                        <svg class="h-6 w-6 text-emerald-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Barra de acciones superior -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">
            <!-- Botón Nuevo Despacho -->
            <a href="{{ route('despachos.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded inline-flex items-center shadow-md transition duration-300 w-full md:w-auto justify-center md:justify-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nuevo Despacho
            </a>

            <!-- Buscador -->
            <div class="relative w-full md:w-1/2 lg:w-1/3">
                <form action="{{ route('despachos.index') }}" method="GET" class="flex">
                    <div class="relative flex-grow">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="search" id="search" name="search" value="{{ request('search') }}" 
                            class="block w-full p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-l-lg bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500" 
                            placeholder="Buscar despacho..." aria-label="Buscar">
                    </div>
                    <button type="submit" class="px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-r-lg border border-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800">
                        Buscar
                    </button>
                </form>
            </div>
        </div>

        <!-- Contador de resultados -->
        @if(request('search'))
            <div class="text-gray-600 dark:text-gray-300 mb-4">
                <span class="font-medium">{{ $despachos->total() }}</span> resultados encontrados para "<span class="italic">{{ request('search') }}</span>"
                <a href="{{ route('despachos.index') }}" class="ml-2 text-indigo-600 hover:underline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Limpiar búsqueda
                </a>
            </div>
        @endif

        <!-- Tabla de despachos -->
        <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
            @if($despachos->isEmpty())
                <div class="bg-gray-50 dark:bg-gray-800 p-10 text-center rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 text-lg">No se encontraron despachos.</p>
                    @if(request('search'))
                        <a href="{{ route('despachos.index') }}" class="mt-3 inline-block text-indigo-600 hover:underline">Ver todos los despachos</a>
                    @endif
                </div>
            @else
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Centro</th>
                            <th scope="col" class="px-6 py-3">Nombre</th>
                            <th scope="col" class="px-6 py-3">Siglas</th>
                            <th scope="col" class="px-6 py-3">Teléfono</th>
                            <th scope="col" class="px-6 py-3">Puestos</th>
                            <th scope="col" class="px-6 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($despachos as $despacho)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4">
                                    @if($despacho->centro && $despacho->centro->nombre_centro)
                                        <span class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 text-xs font-medium px-2.5 py-0.5 rounded">
                                            {{ $despacho->centro->nombre_centro }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500 text-xs italic">
                                            Sin asignar
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    {{ $despacho->nombre_despacho }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $despacho->siglas_despacho ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $despacho->telefono_despacho ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 text-xs font-medium px-2.5 py-0.5 rounded">
                                        {{ $despacho->numero_puestos }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 flex space-x-2">
                                    <a href="{{ route('despachos.show', $despacho->id_despacho) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 hover:underline flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Ver
                                    </a>
                                    <a href="{{ route('despachos.edit', $despacho->id_despacho) }}" class="text-cyan-600 hover:text-cyan-800 dark:text-cyan-400 dark:hover:text-cyan-300 hover:underline flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Editar
                                    </a>
                                    <form action="{{ route('despachos.destroy', $despacho->id_despacho) }}" method="POST" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 dark:text-rose-400 dark:hover:text-rose-300 hover:underline flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <!-- Paginación -->
                <div class="px-6 py-3">
                    {{ $despachos->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // SweetAlert para mostrar mensajes de sesión
            @if(session('swal'))
                Swal.fire({
                    icon: "{{ session('swal.icon') }}",
                    title: "{{ session('swal.title') }}",
                    text: "{{ session('swal.text') }}",
                    timer: 3000
                });
            @endif

            // Configuración para formularios de eliminación
            const forms = document.querySelectorAll('.delete-form');
            forms.forEach(form => {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    Swal.fire({
                        title: "¿Estás seguro?",
                        text: "Esta acción eliminará el despacho y no se puede deshacer.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Sí, eliminar",
                        cancelButtonText: "Cancelar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
    @endpush
</x-app-layout>