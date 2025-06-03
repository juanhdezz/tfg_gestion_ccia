<x-app-layout>    <div class="container mx-auto p-4">
        <!-- Información del contexto actual -->
        <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="text-blue-800 dark:text-blue-200">
                        <span class="font-medium">
                            @if($estaEnProximoCurso)
                                📅 Curso 25/26 - {{ $cuatrimestreSeleccionado == 1 ? 'Primer' : 'Segundo' }} Semestre
                            @else
                                📅 Curso 24/25 - {{ $cuatrimestreSeleccionado == 1 ? 'Primer' : 'Segundo' }} Semestre
                            @endif
                        </span>
                    </div>
                    @if($dentroDePlazo)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-1 animate-pulse"></span>
                            Plazo abierto
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                            Solo consulta
                        </span>
                    @endif
                </div>
                <div class="flex space-x-2">                    @if($dentroDePlazo)
                        @php
                            $editParams = ['cuatrimestre' => $cuatrimestreSeleccionado];
                            if($esAdmin && $miembroSeleccionado) {
                                $editParams['miembro'] = $miembroSeleccionado;
                            } else {
                                $editParams['despacho'] = $despachoSeleccionado;
                            }
                        @endphp
                        <a href="{{ route('tutorias.index', $editParams) }}" 
                           class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar
                        </a>
                    @endif
                    <a href="{{ route('tutorias.gestion') }}" 
                       class="inline-flex items-center px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white rounded text-sm transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Cambiar Curso/Semestre
                    </a>
                </div>
            </div>
        </div>        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-blue-500">Visualización de
            Tutorías</h1>

        <!-- Información sobre las horas de tutorías -->
        @if(isset($horasTotales) && isset($horasMaximasPermitidas))
            <div class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-400 text-blue-800 dark:bg-blue-900 dark:border-blue-700 dark:text-blue-300">
                <p><strong>Horas configuradas:</strong> {{ $horasTotales ?? 0 }} / {{ $horasMaximasPermitidas }} horas</p>
                @if($horasTotales > 0)
                    <div class="mt-2">
                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ min(($horasTotales / $horasMaximasPermitidas) * 100, 100) }}%"></div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 dark:bg-green-800 dark:text-green-100"
                role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 dark:bg-red-800 dark:text-red-100"
                role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @if (session('info'))
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4 dark:bg-blue-800 dark:text-blue-100"
                role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <p>{{ session('info') }}</p>
                </div>
            </div>
        @endif

        <!-- Debug temporal -->
        {{-- <div class="p-4 mb-4 bg-gray-100 rounded dark:bg-gray-800">
            <p><strong>Despacho seleccionado:</strong> {{ $despachoSeleccionado }}</p>
            <p><strong>Cuatrimestre:</strong> {{ $cuatrimestreSeleccionado }}</p>
            <p><strong>Total tutorías encontradas:</strong> {{ $tutorias->count() }}</p>

            @if ($tutorias->count() > 0)
                <h3 class="font-bold mt-2">Primeras tutorías encontradas:</h3>
                <ul>
                    @foreach ($tutorias->take(5) as $t)
                        <li>• Día {{ $t->dia }}, {{ $t->inicio }}-{{ $t->fin }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-red-500">No se encontraron tutorías para este despacho y cuatrimestre.</p>
            @endif
        </div> --}}        <!-- Selectores -->
        <div class="flex flex-wrap gap-4 mb-6">
            @if($esAdmin)                <!-- Selector de miembro para administradores -->
                <div class="flex-1 min-w-[250px]">
                    <label for="miembro" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Usuario:</label>
                    <select id="miembro" name="miembro" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">-- Seleccione un usuario --</option>
                        @foreach ($miembros as $usuario)
                            <option value="{{ $usuario->id_usuario }}" 
                                {{ $miembroSeleccionado == $usuario->id_usuario ? 'selected' : '' }}
                                data-despacho="{{ $usuario->id_despacho }}">
                                {{ $usuario->apellidos }}, {{ $usuario->nombre }}
                                @if($usuario->despacho)
                                    ({{ $usuario->despacho->nombre_despacho }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
            @else
                <!-- Selector de despacho para usuarios normales -->
                <div class="flex-1 min-w-[250px]">
                    <label for="despacho" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Despacho:</label>
                    <select id="despacho" name="despacho" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @foreach ($despachos as $despacho)
                            <option value="{{ $despacho->id_despacho }}"
                                {{ $despachoSeleccionado == $despacho->id_despacho ? 'selected' : '' }}>
                                {{ $despacho->nombre_despacho }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="flex-1 min-w-[250px]">
                <label for="cuatrimestre" class="block mb-1">Cuatrimestre:</label>
                <select id="cuatrimestre" name="cuatrimestre" class="w-full px-4 py-2 border rounded">
                    <option value="1" {{ $cuatrimestreSeleccionado == 1 ? 'selected' : '' }}>Primer Cuatrimestre
                    </option>
                    <option value="2" {{ $cuatrimestreSeleccionado == 2 ? 'selected' : '' }}>Segundo Cuatrimestre
                    </option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="button" id="actualizar-vista" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Ver horario
                </button>
            </div>
        </div>

        <!-- Tabla con tutorías -->
        <div class="overflow-x-auto shadow-md rounded-lg mb-6">
            <table class="w-full text-sm text-center">
                <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3">Hora</th>
                        <th class="px-4 py-3">Lunes</th>
                        <th class="px-4 py-3">Martes</th>
                        <th class="px-4 py-3">Miércoles</th>
                        <th class="px-4 py-3">Jueves</th>
                        <th class="px-4 py-3">Viernes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($horas as $hora)
    <tr class="border-b dark:border-gray-700 odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900 dark:even:bg-gray-800">
        <td class="px-4 py-2 font-medium whitespace-nowrap">
            {{ $hora['inicio'] }} - {{ $hora['fin'] }}
        </td>

        @foreach ($diasSemana as $nombreDia)
            @php
                // Convertimos el nombre del día a string para comparar
                $diaStr = $nombreDia; // Ahora $nombreDia es "Lunes", "Martes", etc.
                $inicioStr = $hora['inicio'];
                $finStr = $hora['fin'];

                // Buscar tutorías que coincidan exactamente
                $hayTutoria = false;
                $tutoriaId = null;

                foreach ($tutorias as $tutoria) {
                    if (
                        $tutoria->dia == $diaStr && // Comparar nombres de días directamente
                        trim($tutoria->inicio) == trim($inicioStr) &&
                        trim($tutoria->fin) == trim($finStr)
                    ) {
                        $hayTutoria = true;
                        $tutoriaId = $tutoria->id_tutoria;
                        break;
                    }
                }

                // Crear un id único para esta celda
                $celdaId = "celda-{$diaStr}-{$inicioStr}-{$finStr}";
            @endphp

            <td class="p-1">
                <!-- Usamos style inline para forzar colores -->
                <div id="{{ $celdaId }}"
                    class="h-12 w-full flex items-center justify-center rounded"
                    style="{{ $hayTutoria ? 'background-color: #93c5fd;' : '' }}"
                    data-es-tutoria="{{ $hayTutoria ? 'true' : 'false' }}"
                    data-dia="{{ $diaStr }}" data-inicio="{{ $inicioStr }}"
                    data-fin="{{ $finStr }}" data-tutoria-id="{{ $tutoriaId }}">
                    <span style="{{ $hayTutoria ? 'color: #1e3a8a; font-weight: bold;' : '' }}">
                        {{ $hayTutoria ? 'Tutoría' : '' }}
                    </span>
                </div>
            </td>
        @endforeach
    </tr>
@endforeach
                </tbody>
            </table>
        </div>

        <!-- Botones inferiores -->
        <div class="flex justify-between">
            <a href="{{ route('tutorias.index', ['despacho' => $despachoSeleccionado, 'cuatrimestre' => $cuatrimestreSeleccionado]) }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Editar tutorías
            </a>

            <a href="{{ route('dashboard') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Registrar cuántas celdas tienen tutorías
                const celdasConTutoria = document.querySelectorAll('[data-es-tutoria="true"]');
                console.log(`Se han encontrado ${celdasConTutoria.length} celdas con tutorías`);

                // Imprimir detalles de cada celda con tutoría para depuración
                celdasConTutoria.forEach(celda => {
                    console.log(
                        `Celda: día=${celda.dataset.dia}, hora=${celda.dataset.inicio}-${celda.dataset.fin}`
                        );

                    // Aplicar estilos
                    celda.style.backgroundColor = '#93c5fd';
                    celda.style.border = '1px solid #3b82f6';

                    const span = celda.querySelector('span');
                    if (span) {
                        span.style.color = '#1e3a8a';
                        span.style.fontWeight = 'bold';
                        span.textContent = 'Tutoría';
                    }
                });

                // También verificar todas las tutorías desde JavaScript para depuración
                console.log('Todas las celdas:', document.querySelectorAll('[data-dia]').length);
            });            // Añadir comportamiento al botón de actualizar vista
            document.getElementById('actualizar-vista').addEventListener('click', function() {
                const cuatrimestre = document.getElementById('cuatrimestre').value;
                const url = new URL('{{ route('tutorias.ver') }}', window.location.origin);
                url.searchParams.set('cuatrimestre', cuatrimestre);

                @if($esAdmin)
                const miembro = document.getElementById('miembro').value;
                if (miembro) {
                    url.searchParams.set('miembro', miembro);
                }
                @else
                const despacho = document.getElementById('despacho').value;
                if (despacho) {
                    url.searchParams.set('despacho', despacho);
                }
                @endif

                window.location.href = url.toString();
            });
        </script>
    @endpush
</x-app-layout>
