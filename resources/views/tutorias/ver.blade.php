<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-blue-500">Visualización de
            Tutorías</h1>

        <!-- Debug temporal -->
        <div class="p-4 mb-4 bg-gray-100 rounded dark:bg-gray-800">
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
        </div>

        <!-- Selectores -->
        <div class="flex flex-wrap gap-4 mb-6">
            <div class="flex-1 min-w-[250px]">
                <label for="despacho" class="block mb-1">Despacho:</label>
                <select id="despacho" name="despacho" class="w-full px-4 py-2 border rounded">
                    @foreach ($despachos as $despacho)
                        <option value="{{ $despacho->id_despacho }}"
                            {{ $despachoSeleccionado == $despacho->id_despacho ? 'selected' : '' }}>
                            {{ $despacho->nombre_despacho }}
                        </option>
                    @endforeach
                </select>
            </div>

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
            });

            // Añadir comportamiento al botón de actualizar vista
            document.getElementById('actualizar-vista').addEventListener('click', function() {
                const despacho = document.getElementById('despacho').value;
                const cuatrimestre = document.getElementById('cuatrimestre').value;

                window.location.href = `{{ route('tutorias.ver') }}?despacho=${despacho}&cuatrimestre=${cuatrimestre}`;
            });
        </script>
    @endpush
</x-app-layout>
