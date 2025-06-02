<!-- filepath: /c:/xampp/htdocs/laravel/tfg_gestion_ccia/resources/views/asignaturas/index.blade.php -->
<x-app-layout>    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-blue-500">Gestión de
            Asignaturas</h1>        <!-- Información del ordenamiento -->
        <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg border border-blue-200 dark:border-blue-700">
            <p class="text-sm text-blue-800 dark:text-blue-200">
                <strong>📊 Ordenamiento:</strong> Las asignaturas se muestran agrupadas por tipo: primero 📚 <strong>Grados</strong> (agrupados por titulación), luego 🎓 <strong>Másteres</strong>. 
                Dentro de cada titulación están ordenadas alfabéticamente por nombre de asignatura.
            </p>
        </div>

        <!-- Formulario de búsqueda -->
        <form method="GET" action="{{ route('asignaturas.index') }}" class="mb-4">
            <input type="text" name="search" placeholder="Buscar asignatura..." value="{{ request('search') }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600">
        </form>

        <div class="flex justify-end mb-4">
            <a href="{{ route('asignaturas.grupos') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                Gestionar Grupos
            </a>
            <a href="{{ route('asignaturas.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                Añadir Asignatura
            </a>
            <button id="toggle-extintas"
                class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
                Mostrar Extintas
            </button>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Estado</th>
                        <th scope="col" class="px-6 py-3">ID Asignatura</th>
                        <th scope="col" class="px-6 py-3">Nombre Asignatura</th>
                        <th scope="col" class="px-6 py-3">Titulación</th>
                        <th scope="col" class="px-6 py-3">Curso</th>
                        <th scope="col" class="px-6 py-3">Cuatrimestre</th>
                        <th scope="col" class="px-6 py-3">Créditos Totales</th>
                        <th scope="col" class="px-6 py-3">ECTS Totales</th>
                        <th scope="col" class="px-6 py-3">Coordinador</th>
                        <th scope="col" class="px-6 py-3">Total Grupos Teoría</th>
                        <th scope="col" class="px-6 py-3">Total Grupos Práctica</th>
                        <th scope="col" class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>                <tbody>
                    @php
                        $currentTitulationType = null;
                        $currentTitulacion = null;
                        $grados = $asignaturas->filter(function($asignatura) {
                            return !str_starts_with($asignatura->titulacion->nombre_titulacion ?? '', 'Máster');
                        });
                        $masters = $asignaturas->filter(function($asignatura) {
                            return str_starts_with($asignatura->titulacion->nombre_titulacion ?? '', 'Máster');
                        });
                        $ordenedAsignaturas = $grados->concat($masters);
                    @endphp
                    
                    @foreach ($ordenedAsignaturas as $index => $asignatura)
                        @php
                            $isMaster = str_starts_with($asignatura->titulacion->nombre_titulacion ?? '', 'Máster');
                            $newTitulationType = $isMaster ? 'Máster' : 'grado';
                            $titulacionName = $asignatura->titulacion->nombre_titulacion ?? '';
                        @endphp
                        
                        {{-- Mostrar separador visual cuando cambiamos de grados a másteres --}}
                        @if ($currentTitulationType !== $newTitulationType && $currentTitulationType !== null)
                            <tr class="bg-blue-100 dark:bg-blue-900">
                                <td colspan="12" class="px-6 py-3 text-center font-bold text-blue-800 dark:text-blue-200">
                                    🎓 MÁSTERES
                                </td>
                            </tr>
                        @elseif ($currentTitulationType === null && !$isMaster)
                            <tr class="bg-green-100 dark:bg-green-900">
                                <td colspan="12" class="px-6 py-3 text-center font-bold text-green-800 dark:text-green-200">
                                    📚 GRADOS
                                </td>
                            </tr>
                        @elseif ($currentTitulationType === null && $isMaster)
                            <tr class="bg-blue-100 dark:bg-blue-900">
                                <td colspan="12" class="px-6 py-3 text-center font-bold text-blue-800 dark:text-blue-200">
                                    🎓 MÁSTERES
                                </td>
                            </tr>
                        @endif
                        
                        {{-- Mostrar separador por titulación dentro de grados --}}
                        @if (!$isMaster && $currentTitulacion !== $titulacionName)
                            <tr class="bg-yellow-50 dark:bg-yellow-900">
                                <td colspan="12" class="px-6 py-2 text-left font-semibold text-yellow-800 dark:text-yellow-200 border-l-4 border-yellow-500">
                                    📖 {{ $titulacionName }}
                                </td>
                            </tr>
                        @endif
                        
                        @php 
                            $currentTitulationType = $newTitulationType;
                            if (!$isMaster) {
                                $currentTitulacion = $titulacionName;
                            }
                        @endphp
                        
                        <tr class="border-b dark:border-gray-700 border-gray-200 {{ $asignatura->estado == 'Extinta' ? 'bg-gray-900 dark:bg-gray-800' : 'odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800' }}">
                            <td class="px-6 py-4">
                                {{ $asignatura->estado }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $asignatura->id_asignatura }}
                            </td>
                            <td class="px-6 py-4">
                                <strong> {{ $asignatura->nombre_asignatura }} </strong>
                            </td>
                            <td class="px-6 py-4">
                                {{ $asignatura->titulacion->nombre_titulacion }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $asignatura->curso }}º
                            </td>
                            <td class="px-6 py-4">
                                {{ $asignatura->cuatrimestre }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $asignatura->creditos_teoria + $asignatura->creditos_practicas }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $asignatura->ects_teoria + $asignatura->ects_practicas }}
                            </td>                            <td class="px-6 py-4">
                                {{ $asignatura->coordinador ? $asignatura->coordinador->nombre . ' ' . $asignatura->coordinador->apellidos : 'No asignado' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $asignatura->grupos_teoria }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $asignatura->grupos_practicas }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <a href="{{ route('asignaturas.show', $asignatura->id_asignatura) }}" class="font-medium text-green-600 dark:text-green-500 hover:underline">
                                        Ver &#128270;
                                    </a>
                                    <a href="{{ route('asignaturas.edit', $asignatura->id_asignatura) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                        Editar &#9999;
                                    </a>
                                    <a href="{{ route('asignaturas.mostrar-formulario-equivalencias', $asignatura->id_asignatura) }}" class="font-medium text-purple-600 dark:text-purple-500 hover:underline">
                                        Equivalencias &#8644;
                                    </a>
                                    <form class="delete-form" action="{{ route('asignaturas.destroy', $asignatura->id_asignatura) }}" method="POST" class="inline">
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

        <!-- Tabla de asignaturas extintas -->
        <div id="extintas-table" class="relative overflow-x-auto shadow-md sm:rounded-lg mt-8 hidden">
            <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-red-500">Asignaturas
                Extintas</h2>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Estado</th>
                        <th scope="col" class="px-6 py-3">ID Asignatura</th>
                        <th scope="col" class="px-6 py-3">Nombre Asignatura</th>
                        <th scope="col" class="px-6 py-3">Titulación</th>
                        <th scope="col" class="px-6 py-3">Curso</th>
                        <th scope="col" class="px-6 py-3">Cuatrimestre</th>
                        <th scope="col" class="px-6 py-3">Créditos Totales</th>
                        <th scope="col" class="px-6 py-3">ECTS Totales</th>
                        <th scope="col" class="px-6 py-3">Coordinador</th>
                        <th scope="col" class="px-6 py-3">Total Grupos Teoría</th>
                        <th scope="col" class="px-6 py-3">Total Grupos Práctica</th>
                        <th scope="col" class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>                <tbody>
                    @php
                        $currentTitulationTypeExtintas = null;
                        $currentTitulacionExtintas = null;
                        $gradosExtintas = $asignaturasExtintas->filter(function($asignatura) {
                            return !str_starts_with($asignatura->titulacion->nombre_titulacion ?? '', 'Máster');
                        });
                        $mastersExtintas = $asignaturasExtintas->filter(function($asignatura) {
                            return str_starts_with($asignatura->titulacion->nombre_titulacion ?? '', 'Máster');
                        });
                        $ordenedAsignaturasExtintas = $gradosExtintas->concat($mastersExtintas);
                    @endphp
                    
                    @foreach ($ordenedAsignaturasExtintas as $index => $asignatura)
                        @php
                            $isMasterExtinta = str_starts_with($asignatura->titulacion->nombre_titulacion ?? '', 'Máster');
                            $newTitulationTypeExtinta = $isMasterExtinta ? 'Máster' : 'grado';
                            $titulacionNameExtinta = $asignatura->titulacion->nombre_titulacion ?? '';
                        @endphp
                        
                        {{-- Mostrar separador visual cuando cambiamos de grados a másteres --}}
                        @if ($currentTitulationTypeExtintas !== $newTitulationTypeExtinta && $currentTitulationTypeExtintas !== null)
                            <tr class="bg-blue-100 dark:bg-blue-900">
                                <td colspan="12" class="px-6 py-3 text-center font-bold text-blue-800 dark:text-blue-200">
                                    🎓 MÁSTERES EXTINTOS
                                </td>
                            </tr>
                        @elseif ($currentTitulationTypeExtintas === null && !$isMasterExtinta)
                            <tr class="bg-green-100 dark:bg-green-900">
                                <td colspan="12" class="px-6 py-3 text-center font-bold text-green-800 dark:text-green-200">
                                    📚 GRADOS EXTINTOS
                                </td>
                            </tr>
                        @elseif ($currentTitulationTypeExtintas === null && $isMasterExtinta)
                            <tr class="bg-blue-100 dark:bg-blue-900">
                                <td colspan="12" class="px-6 py-3 text-center font-bold text-blue-800 dark:text-blue-200">
                                    🎓 MÁSTERES EXTINTOS
                                </td>
                            </tr>
                        @endif
                        
                        {{-- Mostrar separador por titulación dentro de grados extintos --}}
                        @if (!$isMasterExtinta && $currentTitulacionExtintas !== $titulacionNameExtinta)
                            <tr class="bg-yellow-50 dark:bg-yellow-900">
                                <td colspan="12" class="px-6 py-2 text-left font-semibold text-yellow-800 dark:text-yellow-200 border-l-4 border-yellow-500">
                                    📖 {{ $titulacionNameExtinta }}
                                </td>
                            </tr>
                        @endif
                        
                        @php 
                            $currentTitulationTypeExtintas = $newTitulationTypeExtinta;
                            if (!$isMasterExtinta) {
                                $currentTitulacionExtintas = $titulacionNameExtinta;
                            }
                        @endphp
                        
                        <tr class="border-b dark:border-gray-700 border-gray-200 bg-gray-900 dark:bg-gray-800">
                            <td class="px-6 py-4">
                                {{ $asignatura->estado }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $asignatura->id_asignatura }}
                            </td>
                            <td class="px-6 py-4">
                                <strong> {{ $asignatura->nombre_asignatura }} </strong>
                            </td>
                            <td class="px-6 py-4">
                                {{ $asignatura->titulacion->nombre_titulacion }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $asignatura->curso }}º
                            </td>
                            <td class="px-6 py-4">
                                {{ $asignatura->cuatrimestre }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $asignatura->creditos_teoria + $asignatura->creditos_practicas }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $asignatura->ects_teoria + $asignatura->ects_practicas }}
                            </td>                            <td class="px-6 py-4">
                                {{ $asignatura->coordinador ? $asignatura->coordinador->nombre . ' ' . $asignatura->coordinador->apellidos : 'No asignado' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $asignatura->grupos_teoria }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $asignatura->grupos_practicas }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <a href="{{ route('asignaturas.show', $asignatura->id_asignatura) }}"
                                        class="font-medium text-green-600 dark:text-green-500 hover:underline">
                                        Ver &#128270;
                                    </a>
                                    <a href="{{ route('asignaturas.edit', $asignatura->id_asignatura) }}"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                        Editar &#9999;
                                    </a>
                                    <a href="{{ route('asignaturas.mostrar-formulario-equivalencias', $asignatura->id_asignatura) }}"
                                        class="font-medium text-purple-600 dark:text-purple-500 hover:underline">
                                        Equivalencias &#8644;
                                    </a>
                                    <form class="delete-form"
                                        action="{{ route('asignaturas.destroy', $asignatura->id_asignatura) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="font-medium text-red-600 dark:text-red-500 hover:underline">
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

            document.getElementById('toggle-extintas').addEventListener('click', function() {
                var extintasTable = document.getElementById('extintas-table');
                if (extintasTable.classList.contains('hidden')) {
                    extintasTable.classList.remove('hidden');
                    this.textContent = 'Ocultar Extintas';
                } else {
                    extintasTable.classList.add('hidden');
                    this.textContent = 'Mostrar Extintas';
                }
            });
        </script>
    @endpush
</x-app-layout>
