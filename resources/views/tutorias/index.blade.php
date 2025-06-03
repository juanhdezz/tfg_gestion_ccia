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
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-1 animate-pulse"></span>
                        Plazo abierto para edición
                    </span>
                </div>
                <a href="{{ route('tutorias.gestion') }}" 
                   class="inline-flex items-center px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white rounded text-sm transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Cambiar Curso/Semestre
                </a>
            </div>
        </div>

        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-blue-500">Editar Horario de
            Tutorías</h1>        @if (session('success'))
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
        @endif<form id="tutorias-form" method="POST" action="{{ route('tutorias.actualizar') }}" class="mb-6">
            @csrf
            
            @if($esAdmin && $miembroSeleccionado)
                <input type="hidden" name="miembro" value="{{ $miembroSeleccionado }}">
            @endif<!-- Selector de despacho o miembro (según el rol) -->
            <div class="flex flex-wrap gap-4 mb-6">
                @if($esAdmin)
                    <!-- Selector de miembro para administradores -->
                    <div class="flex-1 min-w-[250px]">                        <label for="miembro" 
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Seleccione un usuario:</label><select id="miembro" name="miembro"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
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
                @endif

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
                <p class="font-bold mt-2">Debe seleccionar exactamente {{ $horasMaximasPermitidas }} horas de tutorías ({{ $horasMaximasPermitidas * 2 }} slots de 30 minutos).</p>
            </div>

            <!-- Contador de horas seleccionadas -->
            <div class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-400 text-blue-800 dark:bg-blue-900 dark:border-blue-700 dark:text-blue-300">                <p><strong>Horas actuales:</strong> <span id="horas-actuales">{{ $horasTotales ?? 0 }}</span> / {{ $horasMaximasPermitidas }} horas</p>
                <p><strong>Horas seleccionadas:</strong> <span id="horas-seleccionadas">0</span> / {{ $horasMaximasPermitidas }} horas</p>
                <div class="mt-2">
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        <div id="barra-progreso" class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Debug temporal - mostrar tutorías cargadas -->
            {{-- @if($tutorias->count() > 0)
            <div class="mb-4 p-4 bg-gray-100 border rounded text-sm">
                <strong>Debug - Tutorías encontradas:</strong>
                @foreach($tutorias as $tutoria)
                    <div>{{ $tutoria->dia }} {{ $tutoria->inicio }}-{{ $tutoria->fin }}</div>
                @endforeach
            </div>
            @endif --}}

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
            </div>        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {                // Función para actualizar el contador de horas
                function actualizarContadorHoras() {
                    const celdasSeleccionadas = document.querySelectorAll('.celda-horario[data-seleccionada="true"]');
                    const horasSeleccionadas = celdasSeleccionadas.length * 0.5; // Cada celda son 30 minutos
                    const horasMaximas = {{ $horasMaximasPermitidas }};
                    
                    document.getElementById('horas-seleccionadas').textContent = horasSeleccionadas;
                    
                    // Actualizar barra de progreso
                    const porcentaje = (horasSeleccionadas / horasMaximas) * 100;
                    const barraProgreso = document.getElementById('barra-progreso');
                    barraProgreso.style.width = porcentaje + '%';
                    
                    // Cambiar color según las horas
                    if (horasSeleccionadas === horasMaximas) {
                        barraProgreso.className = 'bg-green-600 h-2.5 rounded-full';
                    } else if (horasSeleccionadas > horasMaximas) {
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
                });                // Validación antes del envío del formulario
                document.getElementById('tutorias-form').addEventListener('submit', function(e) {
                    const horasSeleccionadas = parseFloat(document.getElementById('horas-seleccionadas').textContent);
                    const horasMaximas = {{ $horasMaximasPermitidas }};
                    
                    if (horasSeleccionadas !== horasMaximas) {
                        e.preventDefault();
                        alert(`Debe seleccionar exactamente ${horasMaximas} horas de tutorías. Ha seleccionado ${horasSeleccionadas} horas.`);
                        return false;
                    }
                });// Inicializar contador con las tutorías ya existentes
                actualizarContadorHoras();

                @if($esAdmin)
                // Manejar cambios en el selector de miembros para administradores
                document.getElementById('miembro').addEventListener('change', function() {
                    const form = document.getElementById('tutorias-form');
                    const cuatrimestre = document.getElementById('cuatrimestre').value;
                    
                    if (this.value) {
                        // Redireccionar con el miembro seleccionado
                        const url = new URL(window.location.href);
                        url.searchParams.set('miembro', this.value);
                        url.searchParams.set('cuatrimestre', cuatrimestre);
                        window.location.href = url.toString();
                    }
                });
                @else
                // Manejar cambios en el selector de despachos para usuarios normales
                document.getElementById('despacho').addEventListener('change', function() {
                    const cuatrimestre = document.getElementById('cuatrimestre').value;
                    
                    if (this.value) {
                        // Redireccionar con el despacho seleccionado
                        const url = new URL(window.location.href);
                        url.searchParams.set('despacho', this.value);
                        url.searchParams.set('cuatrimestre', cuatrimestre);
                        window.location.href = url.toString();
                    }
                });
                @endif

                // Manejar cambios en el selector de cuatrimestre
                document.getElementById('cuatrimestre').addEventListener('change', function() {
                    const url = new URL(window.location.href);
                    url.searchParams.set('cuatrimestre', this.value);
                    
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
            });
        </script>
    @endpush
</x-app-layout>
