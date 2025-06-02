<!-- filepath: /resources/views/ordenacion/fase3.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-purple-500">
            Elección de Ordenación Docente - Tercera Fase
        </h1>

        <div class="mb-4 bg-purple-50 border-l-4 border-purple-400 p-4 dark:bg-purple-200">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-purple-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-purple-700">
                        Actualmente estamos en la <strong>tercera fase</strong>. En esta fase se pueden seleccionar asignaturas adicionales sin eliminar las asignaciones de turnos superiores.
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
                                       class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded">
                                <label for="pasar_turno" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    Pasar Turno
                                    @if(is_null(optional($perfil)->pasar_turno))
                                        <span class="text-gray-500 italic"> - (no configurado)</span>
                                    @endif
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
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
                <button id="toggle-perfil" class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300">
                    Mostrar/Ocultar
                </button>
            </div>
            <div id="perfil-content" class="p-4 hidden">
                @include('ordenacion.partials.perfil_academico')
            </div>
        </div>
        
        <!-- Sección de estado actual del proceso -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden">
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Estado del Proceso</h2>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-purple-50 dark:bg-purple-900/30 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                        <h3 class="font-semibold text-purple-800 dark:text-purple-300">Fase Actual</h3>
                        <p class="text-purple-600 dark:text-purple-400">Tercera Fase</p>
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <h3 class="font-semibold text-blue-800 dark:text-blue-300">Turno Actual</h3>
                        <p class="text-blue-600 dark:text-blue-400">{{ $turno ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/30 border border-gray-200 dark:border-gray-800 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-300">Estado</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            @switch($estado ?? 'unknown')
                                @case('activo')
                                    Proceso Activo
                                    @break
                                @case('pausado')
                                    Proceso Pausado
                                    @break
                                @case('finalizado')
                                    Proceso Finalizado
                                    @break
                                @default
                                    Estado Desconocido
                            @endswitch
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Información adicional sobre la fase 3 -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden">
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Información sobre la Tercera Fase</h2>
            </div>
            <div class="p-4">
                <div class="prose dark:prose-invert max-w-none">
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        En la tercera fase del proceso de ordenación docente:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2">
                        <li>Los profesores pueden seleccionar asignaturas adicionales disponibles</li>
                        <li>No se eliminan las asignaciones ya realizadas por profesores en turnos superiores</li>
                        <li>Se mantiene el orden de turnos establecido para la selección</li>
                        <li>Solo se pueden asignar créditos que queden disponibles en cada asignatura</li>
                        <li>Se respetan las restricciones del perfil académico de cada profesor</li>
                    </ul>                </div>
            </div>
        </div>
        @endrole
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.getElementById('toggle-perfil');
            const perfilContent = document.getElementById('perfil-content');
            
            if (toggleButton && perfilContent) {
                toggleButton.addEventListener('click', function() {
                    perfilContent.classList.toggle('hidden');
                });
            }
        });
    </script>
</x-app-layout>
