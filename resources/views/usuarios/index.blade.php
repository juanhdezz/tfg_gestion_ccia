<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-blue-500">Gestión de Usuarios</h1>
        
        <!-- Formulario de búsqueda -->
        <form method="GET" action="{{ route('usuarios.index') }}" class="mb-4">
            <input type="text" name="search" placeholder="Buscar usuario..." value="{{ request('search') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600">
        </form>

        <div class="flex justify-end mb-4">
            <a href="{{ route('usuarios.export') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Exportar Usuarios
            </a>
            <a href="{{ route('usuarios.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                Añadir Usuario
            </a>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nombre Completo</th>
                        <th scope="col" class="px-6 py-3">DNI/Pasaporte</th>
                        <th scope="col" class="px-6 py-3">Correo</th>
                        <th scope="col" class="px-6 py-3">Tipo Usuario</th>
                        <th scope="col" class="px-6 py-3">Despacho</th>
                        <th scope="col" class="px-6 py-3">Miembro Actual</th>
                        <th scope="col" class="px-6 py-3">Miembro consejo</th>
                        <th scope="col" class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $usuario)
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                        <td class="px-6 py-4">
                            {{ $usuario->nombre }} {{ $usuario->apellidos }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $usuario->dni_pasaporte }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $usuario->correo }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $usuario->tipo_usuario }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $usuario->despacho ? $usuario->despacho->nombre_despacho : 'Sin despacho' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $usuario->miembro_actual }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $usuario->miembro_consejo }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('usuarios.show', $usuario->id_usuario) }}" class="font-medium text-green-600 dark:text-green-500 hover:underline">
                                    Ver &#128270;
                                </a>
                                <a href="{{ route('usuarios.edit', $usuario->id_usuario) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                    Editar &#9999;
                                </a>
                                <!-- Botón Impersonar - Solo para administradores -->
        @if(Auth::user()->hasRole('admin') && !$usuario->hasRole('admin') && Auth::id() !== $usuario->id_usuario)
        <form method="POST" action="{{ route('impersonate.start', $usuario->id_usuario) }}" class="inline">
            @csrf
            <button type="submit" 
                    class="text-amber-600 hover:text-amber-800 dark:text-amber-400 dark:hover:text-amber-300 hover:underline font-medium"
                    onclick="return confirm('¿Estás seguro de que quieres impersonar a {{ $usuario->nombre }} {{ $usuario->apellidos }}?\n\nEsta acción será registrada por seguridad.')">
                🎭 Impersonar
            </button>
        </form>
        @endif
                                <form class="delete-form" action="{{ route('usuarios.destroy', $usuario->id_usuario) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                                        Eliminar &#10060;
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script>
        forms = document.querySelectorAll('.delete-form');
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                Swal.fire({
                    title: "Estás seguro?",
                    text: "Esta acción no se puede deshacer.",
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
    </script>
    @endpush
</x-app-layout>