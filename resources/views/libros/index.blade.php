<!-- filepath: c:\xampp\htdocs\laravel\tfg_gestion_ccia\resources\views\libros\index.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-4">
        <!-- Header con selector de BD para admin/secretario -->
        @role('admin|secretario')
            <div class="mb-6">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 rounded-xl p-4 border border-blue-200 dark:border-gray-600 shadow-sm">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <!-- Información de gestión -->
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 1.79 4 4 4h8c0-2.21-1.79-4-4-4H8c-2.21 0-4-1.79-4-4zm0 0c0-2.21 1.79-4 4-4h8c2.21 0 4 1.79 4 4v10c0 2.21-1.79 4-4 4H8c-2.21 0-4-1.79-4-4V7z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Panel de Gestión</h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Curso académico activo del sistema</p>
                            </div>
                        </div>
                        
                        <!-- Selector de base de datos -->
                        <div class="lg:w-72">
                            <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-600 shadow-sm">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    Base de Datos Activa
                                </label>
                                <x-database-selector />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endrole
        <h1 class="text-3xl font-bold mb-4 text-indigo-800 dark:text-indigo-300 border-b-2 border-indigo-500 pb-2">
            Gestión de Solicitudes de Libros
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

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <div class="flex">
                    <div class="py-1">
                        <svg class="h-6 w-6 text-red-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <span class="block sm:inline font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Barra de acciones superior -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">
            <!-- Botón Nueva Solicitud -->
            <a href="{{ route('libros.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded inline-flex items-center shadow-md transition duration-300 w-full md:w-auto justify-center md:justify-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Solicitud de Libro
            </a>

            <!-- Botón Imprimir Listado -->
    <a href="{{ route('libros.imprimir', ['estado' => request('estado'), 'search' => request('search')]) }}"
        target="_blank"
        class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded inline-flex items-center shadow-md transition duration-300 w-full md:w-auto justify-center md:justify-start">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
        </svg>
        Imprimir Listado
    </a>
            

            <!-- Filtros y Buscador -->
            <div class="w-full md:w-2/3 space-y-2">
                <form action="{{ route('libros.index') }}" method="GET" class="flex flex-col md:flex-row gap-2">
                    <!-- Filtro por estado -->
                    <div class="relative w-full md:w-1/3">
                        <select id="estado" name="estado"
                            class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500">
                            <option value="">Todos los estados</option>
                            @foreach ($estados as $estado)
                                <option value="{{ $estado }}"
                                    {{ request('estado') == $estado ? 'selected' : '' }}>
                                    {{ $estado }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Buscador -->
                    <div class="relative flex-grow flex">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="search" id="search" name="search" value="{{ request('search') }}"
                            class="block w-full p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-l-lg bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                            placeholder="Buscar libro, autor, ISBN..." aria-label="Buscar">
                        <button type="submit"
                            class="px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-r-lg border border-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800">
                            Buscar
                        </button>
                    </div>

                    @if (request('search') || request('estado'))
                        <a href="{{ route('libros.index') }}"
                            class="px-4 py-2.5 text-sm font-medium text-indigo-600 bg-white rounded-lg border border-indigo-600 hover:bg-indigo-50 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:bg-gray-700 dark:text-indigo-400 dark:border-indigo-500 dark:hover:bg-gray-600">
                            Limpiar filtros
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Pestañas para las diferentes categorías -->
        <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="categoriasTabs" role="tablist">
                <li class="mr-2" role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 rounded-t-lg active border-indigo-500 text-indigo-600 dark:border-indigo-500 dark:text-indigo-500"
                        id="asignatura-tab" data-tabs-target="#asignatura" type="button" role="tab"
                        aria-controls="asignatura" aria-selected="true">
                        Asignatura
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                        id="proyecto-tab" data-tabs-target="#proyecto" type="button" role="tab"
                        aria-controls="proyecto" aria-selected="false">
                        Proyecto
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                        id="Grupo-tab" data-tabs-target="#Grupo" type="button" role="tab"
                        aria-controls="Grupo" aria-selected="false">
                        Grupo de Investigación
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                        id="posgrado-tab" data-tabs-target="#posgrado" type="button" role="tab"
                        aria-controls="posgrado" aria-selected="false">
                        Posgrado
                    </button>
                </li>
                <li role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                        id="otros-tab" data-tabs-target="#otros" type="button" role="tab"
                        aria-controls="otros" aria-selected="false">
                        Otros
                    </button>
                </li>
            </ul>
        </div>

        <!-- Contenido de las pestañas -->
        <div id="categoriasTabsContent">
            <!-- Pestaña de Asignatura -->
            <div class="block" id="asignatura" role="tabpanel" aria-labelledby="asignatura-tab">
                <!-- Contador de resultados y chips de filtros activos -->
                <div class="flex flex-wrap items-center gap-3 mb-4">                    <span class="text-gray-600 dark:text-gray-300">
                        <span class="font-medium">{{ $librosAsignatura->total() }}</span> solicitudes para asignaturas
                        encontradas
                    </span>

                    @if (request('estado'))
                        <div class="flex items-center gap-1">
                            @php
                                $estadoClasses = '';
                                switch(request('estado')) {
                                    case 'Pendiente Aceptación':
                                        $estadoClasses = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                                        break;
                                    case 'Aceptado':
                                        $estadoClasses = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                                        break;
                                    case 'Denegado':
                                        $estadoClasses = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                        break;
                                    case 'Recibido':
                                        $estadoClasses = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
                                        break;
                                    case 'Biblioteca':
                                        $estadoClasses = 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300';
                                        break;
                                    case 'Agotado/Descatalogado':
                                        $estadoClasses = 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
                                        break;
                                }
                            @endphp
                            <span class="text-xs font-medium px-2.5 py-0.5 rounded {{ $estadoClasses }}">
                                Filtrado por: {{ request('estado') }}
                            </span>
                            <a href="{{ request()->url() }}?{{ http_build_query(request()->except('estado')) }}"
                                class="text-gray-500 hover:text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        </div>
                    @endif

                    @if (request('search'))
                        <div class="flex items-center gap-1">
                            <span
                                class="bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 text-xs font-medium px-2.5 py-0.5 rounded">
                                Búsqueda: "{{ request('search') }}"
                            </span>
                            <a href="{{ request()->url() }}?{{ http_build_query(request()->except('search')) }}"
                                class="text-gray-500 hover:text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Tabla de libros para asignatura -->
                <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                    @if ($librosAsignatura->isEmpty())
                        <div class="bg-gray-50 dark:bg-gray-800 p-10 text-center rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 text-lg">No se encontraron solicitudes de libros
                                para asignaturas.</p>
                            @if (request('search') || request('estado'))
                                <a href="{{ route('libros.index') }}"
                                    class="mt-3 inline-block text-indigo-600 hover:underline">Ver todas las
                                    solicitudes</a>
                            @endif
                        </div>
                    @else
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Libro</th>
                                    <th scope="col" class="px-6 py-3">Autor</th>
                                    <th scope="col" class="px-6 py-3">ISBN</th>
                                    <th scope="col" class="px-6 py-3">Asignatura</th>
                                    <th scope="col" class="px-6 py-3">Solicitante</th>
                                    <th scope="col" class="px-6 py-3">Fecha</th>
                                    <th scope="col" class="px-6 py-3">Estado</th>
                                    <th scope="col" class="px-6 py-3">Precio</th>
                                    <th scope="col" class="px-6 py-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($librosAsignatura as $solicitud)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ $solicitud->libro->titulo }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $solicitud->libro->autor }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $solicitud->libro->isbn }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 text-xs font-medium px-2.5 py-0.5 rounded">
                                                {{ $solicitud->asignatura->nombre_asignatura ?? 'Sin especificar' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $solicitud->usuario->nombre }} {{ $solicitud->usuario->apellidos }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $solicitud->fecha_solicitud->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="text-xs font-medium px-2.5 py-0.5 rounded
                                                {{ $solicitud->estado == 'Pendiente Aceptación' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : '' }}
                                                {{ $solicitud->estado == 'Aceptado' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : '' }}
                                                {{ $solicitud->estado == 'Denegado' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : '' }}
                                                {{ $solicitud->estado == 'Recibido' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : '' }}
                                                {{ $solicitud->estado == 'Biblioteca' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' : '' }}
                                                {{ $solicitud->estado == 'Agotado/Descatalogado' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300' : '' }}">
                                                {{ $solicitud->estado }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $solicitud->precio }}€
                                            <span
                                                class="text-xs text-gray-500 dark:text-gray-400">({{ $solicitud->num_ejemplares }}
                                                uds.)</span>
                                        </td>
                                        <td class="px-6 py-4 flex space-x-2">
                                            <button type="button"
                                                data-modal-target="detalleModalAsignatura-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                data-modal-toggle="detalleModalAsignatura-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 hover:underline flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Ver
                                            </button>
                                        
                                            @role('admin|secretario')
                                                @if ($solicitud->estado == 'Pendiente Aceptación')
                                                    <form
                                                        action="{{ route('libros.aprobar', [$solicitud->id_libro, $solicitud->id_usuario, $solicitud->fecha_solicitud->format('Y-m-d')]) }}"
                                                        method="POST" class="inline aprobar-form">
                                                        @csrf
                                                        <button type="submit"
                                                            class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 hover:underline flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            Aprobar
                                                        </button>
                                                    </form>
                                        
                                                    <button type="button"
                                                        data-modal-target="denegarModal-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        data-modal-toggle="denegarModal-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        class="text-rose-600 hover:text-rose-800 dark:text-rose-400 dark:hover:text-rose-300 hover:underline flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Denegar
                                                    </button>
                                                @endif
                                            @endrole                                            @if ($solicitud->estado == 'Aceptado')
                                                @role('admin|secretario')
                                                    <form
                                                        action="{{ route('libros.biblioteca', [$solicitud->id_libro, $solicitud->id_usuario, $solicitud->fecha_solicitud->format('Y-m-d')]) }}"
                                                        method="POST" class="inline biblioteca-form">
                                                        @csrf
                                                        <button type="submit"
                                                            class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300 hover:underline flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                            </svg>
                                                            Biblioteca
                                                        </button>
                                                    </form>

                                                    <button type="button"
                                                        data-modal-target="agotadoModal-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        data-modal-toggle="agotadoModal-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 hover:underline flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                                                        </svg>
                                                        Agotado/Descatalogado
                                                    </button>
                                                @endrole
                                            @endif

                                            @role('admin|secretario')
                                                @if ($solicitud->estado == 'Biblioteca')
                                                    <form
                                                        action="{{ route('libros.recibir', [$solicitud->id_libro, $solicitud->id_usuario, $solicitud->fecha_solicitud->format('Y-m-d')]) }}"
                                                        method="POST" class="inline recibir-form">
                                                        @csrf
                                                        <button type="submit"
                                                            class="text-cyan-600 hover:text-cyan-800 dark:text-cyan-400 dark:hover:text-cyan-300 hover:underline flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                                            </svg>
                                                            Recibido
                                                        </button>
                                                    </form>
                                                @endif
                                            @endrole

                                            @role('admin|secretario')
                                                @if ($solicitud->estado == 'Agotado/Descatalogado')
                                                    <button type="button"
                                                        data-modal-target="agotadoModal-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        data-modal-toggle="agotadoModal-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 hover:underline flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                                                        </svg>
                                                        Ver observaciones
                                                    </button>
                                                @endif
                                            @endrole
                                        </td>
                                    </tr>
                                @endforeach
                                
                            </tbody>
                        </table>

                        <!-- Paginación -->
                        <div class="px-6 py-3">
                            {{ $librosAsignatura->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pestaña de Proyecto -->
            <!-- Pestaña de Proyecto -->
            <div class="hidden" id="proyecto" role="tabpanel" aria-labelledby="proyecto-tab">
                <!-- Contador de resultados y chips de filtros activos -->
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    <span class="text-gray-600 dark:text-gray-300">
                        <span class="font-medium">{{ $librosProyecto->total() }}</span> solicitudes para proyectos
                        encontradas
                    </span>

                    @if (request('estado'))
                        <div class="flex items-center gap-1">
                            <span
                                class="text-xs font-medium px-2.5 py-0.5 rounded
                    {{ request('estado') == 'Pendiente Aceptación' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : '' }}
                    {{ request('estado') == 'Aceptado' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : '' }}
                    {{ request('estado') == 'Denegado' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : '' }}
                    {{ request('estado') == 'Recibido' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : '' }}
                    {{ request('estado') == 'Biblioteca' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' : '' }}
                    {{ request('estado') == 'Agotado/Descatalogado' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300' : '' }}">
                                Filtrado por: {{ request('estado') }}
                            </span>
                            <a href="{{ request()->url() }}?{{ http_build_query(request()->except('estado')) }}"
                                class="text-gray-500 hover:text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        </div>
                    @endif

                    @if (request('search'))
                        <div class="flex items-center gap-1">
                            <span
                                class="bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 text-xs font-medium px-2.5 py-0.5 rounded">
                                Búsqueda: "{{ request('search') }}"
                            </span>
                            <a href="{{ request()->url() }}?{{ http_build_query(request()->except('search')) }}"
                                class="text-gray-500 hover:text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Tabla de libros para proyecto -->
                <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                    @if ($librosProyecto->isEmpty())
                        <div class="bg-gray-50 dark:bg-gray-800 p-10 text-center rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 text-lg">No se encontraron solicitudes de libros
                                para
                                proyectos{{ request('search') ? ' con el término "' . request('search') . '"' : '' }}{{ request('estado') ? ' en estado "' . request('estado') . '"' : '' }}.
                            </p>
                            @if (request('search') || request('estado'))
                                <a href="{{ route('libros.index') }}#proyecto-tab"
                                    class="mt-3 inline-block text-indigo-600 hover:underline">Ver todas las
                                    solicitudes</a>
                            @endif
                        </div>
                    @else
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Libro</th>
                                    <th scope="col" class="px-6 py-3">Autor</th>
                                    <th scope="col" class="px-6 py-3">ISBN</th>
                                    <th scope="col" class="px-6 py-3">Proyecto</th>
                                    <th scope="col" class="px-6 py-3">Solicitante</th>
                                    <th scope="col" class="px-6 py-3">Fecha</th>
                                    <th scope="col" class="px-6 py-3">Estado</th>
                                    <th scope="col" class="px-6 py-3">Precio</th>
                                    <th scope="col" class="px-6 py-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($librosProyecto as $solicitud)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ $solicitud->libro->titulo }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $solicitud->libro->autor }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $solicitud->libro->isbn }}
                                        </td>                                        <td class="px-6 py-4">
                                            <span
                                                class="bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300 text-xs font-medium px-2.5 py-0.5 rounded">
                                                {{ $solicitud->proyecto->titulo ?? 'Sin especificar' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $solicitud->usuario->nombre }} {{ $solicitud->usuario->apellidos }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $solicitud->fecha_solicitud->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $estadoClasses = '';
                                                switch($solicitud->estado) {
                                                    case 'Pendiente Aceptación':
                                                        $estadoClasses = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                                                        break;
                                                    case 'Aceptado':
                                                        $estadoClasses = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                                                        break;
                                                    case 'Denegado':
                                                        $estadoClasses = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                                        break;
                                                    case 'Recibido':
                                                        $estadoClasses = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
                                                        break;
                                                    case 'Biblioteca':
                                                        $estadoClasses = 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300';
                                                        break;
                                                    case 'Agotado/Descatalogado':
                                                        $estadoClasses = 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
                                                        break;
                                                }
                                            @endphp
                                            <span class="text-xs font-medium px-2.5 py-0.5 rounded {{ $estadoClasses }}">
                                                {{ $solicitud->estado }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $solicitud->precio }}€
                                            <span
                                                class="text-xs text-gray-500 dark:text-gray-400">({{ $solicitud->num_ejemplares }}
                                                uds.)</span>
                                        </td>
                                        <td class="px-6 py-4 flex space-x-2">
                                            <button type="button"
                                                data-modal-target="detalleModalProyecto-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                data-modal-toggle="detalleModalProyecto-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 hover:underline flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Ver
                                            </button>
                                        
                                            @role('admin|secretario')
                                                @if ($solicitud->estado == 'Pendiente Aceptación')
                                                    <form
                                                        action="{{ route('libros.aprobar', [$solicitud->id_libro, $solicitud->id_usuario, $solicitud->fecha_solicitud->format('Y-m-d')]) }}"
                                                        method="POST" class="inline aprobar-form">
                                                        @csrf
                                                        <button type="submit"
                                                            class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 hover:underline flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            Aprobar
                                                        </button>
                                                    </form>
                                        
                                                    <button type="button"
                                                        data-modal-target="denegarModal-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        data-modal-toggle="denegarModal-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        class="text-rose-600 hover:text-rose-800 dark:text-rose-400 dark:hover:text-rose-300 hover:underline flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Denegar
                                                    </button>
                                                @endif
                                            @endrole                                            @if ($solicitud->estado == 'Aceptado')
                                                @role('admin|secretario')
                                                    <form
                                                        action="{{ route('libros.biblioteca', [$solicitud->id_libro, $solicitud->id_usuario, $solicitud->fecha_solicitud->format('Y-m-d')]) }}"
                                                        method="POST" class="inline biblioteca-form">
                                                        @csrf
                                                        <button type="submit"
                                                            class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300 hover:underline flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                            </svg>
                                                            Biblioteca
                                                        </button>
                                                    </form>

                                                    <button type="button"
                                                        data-modal-target="agotadoModalProyecto-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        data-modal-toggle="agotadoModalProyecto-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 hover:underline flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                                                        </svg>
                                                        Agotado/Descatalogado
                                                    </button>
                                                @endrole
                                            @endif

                                            @role('admin|secretario')
                                                @if ($solicitud->estado == 'Biblioteca')
                                                    <form
                                                        action="{{ route('libros.recibir', [$solicitud->id_libro, $solicitud->id_usuario, $solicitud->fecha_solicitud->format('Y-m-d')]) }}"
                                                        method="POST" class="inline recibir-form">
                                                        @csrf
                                                        <button type="submit"
                                                            class="text-cyan-600 hover:text-cyan-800 dark:text-cyan-400 dark:hover:text-cyan-300 hover:underline flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                                            </svg>
                                                            Recibido
                                                        </button>
                                                    </form>
                                                @endif
                                            @endrole

                                            @role('admin|secretario')
                                                @if ($solicitud->estado == 'Agotado/Descatalogado')
                                                    <button type="button"
                                                        data-modal-target="agotadoModalProyecto-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        data-modal-toggle="agotadoModalProyecto-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 hover:underline flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                                                        </svg>
                                                        Ver observaciones
                                                    </button>
                                                @endif
                                            @endrole
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Paginación -->
                        <div class="px-6 py-3">
                            {{ $librosProyecto->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>

            
            <!-- Pestaña de Grupo de Investigación -->
            <div class="hidden" id="Grupo" role="tabpanel" aria-labelledby="Grupo-tab">
                <!-- Contador de resultados y chips de filtros activos -->
                <div class="flex flex-wrap items-center gap-3 mb-4">                    <span class="text-gray-600 dark:text-gray-300">
                        <span class="font-medium">{{ $librosGrupo->total() }}</span> solicitudes para grupos de investigación
                        encontradas
                    </span>

                    @if (request('estado'))
                        <div class="flex items-center gap-1">
                            @php
                                $estadoClasses = '';
                                switch(request('estado')) {
                                    case 'Pendiente Aceptación':
                                        $estadoClasses = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                                        break;
                                    case 'Aceptado':
                                        $estadoClasses = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                                        break;
                                    case 'Denegado':
                                        $estadoClasses = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                        break;
                                    case 'Recibido':
                                        $estadoClasses = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
                                        break;
                                    case 'Biblioteca':
                                        $estadoClasses = 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300';
                                        break;
                                    case 'Agotado/Descatalogado':
                                        $estadoClasses = 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
                                        break;
                                }
                            @endphp
                            <span class="text-xs font-medium px-2.5 py-0.5 rounded {{ $estadoClasses }}">
                                Filtrado por: {{ request('estado') }}
                            </span>
                            <a href="{{ request()->url() }}?{{ http_build_query(request()->except('estado')) }}"
                                class="text-gray-500 hover:text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        </div>
                    @endif

                    @if (request('search'))
                        <div class="flex items-center gap-1">
                            <span
                                class="bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 text-xs font-medium px-2.5 py-0.5 rounded">
                                Búsqueda: "{{ request('search') }}"
                            </span>
                            <a href="{{ request()->url() }}?{{ http_build_query(request()->except('search')) }}"
                                class="text-gray-500 hover:text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Tabla de libros para grupos de investigación -->
                <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                    @if ($librosGrupo->isEmpty())
                        <div class="bg-gray-50 dark:bg-gray-800 p-10 text-center rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 text-lg">No se encontraron solicitudes de libros
                                para grupos de investigación.</p>
                            @if (request('search') || request('estado'))
                                <a href="{{ route('libros.index') }}#Grupo-tab"
                                    class="mt-3 inline-block text-indigo-600 hover:underline">Ver todas las
                                    solicitudes</a>
                            @endif
                        </div>
                    @else
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Libro</th>
                                    <th scope="col" class="px-6 py-3">Autor</th>
                                    <th scope="col" class="px-6 py-3">ISBN</th>
                                    <th scope="col" class="px-6 py-3">Grupo de Investigación</th>
                                    <th scope="col" class="px-6 py-3">Solicitante</th>
                                    <th scope="col" class="px-6 py-3">Fecha</th>
                                    <th scope="col" class="px-6 py-3">Estado</th>
                                    <th scope="col" class="px-6 py-3">Precio</th>
                                    <th scope="col" class="px-6 py-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($librosGrupo as $solicitud)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ $solicitud->libro->titulo }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $solicitud->libro->autor }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $solicitud->libro->isbn }}
                                        </td>                                        <td class="px-6 py-4">
                                            <span
                                                class="bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-300 text-xs font-medium px-2.5 py-0.5 rounded">
                                                {{ $solicitud->grupo->nombre_grupo ?? 'Sin especificar' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $solicitud->usuario->nombre }} {{ $solicitud->usuario->apellidos }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $solicitud->fecha_solicitud->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $estadoClasses = '';
                                                switch($solicitud->estado) {
                                                    case 'Pendiente Aceptación':
                                                        $estadoClasses = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                                                        break;
                                                    case 'Aceptado':
                                                        $estadoClasses = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                                                        break;
                                                    case 'Denegado':
                                                        $estadoClasses = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                                        break;
                                                    case 'Recibido':
                                                        $estadoClasses = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
                                                        break;
                                                    case 'Biblioteca':
                                                        $estadoClasses = 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300';
                                                        break;
                                                    case 'Agotado/Descatalogado':
                                                        $estadoClasses = 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
                                                        break;
                                                }
                                            @endphp
                                            <span class="text-xs font-medium px-2.5 py-0.5 rounded {{ $estadoClasses }}">
                                                {{ $solicitud->estado }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $solicitud->precio }}€
                                            <span
                                                class="text-xs text-gray-500 dark:text-gray-400">({{ $solicitud->num_ejemplares }}
                                                uds.)</span>
                                        </td>
                                        <td class="px-6 py-4 flex space-x-2">
                                            <button type="button"
                                                data-modal-target="detalleModalGrupo-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                data-modal-toggle="detalleModalGrupo-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 hover:underline flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Ver
                                            </button>
                                        
                                            @role('admin|secretario')
                                                @if ($solicitud->estado == 'Pendiente Aceptación')
                                                    <form
                                                        action="{{ route('libros.aprobar', [$solicitud->id_libro, $solicitud->id_usuario, $solicitud->fecha_solicitud->format('Y-m-d')]) }}"
                                                        method="POST" class="inline aprobar-form">
                                                        @csrf
                                                        <button type="submit"
                                                            class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 hover:underline flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            Aprobar
                                                        </button>
                                                    </form>
                                        
                                                    <button type="button"
                                                        data-modal-target="denegarModal-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        data-modal-toggle="denegarModal-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        class="text-rose-600 hover:text-rose-800 dark:text-rose-400 dark:hover:text-rose-300 hover:underline flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Denegar
                                                    </button>
                                                @endif
                                            @endrole
                                        
                                            @if ($solicitud->estado == 'Aceptado')
                                                @role('admin|secretario')
                                                    <form
                                                        action="{{ route('libros.biblioteca', [$solicitud->id_libro, $solicitud->id_usuario, $solicitud->fecha_solicitud->format('Y-m-d')]) }}"
                                                        method="POST" class="inline biblioteca-form">
                                                        @csrf
                                                        <button type="submit"
                                                            class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300 hover:underline flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                            </svg>
                                                            Biblioteca
                                                        </button>
                                                    </form>
                        
                                                    <button type="button"
                                                        data-modal-target="agotadoModalGrupo-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        data-modal-toggle="agotadoModalGrupo-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 hover:underline flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                                                        </svg>
                                                        Agotado/Descatalogado
                                                    </button>
                                                @endrole
                                            @endif
                        
                                            @if ($solicitud->estado == 'Biblioteca')
                                                @role('admin|secretario')
                                                    <form
                                                        action="{{ route('libros.recibir', [$solicitud->id_libro, $solicitud->id_usuario, $solicitud->fecha_solicitud->format('Y-m-d')]) }}"
                                                        method="POST" class="inline recibir-form">
                                                        @csrf
                                                        <button type="submit"
                                                            class="text-cyan-600 hover:text-cyan-800 dark:text-cyan-400 dark:hover:text-cyan-300 hover:underline flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                                            </svg>
                                                            Recibido
                                                        </button>
                                                    </form>
                                                @endrole
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Paginación -->
                        <div class="px-6 py-3">
                            {{ $librosGrupo->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pestaña de Posgrado -->
            <div class="hidden" id="posgrado" role="tabpanel" aria-labelledby="posgrado-tab">
                <!-- Contador de resultados y chips de filtros activos -->
                <div class="flex flex-wrap items-center gap-3 mb-4">                    <span class="text-gray-600 dark:text-gray-300">
                        <span class="font-medium">{{ $librosPosgrado->total() }}</span> solicitudes para posgrado
                        encontradas
                    </span>

                    @if (request('estado'))
                        <div class="flex items-center gap-1">
                            @php
                                $estadoClasses = '';
                                switch(request('estado')) {
                                    case 'Pendiente Aceptación':
                                        $estadoClasses = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                                        break;
                                    case 'Aceptado':
                                        $estadoClasses = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                                        break;
                                    case 'Denegado':
                                        $estadoClasses = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                        break;
                                    case 'Recibido':
                                        $estadoClasses = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
                                        break;
                                    case 'Biblioteca':
                                        $estadoClasses = 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300';
                                        break;
                                    case 'Agotado/Descatalogado':
                                        $estadoClasses = 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
                                        break;
                                }
                            @endphp
                            <span class="text-xs font-medium px-2.5 py-0.5 rounded {{ $estadoClasses }}">
                                Filtrado por: {{ request('estado') }}
                            </span>
                            <a href="{{ request()->url() }}?{{ http_build_query(request()->except('estado')) }}"
                                class="text-gray-500 hover:text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        </div>
                    @endif

                    @if (request('search'))
                        <div class="flex items-center gap-1">
                            <span
                                class="bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 text-xs font-medium px-2.5 py-0.5 rounded">
                                Búsqueda: "{{ request('search') }}"
                            </span>
                            <a href="{{ request()->url() }}?{{ http_build_query(request()->except('search')) }}"
                                class="text-gray-500 hover:text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Tabla de libros para posgrado -->
                <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                    @if ($librosPosgrado->isEmpty())
                        <div class="bg-gray-50 dark:bg-gray-800 p-10 text-center rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 text-lg">No se encontraron solicitudes de libros
                                para posgrado.</p>
                            @if (request('search') || request('estado'))
                                <a href="{{ route('libros.index') }}#posgrado-tab"
                                    class="mt-3 inline-block text-indigo-600 hover:underline">Ver todas las
                                    solicitudes</a>
                            @endif
                        </div>
                    @else
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Libro</th>
                                    <th scope="col" class="px-6 py-3">Autor</th>
                                    <th scope="col" class="px-6 py-3">ISBN</th>
                                    <th scope="col" class="px-6 py-3">Posgrado</th>
                                    <th scope="col" class="px-6 py-3">Solicitante</th>
                                    <th scope="col" class="px-6 py-3">Fecha</th>
                                    <th scope="col" class="px-6 py-3">Estado</th>
                                    <th scope="col" class="px-6 py-3">Precio</th>
                                    <th scope="col" class="px-6 py-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($librosPosgrado as $solicitud)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ $solicitud->libro->titulo }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $solicitud->libro->autor }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $solicitud->libro->isbn }}
                                        </td>                                        <td class="px-6 py-4">
                                            <span
                                                class="bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-300 text-xs font-medium px-2.5 py-0.5 rounded">
                                                {{ $solicitud->posgrado->nombre ?? 'Sin especificar' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $solicitud->usuario->nombre }} {{ $solicitud->usuario->apellidos }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $solicitud->fecha_solicitud->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $estadoClasses = '';
                                                switch($solicitud->estado) {
                                                    case 'Pendiente Aceptación':
                                                        $estadoClasses = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                                                        break;
                                                    case 'Aceptado':
                                                        $estadoClasses = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                                                        break;
                                                    case 'Denegado':
                                                        $estadoClasses = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                                        break;
                                                    case 'Recibido':
                                                        $estadoClasses = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
                                                        break;
                                                    case 'Biblioteca':
                                                        $estadoClasses = 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300';
                                                        break;
                                                    case 'Agotado/Descatalogado':
                                                        $estadoClasses = 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
                                                        break;
                                                }
                                            @endphp
                                            <span class="text-xs font-medium px-2.5 py-0.5 rounded {{ $estadoClasses }}">
                                                {{ $solicitud->estado }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $solicitud->precio }}€
                                            <span
                                                class="text-xs text-gray-500 dark:text-gray-400">({{ $solicitud->num_ejemplares }}
                                                uds.)</span>
                                        </td>
                                        <td class="px-6 py-4 flex space-x-2">
                                            <button type="button"
                                                data-modal-target="detalleModalPosgrado-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"

                                                data-modal-toggle="detalleModalPosgrado-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"

                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 hover:underline flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Ver
                                            </button>
                                        
                                            @role('admin|secretario')
                                                @if ($solicitud->estado == 'Pendiente Aceptación')
                                                    <form
                                                        action="{{ route('libros.aprobar', [$solicitud->id_libro, $solicitud->id_usuario, $solicitud->fecha_solicitud->format('Y-m-d')]) }}"
                                                        method="POST" class="inline aprobar-form">
                                                        @csrf
                                                        <button type="submit"
                                                            class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 hover:underline flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            Aprobar
                                                        </button>
                                                    </form>
                                                
                                                    <button type="button"
                                                        data-modal-target="denegarModal-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        data-modal-toggle="denegarModal-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        class="text-rose-600 hover:text-rose-800 dark:text-rose-400 dark:hover:text-rose-300 hover:underline flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Denegar
                                                    </button>
                                                @endif
                                            @endrole
                                        
                                            @if ($solicitud->estado == 'Aceptado')
                                                @role('admin|secretario')
                                                    <form
                                                        action="{{ route('libros.biblioteca', [$solicitud->id_libro, $solicitud->id_usuario, $solicitud->fecha_solicitud->format('Y-m-d')]) }}"
                                                        method="POST" class="inline biblioteca-form">
                                                        @csrf
                                                        <button type="submit"
                                                            class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300 hover:underline flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                            </svg>
                                                            Biblioteca
                                                        </button>
                                                    </form>
                        
                                                    <button type="button"
                                                        data-modal-target="agotadoModalPosgrado-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        data-modal-toggle="agotadoModalPosgrado-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 hover:underline flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                                                        </svg>
                                                        Agotado/Descatalogado
                                                    </button>
                                                @endrole
                                            @endif
                        
                                            @if ($solicitud->estado == 'Biblioteca')
                                                @role('admin|secretario')
                                                    <form
                                                        action="{{ route('libros.recibir', [$solicitud->id_libro, $solicitud->id_usuario, $solicitud->fecha_solicitud->format('Y-m-d')]) }}"
                                                        method="POST" class="inline recibir-form">
                                                        @csrf
                                                        <button type="submit"
                                                            class="text-cyan-600 hover:text-cyan-800 dark:text-cyan-400 dark:hover:text-cyan-300 hover:underline flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                                            </svg>
                                                            Recibido
                                                        </button>
                                                    </form>
                                                @endrole
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Paginación -->
                        <div class="px-6 py-3">
                            {{ $librosPosgrado->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pestaña de Otros -->
            <div class="hidden" id="otros" role="tabpanel" aria-labelledby="otros-tab">
                <!-- Contador de resultados y chips de filtros activos -->
                <div class="flex flex-wrap items-center gap-3 mb-4">                    <span class="text-gray-600 dark:text-gray-300">
                        <span class="font-medium">{{ $librosOtros->total() }}</span> solicitudes para otros motivos
                        encontradas
                    </span>

                    @if (request('estado'))
                        <div class="flex items-center gap-1">
                            @php
                                $estadoClasses = '';
                                switch(request('estado')) {
                                    case 'Pendiente Aceptación':
                                        $estadoClasses = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                                        break;
                                    case 'Aceptado':
                                        $estadoClasses = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                                        break;
                                    case 'Denegado':
                                        $estadoClasses = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                        break;
                                    case 'Recibido':
                                        $estadoClasses = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
                                        break;
                                    case 'Biblioteca':
                                        $estadoClasses = 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300';
                                        break;
                                    case 'Agotado/Descatalogado':
                                        $estadoClasses = 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
                                        break;
                                }
                            @endphp
                            <span class="text-xs font-medium px-2.5 py-0.5 rounded {{ $estadoClasses }}">
                                Filtrado por: {{ request('estado') }}
                            </span>
                            <a href="{{ request()->url() }}?{{ http_build_query(request()->except('estado')) }}"
                                class="text-gray-500 hover:text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        </div>
                    @endif

                    @if (request('search'))
                        <div class="flex items-center gap-1">
                            <span
                                class="bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 text-xs font-medium px-2.5 py-0.5 rounded">
                                Búsqueda: "{{ request('search') }}"
                            </span>
                            <a href="{{ request()->url() }}?{{ http_build_query(request()->except('search')) }}"
                                class="text-gray-500 hover:text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Tabla de libros para otros fondos -->
                <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                    @if ($librosOtros->isEmpty())
                        <div class="bg-gray-50 dark:bg-gray-800 p-10 text-center rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 text-lg">No se encontraron solicitudes de libros
                                para otros fondos.</p>
                            @if (request('search') || request('estado'))
                                <a href="{{ route('libros.index') }}#otros-tab"
                                    class="mt-3 inline-block text-indigo-600 hover:underline">Ver todas las
                                    solicitudes</a>
                            @endif
                        </div>
                    @else
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Libro</th>
                                    <th scope="col" class="px-6 py-3">Autor</th>
                                    <th scope="col" class="px-6 py-3">ISBN</th>
                                    <th scope="col" class="px-6 py-3">Solicitante</th>
                                    <th scope="col" class="px-6 py-3">Fecha</th>
                                    <th scope="col" class="px-6 py-3">Estado</th>
                                    <th scope="col" class="px-6 py-3">Precio</th>
                                    <th scope="col" class="px-6 py-3">Acciones</th>
                                </tr>
                            </thead>                            <tbody>
                                @foreach ($librosOtros as $solicitud)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ $solicitud->libro->titulo }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $solicitud->libro->autor }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $solicitud->libro->isbn }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $solicitud->usuario->nombre }} {{ $solicitud->usuario->apellidos }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $solicitud->fecha_solicitud->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4">                                            @php
                                                switch($solicitud->estado) {
                                                    case 'Pendiente Aceptación':
                                                        $classColor = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                                                        break;
                                                    case 'Aceptado':
                                                        $classColor = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                                                        break;
                                                    case 'Denegado':
                                                        $classColor = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                                        break;
                                                    case 'Recibido':
                                                        $classColor = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
                                                        break;
                                                    case 'Biblioteca':
                                                        $classColor = 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300';
                                                        break;                                                    case 'Agotado/Descatalogado':
                                                        $classColor = 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
                                                        break;
                                                    default:
                                                        $classColor = 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
                                                }
                                            @endphp
                                            <span class="text-xs font-medium px-2.5 py-0.5 rounded {{ $classColor }}">
                                                {{ $solicitud->estado }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $solicitud->precio }}€
                                            <span
                                                class="text-xs text-gray-500 dark:text-gray-400">({{ $solicitud->num_ejemplares }}
                                                uds.)</span>
                                        </td>
                                        <td class="px-6 py-4 flex space-x-2">
                                            <button type="button"
                                                data-modal-target="detalleModalOtros-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"

                                                data-modal-toggle="detalleModalOtros-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"

                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 hover:underline flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Ver
                                            </button>
                                        
                                            @role('admin|secretario')
                                                @if ($solicitud->estado == 'Pendiente Aceptación')
                                                    <form
                                                        action="{{ route('libros.aprobar', [$solicitud->id_libro, $solicitud->id_usuario, $solicitud->fecha_solicitud->format('Y-m-d')]) }}"
                                                        method="POST" class="inline aprobar-form">
                                                        @csrf
                                                        <button type="submit"
                                                            class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 hover:underline flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            Aprobar
                                                        </button>
                                                    </form>
                                                
                                                    <button type="button"
                                                        data-modal-target="denegarModalOtros-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        data-modal-toggle="denegarModalOtros-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        class="text-rose-600 hover:text-rose-800 dark:text-rose-400 dark:hover:text-rose-300 hover:underline flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Denegar
                                                    </button>
                                                @endif
                                            @endrole                                            @if ($solicitud->estado == 'Aceptado')
                                                @role('admin|secretario')
                                                    <form
                                                        action="{{ route('libros.biblioteca', [$solicitud->id_libro, $solicitud->id_usuario, $solicitud->fecha_solicitud->format('Y-m-d')]) }}"
                                                        method="POST" class="inline biblioteca-form">
                                                        @csrf
                                                        <button type="submit"
                                                            class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300 hover:underline flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                            </svg>
                                                            Biblioteca
                                                        </button>
                                                    </form>
                                                    
                                                    <button type="button"
                                                        data-modal-target="agotadoModalOtros-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        data-modal-toggle="agotadoModalOtros-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 hover:underline flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                                                        </svg>
                                                        Agotado/Descatalogado
                                                    </button>
                                                @endrole
                                            @endif
                                            
                                            @if ($solicitud->estado == 'Biblioteca')
                                                @role('admin|secretario')
                                                    <form
                                                        action="{{ route('libros.recibir', [$solicitud->id_libro, $solicitud->id_usuario, $solicitud->fecha_solicitud->format('Y-m-d')]) }}"
                                                        method="POST" class="inline recibir-form">
                                                        @csrf
                                                        <button type="submit"
                                                            class="text-cyan-600 hover:text-cyan-800 dark:text-cyan-400 dark:hover:text-cyan-300 hover:underline flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                                            </svg>
                                                            Recibido
                                                        </button>
                                                    </form>
                                                @endrole
                                            @endif                                            @role('admin|secretario')
                                                @if ($solicitud->estado == 'Agotado/Descatalogado')
                                                    <button type="button"
                                                        data-modal-target="agotadoModalOtros-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        data-modal-toggle="agotadoModalOtros-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                                        class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 hover:underline flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                                                        </svg>
                                                        Agotado
                                                    </button>
                                                @endif
                                            @endrole
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Paginación -->
                        <div class="px-6 py-3">
                            {{ $librosOtros->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Modales para los libros de otros fondos -->
            @foreach ($librosOtros as $solicitud)
                <!-- Modal de detalle para libros de otros fondos -->
                <div id="detalleModalOtros-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"

                    tabindex="-1" aria-hidden="true"
                    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative w-full max-w-2xl max-h-full">
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Detalles de la Solicitud
                                </h3>
                                <button type="button"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                    data-modal-hide="detalleModalOtros-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="p-6 space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Información del Libro</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                            <span class="font-semibold">Título:</span> {{ $solicitud->libro->titulo }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Autor:</span> {{ $solicitud->libro->autor }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">ISBN:</span> {{ $solicitud->libro->isbn }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Editorial:</span> {{ $solicitud->libro->editorial }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Precio:</span> {{ $solicitud->precio }}€
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Ejemplares:</span> {{ $solicitud->num_ejemplares }}
                                        </p>
                                    </div>

                                    <div>
                                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Detalles de la Solicitud</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                            <span class="font-semibold">Tipo de Fondo:</span>
                                            {{ $solicitud->tipo_fondo ?? 'No especificado' }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Código/Referencia:</span>
                                            {{ $solicitud->referencia_fondo ?? 'No especificado' }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Solicitante:</span> {{ $solicitud->usuario->nombre }}
                                            {{ $solicitud->usuario->apellidos }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Fecha de solicitud:</span>
                                            {{ $solicitud->fecha_solicitud->format('d/m/Y') }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Estado:</span>
                                            <span
                                                class="text-xs font-medium px-2.5 py-0.5 rounded
                                                @if ($solicitud->estado == 'Pendiente Aceptación') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                                @elseif($solicitud->estado == 'Aceptado') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                                @elseif($solicitud->estado == 'Denegado') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                                @elseif($solicitud->estado == 'Recibido') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 @endif">
                                                {{ $solicitud->estado }}
                                            </span>
                                        </p>
                                        @if ($solicitud->justificacion)
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                <span class="font-semibold">Justificación:</span>
                                                {{ $solicitud->justificacion }}
                                            </p>
                                        @endif
                                        @if ($solicitud->observaciones)
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                <span class="font-semibold">Observaciones:</span>
                                                {{ $solicitud->observaciones }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-end p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <button
                                    data-modal-hide="detalleModalOtros-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                    type="button"
                                    class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal de denegar para libros de otros fondos (solo si es necesario) -->
                @if ($esDirector && $solicitud->estado == 'Pendiente Aceptación')
                    <div id="denegarModalOtros-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"

                        tabindex="-1" aria-hidden="true"
                        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative w-full max-w-md max-h-full">
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                        Denegar Solicitud
                                    </h3>
                                    <button type="button"
                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                        data-modal-hide="denegarModalOtros-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                    </svg>
                                    </button>
                                </div>
                                <form
                                    action="{{ route('libros.denegar', [$solicitud->id_libro, $solicitud->id_usuario, $solicitud->fecha_solicitud->format('Y-m-d')]) }}"
                                    method="POST">
                                    @csrf
                                    <div class="p-6 space-y-6">
                                        <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                            Por favor, indique el motivo por el que deniega esta solicitud:
                                        </p>
                                        <textarea name="observaciones" rows="3"
                                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                            placeholder="Motivo de denegación..."></textarea>
                                    </div>
                                    <div
                                        class="flex items-center justify-end p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                                        <button
                                            data-modal-hide="denegarModalOtros-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                            type="button"
                                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancelar</button>
                                        <button type="submit"
                                            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">Denegar
                                            solicitud</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Modales para marcar como agotado/descatalogado -->
@foreach ($librosAsignatura as $solicitud)
<!-- Modal de detalle para libros de otros fondos -->
                <div id="detalleModalAsignatura-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"

                    tabindex="-1" aria-hidden="true"
                    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative w-full max-w-2xl max-h-full">
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Detalles de la Solicitud
                                </h3>
                                <button type="button"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                    data-modal-hide="detalleModalAsignatura-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="p-6 space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Información del Libro</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                            <span class="font-semibold">Título:</span> {{ $solicitud->libro->titulo }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Autor:</span> {{ $solicitud->libro->autor }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">ISBN:</span> {{ $solicitud->libro->isbn }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Editorial:</span> {{ $solicitud->libro->editorial }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Precio:</span> {{ $solicitud->precio }}€
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Ejemplares:</span> {{ $solicitud->num_ejemplares }}
                                        </p>
                                    </div>

                                    <div>
                                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Detalles de la Solicitud</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                            <span class="font-semibold">Tipo de Fondo:</span>
                                            {{ $solicitud->tipo_fondo ?? 'No especificado' }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Código/Referencia:</span>
                                            {{ $solicitud->referencia_fondo ?? 'No especificado' }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Solicitante:</span> {{ $solicitud->usuario->nombre }}
                                            {{ $solicitud->usuario->apellidos }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Fecha de solicitud:</span>
                                            {{ $solicitud->fecha_solicitud->format('d/m/Y') }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Estado:</span>
                                            <span
                                                class="text-xs font-medium px-2.5 py-0.5 rounded
                                                @if ($solicitud->estado == 'Pendiente Aceptación') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                                @elseif($solicitud->estado == 'Aceptado') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                                @elseif($solicitud->estado == 'Denegado') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                                @elseif($solicitud->estado == 'Recibido') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 @endif">
                                                {{ $solicitud->estado }}
                                            </span>
                                        </p>
                                        @if ($solicitud->justificacion)
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                <span class="font-semibold">Justificación:</span>
                                                {{ $solicitud->justificacion }}
                                            </p>
                                        @endif
                                        @if ($solicitud->observaciones)
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                <span class="font-semibold">Observaciones:</span>
                                                {{ $solicitud->observaciones }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-end p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <button
                                    data-modal-hide="detalleModalAsignatura-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                    type="button"
                                    class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
    <!-- Modal para marcar como agotado/descatalogado - Asignatura -->
    <div id="agotadoModal-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}" 
         tabindex="-1" aria-hidden="true" 
         class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button" 
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" 
                        data-modal-hide="agotadoModal-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Cerrar modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                        ¿Está seguro de que desea marcar este libro como agotado/descatalogado?
                    </h3>
                    
                    <form action="{{ route('libros.agotado', [$solicitud->id_libro, $solicitud->id_usuario, $solicitud->fecha_solicitud->format('Y-m-d')]) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="observaciones-agotado-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}" 
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Observaciones (opcional)
                            </label>
                            <textarea id="observaciones-agotado-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}" 
                                      name="observaciones" 
                                      rows="3" 
                                      class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                      placeholder="Motivo del agotamiento o descatalogación..."></textarea>
                        </div>
                        
                        <button type="submit" 
                                class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center me-2">
                            Sí, marcar como agotado
                        </button>
                        <button data-modal-hide="agotadoModal-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}" 
                                type="button" 
                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                            Cancelar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@foreach ($librosProyecto as $solicitud)
<!-- Modal de detalle para libros de otros fondos -->
                <div id="detalleModalProyecto-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"

                    tabindex="-1" aria-hidden="true"
                    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative w-full max-w-2xl max-h-full">
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Detalles de la Solicitud
                                </h3>
                                <button type="button"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                    data-modal-hide="detalleModalProyecto-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="p-6 space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Información del Libro</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                            <span class="font-semibold">Título:</span> {{ $solicitud->libro->titulo }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Autor:</span> {{ $solicitud->libro->autor }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">ISBN:</span> {{ $solicitud->libro->isbn }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Editorial:</span> {{ $solicitud->libro->editorial }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Precio:</span> {{ $solicitud->precio }}€
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Ejemplares:</span> {{ $solicitud->num_ejemplares }}
                                        </p>
                                    </div>

                                    <div>
                                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Detalles de la Solicitud</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                            <span class="font-semibold">Tipo de Fondo:</span>
                                            {{ $solicitud->tipo_fondo ?? 'No especificado' }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Código/Referencia:</span>
                                            {{ $solicitud->referencia_fondo ?? 'No especificado' }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Solicitante:</span> {{ $solicitud->usuario->nombre }}
                                            {{ $solicitud->usuario->apellidos }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Fecha de solicitud:</span>
                                            {{ $solicitud->fecha_solicitud->format('d/m/Y') }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Estado:</span>
                                            <span
                                                class="text-xs font-medium px-2.5 py-0.5 rounded
                                                @if ($solicitud->estado == 'Pendiente Aceptación') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                                @elseif($solicitud->estado == 'Aceptado') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                                @elseif($solicitud->estado == 'Denegado') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                                @elseif($solicitud->estado == 'Recibido') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 @endif">
                                                {{ $solicitud->estado }}
                                            </span>
                                        </p>
                                        @if ($solicitud->justificacion)
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                <span class="font-semibold">Justificación:</span>
                                                {{ $solicitud->justificacion }}
                                            </p>
                                        @endif
                                        @if ($solicitud->observaciones)
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                <span class="font-semibold">Observaciones:</span>
                                                {{ $solicitud->observaciones }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-end p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <button
                                    data-modal-hide="detalleModalProyecto-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                    type="button"
                                    class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
    <!-- Modal para marcar como agotado/descatalogado - Proyecto -->
    <div id="agotadoModalProyecto-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}" 
         tabindex="-1" aria-hidden="true" 
         class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button" 
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" 
                        data-modal-hide="agotadoModalProyecto-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Cerrar modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                        ¿Está seguro de que desea marcar este libro como agotado/descatalogado?
                    </h3>
                    
                    <form action="{{ route('libros.agotado', [$solicitud->id_libro, $solicitud->id_usuario, $solicitud->fecha_solicitud->format('Y-m-d')]) }}?tipo=proyecto" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="observaciones-agotado-proyecto-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}" 
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Observaciones (opcional)
                            </label>
                            <textarea id="observaciones-agotado-proyecto-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}" 
                                      name="observaciones" 
                                      rows="3" 
                                      class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                      placeholder="Motivo del agotamiento o descatalogación..."></textarea>
                        </div>
                        
                        <button type="submit" 
                                class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center me-2">
                            Sí, marcar como agotado
                        </button>
                        <button data-modal-hide="agotadoModalProyecto-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}" 
                                type="button" 
                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                            Cancelar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@foreach ($librosGrupo as $solicitud)
<!-- Modal de detalle para libros de otros fondos -->
                <div id="detalleModalGrupo-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"

                    tabindex="-1" aria-hidden="true"
                    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative w-full max-w-2xl max-h-full">
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Detalles de la Solicitud
                                </h3>
                                <button type="button"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                    data-modal-hide="detalleModalGrupo-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="p-6 space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Información del Libro</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                            <span class="font-semibold">Título:</span> {{ $solicitud->libro->titulo }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Autor:</span> {{ $solicitud->libro->autor }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">ISBN:</span> {{ $solicitud->libro->isbn }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Editorial:</span> {{ $solicitud->libro->editorial }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Precio:</span> {{ $solicitud->precio }}€
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Ejemplares:</span> {{ $solicitud->num_ejemplares }}
                                        </p>
                                    </div>

                                    <div>
                                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Detalles de la Solicitud</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                            <span class="font-semibold">Tipo de Fondo:</span>
                                            {{ $solicitud->tipo_fondo ?? 'No especificado' }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Código/Referencia:</span>
                                            {{ $solicitud->referencia_fondo ?? 'No especificado' }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Solicitante:</span> {{ $solicitud->usuario->nombre }}
                                            {{ $solicitud->usuario->apellidos }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Fecha de solicitud:</span>
                                            {{ $solicitud->fecha_solicitud->format('d/m/Y') }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Estado:</span>
                                            <span
                                                class="text-xs font-medium px-2.5 py-0.5 rounded
                                                @if ($solicitud->estado == 'Pendiente Aceptación') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                                @elseif($solicitud->estado == 'Aceptado') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                                @elseif($solicitud->estado == 'Denegado') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                                @elseif($solicitud->estado == 'Recibido') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 @endif">
                                                {{ $solicitud->estado }}
                                            </span>
                                        </p>
                                        @if ($solicitud->justificacion)
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                <span class="font-semibold">Justificación:</span>
                                                {{ $solicitud->justificacion }}
                                            </p>
                                        @endif
                                        @if ($solicitud->observaciones)
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                <span class="font-semibold">Observaciones:</span>
                                                {{ $solicitud->observaciones }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-end p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <button
                                    data-modal-hide="detalleModalGrupo-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                    type="button"
                                    class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
    <!-- Modal para marcar como agotado/descatalogado - Grupo -->
    <div id="agotadoModalGrupo-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}" 
         tabindex="-1" aria-hidden="true" 
         class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button" 
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" 
                        data-modal-hide="agotadoModalGrupo-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Cerrar modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                        ¿Está seguro de que desea marcar este libro como agotado/descatalogado?
                    </h3>
                    
                    <form action="{{ route('libros.agotado', [$solicitud->id_libro, $solicitud->id_usuario, $solicitud->fecha_solicitud->format('Y-m-d')]) }}?tipo=grupo" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="observaciones-agotado-grupo-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}" 
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Observaciones (opcional)
                            </label>
                            <textarea id="observaciones-agotado-grupo-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}" 
                                      name="observaciones" 
                                      rows="3" 
                                      class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                      placeholder="Motivo del agotamiento o descatalogación..."></textarea>
                        </div>
                        
                        <button type="submit" 
                                class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center me-2">
                            Sí, marcar como agotado
                        </button>
                        <button data-modal-hide="agotadoModalGrupo-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}" 
                                type="button" 
                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                            Cancelar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@foreach ($librosPosgrado as $solicitud)
<!-- Modal de detalle para libros de otros fondos -->
                <div id="detalleModalPosgrado-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"

                    tabindex="-1" aria-hidden="true"
                    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative w-full max-w-2xl max-h-full">
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Detalles de la Solicitud
                                </h3>
                                <button type="button"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                    data-modal-hide="detalleModalPosgrado-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="p-6 space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Información del Libro</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                            <span class="font-semibold">Título:</span> {{ $solicitud->libro->titulo }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Autor:</span> {{ $solicitud->libro->autor }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">ISBN:</span> {{ $solicitud->libro->isbn }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Editorial:</span> {{ $solicitud->libro->editorial }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Precio:</span> {{ $solicitud->precio }}€
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Ejemplares:</span> {{ $solicitud->num_ejemplares }}
                                        </p>
                                    </div>

                                    <div>
                                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Detalles de la Solicitud</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                            <span class="font-semibold">Tipo de Fondo:</span>
                                            {{ $solicitud->tipo_fondo ?? 'No especificado' }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Código/Referencia:</span>
                                            {{ $solicitud->referencia_fondo ?? 'No especificado' }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Solicitante:</span> {{ $solicitud->usuario->nombre }}
                                            {{ $solicitud->usuario->apellidos }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Fecha de solicitud:</span>
                                            {{ $solicitud->fecha_solicitud->format('d/m/Y') }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="font-semibold">Estado:</span>
                                            <span
                                                class="text-xs font-medium px-2.5 py-0.5 rounded
                                                @if ($solicitud->estado == 'Pendiente Aceptación') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                                @elseif($solicitud->estado == 'Aceptado') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                                @elseif($solicitud->estado == 'Denegado') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                                @elseif($solicitud->estado == 'Recibido') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 @endif">
                                                {{ $solicitud->estado }}
                                            </span>
                                        </p>
                                        @if ($solicitud->justificacion)
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                <span class="font-semibold">Justificación:</span>
                                                {{ $solicitud->justificacion }}
                                            </p>
                                        @endif
                                        @if ($solicitud->observaciones)
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                <span class="font-semibold">Observaciones:</span>
                                                {{ $solicitud->observaciones }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-end p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <button
                                    data-modal-hide="detalleModalPosgrado-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}"
                                    type="button"
                                    class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
    <!-- Modal para marcar como agotado/descatalogado - Posgrado -->
    <div id="agotadoModalPosgrado-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}" 
         tabindex="-1" aria-hidden="true" 
         class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button" 
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" 
                        data-modal-hide="agotadoModalPosgrado-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Cerrar modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                        ¿Está seguro de que desea marcar este libro como agotado/descatalogado?
                    </h3>
                    
                    <form action="{{ route('libros.agotado', [$solicitud->id_libro, $solicitud->id_usuario, $solicitud->fecha_solicitud->format('Y-m-d')]) }}?tipo=posgrado" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="observaciones-agotado-posgrado-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}" 
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Observaciones (opcional)
                            </label>
                            <textarea id="observaciones-agotado-posgrado-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}" 
                                      name="observaciones" 
                                      rows="3" 
                                      class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                      placeholder="Motivo del agotamiento o descatalogación..."></textarea>
                        </div>
                        
                        <button type="submit" 
                                class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center me-2">
                            Sí, marcar como agotado
                        </button>
                        <button data-modal-hide="agotadoModalPosgrado-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}" 
                                type="button" 
                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                            Cancelar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@foreach ($librosOtros as $solicitud)
    <!-- Modal para marcar como agotado/descatalogado - Otros -->
    <div id="agotadoModalOtros-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}" 
         tabindex="-1" aria-hidden="true" 
         class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button" 
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" 
                        data-modal-hide="agotadoModalOtros-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Cerrar modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                        ¿Está seguro de que desea marcar este libro como agotado/descatalogado?
                    </h3>
                    
                    <form action="{{ route('libros.agotado', [$solicitud->id_libro, $solicitud->id_usuario, $solicitud->fecha_solicitud->format('Y-m-d')]) }}?tipo=otros" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="observaciones-agotado-otros-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}" 
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Observaciones (opcional)
                            </label>
                            <textarea id="observaciones-agotado-otros-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}" 
                                      name="observaciones" 
                                      rows="3" 
                                      class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                      placeholder="Motivo del agotamiento o descatalogación..."></textarea>
                        </div>
                        
                        <button type="submit" 
                                class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center me-2">
                            Sí, marcar como agotado
                        </button>
                        <button data-modal-hide="agotadoModalOtros-{{ $solicitud->id_libro }}-{{ $solicitud->id_usuario }}-{{ $solicitud->fecha_solicitud->format('Y-m-d') }}" 
                                type="button" 
                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                            Cancelar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach


    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Inicialización de las pestañas
                const tabElements = [{
                        id: 'asignatura-tab',
                        triggerEl: document.getElementById('asignatura-tab'),
                        targetEl: document.getElementById('asignatura')
                    },
                    {
                        id: 'proyecto-tab',
                        triggerEl: document.getElementById('proyecto-tab'),
                        targetEl: document.getElementById('proyecto')
                    },
                    {
                        id: 'Grupo-tab',
                        triggerEl: document.getElementById('Grupo-tab'),
                        targetEl: document.getElementById('Grupo')
                    },
                    {
                        id: 'posgrado-tab',
                        triggerEl: document.getElementById('posgrado-tab'),
                        targetEl: document.getElementById('posgrado')
                    },
                    {
                        id: 'otros-tab',
                        triggerEl: document.getElementById('otros-tab'),
                        targetEl: document.getElementById('otros')
                    }
                ];

                // Función para manejar las pestañas manualmente (ya que estamos usando Alpine.js)
                function setupTabs() {
                    tabElements.forEach(tab => {
                        tab.triggerEl.addEventListener('click', () => {
                            // Ocultar todos los contenidos
                            tabElements.forEach(t => {
                                t.targetEl.classList.add('hidden');
                                t.triggerEl.classList.remove('border-indigo-500',
                                    'text-indigo-600', 'dark:border-indigo-500',
                                    'dark:text-indigo-500');
                                t.triggerEl.classList.add('border-transparent',
                                    'hover:text-gray-600', 'hover:border-gray-300',
                                    'dark:hover:text-gray-300');
                            });

                            // Mostrar el contenido seleccionado
                            tab.targetEl.classList.remove('hidden');
                            tab.triggerEl.classList.remove('border-transparent', 'hover:text-gray-600',
                                'hover:border-gray-300', 'dark:hover:text-gray-300');
                            tab.triggerEl.classList.add('border-indigo-500', 'text-indigo-600',
                                'dark:border-indigo-500', 'dark:text-indigo-500');
                        });
                    });
                }

                setupTabs();

                // Auto-submit al cambiar el filtro de estado
                document.getElementById('estado').addEventListener('change', function() {
                    this.form.submit();
                });

                // SweetAlert para mostrar mensajes de sesión
                @if (session('swal'))
                    Swal.fire({
                        icon: "{{ session('swal.icon') }}",
                        title: "{{ session('swal.title') }}",
                        text: "{{ session('swal.text') }}",
                        timer: 3000
                    });
                @endif

                // Configuración para formularios de aprobación
                const aprobarForms = document.querySelectorAll('.aprobar-form');
                aprobarForms.forEach(form => {
                    form.addEventListener('submit', (e) => {
                        e.preventDefault();
                        Swal.fire({
                            title: "¿Aprobar esta solicitud?",
                            text: "Esta acción marcará la solicitud como aprobada y en proceso de pedido.",
                            icon: "question",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Sí, aprobar",
                            cancelButtonText: "Cancelar"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });

                // Configuración para formularios de biblioteca
                const bibliotecaForms = document.querySelectorAll('.biblioteca-form');
                bibliotecaForms.forEach(form => {
                    form.addEventListener('submit', (e) => {
                        e.preventDefault();
                        Swal.fire({
                            title: "¿Marcar como 'En Biblioteca'?",
                            text: "Esto indicará que el libro ha llegado y está disponible en la biblioteca.",
                            icon: "question",
                            showCancelButton: true,
                            confirmButtonColor: "#7c3aed",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Sí, marcar",
                            cancelButtonText: "Cancelar"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
                
                

                // Configuración para formularios de recepción
                const recibirForms = document.querySelectorAll('.recibir-form');
                recibirForms.forEach(form => {
                    form.addEventListener('submit', (e) => {
                        e.preventDefault();
                        Swal.fire({
                            title: "¿Confirmar recepción?",
                            text: "Esto confirmará que el libro ha sido recibido físicamente.",
                            icon: "question",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Sí, confirmar",
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
