<x-app-layout>
    @if($dentroDePlazo)

    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-blue-500">Editar Horario de
            Tutorías</h1>

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

        <form id="tutorias-form" method="POST" action="{{ route('tutorias.actualizar') }}" class="mb-6">
            @csrf
            <!-- Selector de despacho -->
            <div class="flex flex-wrap gap-4 mb-6">
                <div class="flex-1 min-w-[250px]">
                    <label for="despacho"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Seleccione un
                        despacho:</label>
                    <select id="despacho" name="id_despacho"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @foreach ($despachos as $despacho)
                            <option value="{{ $despacho->id_despacho }}"
                                {{ $despachoSeleccionado == $despacho->id_despacho ? 'selected' : '' }}>
                                {{ $despacho->nombre_despacho }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1 min-w-[250px]">
                    <label for="cuatrimestre"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cuatrimestre:</label>
                    <select id="cuatrimestre" name="cuatrimestre"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="1" {{ $cuatrimestreSeleccionado == 1 ? 'selected' : '' }}>Primer
                            Cuatrimestre</option>
                        <option value="2" {{ $cuatrimestreSeleccionado == 2 ? 'selected' : '' }}>Segundo
                            Cuatrimestre</option>
                    </select>
                </div>
            </div>            <div
                class="p-4 mb-6 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 dark:bg-yellow-900 dark:border-yellow-700 dark:text-yellow-300">
                <p class="font-bold">Nota:</p>
                <p>Al guardar este horario, se reemplazarán todas las tutorías existentes para el despacho y
                    cuatrimestre seleccionados.</p>
                <p>Para ver las tutorías actuales, use el botón <b>"Ver tutorías actuales"</b> que encontrará al final
                    del formulario.</p>
                <p class="font-bold mt-2">Debe seleccionar exactamente 6 horas de tutorías (12 slots de 30 minutos).</p>
            </div>

            <!-- Contador de horas seleccionadas -->
            <div class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-400 text-blue-800 dark:bg-blue-900 dark:border-blue-700 dark:text-blue-300">
                <p><strong>Horas actuales:</strong> <span id="horas-actuales">{{ $horasTotales ?? 0 }}</span> / 6 horas</p>
                <p><strong>Horas seleccionadas:</strong> <span id="horas-seleccionadas">0</span> / 6 horas</p>
                <div class="mt-2">
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        <div id="barra-progreso" class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Debug temporal - mostrar tutorías cargadas -->
            @if($tutorias->count() > 0)
            <div class="mb-4 p-4 bg-gray-100 border rounded text-sm">
                <strong>Debug - Tutorías encontradas:</strong>
                @foreach($tutorias as $tutoria)
                    <div>{{ $tutoria->dia }} {{ $tutoria->inicio }}-{{ $tutoria->fin }}</div>
                @endforeach
            </div>
            @endif

            <!-- Tabla de horarios -->
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-6">
                <table id="tabla-horarios" class="w-full text-sm text-center text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Hora</th>
                            <th scope="col" class="px-4 py-3">Lunes</th>
                            <th scope="col" class="px-4 py-3">Martes</th>
                            <th scope="col" class="px-4 py-3">Miércoles</th>
                            <th scope="col" class="px-4 py-3">Jueves</th>
                            <th scope="col" class="px-4 py-3">Viernes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($horas as $hora)
                            <tr
                                class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">                                <td class="px-4 py-2 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                    {{ $hora['inicio'] }} - {{ $hora['fin'] }}
                                </td>
                                @foreach ($diasSemana as $dia => $nombreDia)
                                    @php
                                        $celdaId = "celda-{$dia}-{$hora['inicio']}-{$hora['fin']}";
                                        
                                        // Verificar si existe una tutoría para esta celda
                                        $tieneTutoria = false;
                                        if ($tutorias->count() > 0) {
                                            foreach ($tutorias as $tutoria) {
                                                if ($tutoria->dia == $nombreDia && 
                                                    $tutoria->inicio == $hora['inicio'] && 
                                                    $tutoria->fin == $hora['fin']) {
                                                    $tieneTutoria = true;
                                                    break;
                                                }
                                            }
                                        }
                                    @endphp

                                    <td class="px-1 py-1">
                                        <div id="{{ $celdaId }}"
                                            class="celda-horario h-12 w-full flex items-center justify-center cursor-pointer rounded transition-colors duration-150 {{ $tieneTutoria ? 'bg-blue-200 dark:bg-blue-700' : '' }}"
                                            data-dia="{{ $nombreDia }}" data-inicio="{{ $hora['inicio'] }}"
                                            data-fin="{{ $hora['fin'] }}" data-seleccionada="{{ $tieneTutoria ? 'true' : 'false' }}">
                                            <span class="text-xs {{ $tieneTutoria ? 'text-blue-800 dark:text-blue-300' : 'text-gray-500 dark:text-gray-400' }}">{{ $tieneTutoria ? 'Tutoría' : '' }}</span>
                                        </div>
                                        <input type="hidden"
                                            name="tutorias[{{ $nombreDia }}][{{ $hora['inicio'] }}][{{ $hora['fin'] }}]"
                                            value="{{ $tieneTutoria ? '1' : '0' }}">
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between">
                <a href="{{ route('tutorias.ver', ['despacho' => $despachoSeleccionado, 'cuatrimestre' => $cuatrimestreSeleccionado]) }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Ver tutorías actuales
                </a>

                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
    @else
    <div class="mt-5 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
        <strong>Aviso:</strong> No es posible modificar las tutorías en este momento. Los plazos para
        {{ $estaEnProximoCurso ? 'el próximo curso' : 'el curso actual' }}
        ({{ $cuatrimestreSeleccionado == 1 ? 'primer' : 'segundo' }} cuatrimestre)
        están cerrados.        <a href="{{ route('plazos.index') }}" class="underline text-blue-600">Ver plazos disponibles</a>
    </div>
@endif

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Función para actualizar el contador de horas
                function actualizarContadorHoras() {
                    const celdasSeleccionadas = document.querySelectorAll('.celda-horario[data-seleccionada="true"]');
                    const horasSeleccionadas = celdasSeleccionadas.length * 0.5; // Cada celda son 30 minutos
                    
                    document.getElementById('horas-seleccionadas').textContent = horasSeleccionadas;
                    
                    // Actualizar barra de progreso
                    const porcentaje = (horasSeleccionadas / 6) * 100;
                    const barraProgreso = document.getElementById('barra-progreso');
                    barraProgreso.style.width = porcentaje + '%';
                    
                    // Cambiar color según las horas
                    if (horasSeleccionadas === 6) {
                        barraProgreso.className = 'bg-green-600 h-2.5 rounded-full';
                    } else if (horasSeleccionadas > 6) {
                        barraProgreso.className = 'bg-red-600 h-2.5 rounded-full';
                    } else {
                        barraProgreso.className = 'bg-blue-600 h-2.5 rounded-full';
                    }
                }

                // Manejar clics en las celdas del horario
                const celdasHorario = document.querySelectorAll('.celda-horario');
                celdasHorario.forEach(celda => {
                    celda.addEventListener('click', function() {
                        // Obtener datos de la celda
                        const dia = this.dataset.dia;
                        const inicio = this.dataset.inicio;
                        const fin = this.dataset.fin;

                        // Cambiar estado de selección
                        const estaSeleccionada = this.dataset.seleccionada === 'true';
                        this.dataset.seleccionada = !estaSeleccionada;

                        // Actualizar estilo visual
                        if (!estaSeleccionada) {
                            this.classList.add('bg-blue-200', 'dark:bg-blue-700');
                            this.querySelector('span').classList.add('text-blue-800',
                                'dark:text-blue-300');
                            this.querySelector('span').classList.remove('text-gray-500',
                                'dark:text-gray-400');
                            this.querySelector('span').textContent = 'Tutoría';
                        } else {
                            this.classList.remove('bg-blue-200', 'dark:bg-blue-700');
                            this.querySelector('span').classList.remove('text-blue-800',
                                'dark:text-blue-300');
                            this.querySelector('span').classList.add('text-gray-500',
                                'dark:text-gray-400');
                            this.querySelector('span').textContent = '';
                        }

                        // Actualizar el valor del campo oculto
                        const inputHidden = document.querySelector(
                            `input[name="tutorias[${dia}][${inicio}][${fin}]"]`);
                        inputHidden.value = !estaSeleccionada ? '1' : '0';
                        
                        // Actualizar contador de horas
                        actualizarContadorHoras();
                    });
                });

                // Validación antes del envío del formulario
                document.getElementById('tutorias-form').addEventListener('submit', function(e) {
                    const horasSeleccionadas = parseFloat(document.getElementById('horas-seleccionadas').textContent);
                    
                    if (horasSeleccionadas !== 6) {
                        e.preventDefault();
                        alert(`Debe seleccionar exactamente 6 horas de tutorías. Ha seleccionado ${horasSeleccionadas} horas.`);
                        return false;
                    }
                });

                // Inicializar contador con las tutorías ya existentes
                actualizarContadorHoras();
            });
        </script>
    @endpush
</x-app-layout>
