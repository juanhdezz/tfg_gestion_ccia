<!-- filepath: /resources/views/ordenacion/fase1.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-blue-500">
            Elección de Ordenación Docente - Primera Fase
        </h1>
        
        <div class="mb-4 bg-yellow-50 border-l-4 border-yellow-400 p-4 dark:bg-yellow-200">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Actualmente estamos en la <strong>primera fase</strong>. Los profesores pueden escoger las asignaturas que hayan impartido anteriormente
                        durante un periodo máximo de {{ $cursos_con_preferencia ?? 'N/A' }} cursos académicos.
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
        
        <!-- Sección de asignaciones que puede mantener -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden">
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                    Asignaturas del curso anterior impartidas durante menos de {{ $cursos_con_preferencia ?? 'N/A' }} cursos
                </h2>
            </div>
            <div class="p-4">
                <p class="text-center mb-4 text-gray-700 dark:text-gray-300">
                    Marcar las asignaturas que se quieran mantener para el próximo curso
                </p>
                
                @if(isset($asignaciones_previas) && count($asignaciones_previas) > 0)
                    <form action="{{ route('ordenacion.mantener') }}" method="POST">
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
                                        <th scope="col" class="px-6 py-3">Estado</th>
                                        <th scope="col" class="px-6 py-3">Antigüedad</th>
                                        <th scope="col" class="px-6 py-3">Seleccionar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($asignaciones_previas as $asignacion)
                                        @if(optional($asignacion)->existe)
                                            @php
                                                $rowClass = '';
                                                if(optional($asignacion)->posible_perdida) {
                                                    $rowClass = 'bg-red-100 dark:bg-red-900';
                                                } elseif(optional($asignacion)->posible_no_fase2) {
                                                    $rowClass = 'bg-yellow-100 dark:bg-yellow-900';
                                                }
                                            @endphp
                                            
                                            <tr class="border-b dark:border-gray-700 {{ $rowClass }}">
                                                <td class="px-6 py-4">{{ optional($asignacion)->nombre_asignatura ?? 'N/A' }}</td>
                                                <td class="px-6 py-4">{{ optional($asignacion)->nombre_titulacion ?? 'Libre Configuración Específica' }}</td>
                                                <td class="px-6 py-4">{{ optional($asignacion)->curso ?? 'N/A' }}º</td>
                                                <td class="px-6 py-4">{{ optional($asignacion)->cuatrimestre ?? 'N/A' }}</td>
                                                <td class="px-6 py-4">{{ optional($asignacion)->tipo ?? 'N/A' }}</td>
                                                <td class="px-6 py-4">{{ optional($asignacion)->grupo ?? 'N/A' }}</td>
                                                <td class="px-6 py-4">{{ optional($asignacion)->creditos ?? 'N/A' }}</td>
                                                <td class="px-6 py-4">Posible mantenerla en primera fase</td>
                                                <td class="px-6 py-4">
                                                    {{ optional($asignacion)->antiguedad ?? 0 }} 
                                                    {{ (optional($asignacion)->antiguedad ?? 0) == 1 ? 'año' : 'años' }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    <input type="checkbox" name="asignaturas[]" 
                                                           value="{{ optional($asignacion)->id_asignatura ?? '' }}_{{ optional($asignacion)->tipo ?? '' }}_{{ optional($asignacion)->grupo ?? '' }}"
                                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded">
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if(isset($asignaciones_previas) && $asignaciones_previas->where('posible_perdida', true)->count() > 0)
                            <div class="mt-4 p-4 text-sm text-red-700 bg-red-100 dark:bg-red-200 dark:text-red-800 rounded-lg">
                                <strong>ATENCIÓN:</strong> Debido a la reducción en el número de grupos de una asignatura sobre la que mantiene derecho de reserva, 
                                es posible que al terminar la 1ª fase no se pueda realizar la asignación.
                                Las asignaturas con este problema aparecen sobre fondo rojo.
                            </div>
                        @endif
                        
                        @if(isset($asignaciones_previas) && $asignaciones_previas->where('posible_no_fase2', true)->count() > 0)
                            <div class="mt-4 p-4 text-sm text-yellow-700 bg-yellow-100 dark:bg-yellow-200 dark:text-yellow-800 rounded-lg">
                                <strong>ATENCIÓN:</strong> Debido a la reducción en el número de grupos de una asignatura sobre la que mantiene derecho de reserva, 
                                es posible que todos los grupos queden asignados en primera fase, por lo que si no lo reserva, (y a pesar de haberlo impartido durante 
                                menos de {{ $cursos_con_preferencia ?? 'N/A' }} años), no podrá impartirlo el curso que viene. Dichas asignaturas aparecen sobre fondo amarillo 
                                y eventualmente en rojo si además es posible que la reserva no garantice la asignación.
                            </div>
                        @endif
                        
                        <div class="text-center mt-6">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Mantener asignaturas seleccionadas
                            </button>
                        </div>
                    </form>
                @else
                    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4">
                        Actualmente no tiene ninguna asignatura para mantener.
                    </div>
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
            </div>        </div>
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