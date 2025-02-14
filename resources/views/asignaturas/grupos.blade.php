<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-blue-500">Gestión de Grupos</h1>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            @foreach ($asignaturas->groupBy('titulacion.nombre_titulacion') as $titulacion => $asignaturasGrupo)
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-3 px-6 py-2 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white">
                        {{ $titulacion }}
                    </h2>
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Estado</th>
                                <th scope="col" class="px-6 py-3">Nombre Asignatura</th>
                                <th scope="col" class="px-6 py-3">Curso</th>
                                <th scope="col" class="px-6 py-3">Créditos Teoría</th>
                                <th scope="col" class="px-6 py-3">Créditos Prácticas</th>
                                <th scope="col" class="px-6 py-3">Grupos Teoría</th>
                                <th scope="col" class="px-6 py-3">Grupos Prácticas</th>
                                <th scope="col" class="px-6 py-3">Fraccionable</th>
                                <th scope="col" class="px-6 py-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($asignaturasGrupo as $asignatura)
                            <tr class=" odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <td class="px-6 py-4">
                                    {{ $asignatura->estado }}
                                </td>
                                <td class="px-6 py-4">
                                    <strong>{{ $asignatura->nombre_asignatura }}</strong>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $asignatura->curso }}º
                                </td>
                                <td class="px-6 py-4">
                                    {{ $asignatura->creditos_teoria }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $asignatura->creditos_practicas }}
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('asignaturas.updateGrupos', $asignatura->id_asignatura) }}" 
                                          method="POST" 
                                          class="inline-flex">
                                        @csrf
                                        @method('PATCH')
                                        <div class="flex items-center">
                                            <input type="number" 
                                                   name="grupos_teoria" 
                                                   value="{{ $asignatura->grupos_teoria }}"
                                                   class="w-20 px-2 py-1 border rounded dark:bg-gray-700 dark:border-gray-600"
                                                   min="1">
                                            <button type="submit" 
                                                    class="ml-2 text-blue-600 dark:text-blue-500 hover:underline">
                                                ✓
                                            </button>
                                        </div>
                                    </form>
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('asignaturas.updateGrupos', $asignatura->id_asignatura) }}" 
                                          method="POST" 
                                          class="inline-flex">
                                        @csrf
                                        @method('PATCH')
                                        <div class="flex items-center">
                                            <input type="number" 
                                                   name="grupos_practicas" 
                                                   value="{{ $asignatura->grupos_practicas }}"
                                                   class="w-20 px-2 py-1 border rounded dark:bg-gray-700 dark:border-gray-600"
                                                   min="1">
                                            <button type="submit" 
                                                    class="ml-2 text-blue-600 dark:text-blue-500 hover:underline">
                                                ✓
                                            </button>
                                        </div>
                                    </form>
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('asignaturas.updateGrupos', $asignatura->id_asignatura) }}" 
                                          method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="checkbox" 
                                               name="fraccionable" 
                                               value="1"
                                               {{ $asignatura->fraccionable ? 'checked' : '' }}
                                               onchange="this.form.submit()"
                                               class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                                    </form>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('asignaturas.show', $asignatura->id_asignatura) }}" 
                                       class="font-medium text-green-600 dark:text-green-500 hover:underline">
                                        Ver &#128270;
                                    </a>
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