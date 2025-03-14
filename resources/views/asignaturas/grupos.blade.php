{{-- <!-- filepath: /c:/xampp/htdocs/laravel/tfg_gestion_ccia/resources/views/asignaturas/grupos.blade.php -->
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
</x-app-layout> --}}

{{-- <x-app-layout>
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
                                        <form action="{{ route('asignaturas.reasignarGrupos', $asignatura->id_asignatura) }}" 
                                              method="POST" 
                                              class="space-y-4">
                                            @csrf
                                            @method('PATCH')
                                            
                                            <!-- Grupos existentes -->
                                            @php
                                                $gruposTeoria = $asignatura->grupos->whereNotNull('grupo_teoria')->unique('grupo_teoria');
                                                // Identificar grupos de práctica no asignados a grupos de teoría (si existen)
                                                $gruposPracticaNoAsignados = $asignatura->grupos->whereNull('grupo_teoria')->whereNotNull('grupo_practica');
                                            @endphp
                                    
                                            <!-- Contenedor principal con flexbox para los grupos de teoría -->
                                            <div class="flex flex-wrap gap-4">
                                                @foreach($gruposTeoria as $grupoTeoria)
                                                    <div class="border rounded p-3 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex-1 min-w-64">
                                                        <!-- Grupo de teoría (no editable) -->
                                                        <div class="flex items-center gap-2 mb-3 bg-blue-100 dark:bg-blue-900 p-2 rounded">
                                                            <span class="font-medium">Grupo Teoría:</span>
                                                            <span class="font-bold text-lg">{{ $grupoTeoria->grupo_teoria }}</span>
                                                            <!-- Campo oculto para mantener el valor del grupo de teoría -->
                                                            <input type="hidden" 
                                                                   name="grupos_teoria[{{ $grupoTeoria->grupo_teoria }}][numero]" 
                                                                   value="{{ $grupoTeoria->grupo_teoria }}">
                                                        </div>
                                    
                                                        <!-- Grupos de práctica asociados -->
                                                        <div class="space-y-2 p-2">
                                                            <div class="text-sm font-medium mb-2">Grupos de Práctica asignados:</div>
                                                            
                                                            <div class="max-h-64 overflow-y-auto space-y-2 p-1">
                                                                @foreach($asignatura->grupos->where('grupo_teoria', $grupoTeoria->grupo_teoria)->whereNotNull('grupo_practica') as $grupoPractica)
                                                                    <div class="flex items-center gap-2 p-2 bg-white dark:bg-gray-700 rounded border border-gray-200 dark:border-gray-600">
                                                                        <div class="flex-1 flex items-center">
                                                                            <span class="mr-2">Práctica:</span>
                                                                            <span class="font-bold">{{ $grupoPractica->grupo_practica }}</span>
                                                                            <input type="hidden" 
                                                                                   name="grupos_practica[{{ $grupoPractica->id }}][numero]" 
                                                                                   value="{{ $grupoPractica->grupo_practica }}">
                                                                        </div>
                                                                        
                                                                        <div>
                                                                            <!-- Selector para reasignar el grupo de prácticas -->
                                                                            <select 
                                                                                name="grupos_practica[{{ $grupoPractica->id }}][grupo_teoria]"
                                                                                class="ml-2 px-2 py-1 border rounded dark:bg-gray-600 dark:border-gray-500 text-sm">
                                                                                @foreach($gruposTeoria as $gt)
                                                                                    <option value="{{ $gt->grupo_teoria }}" 
                                                                                            {{ $gt->grupo_teoria == $grupoTeoria->grupo_teoria ? 'selected' : '' }}>
                                                                                        Teoría {{ $gt->grupo_teoria }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            
                                            <!-- Grupos de prácticas no asignados (si existen) -->
                                            @if($gruposPracticaNoAsignados->count() > 0)
                                                <div class="border rounded p-3 dark:border-gray-700 bg-yellow-50 dark:bg-yellow-900/20 mt-4">
                                                    <h3 class="font-medium mb-2">Grupos de Práctica sin asignar:</h3>
                                                    
                                                    <div class="space-y-2">
                                                        @foreach($gruposPracticaNoAsignados as $grupoPractica)
                                                            <div class="flex items-center gap-2 p-2 bg-white dark:bg-gray-700 rounded border border-gray-200 dark:border-gray-600">
                                                                <div class="flex-1">
                                                                    <span class="mr-2">Práctica:</span>
                                                                    <span class="font-bold">{{ $grupoPractica->grupo_practica }}</span>
                                                                    <input type="hidden" 
                                                                           name="grupos_practica_sin_asignar[{{ $grupoPractica->id }}][numero]" 
                                                                           value="{{ $grupoPractica->grupo_practica }}">
                                                                </div>
                                                                
                                                                <div>
                                                                    <label class="mr-2 text-sm">Asignar a:</label>
                                                                    <select 
                                                                        name="grupos_practica_sin_asignar[{{ $grupoPractica->id }}][grupo_teoria]"
                                                                        class="px-2 py-1 border rounded dark:bg-gray-600 dark:border-gray-500 text-sm">
                                                                        <option value="">-- Seleccionar grupo --</option>
                                                                        @foreach($gruposTeoria as $gt)
                                                                            <option value="{{ $gt->grupo_teoria }}">
                                                                                Grupo {{ $gt->grupo_teoria }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                    
                                            <!-- Botón de guardar cambios -->
                                            <div class="mt-4 text-center">
                                                <button type="submit" 
                                                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">
                                                    Guardar cambios de asignación
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
</x-app-layout> --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Grupos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Buscador -->
                <div class="mb-4">
                    <form action="{{ route('asignaturas.grupos') }}" method="GET">
                        <div class="flex gap-2">
                            <input type="text" name="search" placeholder="Buscar asignatura..." 
                                value="{{ request('search') }}"
                                class="border rounded px-4 py-2 flex-grow">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                Buscar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Listado de asignaturas por titulación -->
                @foreach ($asignaturas->groupBy('titulacion.nombre_titulacion') as $titulacion => $asignaturasGrupo)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2 bg-gray-100 p-2 rounded">
                            {{ $titulacion }}
                        </h3>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr class="bg-gray-100 text-gray-700">
                                        <th class="py-2 px-4 text-left">Estado</th>
                                        <th class="py-2 px-4 text-left">Nombre Asignatura</th>
                                        <th class="py-2 px-4 text-center">Curso</th>
                                        <th class="py-2 px-4 text-left">Grupos</th>
                                        <th class="py-2 px-4 text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($asignaturasGrupo as $asignatura)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="py-2 px-4">{{ $asignatura->estado }}</td>
                                            <td class="py-2 px-4">{{ $asignatura->nombre_asignatura }}</td>
                                            <td class="py-2 px-4 text-center">{{ $asignatura->curso }}º</td>
                                            <td class="py-2 px-4">
                                                <form action="{{ route('asignaturas.reasignar-grupos', $asignatura->id_asignatura) }}" 
                                                    method="POST" id="form-reasignar-{{ $asignatura->id_asignatura }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    
                                                    <div class="grupos-container" id="grupos-{{ $asignatura->id_asignatura }}">
                                                        <!-- Contenedor para los grupos de teoría -->
                                                        <div class="flex flex-wrap gap-4 mb-4" id="grupos-teoria-{{ $asignatura->id_asignatura }}">
                                                            @for ($i = 1; $i <= $asignatura->grupos_teoria; $i++)
                                                                <div class="border rounded-lg p-3 bg-gray-50 flex-grow">
                                                                    <div class="bg-blue-100 rounded p-2 mb-2 text-center font-medium">
                                                                        Grupo Teoría {{ $i }}
                                                                    </div>
                                                                    <!-- Contenedor para grupos de práctica de este grupo de teoría -->
                                                                    <div class="min-h-[100px] border border-dashed border-gray-300 rounded p-2 grupo-practica-dropzone"
                                                                        data-teoria="{{ $i }}" 
                                                                        data-asignatura="{{ $asignatura->id_asignatura }}">
                                                                        
                                                                        @foreach($asignatura->distribucion_grupos->where('grupo_teoria', $i) as $distribucion)
                                                                            @for ($j = 1; $j <= $distribucion->total_practicas; $j++)
                                                                                <div class="grupo-practica-item bg-green-100 rounded p-2 mb-2 cursor-move"
                                                                                    data-practica="{{ $j }}" 
                                                                                    data-teoria-actual="{{ $i }}"
                                                                                    draggable="true">
                                                                                    Práctica {{ $j }}
                                                                                    <input type="hidden" 
                                                                                        name="grupos[{{ $i }}][]" 
                                                                                        value="{{ $j }}">
                                                                                </div>
                                                                            @endfor
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endfor
                                                        </div>
                                                        
                                                        <div class="flex justify-end mt-2">
                                                            <button type="submit" 
                                                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                                                Guardar cambios
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>
                                            
                                            <td class="py-2 px-4 text-center">
                                                <a href="{{ route('asignaturas.show', $asignatura->id_asignatura) }}" 
                                                    class="text-blue-500 hover:text-blue-700">
                                                    Ver 🔎
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- JavaScript para manejar el drag and drop -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtenemos todos los elementos arrastrables
            const draggables = document.querySelectorAll('.grupo-practica-item');
            const dropzones = document.querySelectorAll('.grupo-practica-dropzone');
            
            // Configuramos los eventos para elementos arrastrables
            draggables.forEach(draggable => {
                draggable.addEventListener('dragstart', function(e) {
                    draggable.classList.add('bg-gray-300');
                    e.dataTransfer.setData('text/plain', JSON.stringify({
                        practica: draggable.dataset.practica,
                        teoriaActual: draggable.dataset.teoriaActual,
                        asignatura: draggable.closest('.grupos-container').id.split('-')[1]
                    }));
                });
                
                draggable.addEventListener('dragend', function() {
                    draggable.classList.remove('bg-gray-300');
                });
            });
            
            // Configuramos los eventos para las zonas de destino
            dropzones.forEach(dropzone => {
                dropzone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    dropzone.classList.add('border-blue-500');
                });
                
                dropzone.addEventListener('dragleave', function() {
                    dropzone.classList.remove('border-blue-500');
                });
                
                dropzone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    dropzone.classList.remove('border-blue-500');
                    
                    const data = JSON.parse(e.dataTransfer.getData('text/plain'));
                    const asignaturaId = data.asignatura;
                    const grupoPractica = data.practica;
                    const grupoTeoriaAnterior = data.teoriaActual;
                    const grupoTeoriaNuevo = dropzone.dataset.teoria;
                    
                    // Solo procesamos si el destino es diferente al origen
                    if (grupoTeoriaAnterior !== grupoTeoriaNuevo && asignaturaId === dropzone.dataset.asignatura) {
                        // Clonar el elemento arrastrado
                        const elementoOriginal = document.querySelector(`.grupo-practica-item[data-practica="${grupoPractica}"][data-teoria-actual="${grupoTeoriaAnterior}"]`);
                        
                        if (elementoOriginal) {
                            // Crear un nuevo elemento
                            const nuevoElemento = elementoOriginal.cloneNode(true);
                            nuevoElemento.dataset.teoriaActual = grupoTeoriaNuevo;
                            
                            // Actualizar el input hidden
                            const hiddenInput = nuevoElemento.querySelector('input[type="hidden"]');
                            hiddenInput.name = `grupos[${grupoTeoriaNuevo}][]`;
                            
                            // Añadir al nuevo contenedor y eliminar del anterior
                            dropzone.appendChild(nuevoElemento);
                            elementoOriginal.remove();
                            
                            // Reconfigurar eventos para el nuevo elemento
                            configurarEventosDragDrop(nuevoElemento);
                        }
                    }
                });
            });
            
            function configurarEventosDragDrop(elemento) {
                elemento.addEventListener('dragstart', function(e) {
                    elemento.classList.add('bg-gray-300');
                    e.dataTransfer.setData('text/plain', JSON.stringify({
                        practica: elemento.dataset.practica,
                        teoriaActual: elemento.dataset.teoriaActual,
                        asignatura: elemento.closest('.grupos-container').id.split('-')[1]
                    }));
                });
                
                elemento.addEventListener('dragend', function() {
                    elemento.classList.remove('bg-gray-300');
                });
            }
        });
    </script>
</x-app-layout>