<x-app-layout>
    <div class="container mx-auto p-6">
        <div class="max-w-6xl mx-auto">            <!-- Título principal -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                    Gestión de Tutorías
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300">
                    Seleccione el curso académico y semestre para gestionar las tutorías
                </p>
            </div>

            <!-- Información del usuario y contexto actual -->
            <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-4 mb-6">
                <div class="flex items-center space-x-2 text-blue-800 dark:text-blue-200">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">
                        Usuario: {{ Auth::user()->nombre }} {{ Auth::user()->apellido1 }} {{ Auth::user()->apellido2 }}
                    </span>
                </div>
                @if(Auth::user()->id_despacho)
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        Despacho asignado: {{ Auth::user()->despacho->nombre_despacho ?? 'No asignado' }}
                    </div>
                @endif
            </div>

            <!-- Grid de cursos académicos -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                @foreach($cursosDisponibles as $curso)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <!-- Cabecera del curso -->
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-2xl font-bold">{{ $curso['nombre_corto'] }}</h2>
                                    <p class="text-blue-100 mt-1">{{ $curso['nombre_completo'] }}</p>
                                </div>
                                <div class="text-right">
                                    @if($curso['conexion'] === Session::get('db_connection', 'mysql'))
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-500 text-white">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            Activo
                                        </span>
                                    @else                                        <form action="{{ route('cambiar.base.datos') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="connection" value="{{ $curso['conexion'] }}">
                                            <input type="hidden" name="context" value="tutorias">
                                            <button type="submit" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-400 hover:bg-blue-300 text-white transition-colors">
                                                Activar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Semestres -->
                        <div class="p-6">
                            <div class="grid grid-cols-1 gap-4">
                                @foreach($curso['semestres'] as $semestre)
                                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <div class="flex items-center justify-between mb-3">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                {{ $semestre['nombre'] }}
                                            </h3>
                                            @if($semestre['activo'])
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-1 animate-pulse"></span>
                                                    Plazo abierto
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                    Plazo cerrado
                                                </span>
                                            @endif
                                        </div>

                                        <div class="flex space-x-3">
                                            <!-- Botón para editar/ver tutorías -->
                                            @if($curso['conexion'] === Session::get('db_connection', 'mysql'))
                                                @if($semestre['activo'])
                                                    <a href="{{ route('tutorias.index', ['cuatrimestre' => $semestre['numero']]) }}" 
                                                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg font-medium transition-colors">
                                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Editar Tutorías
                                                    </a>
                                                @else
                                                    <a href="{{ route('tutorias.ver', ['cuatrimestre' => $semestre['numero']]) }}" 
                                                       class="flex-1 bg-gray-600 hover:bg-gray-700 text-white text-center py-2 px-4 rounded-lg font-medium transition-colors">
                                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        Ver Tutorías
                                                    </a>
                                                @endif
                                            @else
                                                <div class="flex-1 bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 text-center py-2 px-4 rounded-lg font-medium cursor-not-allowed">
                                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m2-12V3m0 0V1m0 2h2M12 3H10m7 4h2m0 0h2m-2 0v2m0-2V5m-7 7h2m0 0h2m-2 0v2m0-2V10"></path>
                                                    </svg>
                                                    Cambiar a este curso
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Información adicional del plazo si está disponible -->
                                        @php
                                            $plazoInfo = collect($plazosInfo)->firstWhere(function($plazo) use ($semestre, $curso) {
                                                return $plazo['conexion'] === $curso['conexion'] && 
                                                       str_contains($plazo['nombre'], $semestre['numero'] == 1 ? 'PRIMER' : 'SEGUNDO');
                                            });
                                        @endphp

                                        @if($plazoInfo)
                                            <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                                                <div class="flex items-center space-x-4">
                                                    <span>📅 {{ $plazoInfo['fecha_inicio'] }} - {{ $plazoInfo['fecha_fin'] }}</span>
                                                    @if($plazoInfo['activo'] && $plazoInfo['dias_restantes'] > 0)
                                                        <span class="text-orange-600 dark:text-orange-400 font-medium">
                                                            ⏰ {{ $plazoInfo['dias_restantes'] }} días restantes
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Panel de información adicional -->
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    Información Importante
                </h3>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700 dark:text-gray-300">
                    <div class="space-y-2">
                        <h4 class="font-medium text-gray-900 dark:text-white">📋 Requisitos para las Tutorías:</h4>
                        <ul class="space-y-1 list-disc list-inside ml-4">
                            <li>Horas semanales por semestre según categoría docente</li>
                            <li>Cálculo: (créditos_docencia ÷ 3) con máximo 6 horas</li>
                            <li>Por defecto: 6 horas si no hay categoría asignada</li>
                            <li>Horarios en intervalos de 30 minutos</li>
                            <li>Solo durante el plazo establecido</li>
                            <li>Despacho debe estar asignado</li>
                        </ul>
                    </div>
                    
                    <div class="space-y-2">
                        <h4 class="font-medium text-gray-900 dark:text-white">🔄 Cambio entre Cursos:</h4>
                        <ul class="space-y-1 list-disc list-inside ml-4">
                            <li>Use el botón "Activar" para cambiar de curso</li>
                            <li>Solo puede editar el curso activo</li>
                            <li>Los cambios se guardan automáticamente</li>
                            <li>Puede consultar ambos cursos</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-4 flex justify-center">
                    <a href="{{ route('tutorias.plazos') }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Ver Todos los Plazos
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
