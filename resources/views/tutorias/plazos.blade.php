<x-app-layout>
    <div class="container mx-auto p-6">
        <div class="max-w-6xl mx-auto">
            <!-- Título principal -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                    Plazos de Tutorías
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300">
                    Información sobre los plazos para modificar tutorías en todos los cursos académicos
                </p>
            </div>            <!-- Navegación -->
            <div class="mb-6">
                <a href="{{ route('tutorias.gestion') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver a Gestión
                </a>
            </div>

            @if($plazos->count() > 0)
                <!-- Lista de plazos -->
                <div class="space-y-4">
                    @foreach($plazos->groupBy(function($plazo) { 
                        return str_contains($plazo->nombre_plazo, 'CURSO SIGUIENTE') ? 'Próximo Curso' : 'Curso Actual';
                    }) as $grupo => $plazosGrupo)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <!-- Cabecera del grupo -->
                            <div class="bg-gradient-to-r {{ $grupo === 'Curso Actual' ? 'from-blue-600 to-blue-700' : 'from-green-600 to-green-700' }} text-white p-4">
                                <h2 class="text-xl font-bold">{{ $grupo }}</h2>
                            </div>

                            <!-- Tabla de plazos -->
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Plazo
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Fecha Inicio
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Fecha Fin
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Estado
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Descripción
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($plazosGrupo as $plazo)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        @if(str_contains($plazo->nombre_plazo, 'PRIMER'))
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 mr-2">
                                                                1º Semestre
                                                            </span>
                                                        @elseif(str_contains($plazo->nombre_plazo, 'SEGUNDO'))
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100 mr-2">
                                                                2º Semestre
                                                            </span>
                                                        @endif
                                                        {{ $plazo->nombre_plazo }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900 dark:text-white">
                                                        {{ $plazo->fecha_inicio_formateada }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900 dark:text-white">
                                                        {{ $plazo->fecha_fin_formateada }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($plazo->activo)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                            <span class="w-2 h-2 bg-green-400 rounded-full mr-1 animate-pulse"></span>
                                                            Activo
                                                        </span>
                                                    @else
                                                        @php
                                                            $fechaActual = \Carbon\Carbon::now();
                                                            $fechaInicio = \Carbon\Carbon::parse($plazo->fecha_inicio);
                                                            $fechaFin = \Carbon\Carbon::parse($plazo->fecha_fin);
                                                        @endphp
                                                        
                                                        @if($fechaActual->lt($fechaInicio))
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                                </svg>
                                                                Próximamente
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z" clip-rule="evenodd"></path>
                                                                </svg>
                                                                Finalizado
                                                            </span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $plazo->descripcion ?? 'Plazo para modificar tutorías' }}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Panel de información -->
                <div class="mt-8 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Información sobre los Plazos
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-blue-800 dark:text-blue-200">
                        <div>
                            <h4 class="font-medium mb-2">📅 Estados de los Plazos:</h4>
                            <ul class="space-y-1 list-disc list-inside ml-4">
                                <li><span class="inline-block w-2 h-2 bg-green-400 rounded-full mr-1"></span><strong>Activo:</strong> Puede modificar tutorías</li>
                                <li><span class="inline-block w-2 h-2 bg-yellow-400 rounded-full mr-1"></span><strong>Próximamente:</strong> Plazo aún no iniciado</li>
                                <li><span class="inline-block w-2 h-2 bg-gray-400 rounded-full mr-1"></span><strong>Finalizado:</strong> Plazo ya cerrado</li>
                            </ul>
                        </div>
                        
                        <div>
                            <h4 class="font-medium mb-2">🎯 Importante:</h4>
                            <ul class="space-y-1 list-disc list-inside ml-4">
                                <li>Solo puede editar durante plazos activos</li>
                                <li>Fuera del plazo solo puede consultar</li>
                                <li>Cada semestre tiene su propio plazo</li>
                                <li>Los cambios se guardan automáticamente</li>
                            </ul>
                        </div>
                    </div>
                </div>

            @else
                <!-- Estado vacío -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay plazos configurados</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        No se encontraron plazos de tutorías en la base de datos actual.
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
