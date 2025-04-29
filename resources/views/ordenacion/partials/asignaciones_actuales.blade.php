<!-- filepath: /resources/views/ordenacion/partials/asignaciones_actuales.blade.php -->
@if(count($asignaciones_actuales) > 0)
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Asignatura</th>
                    <th scope="col" class="px-6 py-3">Titulación</th>
                    <th scope="col" class="px-6 py-3">Curso</th>
                    <th scope="col" class="px-6 py-3">Tipo</th>
                    <th scope="col" class="px-6 py-3">Cuatr.</th>
                    <th scope="col" class="px-6 py-3">Grupo</th>
                    <th scope="col" class="px-6 py-3">Créditos</th>
                    <th scope="col" class="px-6 py-3">Fase</th>
                    <th scope="col" class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @php $totalCreditos = 0; @endphp
                @foreach($asignaciones_actuales as $asignacion)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4">
                            {{ $asignacion->asignatura->nombre_asignatura }}
                            @if(in_array($asignacion->asignatura->id_titulacion, ['99999', '1003', '1004']))
                                <span class="text-blue-600 dark:text-blue-400">(Posgrado)</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            {{ $asignacion->asignatura->titulacion->nombre_titulacion ?? 'Libre Configuración Específica' }}
                        </td>
                        <td class="px-6 py-4">{{ $asignacion->asignatura->curso }}º</td>
                        <td class="px-6 py-4">{{ $asignacion->tipo }}</td>
                        <td class="px-6 py-4">{{ $asignacion->asignatura->cuatrimestre }}</td>
                        <td class="px-6 py-4">
                            @if(isset($es_turno_usuario) && $es_turno_usuario)
                                <form action="{{ route('ordenacion.cambiar-grupo') }}" method="POST" class="flex space-x-2">
                                    @csrf
                                    <input type="hidden" name="id_asignatura" value="{{ $asignacion->id_asignatura }}">
                                    <input type="hidden" name="tipo" value="{{ $asignacion->tipo }}">
                                    <input type="hidden" name="grupo_original" value="{{ $asignacion->grupo }}">
                                    
                                    <select name="grupo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-16">
                                        @for($i = 1; $i <= ($asignacion->tipo == 'Teoría' ? 
                                              $asignacion->asignatura->grupos_teoria : 
                                              $asignacion->asignatura->grupos_practicas); $i++)
                                            <option value="{{ $i }}" {{ $i == $asignacion->grupo ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    
                                    <button type="submit" class="text-blue-600 dark:text-blue-500 hover:underline">
                                        Cambiar
                                    </button>
                                </form>
                            @else
                                {{ $asignacion->grupo }}
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $asignacion->creditos }}</td>
                        <td class="px-6 py-4">
                            @if($asignacion->en_primera_fase)
                                <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                    Primera fase
                                </span>
                            @else
                                <span class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                    Segunda fase
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('ordenacion.eliminar') }}" method="POST" class="inline-block">
                                @csrf
                                <input type="hidden" name="id_asignatura" value="{{ $asignacion->id_asignatura }}">
                                <input type="hidden" name="tipo" value="{{ $asignacion->tipo }}">
                                <input type="hidden" name="grupo" value="{{ $asignacion->grupo }}">
                                <input type="hidden" name="creditos" value="{{ $asignacion->creditos }}">
                                <button type="submit" 
                                        onclick="return confirm('¿Está seguro de que desea eliminar esta asignación?')"
                                        class="font-medium text-red-600 dark:text-red-500 hover:underline">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @php $totalCreditos += $asignacion->creditos; @endphp
                @endforeach
                <tr class="bg-gray-50 border-b dark:bg-gray-700 dark:border-gray-600">
                    <td colspan="6" class="px-6 py-4 text-right font-bold">Total créditos:</td>
                    <td class="px-6 py-4 font-bold">{{ $totalCreditos }}</td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>
    </div>
@else
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4">
        No tiene asignaturas asignadas para el próximo curso.
    </div>
@endif