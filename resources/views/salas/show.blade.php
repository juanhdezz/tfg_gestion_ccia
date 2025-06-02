<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalles de la Sala') }}
        </h2>
    </x-slot>

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <!-- Encabezado -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $sala->nombre }}</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Información detallada de la sala
                </p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('salas.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver
                </a>
                
                @role('admin|secretario')
                <a href="{{ route('salas.edit', $sala->id_sala) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar
                </a>
                @endrole
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Información de la sala -->
            <div class="lg:col-span-2">
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Información de la Sala
                    </h3>
                    
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nombre</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">{{ $sala->nombre }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Localización</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $sala->localizacion }}</dd>
                        </div>
                        
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Días de Anticipación</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $sala->dias_anticipacion_reserva }} {{ $sala->dias_anticipacion_reserva == 1 ? 'día' : 'días' }}
                                <span class="text-gray-500 dark:text-gray-400 ml-2">
                                    (mínimo para realizar reservas)
                                </span>
                            </dd>
                        </div>
                    </dl>

                    <!-- Fechas de registro -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-600">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Información del Sistema</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-gray-500 dark:text-gray-400">
                            <div>
                                <span class="font-medium">Registrada:</span>
                                {{ $sala->created_at ? $sala->created_at->format('d/m/Y H:i') : 'N/A' }}
                            </div>
                            <div>
                                <span class="font-medium">Última actualización:</span>
                                {{ $sala->updated_at ? $sala->updated_at->format('d/m/Y H:i') : 'N/A' }}
                            </div>
                        </div>                    </div>
                </div>
            </div>

            <!-- Panel lateral con estadísticas -->
            <div class="space-y-6">
                <!-- Estadísticas -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Estadísticas de Uso
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Total de reservas</span>
                            <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $estadisticas['total'] }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Pendientes</span>
                            <span class="text-lg font-bold text-yellow-600 dark:text-yellow-400">{{ $estadisticas['pendientes'] }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Aprobadas</span>
                            <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ $estadisticas['aprobadas'] }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Rechazadas</span>
                            <span class="text-lg font-bold text-red-600 dark:text-red-400">{{ $estadisticas['rechazadas'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Acciones disponibles -->
                @role('admin|secretario')
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Acciones Disponibles
                    </h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('salas.edit', $sala->id_sala) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar Sala
                        </a>
                        
                        <a href="{{ route('reserva_salas.create') }}?sala={{ $sala->id_sala }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nueva Reserva
                        </a>
                        
                        @if($estadisticas['total'] == 0)
                        <button onclick="eliminarSala({{ $sala->id_sala }})" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Eliminar Sala
                        </button>
                        @else
                        <div class="w-full p-3 bg-yellow-100 dark:bg-yellow-900/20 border border-yellow-300 dark:border-yellow-800 rounded-lg">
                            <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                No se puede eliminar: tiene reservas asociadas
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
                @endrole
            </div>
        </div>    </div>
</div>

@role('admin|secretario')
<!-- Formulario oculto para eliminar -->
<form id="deleteSalaForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endrole

@role('admin|secretario')
@push('scripts')
<script>
function eliminarSala(salaId) {
    Swal.fire({
        title: '¿Eliminar sala?',
        text: '¿Estás seguro de que deseas eliminar esta sala? Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('deleteSalaForm');
            form.action = `/salas/${salaId}`;
            form.submit();
        }
    });
}
</script>
@endpush
@endrole
</x-app-layout>
