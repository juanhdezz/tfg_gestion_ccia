<!-- filepath: /c:/xampp/htdocs/laravel/tfg_gestion_ccia/resources/views/asignaturas/grupos.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-blue-500">Gestión de Grupos</h1>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Formulario de búsqueda -->
        <form method="GET" action="{{ route('asignaturas.grupos') }}" class="mb-4">
            <input type="text" name="search" placeholder="Buscar asignatura..." value="{{ request('search') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600">
        </form>

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
                                <th scope="col" class="px-6 py-3">Grupos</th>
                                <th scope="col" class="px-6 py-3">Fraccionable</th>
                                <th scope="col" class="px-6 py-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($asignaturasGrupo as $asignatura)
                                <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                    <td class="px-6 py-4">{{ $asignatura->estado }}</td>
                                    <td class="px-6 py-4"><strong>{{ $asignatura->nombre_asignatura }}</strong></td>
                                    <td class="px-6 py-4">{{ $asignatura->curso }}º</td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('asignaturas.updateGrupos', $asignatura->id_asignatura) }}" 
                                              method="POST" 
                                              class="space-y-4">
                                            @csrf
                                            @method('PATCH')
                                            
                                            <!-- Grupos existentes -->
                                            @php
                                                $gruposTeoria = $asignatura->grupos->whereNotNull('grupo_teoria')->unique('grupo_teoria');
                                            @endphp
                                    
                                            @foreach($gruposTeoria as $grupoTeoria)
                                                <div class="border rounded p-3 dark:border-gray-700">
                                                    <!-- Grupo de teoría -->
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <label class="font-medium">Grupo Teoría:</label>
                                                        <input type="number" 
                                                               name="grupos_teoria[{{ $grupoTeoria->grupo_teoria }}][numero]" 
                                                               value="{{ $grupoTeoria->grupo_teoria }}"
                                                               class="w-20 px-2 py-1 border rounded dark:bg-gray-700 dark:border-gray-600"
                                                               min="1">
                                                        
                                                        <!-- Botón para eliminar grupo de teoría -->
                                                        <button type="submit" 
                                                                name="eliminar_grupo_teoria" 
                                                                value="{{ $grupoTeoria->grupo_teoria }}"
                                                                class="ml-2 text-red-600 dark:text-red-500 hover:underline"
                                                                onclick="return confirm('¿Estás seguro? Se eliminarán también los grupos de prácticas asociados.')">
                                                            ❌
                                                        </button>
                                                    </div>
                                    
                                                    <!-- Grupos de práctica asociados -->
                                                    <div class="ml-6 space-y-2">
                                                        <label class="text-sm font-medium">Grupos de Práctica:</label>
                                                        @foreach($asignatura->grupos->where('grupo_teoria', $grupoTeoria->grupo_teoria)->whereNotNull('grupo_practica') as $grupoPractica)
                                                            <div class="flex items-center gap-2">
                                                                <input type="number" 
                                                                       name="grupos_teoria[{{ $grupoTeoria->grupo_teoria }}][practicas][]" 
                                                                       value="{{ $grupoPractica->grupo_practica }}"
                                                                       class="w-20 px-2 py-1 border rounded dark:bg-gray-700 dark:border-gray-600"
                                                                       min="1">
                                                                
                                                                <!-- Botón para eliminar grupo de práctica -->
                                                                <button type="submit" 
                                                                        name="eliminar_grupo_practica" 
                                                                        value="{{ $grupoPractica->id }}"
                                                                        class="text-red-600 dark:text-red-500 hover:underline"
                                                                        onclick="return confirm('¿Estás seguro de eliminar este grupo de prácticas?')">
                                                                    ❌
                                                                </button>
                                                            </div>
                                                        @endforeach
                                    
                                                        <!-- Botón para añadir grupo de práctica -->
                                                        <button type="submit" 
                                                                name="nuevo_grupo_practica" 
                                                                value="{{ $grupoTeoria->grupo_teoria }}"
                                                                class="text-sm px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                                                            + Añadir Grupo Práctica
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                    
                                            <!-- Botón para añadir nuevo grupo de teoría -->
                                            <button type="submit" 
                                                    name="nuevo_grupo_teoria" 
                                                    value="1"
                                                    class="mt-4 px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                                + Nuevo Grupo de Teoría
                                            </button>
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