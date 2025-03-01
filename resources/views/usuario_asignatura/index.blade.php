<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-blue-500">
            Gestión de Asignaciones
        </h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <a href="{{ route('usuario_asignatura.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">
            Nueva Asignación
        </a>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            @foreach ($asignaciones->groupBy('asignatura.titulacion.nombre_titulacion') as $titulacion => $asignacionesGrupo)
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-3 px-6 py-2 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white">
                        {{ $titulacion }}
                    </h2>

                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Usuario</th>
                                <th scope="col" class="px-6 py-3">Asignatura</th>
                                <th scope="col" class="px-6 py-3">Tipo</th>
                                <th scope="col" class="px-6 py-3">Grupo</th>
                                <th scope="col" class="px-6 py-3">Antigüedad</th>
                                <th scope="col" class="px-6 py-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($asignacionesGrupo as $asignacion)
                                <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                    <td class="px-6 py-4">{{ $asignacion->usuario->apellidos }} ,{{ $asignacion->usuario->nombre }}</td>
                                    <td class="px-6 py-4">{{ $asignacion->asignatura->nombre_asignatura }}</td>
                                    <td class="px-6 py-4">{{ $asignacion->tipo }}</td>
                                    <td class="px-6 py-4">{{ $asignacion->grupo }}</td>
                                    <td class="px-6 py-4">{{ $asignacion->antiguedad }}</td>
                                    <td class="px-6 py-4 flex space-x-2">
                                        <a href="{{ route('usuario_asignatura.edit', [$asignacion->id_asignatura, $asignacion->id_usuario, $asignacion->tipo, $asignacion->grupo]) }}" 
                                           class="text-blue-500 hover:underline">Editar</a>
                                        <form action="{{ route('usuario_asignatura.destroy', [$asignacion->id_asignatura, $asignacion->id_usuario, $asignacion->tipo, $asignacion->grupo]) }}" 
                                              method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('¿Estás seguro de eliminar esta asignación?')">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
