{{-- filepath: c:\xampp\htdocs\laravel\tfg_gestion_ccia\resources\views\reserva_salas\calendario.blade.php --}}
<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-blue-500">Calendario de Reservas</h1>

        <!-- Filtros para el calendario -->
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <form method="GET" action="{{ route('reserva_salas.calendario') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Selector de sala -->
                <div>
                    <label for="id_sala" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sala:</label>
                    <select name="id_sala" id="id_sala" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600">
                        @foreach($salas as $sala)
                            <option value="{{ $sala->id_sala }}" {{ $salaSeleccionada == $sala->id_sala ? 'selected' : '' }}>
                                {{ $sala->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Selector de semana -->
                <div>
                    <label for="semana" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Semana:</label>
                    <input type="week" id="semana" name="semana" value="{{ $semana ?? date('Y-W') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600">
                </div>

                <!-- Botones de acción -->
                <div class="flex gap-2 items-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-md text-white">
                        Ver reservas
                    </button>
                    <a href="{{ route('reserva_salas.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded-md text-white">
                        Volver al listado
                    </a>
                    <a href="{{ route('reserva_salas.create') }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded-md text-white">
                        Realizar Reserva
                    </a>
                </div>
            </form>
        </div>

        <!-- Información de la sala seleccionada -->
        <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 dark:bg-blue-900/20 dark:border-blue-500/40">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2h.01a1 1 0 000-2H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700 dark:text-blue-400">
                        <strong>{{ $salaInfo->nombre ?? 'Seleccione una sala' }}</strong>
                        @if(isset($salaInfo))
                            - {{ $salaInfo->localizacion }} ({{ $salaInfo->capacidad }} personas)
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Calendario semanal por intervalos de media hora -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <!-- Cabecera de días -->
            <div class="grid grid-cols-6 border-b border-gray-200 dark:border-gray-700">
                <div class="p-3 font-semibold text-center text-gray-500 dark:text-gray-400 border-r border-gray-200 dark:border-gray-700">Hora</div>
                @foreach($dias as $dia)
                    <div class="p-3 font-semibold text-center {{ $dia['esHoy'] ? 'bg-blue-100 dark:bg-blue-900/30' : '' }}">
                        <div class="text-gray-800 dark:text-gray-200">{{ $dia['nombre'] }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $dia['fecha'] }}</div>
                    </div>
                @endforeach
            </div>

            <!-- Filas de horas con intervalos de media hora -->
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($intervalos as $intervalo)
                    <div class="grid grid-cols-6 min-h-[40px] {{ $intervalo['esComienzo'] ? 'border-t-2 border-gray-300 dark:border-gray-600' : '' }}">
                        <!-- Columna de la hora -->
                        <div class="p-1 text-center text-sm text-gray-500 dark:text-gray-400 border-r border-gray-200 dark:border-gray-700 flex items-center justify-center font-mono {{ $intervalo['esComienzo'] ? 'font-bold' : 'text-xs' }}">
                            {{ $intervalo['hora'] }}
                        </div>

                        <!-- Columnas de días con sus reservas -->
                        @foreach($dias as $indiceDia => $dia)
                            <div class="p-1 relative {{ $dia['esHoy'] ? 'bg-blue-50 dark:bg-blue-900/10' : '' }} hover:bg-gray-50 dark:hover:bg-gray-700/30
                                {{ $intervalo['esComienzo'] ? 'border-t border-gray-300 dark:border-gray-600' : '' }}">
                                @if(isset($reservas[$indiceDia][$intervalo['valor']]))
                                    @foreach($reservas[$indiceDia][$intervalo['valor']] as $reserva)
                                        <a href="{{ route('reserva_salas.show', [
                                            'id_sala' => $reserva->id_sala,
                                            'fecha' => $reserva->fecha->format('Y-m-d'),
                                            'hora_inicio' => $reserva->hora_inicio->format('H:i:s'),
                                            'estado' => $reserva->estado
                                        ]) }}" class="block rounded p-1 text-xs h-full w-full overflow-hidden
                                            {{ $reserva->estado == 'Validada' ? 'bg-green-200 dark:bg-green-900/70 text-green-800 dark:text-green-200' : 
                                               ($reserva->estado == 'Pendiente Validación' ? 'bg-yellow-200 dark:bg-yellow-900/70 text-yellow-800 dark:text-yellow-200' : 
                                               'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200') }}">
                                            <div class="font-bold">{{ $reserva->hora_inicio->format('H:i') }} - {{ $reserva->hora_fin->format('H:i') }}</div>
                                            <div class="truncate">{{ $reserva->usuario->nombre }} {{ $reserva->usuario->apellidos }}</div>
                                            <div class="truncate">{{ $reserva->motivo->descripcion }}</div>
                                        </a>
                                    @endforeach
                                @else
                                    <!-- Celda vacía, mostrar enlace para reservar esta hora -->
                                    <a href="{{ route('reserva_salas.create', ['fecha' => $dia['fecha_completa'], 'hora_inicio' => $intervalo['valor'], 'id_sala' => $salaSeleccionada]) }}" 
                                       class="block text-center p-2 text-xs text-gray-400 dark:text-gray-500 h-full w-full hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 dark:hover:text-blue-400">
                                        @if($intervalo['esComienzo'])
                                            <svg class="h-4 w-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        @endif
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Leyenda del calendario -->
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
            <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Leyenda</h3>
            <div class="flex flex-wrap gap-4">
                <div class="flex items-center">
                    <div class="w-4 h-4 rounded bg-green-200 dark:bg-green-900/70 mr-2"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Reserva Validada</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 rounded bg-yellow-200 dark:bg-yellow-900/70 mr-2"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Pendiente Validación</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 rounded bg-gray-200 dark:bg-gray-700 mr-2"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Rechazada/Cancelada</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 flex items-center justify-center border border-dashed border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-800 mr-2">
                        <svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Disponible para reservar</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>