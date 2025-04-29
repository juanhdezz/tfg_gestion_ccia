<!-- filepath: /resources/views/ordenacion/resumen.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-4" id="contenido-imprimible">
        <div class="print:hidden">
            <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-blue-500">
                Resumen de Ordenación Docente
            </h1>
            
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <p class="text-gray-600 dark:text-gray-300">{{ $curso_siguiente }}</p>
                    <p class="text-gray-600 dark:text-gray-300">Fecha: {{ now()->format('d/m/Y') }}</p>
                </div>
                <div>
                    <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Imprimir Resumen
                    </button>
                </div>
            </div>
        </div>

        <!-- Para impresión -->
        <div class="hidden print:block mb-8">
            <div class="flex justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Resumen de Ordenación Docente</h1>
                    <p>{{ $curso_siguiente }}</p>
                </div>
                <div class="text-right">
                    <p>Departamento de Ciencias de la Computación e I.A.</p>
                    <p>Universidad de Granada</p>
                    <p>Fecha: {{ now()->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Datos del profesor -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden print:mb-8 print:border print:border-gray-300">
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600 print:bg-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Datos del Profesor</h2>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-700 dark:text-gray-300"><strong>Nombre:</strong> {{ $usuario->nombre }} {{ $usuario->apellidos }}</p>
                        <p class="text-gray-700 dark:text-gray-300"><strong>Categoría:</strong> {{ $usuario->categoriaDocente->nombre_categoria }}</p>
                    </div>
                    <div>
                        <p class="text-gray-700 dark:text-gray-300"><strong>Email:</strong> {{ $usuario->email }}</p>
                        <p class="text-gray-700 dark:text-gray-300"><strong>Créditos de docencia:</strong> {{ $usuario->categoriaDocente->creditos_docencia }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Compensaciones y reducciones -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden print:mb-8 print:border print:border-gray-300">
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600 print:bg-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Compensaciones Docentes</h2>
            </div>
            <div class="p-4">
                @if($creditos_compensacion > 0)
                    <p class="text-gray-700 dark:text-gray-300 mb-2">Total de créditos de compensación: <strong>{{ $creditos_compensacion }}</strong></p>
                    <p class="text-gray-700 dark:text-gray-300">Para ver el detalle de sus compensaciones docentes contacte con el responsable del departamento.</p>
                @else
                    <p class="text-gray-700 dark:text-gray-300">No tiene compensaciones docentes aplicadas.</p>
                @endif
            </div>
        </div>

        <!-- Docencia asignada para el próximo curso -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden print:mb-8 print:border print:border-gray-300">
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600 print:bg-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Docencia Asignada para {{ $curso_siguiente }}</h2>
            </div>
            <div class="p-4">
                @if(count($asignaciones) > 0)
                    <div class="overflow-x-auto relative">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 print:border-collapse print:w-full">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 print:bg-gray-200">
                                <tr>
                                    <th scope="col" class="px-6 py-3 print:border print:border-gray-300">Asignatura</th>
                                    <th scope="col" class="px-6 py-3 print:border print:border-gray-300">Titulación</th>
                                    <th scope="col" class="px-6 py-3 print:border print:border-gray-300">Curso</th>
                                    <th scope="col" class="px-6 py-3 print:border print:border-gray-300">Cuatr.</th>
                                    <th scope="col" class="px-6 py-3 print:border print:border-gray-300">Tipo</th>
                                    <th scope="col" class="px-6 py-3 print:border print:border-gray-300">Grupo</th>
                                    <th scope="col" class="px-6 py-3 print:border print:border-gray-300">Créditos</th>
                                    <th scope="col" class="px-6 py-3 print:border print:border-gray-300">Fase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalCreditos = 0; $totalPosgrado = 0; @endphp
                                @foreach($asignaciones as $asignacion)
                                    @php 
                                        $esPosgrado = in_array($asignacion->asignatura->id_titulacion, ['99999', '1003', '1004']);
                                        if($esPosgrado) {
                                            $totalPosgrado += $asignacion->creditos;
                                        } else {
                                            $totalCreditos += $asignacion->creditos;
                                        }
                                    @endphp
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 print:border-b print:border-gray-300">
                                        <td class="px-6 py-4 font-medium print:border print:border-gray-300">
                                            {{ $asignacion->asignatura->nombre_asignatura }}
                                            @if($esPosgrado)
                                                <span class="text-blue-600 dark:text-blue-400 print:text-blue-800">(Posgrado)</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 print:border print:border-gray-300">
                                            {{ $asignacion->asignatura->titulacion->nombre_titulacion ?? 'Libre Configuración Específica' }}
                                        </td>
                                        <td class="px-6 py-4 print:border print:border-gray-300">{{ $asignacion->asignatura->curso }}º</td>
                                        <td class="px-6 py-4 print:border print:border-gray-300">{{ $asignacion->asignatura->cuatrimestre }}</td>
                                        <td class="px-6 py-4 print:border print:border-gray-300">{{ $asignacion->tipo }}</td>
                                        <td class="px-6 py-4 print:border print:border-gray-300">{{ $asignacion->grupo }}</td>
                                        <td class="px-6 py-4 print:border print:border-gray-300">{{ $asignacion->creditos }}</td>
                                        <td class="px-6 py-4 print:border print:border-gray-300">
                                            @if($asignacion->en_primera_fase)
                                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300 print:bg-transparent print:text-green-800 print:border print:border-green-800 print:px-1">
                                                    Primera fase
                                                </span>
                                            @else
                                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300 print:bg-transparent print:text-blue-800 print:border print:border-blue-800 print:px-1">
                                                    Segunda fase
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <!-- Fila de total de créditos estándar -->
                                <tr class="bg-gray-50 border-b dark:bg-gray-700 print:border-b print:border-gray-300">
                                    <td colspan="6" class="px-6 py-4 text-right font-bold print:border print:border-gray-300">Total créditos estándar:</td>
                                    <td class="px-6 py-4 font-bold print:border print:border-gray-300">{{ $totalCreditos }}</td>
                                    <td class="print:border print:border-gray-300"></td>
                                </tr>
                                <!-- Fila de total de créditos posgrado -->
                                <tr class="bg-gray-50 border-b dark:bg-gray-700 print:border-b print:border-gray-300">
                                    <td colspan="6" class="px-6 py-4 text-right font-bold print:border print:border-gray-300">Total créditos posgrado:</td>
                                    <td class="px-6 py-4 font-bold print:border print:border-gray-300">{{ $totalPosgrado }}</td>
                                    <td class="print:border print:border-gray-300"></td>
                                </tr>
                                <!-- Fila de total general -->
                                <tr class="bg-gray-200 dark:bg-gray-600 font-bold print:bg-gray-200">
                                    <td colspan="6" class="px-6 py-4 text-right print:border print:border-gray-300">TOTAL CRÉDITOS:</td>
                                    <td class="px-6 py-4 print:border print:border-gray-300">{{ $totalCreditos + $totalPosgrado }}</td>
                                    <td class="print:border print:border-gray-300"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 print:border print:border-yellow-400 print:bg-yellow-50">
                        No tiene asignaturas asignadas para el próximo curso.
                    </div>
                @endif
            </div>
        </div>

        <!-- Resumen de carga docente -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden print:mb-8 print:border print:border-gray-300">
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600 print:bg-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Resumen de Carga Docente</h2>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 print:grid-cols-2">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg print:bg-gray-100 print:border print:border-gray-300">
                        <h3 class="text-lg font-semibold mb-3 text-gray-800 dark:text-white">Créditos Disponibles</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Créditos de docencia:</span>
                                <span class="font-medium">{{ $usuario->categoriaDocente->creditos_docencia }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Compensaciones:</span>
                                <span class="font-medium">{{ $creditos_compensacion }}</span>
                            </div>
                            <div class="border-t border-gray-300 dark:border-gray-600 my-2 print:border-gray-400"></div>
                            <div class="flex justify-between font-bold">
                                <span>Total a impartir:</span>
                                <span>{{ $usuario->categoriaDocente->creditos_docencia - $creditos_compensacion }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg print:bg-gray-100 print:border print:border-gray-300">
                        <h3 class="text-lg font-semibold mb-3 text-gray-800 dark:text-white">Créditos Asignados</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Créditos estándar:</span>
                                <span class="font-medium">{{ $totalCreditos }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Créditos posgrado:</span>
                                <span class="font-medium">{{ $totalPosgrado }}</span>
                            </div>
                            <div class="border-t border-gray-300 dark:border-gray-600 my-2 print:border-gray-400"></div>
                            <div class="flex justify-between font-bold">
                                <span>Total asignado:</span>
                                <span>{{ $totalCreditos + $totalPosgrado }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 print:mt-4">
                    @php
                        $diferenciaCreditos = ($totalCreditos + $totalPosgrado) - ($usuario->categoriaDocente->creditos_docencia - $creditos_compensacion);
                    @endphp

                    @if(abs($diferenciaCreditos) < 0.1)
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 print:bg-green-50 print:border print:border-green-500">
                            <p class="font-medium">Carga docente equilibrada correctamente.</p>
                        </div>
                    @elseif($diferenciaCreditos > 0)
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 print:bg-yellow-50 print:border print:border-yellow-500">
                            <p class="font-medium">Exceso de créditos: {{ number_format($diferenciaCreditos, 2) }} créditos por encima de lo requerido.</p>
                        </div>
                    @else
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 print:bg-red-50 print:border print:border-red-500">
                            <p class="font-medium">Déficit de créditos: {{ number_format(abs($diferenciaCreditos), 2) }} créditos por debajo de lo requerido.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Distribución por cuatrimestres -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden print:mb-8 print:border print:border-gray-300">
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600 print:bg-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Distribución por Cuatrimestres</h2>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 print:grid-cols-2">
                    @php
                        $creditosPrimerCuatrimestre = 0;
                        $creditosSegundoCuatrimestre = 0;
                        $creditosAnuales = 0;
                        
                        foreach($asignaciones as $asignacion) {
                            if($asignacion->asignatura->cuatrimestre == 1) {
                                $creditosPrimerCuatrimestre += $asignacion->creditos;
                            } else if($asignacion->asignatura->cuatrimestre == 2) {
                                $creditosSegundoCuatrimestre += $asignacion->creditos;
                            } else {
                                $creditosAnuales += $asignacion->creditos;
                            }
                        }
                    @endphp
                    
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg print:bg-gray-100 print:border print:border-gray-300">
                        <h3 class="text-lg font-semibold mb-3 text-gray-800 dark:text-white">Primer Cuatrimestre</h3>
                        <div class="text-3xl font-bold text-center text-blue-600 dark:text-blue-400">
                            {{ number_format($creditosPrimerCuatrimestre, 2) }}
                        </div>
                        <p class="text-center text-gray-500 dark:text-gray-400 mt-2">créditos</p>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg print:bg-gray-100 print:border print:border-gray-300">
                        <h3 class="text-lg font-semibold mb-3 text-gray-800 dark:text-white">Segundo Cuatrimestre</h3>
                        <div class="text-3xl font-bold text-center text-blue-600 dark:text-blue-400">
                            {{ number_format($creditosSegundoCuatrimestre, 2) }}
                        </div>
                        <p class="text-center text-gray-500 dark:text-gray-400 mt-2">créditos</p>
                    </div>
                </div>
                
                @if($creditosAnuales > 0)
                    <div class="mt-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg print:bg-gray-100 print:border print:border-gray-300">
                            <h3 class="text-lg font-semibold mb-3 text-gray-800 dark:text-white">Asignaturas Anuales</h3>
                            <div class="text-3xl font-bold text-center text-blue-600 dark:text-blue-400">
                                {{ number_format($creditosAnuales, 2) }}
                            </div>
                            <p class="text-center text-gray-500 dark:text-gray-400 mt-2">créditos</p>
                        </div>
                    </div>
                @endif
                
                @if(abs($creditosPrimerCuatrimestre - $creditosSegundoCuatrimestre) > 3)
                    <div class="mt-6">
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 print:bg-yellow-50 print:border print:border-yellow-500">
                            <p class="font-medium">Desequilibrio entre cuatrimestres: La diferencia es de {{ number_format(abs($creditosPrimerCuatrimestre - $creditosSegundoCuatrimestre), 2) }} créditos.</p>
                            <p class="mt-2">Se recomienda una distribución más equilibrada entre ambos cuatrimestres.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Pie de página para impresión -->
        <div class="mt-8 text-center hidden print:block">
            <p class="text-sm text-gray-600">Este documento es un resumen informativo de la carga docente asignada.</p>
            <p class="text-sm text-gray-600">Generado el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i') }}</p>
        </div>
    </div>

    @push('scripts')
    <style>
        @media print {
            @page {
                size: portrait;
                margin: 1.5cm;
            }
            
            body {
                font-family: Arial, sans-serif;
                color: #000;
                background-color: #fff;
            }
            
            /* Ocultar elementos no necesarios para impresión */
            nav, header, footer, .print:hidden {
                display: none !important;
            }
            
            /* Estilos para tablas en impresión */
            table {
                width: 100%;
                border-collapse: collapse;
            }
            
            td, th {
                padding: 8px;
                border: 1px solid #ddd;
            }
            
            /* Saltos de página */
            .page-break {
                page-break-after: always;
            }
        }
    </style>
    <script>
        // Script para recargar la página después de imprimir
        // para asegurar que todos los estilos vuelvan a cargarse correctamente
        window.addEventListener('afterprint', function() {
            setTimeout(function() {
                window.location.reload();
            }, 100);
        });
    </script>
    @endpush
</x-app-layout>