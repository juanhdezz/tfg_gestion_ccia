<!-- filepath: /c:/xampp/htdocs/laravel/tfg_gestion_ccia/resources/views/usuarios/index.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-4">
        <div class="flex justify-end mb-4">
            <a href="{{ route('usuarios.export') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Exportar Usuarios
            </a>
            <a href="{{ route('usuarios.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded  ml-2">
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
                            {{ $usuario->id_despacho }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $usuario->miembro_actual }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $usuario->miembro_consejo }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('usuarios.edit', $usuario->id_usuario) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                    Editar
                                </a>
                                <form action="{{ route('usuarios.destroy', $usuario->id_usuario) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                                        Eliminar
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
</x-app-layout>