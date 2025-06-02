<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-indigo-800 dark:text-indigo-300 border-b-2 border-indigo-500 pb-2">
            Gestión de Asignaciones
        </h1>

        @if (session('success'))
            <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 px-4 py-3 rounded mb-4"
                role="alert">
                <div class="flex">
                    <div class="py-1">
                        <svg class="h-6 w-6 text-emerald-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Barra de acciones superior -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">
            <!-- Botón Nueva Asignación -->
            <a href="{{ route('usuario_asignatura.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded inline-flex items-center shadow-md transition duration-300 w-full md:w-auto justify-center md:justify-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Asignación
            </a>

            <!-- Buscador -->
            <div class="relative w-full md:w-1/2 lg:w-1/3">
                <form action="{{ route('usuario_asignatura.index') }}" method="GET" class="flex">
                    <div class="relative flex-grow">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="search" id="search" name="search" value="{{ request('search') }}"
                            class="block w-full p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-l-lg bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                            placeholder="Buscar asignatura..." aria-label="Buscar">
                    </div>
                    <button type="submit"
                        class="px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-r-lg border border-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800">
                        Buscar
                    </button>
                </form>
            </div>
        </div>

        <!-- Contador de resultados -->
        @php
            $totalAsignaturas = 0;
            foreach ($titulaciones as $titulacion) {
                $totalAsignaturas += $titulacion->asignaturas->count();
            }
        @endphp

        @if (request('search'))
            <div class="text-gray-600 dark:text-gray-300 mb-4">
                <span class="font-medium">{{ $totalAsignaturas }}</span> resultados encontrados para "<span
                    class="italic">{{ request('search') }}</span>"
                <a href="{{ route('usuario_asignatura.index') }}" class="ml-2 text-indigo-600 hover:underline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Limpiar búsqueda
                </a>
            </div>
        @endif        <!-- Menú desplegable de titulaciones -->
        @if ($titulaciones->count() > 1 && !request('search'))
            <div class="mb-6 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg shadow-sm">
                <label for="titulacion-selector" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Saltar a titulación:
                </label>
                <div class="relative">
                    <select id="titulacion-selector" 
                            class="block w-full md:w-1/2 lg:w-1/3 p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                            onchange="saltarATitulacion(this.value)">
                        <option value="">Seleccionar titulación...</option>
                        @foreach ($titulaciones as $titulacion)
                            <option value="titulacion-{{ $titulacion->id_titulacion }}">
                                {{ $titulacion->nombre_titulacion }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>
        @endif

        <div class="relative overflow-x-auto shadow-lg sm:rounded-lg">
            @if ($titulaciones->count() == 0)
                <div class="bg-gray-50 dark:bg-gray-800 p-10 text-center rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 text-lg">No se encontraron resultados para tu búsqueda.
                    </p>
                    <a href="{{ route('usuario_asignatura.index') }}"
                        class="mt-3 inline-block text-indigo-600 hover:underline">Ver todas las asignaturas</a>
                </div>
            @else
                <!-- Agrupado por titulaciones -->
                @foreach ($titulaciones as $titulacion)
                    <div id="titulacion-{{ $titulacion->id_titulacion }}"
                        class="mb-8 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <h2
                            class="text-xl font-bold px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-md sticky top-0">
                            {{ $titulacion->nombre_titulacion }}
                            <span class="text-sm ml-2 opacity-75">
                                ({{ $titulacion->asignaturas->count() }} asignaturas)
                            </span>
                        </h2>

                        <!-- Agrupado por asignaturas de esta titulación -->
                        @foreach ($titulacion->asignaturas as $asignatura)
                            <div class="mb-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                                <h3
                                    class="text-lg font-semibold px-6 py-2 bg-gradient-to-r from-blue-500 to-cyan-500 text-white flex justify-between items-center">
                                    <span>{{ $asignatura->nombre_asignatura }}</span>
                                    <span class="text-xs bg-blue-700 px-2 py-1 rounded-full">
                                        Código: {{ $asignatura->id_asignatura }}
                                    </span>
                                </h3>

                                <!-- Nueva implementación: Grupos de teoría con sus grupos de prácticas asociados -->
                                <div class="p-4">
                                    @php
                                        // Obtener grupos únicos de teoría para esta asignatura
                                        $gruposTeoria = $asignatura->grupos
                                            ->pluck('grupo_teoria')
                                            ->unique()
                                            ->sort()
                                            ->filter();
                                            
                                        // Mapeo de grupos de prácticas a grupos de teoría
                                        $gruposPracticaPorTeoria = [];
                                        foreach ($asignatura->grupos as $grupo) {
                                            if (!isset($gruposPracticaPorTeoria[$grupo->grupo_teoria])) {
                                                $gruposPracticaPorTeoria[$grupo->grupo_teoria] = [];
                                            }
                                            if ($grupo->grupo_practica) {
                                                $gruposPracticaPorTeoria[$grupo->grupo_teoria][] = $grupo->grupo_practica;
                                            }
                                        }
                                        
                                        // Ordenar los grupos de práctica dentro de cada grupo de teoría
                                        foreach ($gruposPracticaPorTeoria as &$practicas) {
                                            sort($practicas);
                                        }
                                    @endphp

                                    @if ($gruposTeoria->isEmpty())
                                        <div class="bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300 p-4 rounded mb-4">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="font-medium">No hay grupos definidos para esta asignatura</span>
                                            </div>
                                        </div>
                                    @else
                                        @foreach ($gruposTeoria as $grupoTeoria)
                                            <!-- Panel de grupo de teoría -->
                                            <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                                                @php
                                                    // Buscar si existe una asignación para este grupo de teoría
                                                    $asignacionTeoria = $asignaciones
                                                        ->where('id_asignatura', $asignatura->id_asignatura)
                                                        ->where('tipo', 'Teoría')
                                                        ->where('grupo', $grupoTeoria)
                                                        ->first();
                                                @endphp

                                                <!-- Cabecera del grupo de teoría -->
                                                <div class="bg-gradient-to-r from-blue-100 to-blue-200 dark:from-blue-900 dark:to-blue-800 p-4">
                                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                                        <div class="flex items-center mb-2 md:mb-0">
                                                            <div class="bg-blue-600 dark:bg-blue-500 text-white font-bold rounded-lg w-12 h-12 flex items-center justify-center mr-3">
                                                                T{{ $grupoTeoria }}
                                                            </div>
                                                            <div>
                                                                <h4 class="text-lg font-semibold text-blue-800 dark:text-blue-200">
                                                                    Grupo de Teoría {{ $grupoTeoria }}
                                                                </h4>
                                                                <p class="text-blue-600 dark:text-blue-300 text-sm">
                                                                    @if ($asignacionTeoria && $asignacionTeoria->usuario)
                                                                        <span class="font-medium">Profesor:</span>
                                                                        {{ $asignacionTeoria->usuario->nombre }}
                                                                        {{ $asignacionTeoria->usuario->apellidos }}
                                                                        <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-2 py-0.5 rounded ml-2">
                                                                            Antigüedad: {{ $asignacionTeoria->antiguedad }}
                                                                        </span>
                                                                    @else
                                                                        <span class="text-yellow-600 dark:text-yellow-400 flex items-center">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                            </svg>
                                                                            Sin profesor asignado
                                                                        </span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="flex space-x-2">
                                                            @if ($asignacionTeoria)
                                                                <a href="{{ route('usuario_asignatura.edit', [$asignatura->id_asignatura, $asignacionTeoria->id_usuario, 'Teoría', $grupoTeoria]) }}"
                                                                    class="bg-cyan-600 hover:bg-cyan-700 text-white px-3 py-1 rounded text-sm flex items-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                    </svg>
                                                                    Editar
                                                                </a>
                                                                <form action="{{ route('usuario_asignatura.destroy', [$asignatura->id_asignatura, $asignacionTeoria->id_usuario, 'Teoría', $grupoTeoria]) }}" method="POST" class="inline delete-form">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white px-3 py-1 rounded text-sm flex items-center">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                        </svg>
                                                                        Eliminar
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <a href="{{ route('usuario_asignatura.create', ['id_asignatura' => $asignatura->id_asignatura, 'tipo' => 'Teoría', 'grupo' => $grupoTeoria]) }}"
                                                                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1 rounded text-sm flex items-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                                    </svg>
                                                                    Asignar Profesor
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Grupos de práctica asociados a este grupo de teoría -->
                                                <div class="p-0 divide-y divide-gray-200 dark:divide-gray-700">
                                                    <h5 class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800">
                                                        Grupos de prácticas asociados
                                                    </h5>

                                                    @if (empty($gruposPracticaPorTeoria[$grupoTeoria]) || count($gruposPracticaPorTeoria[$grupoTeoria]) === 0)
                                                        <div class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 italic">
                                                            No hay grupos de prácticas asociados a este grupo de teoría
                                                        </div>
                                                    @else
                                                        <div class="divide-y divide-gray-100 dark:divide-gray-800">
                                                            @foreach ($gruposPracticaPorTeoria[$grupoTeoria] as $grupoPractica)
                                                                @php
                                                                    // Buscar si existe una asignación para este grupo de práctica
                                                                    $asignacionPractica = $asignaciones
                                                                        ->where('id_asignatura', $asignatura->id_asignatura)
                                                                        ->where('tipo', 'Prácticas')
                                                                        ->where('grupo', $grupoPractica)
                                                                        ->first();
                                                                @endphp

                                                                <div class="px-4 py-3 flex flex-col md:flex-row md:items-center md:justify-between hover:bg-gray-50 dark:hover:bg-gray-750">
                                                                    <div class="flex items-center mb-2 md:mb-0">
                                                                        <div class="bg-teal-600 dark:bg-teal-500 text-white font-bold rounded-full w-8 h-8 flex items-center justify-center mr-3">
                                                                            P{{ $grupoPractica }}
                                                                        </div>
                                                                        <div>
                                                                            <p class="font-medium text-gray-800 dark:text-gray-200">
                                                                                Grupo de Prácticas {{ $grupoPractica }}
                                                                            </p>
                                                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                                                @if ($asignacionPractica && $asignacionPractica->usuario)
                                                                                    <span class="font-medium">Profesor:</span>
                                                                                    {{ $asignacionPractica->usuario->nombre }}
                                                                                    {{ $asignacionPractica->usuario->apellidos }}
                                                                                    <span class="bg-teal-100 dark:bg-teal-900 text-teal-800 dark:text-teal-200 text-xs px-2 py-0.5 rounded ml-2">
                                                                                        Antigüedad: {{ $asignacionPractica->antiguedad }}
                                                                                    </span>
                                                                                @else
                                                                                    <span class="text-yellow-600 dark:text-yellow-400 flex items-center">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                                        </svg>
                                                                                        Sin profesor asignado
                                                                                    </span>
                                                                                @endif
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex space-x-2">
                                                                        @if ($asignacionPractica)
                                                                            <a href="{{ route('usuario_asignatura.edit', [$asignatura->id_asignatura, $asignacionPractica->id_usuario, 'Prácticas', $grupoPractica]) }}"
                                                                                class="text-cyan-600 hover:text-cyan-800 dark:text-cyan-400 dark:hover:text-cyan-300 hover:underline flex items-center">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                                </svg>
                                                                                Editar
                                                                            </a>
                                                                            <form action="{{ route('usuario_asignatura.destroy', [$asignatura->id_asignatura, $asignacionPractica->id_usuario, 'Prácticas', $grupoPractica]) }}" 
                                                                                  method="POST" class="inline delete-form">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="text-rose-600 hover:text-rose-800 dark:text-rose-400 dark:hover:text-rose-300 hover:underline flex items-center">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                                    </svg>
                                                                                    Eliminar
                                                                                </button>
                                                                            </form>
                                                                        @else
                                                                            <a href="{{ route('usuario_asignatura.create', ['id_asignatura' => $asignatura->id_asignatura, 'tipo' => 'Prácticas', 'grupo' => $grupoPractica]) }}"
                                                                                class="text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300 hover:underline flex items-center">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                                                </svg>
                                                                                Asignar
                                                                            </a>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
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
                        text: "Esta acción eliminará la asignación y no se puede deshacer.",
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
                });            });
        });

        // Función para saltar a una titulación específica
        function saltarATitulacion(titulacionId) {
            if (titulacionId) {
                const elemento = document.getElementById(titulacionId);
                if (elemento) {
                    elemento.scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'start'
                    });
                    // Opcional: agregar un efecto visual al elemento
                    elemento.classList.add('animate-pulse');
                    setTimeout(() => {
                        elemento.classList.remove('animate-pulse');
                    }, 2000);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>