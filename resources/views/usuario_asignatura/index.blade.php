<!-- filepath: /c:/xampp/htdocs/laravel/tfg_gestion_ccia/resources/views/usuario_asignatura/index.blade.php -->
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
        @endif

        <!-- Índice rápido de titulaciones -->
        @if ($titulaciones->count() > 1 && !request('search'))
            <div class="mb-6 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg shadow-sm">
                <h2 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Saltar a titulación:</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach ($titulaciones as $titulacion)
                        <a href="#titulacion-{{ $titulacion->id_titulacion }}"
                            class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 px-2 py-1 rounded text-sm text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-gray-600 transition-colors">
                            {{ $titulacion->nombre_titulacion }}
                        </a>
                    @endforeach
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

                                <!-- Sección para grupos de teoría -->
                                <div class="mb-4">
                                    <h4
                                        class="text-md font-medium pl-8 py-2 bg-gradient-to-r from-amber-100 to-amber-200 dark:from-amber-900 dark:to-amber-800 text-amber-800 dark:text-amber-200 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                        Grupos de Teoría
                                    </h4>
                                    <table class="w-full text-sm text-left">
                                        <thead
                                            class="text-xs uppercase bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 text-blue-700 dark:text-blue-300">
                                            <tr>
                                                <th scope="col" class="px-6 py-3">Grupo</th>
                                                <th scope="col" class="px-6 py-3">Profesor</th>
                                                <th scope="col" class="px-6 py-3">Antigüedad</th>
                                                <th scope="col" class="px-6 py-3">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                // Obtener grupos únicos de teoría para esta asignatura
                                                $gruposTeoria = $asignatura->grupos
                                                    ->pluck('grupo_teoria')
                                                    ->unique()
                                                    ->filter();
                                            @endphp

                                            @if ($gruposTeoria->isEmpty())
                                                <tr class="bg-gray-50 dark:bg-gray-800">
                                                    <td colspan="4"
                                                        class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                                        No hay grupos de teoría definidos para esta asignatura
                                                    </td>
                                                </tr>
                                            @else
                                                @foreach ($gruposTeoria as $grupo)
                                                    @php
                                                        // Buscar si existe una asignación para este grupo de teoría
                                                        $asignacion = $asignaciones
                                                            ->where('id_asignatura', $asignatura->id_asignatura)
                                                            ->where('tipo', 'Teoría')
                                                            ->where('grupo', $grupo)
                                                            ->first();
                                                    @endphp
                                                    <tr
                                                        class="bg-gradient-to-r hover:bg-blue-50 dark:hover:bg-blue-900 {{ $loop->odd ? 'from-white to-blue-50 dark:from-gray-800 dark:to-blue-950' : 'from-blue-50 to-blue-100 dark:from-blue-950 dark:to-blue-900' }} border-b dark:border-gray-700">
                                                        <td
                                                            class="px-6 py-3 font-medium text-blue-800 dark:text-blue-300">
                                                            {{ $grupo }}</td>
                                                        <td class="px-6 py-3">
                                                            @if ($asignacion && $asignacion->usuario)
                                                                <div class="flex items-center">
                                                                    <div
                                                                        class="h-8 w-8 rounded-full bg-blue-200 dark:bg-blue-700 flex items-center justify-center mr-2">
                                                                        <span
                                                                            class="text-blue-800 dark:text-blue-300 font-bold">{{ substr($asignacion->usuario->nombre, 0, 1) }}{{ substr($asignacion->usuario->apellidos, 0, 1) }}</span>
                                                                    </div>
                                                                    <span>{{ $asignacion->usuario->apellidos }},
                                                                        {{ $asignacion->usuario->nombre }}</span>
                                                                </div>
                                                            @else
                                                                <span class="text-gray-400 italic flex items-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="h-5 w-5 mr-1" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                                    </svg>
                                                                    No asignado
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="px-6 py-3">
                                                            @if ($asignacion)
                                                                <span
                                                                    class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 text-xs font-medium px-2.5 py-0.5 rounded">
                                                                    {{ $asignacion->antiguedad }}
                                                                </span>
                                                            @else
                                                                <span class="text-gray-400">-</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-6 py-3 flex space-x-2">
                                                            @if ($asignacion)
                                                                <a href="{{ route('usuario_asignatura.edit', [$asignatura->id_asignatura, $asignacion->id_usuario, 'Teoría', $grupo]) }}"
                                                                    class="text-cyan-600 hover:text-cyan-800 dark:text-cyan-400 dark:hover:text-cyan-300 hover:underline flex items-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="h-4 w-4 mr-1" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                    </svg>
                                                                    Editar
                                                                </a>
                                                                <form
                                                                    action="{{ route('usuario_asignatura.destroy', [$asignatura->id_asignatura, $asignacion->id_usuario, 'Teoría', $grupo]) }}"
                                                                    method="POST" class="inline delete-form">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="text-rose-600 hover:text-rose-800 dark:text-rose-400 dark:hover:text-rose-300 hover:underline flex items-center">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="h-4 w-4 mr-1" fill="none"
                                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                        </svg>
                                                                        Eliminar
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <a href="{{ route('usuario_asignatura.create', ['id_asignatura' => $asignatura->id_asignatura, 'tipo' => 'Teoría', 'grupo' => $grupo]) }}"
                                                                    class="text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300 hover:underline flex items-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="h-4 w-4 mr-1" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 4v16m8-8H4" />
                                                                    </svg>
                                                                    Asignar
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Sección para grupos de práctica -->
                                <div class="mb-4">
                                    <h4
                                        class="text-md font-medium pl-8 py-2 bg-gradient-to-r from-teal-100 to-teal-200 dark:from-teal-900 dark:to-teal-800 text-teal-800 dark:text-teal-200 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                        </svg>
                                        Grupos de Práctica
                                    </h4>
                                    <table class="w-full text-sm text-left">
                                        <thead
                                            class="text-xs uppercase bg-gradient-to-r from-teal-50 to-teal-100 dark:from-teal-900 dark:to-teal-800 text-teal-700 dark:text-teal-300">
                                            <tr>
                                                <th scope="col" class="px-6 py-3">Grupo</th>
                                                <th scope="col" class="px-6 py-3">Profesor</th>
                                                <th scope="col" class="px-6 py-3">Antigüedad</th>
                                                <th scope="col" class="px-6 py-3">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                // Obtener grupos únicos de práctica para esta asignatura
                                                $gruposPractica = $asignatura->grupos
                                                    ->pluck('grupo_practica')
                                                    ->unique()
                                                    ->filter();
                                            @endphp

                                            @if ($gruposPractica->isEmpty())
                                                <tr class="bg-gray-50 dark:bg-gray-800">
                                                    <td colspan="4"
                                                        class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                                        No hay grupos de prácticas definidos para esta asignatura
                                                    </td>
                                                </tr>
                                            @else
                                                @foreach ($gruposPractica as $grupo)
                                                    @php
                                                        // Buscar si existe una asignación para este grupo de práctica
                                                        $asignacion = $asignaciones
                                                            ->where('id_asignatura', $asignatura->id_asignatura)
                                                            ->where('tipo', 'Prácticas')
                                                            ->where('grupo', $grupo)
                                                            ->first();
                                                    @endphp
                                                    <tr
                                                        class="bg-gradient-to-r hover:bg-teal-50 dark:hover:bg-teal-900 {{ $loop->odd ? 'from-white to-teal-50 dark:from-gray-800 dark:to-teal-950' : 'from-teal-50 to-teal-100 dark:from-teal-950 dark:to-teal-900' }} border-b dark:border-gray-700">
                                                        <td
                                                            class="px-6 py-3 font-medium text-teal-800 dark:text-teal-300">
                                                            {{ $grupo }}</td>
                                                        <td class="px-6 py-3">
                                                            @if ($asignacion && $asignacion->usuario)
                                                                <div class="flex items-center">
                                                                    <div
                                                                        class="h-8 w-8 rounded-full bg-teal-200 dark:bg-teal-700 flex items-center justify-center mr-2">
                                                                        <span
                                                                            class="text-teal-800 dark:text-teal-300 font-bold">{{ substr($asignacion->usuario->nombre, 0, 1) }}{{ substr($asignacion->usuario->apellidos, 0, 1) }}</span>
                                                                    </div>
                                                                    <span>{{ $asignacion->usuario->apellidos }},
                                                                        {{ $asignacion->usuario->nombre }}</span>
                                                                </div>
                                                            @else
                                                                <span class="text-gray-400 italic flex items-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="h-5 w-5 mr-1" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                                    </svg>
                                                                    No asignado
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="px-6 py-3">
                                                            @if ($asignacion)
                                                                <span
                                                                    class="bg-teal-100 text-teal-800 dark:bg-teal-900 dark:text-teal-300 text-xs font-medium px-2.5 py-0.5 rounded">
                                                                    {{ $asignacion->antiguedad }}
                                                                </span>
                                                            @else
                                                                <span class="text-gray-400">-</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-6 py-3 flex space-x-2">
                                                            @if ($asignacion)
                                                                <a href="{{ route('usuario_asignatura.edit', [$asignatura->id_asignatura, $asignacion->id_usuario, 'Prácticas', $grupo]) }}"
                                                                    class="text-cyan-600 hover:text-cyan-800 dark:text-cyan-400 dark:hover:text-cyan-300 hover:underline flex items-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="h-4 w-4 mr-1" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                    </svg>
                                                                    Editar
                                                                </a>
                                                                <form
                                                                    action="{{ route('usuario_asignatura.destroy', [$asignatura->id_asignatura, $asignacion->id_usuario, 'Prácticas', $grupo]) }}"
                                                                    method="POST" class="inline delete-form">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="text-rose-600 hover:text-rose-800 dark:text-rose-400 dark:hover:text-rose-300 hover:underline flex items-center">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="h-4 w-4 mr-1" fill="none"
                                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                        </svg>
                                                                        Eliminar
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <a href="{{ route('usuario_asignatura.create', ['id_asignatura' => $asignatura->id_asignatura, 'tipo' => 'Prácticas', 'grupo' => $grupo]) }}"
                                                                    class="text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300 hover:underline flex items-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="h-4 w-4 mr-1" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 4v16m8-8H4" />
                                                                    </svg>
                                                                    Asignar
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
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
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
