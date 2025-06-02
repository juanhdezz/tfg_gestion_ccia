<!-- filepath: /resources/views/ordenacion/fase2.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-blue-500">
            Elección de Ordenación Docente - Segunda Fase
        </h1>

        <div class="mb-4 bg-blue-50 border-l-4 border-blue-400 p-4 dark:bg-blue-200">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        Actualmente estamos en la <strong>segunda fase</strong>. Cuando le llegue su turno podrá seleccionar asignaturas disponibles.
                        El turno actual es <strong>{{ $turno ?? 'N/A' }}</strong>.
                    </p>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                {{ session('error') }}
            </div>        @endif

        @role('admin')
            <!-- Panel de Administración -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden">
                <div class="bg-blue-50 dark:bg-blue-700 px-4 py-2 border-b border-blue-200 dark:border-blue-600">
                    <h2 class="text-xl font-semibold text-blue-800 dark:text-white">Panel de Administración</h2>
                </div>
                <div class="p-4">
                    @include('ordenacion.partials.admin_panel')
                </div>
            </div>

            <!-- Lista de Profesores y Asignaturas de Cursos Anteriores -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden">
                <div class="bg-green-50 dark:bg-green-700 px-4 py-2 border-b border-green-200 dark:border-green-600">
                    <h2 class="text-xl font-semibold text-green-800 dark:text-white">Histórico de Asignaciones ({{ $profesores_cursos_anteriores['total_asignaciones'] ?? 0 }} registros)</h2>
                </div>
                <div class="p-4">
                    @include('ordenacion.partials.profesores_historicos')
                </div>
            </div>
        @endrole

        @role('profesor')
        <!-- Sección de reducciones -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden">
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Compensaciones Docentes</h2>
            </div>
            <div class="p-4">
                @include('ordenacion.partials.reducciones')
            </div>
        </div>
        
        <!-- Sección de asignaciones actuales -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden">
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Asignaturas asignadas en el {{ $curso_siguiente ?? 'próximo curso' }}</h2>
            </div>
            <div class="p-4">
                @include('ordenacion.partials.asignaciones_actuales')
            </div>
        </div>
        
        <!-- Sección de preferencia de pasar turno -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden">
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Pasar Turno</h2>
            </div>
            <div class="p-4">
                @if(is_null($perfil))
                    <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <p class="text-red-800 dark:text-red-300">
                            <strong>Error:</strong> No se ha encontrado el perfil del usuario. Contacte con el administrador.
                        </p>
                    </div>
                @else
                    <form method="post" action="{{ route('ordenacion.pasar-turno-preferencia') }}">
                        @csrf
                        <div class="mb-4">
                            <p class="text-gray-700 dark:text-gray-300 mb-2">
                                Si esta opción está activada, cuando llegue su turno se pasará al siguiente usuario sin realizar ningún cambio en su ordenación docente
                            </p>
                            <div class="flex items-center">
                                <input type="checkbox" name="pasar_turno" id="pasar_turno" 
                                       {{ (optional($perfil)->pasar_turno ?? false) ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded">
                                <label for="pasar_turno" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    Pasar Turno
                                    @if(is_null(optional($perfil)->pasar_turno))
                                        <span class="text-gray-500 italic"> - (no configurado)</span>
                                    @endif
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Guardar Cambios
                        </button>
                    </form>
                @endif
            </div>
        </div>
        
        <!-- Sección de perfil académico -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden">
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Perfil Académico</h2>
                <button id="toggle-perfil" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                    Mostrar/Ocultar
                </button>
            </div>
            <div id="perfil-content" class="p-4 hidden">
                @include('ordenacion.partials.perfil_academico', ['perfil' => $perfil ?? null, 'titulaciones' => $titulaciones ?? []])
            </div>
        </div>
        
        <!-- Sección de asignaturas disponibles (solo si es tu turno) -->
        @if(isset($es_mi_turno) && $es_mi_turno)
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden">
                <div class="bg-green-50 dark:bg-green-700 px-4 py-2 border-b border-green-200 dark:border-green-600">
                    <h2 class="text-xl font-semibold text-green-800 dark:text-white">¡Es tu turno!</h2>
                </div>
                <div class="p-4">
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        Ahora puedes seleccionar asignaturas disponibles según tu perfil académico.
                    </p>
                    
                    @if(isset($asignaturas_disponibles) && count($asignaturas_disponibles) > 0)
                        <form action="{{ route('ordenacion.seleccionar-asignaturas') }}" method="POST">
                            @csrf
                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Asignatura</th>
                                            <th scope="col" class="px-6 py-3">Titulación</th>
                                            <th scope="col" class="px-6 py-3">Curso</th>
                                            <th scope="col" class="px-6 py-3">Cuatr.</th>
                                            <th scope="col" class="px-6 py-3">Tipo</th>
                                            <th scope="col" class="px-6 py-3">Grupo</th>
                                            <th scope="col" class="px-6 py-3">Créditos</th>
                                            <th scope="col" class="px-6 py-3">Seleccionar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($asignaturas_disponibles as $asignatura)
                                            <tr class="border-b dark:border-gray-700">
                                                <td class="px-6 py-4">{{ optional($asignatura)->nombre_asignatura ?? 'N/A' }}</td>
                                                <td class="px-6 py-4">{{ optional($asignatura)->nombre_titulacion ?? 'Libre Configuración Específica' }}</td>
                                                <td class="px-6 py-4">{{ optional($asignatura)->curso ?? 'N/A' }}º</td>
                                                <td class="px-6 py-4">{{ optional($asignatura)->cuatrimestre ?? 'N/A' }}</td>
                                                <td class="px-6 py-4">{{ optional($asignatura)->tipo ?? 'N/A' }}</td>
                                                <td class="px-6 py-4">{{ optional($asignatura)->grupo ?? 'N/A' }}</td>
                                                <td class="px-6 py-4">{{ optional($asignatura)->creditos ?? 'N/A' }}</td>
                                                <td class="px-6 py-4">
                                                    <input type="checkbox" name="asignaturas[]" 
                                                           value="{{ optional($asignatura)->id_asignatura ?? '' }}_{{ optional($asignatura)->tipo ?? '' }}_{{ optional($asignatura)->grupo ?? '' }}"
                                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="text-center mt-6">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Seleccionar asignaturas
                                </button>
                                <button type="button" onclick="window.location.href='{{ route('ordenacion.pasar-turno') }}'" 
                                        class="ml-4 bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                    Pasar turno
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4">
                            No hay asignaturas disponibles que coincidan con tu perfil académico.
                        </div>
                    @endif
                </div>
            </div>
        @endif
        
        <!-- Información del turno actual -->
        @if(isset($usuario_turno_actual))
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden">
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Información del Turno Actual</h2>
                </div>
                <div class="p-4">
                    <p class="text-gray-700 dark:text-gray-300">
                        Turno actual: <strong>{{ $turno ?? 'N/A' }}</strong>
                    </p>
                    <p class="text-gray-700 dark:text-gray-300">
                        Usuario: <strong>{{ optional($usuario_turno_actual)->nombre ?? 'N/A' }} {{ optional($usuario_turno_actual)->apellidos ?? '' }}</strong>
                    </p>
                    @if(isset($es_mi_turno) && !$es_mi_turno)
                        <p class="text-gray-600 dark:text-gray-400 mt-2">
                            Espere a que le llegue su turno para poder seleccionar asignaturas.
                        </p>
                    @endif
                </div>            </div>
        @endif
        @endrole

        <!-- Resumen (disponible para todos) -->
        <div class="flex justify-center mt-6">
            <a href="{{ route('ordenacion.resumen') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Ver Resumen de Ordenación Docente
            </a>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('toggle-perfil').addEventListener('click', function() {
            const perfilContent = document.getElementById('perfil-content');
            perfilContent.classList.toggle('hidden');
        });
    </script>
    @endpush
</x-app-layout>