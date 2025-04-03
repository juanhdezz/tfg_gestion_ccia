{{-- filepath: c:\xampp\htdocs\laravel\tfg_gestion_ccia\resources\views\reserva_salas\pendientes.blade.php --}}
<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-200">
            Reservas Pendientes de Validación
        </h1>
        
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                @if($reservasPendientes->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Sala</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Horario</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Usuario</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Motivo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Observaciones</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($reservasPendientes as $reserva)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                            {{ $reserva->sala->nombre }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                            {{ $reserva->fecha->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                            {{ $reserva->hora_inicio->format('H:i') }} - {{ $reserva->hora_fin->format('H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                            {{ $reserva->usuario->nombre }} {{ $reserva->usuario->apellidos }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                            {{ $reserva->motivo->descripcion }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200">
                                            {{ Str::limit($reserva->observaciones, 50) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button type="button" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs" 
                                                        onclick="openModal('validarModal{{ $reserva->id_sala }}_{{ $reserva->fecha->format('Y-m-d') }}_{{ $reserva->hora_inicio->format('H-i') }}')">
                                                    <i class="fas fa-check"></i> Validar
                                                </button>
                                                <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs"
                                                        onclick="openModal('rechazarModal{{ $reserva->id_sala }}_{{ $reserva->fecha->format('Y-m-d') }}_{{ $reserva->hora_inicio->format('H-i') }}')">
                                                    <i class="fas fa-times"></i> Rechazar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $reservasPendientes->links() }}
                    </div>
                @else
                    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded">
                        No hay reservas pendientes de validación.
                    </div>
                @endif
                
                <div class="mt-6">
                    <a href="{{ route('reserva_salas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <i class="fas fa-arrow-left mr-2"></i> Volver al listado
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modales de validación y rechazo -->
    @foreach($reservasPendientes as $reserva)
        <!-- Modal Validar -->
        <div id="validarModal{{ $reserva->id_sala }}_{{ $reserva->fecha->format('Y-m-d') }}_{{ $reserva->hora_inicio->format('H-i') }}" 
             class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Validar Reserva</h3>
                </div>
                <form action="{{ route('reserva_salas.procesar', ['id_sala' => $reserva->id_sala, 'fecha' => $reserva->fecha->format('Y-m-d'), 'hora_inicio' => $reserva->hora_inicio->format('H:i:s'), 'estado' => $reserva->estado]) }}" method="POST">
                    @csrf
                    <div class="px-6 py-4">
                        <p class="mb-3 text-gray-700 dark:text-gray-300">¿Está seguro que desea validar la siguiente reserva?</p>
                        <ul class="list-disc list-inside mb-4 text-gray-700 dark:text-gray-300">
                            <li><span class="font-semibold">Sala:</span> {{ $reserva->sala->nombre }}</li>
                            <li><span class="font-semibold">Fecha:</span> {{ $reserva->fecha->format('d/m/Y') }}</li>
                            <li><span class="font-semibold">Horario:</span> {{ $reserva->hora_inicio->format('H:i') }} - {{ $reserva->hora_fin->format('H:i') }}</li>
                            <li><span class="font-semibold">Usuario:</span> {{ $reserva->usuario->nombre }} {{ $reserva->usuario->apellidos }}</li>
                            <li><span class="font-semibold">Motivo:</span> {{ $reserva->motivo->descripcion }}</li>
                        </ul>
                        <input type="hidden" name="decision" value="validar">
                        <div class="mb-4">
                            <label for="observaciones" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Observaciones (opcional):</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" 
                                      name="observaciones" rows="3">{{ $reserva->observaciones }}</textarea>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 flex justify-end space-x-3 rounded-b-lg">
                        <button type="button" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md"
                                onclick="closeModal('validarModal{{ $reserva->id_sala }}_{{ $reserva->fecha->format('Y-m-d') }}_{{ $reserva->hora_inicio->format('H-i') }}')">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md">
                            Validar Reserva
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Modal Rechazar -->
        <div id="rechazarModal{{ $reserva->id_sala }}_{{ $reserva->fecha->format('Y-m-d') }}_{{ $reserva->hora_inicio->format('H-i') }}" 
             class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Rechazar Reserva</h3>
                </div>
                <form action="{{ route('reserva_salas.procesar', ['id_sala' => $reserva->id_sala, 'fecha' => $reserva->fecha->format('Y-m-d'), 'hora_inicio' => $reserva->hora_inicio->format('H:i:s'), 'estado' => $reserva->estado]) }}" method="POST">
                    @csrf
                    <div class="px-6 py-4">
                        <p class="mb-3 text-gray-700 dark:text-gray-300">¿Está seguro que desea rechazar la siguiente reserva?</p>
                        <ul class="list-disc list-inside mb-4 text-gray-700 dark:text-gray-300">
                            <li><span class="font-semibold">Sala:</span> {{ $reserva->sala->nombre }}</li>
                            <li><span class="font-semibold">Fecha:</span> {{ $reserva->fecha->format('d/m/Y') }}</li>
                            <li><span class="font-semibold">Horario:</span> {{ $reserva->hora_inicio->format('H:i') }} - {{ $reserva->hora_fin->format('H:i') }}</li>
                            <li><span class="font-semibold">Usuario:</span> {{ $reserva->usuario->nombre }} {{ $reserva->usuario->apellidos }}</li>
                            <li><span class="font-semibold">Motivo:</span> {{ $reserva->motivo->descripcion }}</li>
                        </ul>
                        <input type="hidden" name="decision" value="rechazar">
                        <div class="mb-4">
                            <label for="observaciones" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Motivo del rechazo (requerido):</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" 
                                      name="observaciones" rows="3" required>{{ $reserva->observaciones }}</textarea>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 flex justify-end space-x-3 rounded-b-lg">
                        <button type="button" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md"
                                onclick="closeModal('rechazarModal{{ $reserva->id_sala }}_{{ $reserva->fecha->format('Y-m-d') }}_{{ $reserva->hora_inicio->format('H-i') }}')">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md">
                            Rechazar Reserva
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
    
    @push('scripts')
    <script>
        // Funciones para manejar los modales
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
        
        // Cerrar modales al hacer clic fuera
        document.addEventListener('click', function(event) {
            const modals = document.querySelectorAll('[id^="validarModal"], [id^="rechazarModal"]');
            modals.forEach(function(modal) {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
