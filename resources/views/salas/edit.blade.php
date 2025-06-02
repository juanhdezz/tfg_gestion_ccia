<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Sala') }}
        </h2>
    </x-slot>

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <!-- Encabezado -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Sala</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Modifica los datos de la sala "{{ $sala->nombre }}"
                </p>
            </div>
            <a href="{{ route('salas.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-150 ease-in-out">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>

        <!-- Formulario de edición -->
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
            <form action="{{ route('salas.update', $sala->id_sala) }}" method="POST" id="editSalaForm">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nombre de la sala -->
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nombre de la Sala <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nombre" 
                               name="nombre" 
                               value="{{ old('nombre', $sala->nombre) }}"
                               class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white @error('nombre') border-red-500 @enderror"
                               required
                               maxlength="100"
                               placeholder="Ej: Aula 101">
                        @error('nombre')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Localización -->
                    <div>
                        <label for="localizacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Localización <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="localizacion" 
                               name="localizacion" 
                               value="{{ old('localizacion', $sala->localizacion) }}"
                               class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white @error('localizacion') border-red-500 @enderror"
                               required
                               maxlength="255"
                               placeholder="Ej: Edificio A, Planta 1">
                        @error('localizacion')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Días de anticipación -->
                    <div class="md:col-span-2">
                        <label for="dias_anticipacion_reserva" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Días de Anticipación para Reserva <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   id="dias_anticipacion_reserva" 
                                   name="dias_anticipacion_reserva" 
                                   value="{{ old('dias_anticipacion_reserva', $sala->dias_anticipacion_reserva) }}"
                                   class="block w-full md:w-1/3 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white @error('dias_anticipacion_reserva') border-red-500 @enderror"
                                   required
                                   min="1"
                                   max="365"
                                   placeholder="7">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Número de días mínimos de anticipación necesarios para reservar esta sala (entre 1 y 365 días)
                            </p>
                        </div>
                        @error('dias_anticipacion_reserva')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-600">
                    <a href="{{ route('salas.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-150 ease-in-out">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Actualizar Sala
                    </button>
                </div>
            </form>
        </div>

        <!-- Información adicional -->
        @if($sala->reservas()->count() > 0)
        <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="text-sm font-medium text-blue-800 dark:text-blue-300">Información importante</h4>
                    <p class="text-sm text-blue-700 dark:text-blue-400 mt-1">
                        Esta sala tiene {{ $sala->reservas()->count() }} reserva(s) asociada(s). Los cambios no afectarán a las reservas existentes.
                    </p>
                </div>
            </div>
        </div>
        @endif    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editSalaForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: '¿Confirmar cambios?',
            text: '¿Estás seguro de que deseas actualizar los datos de esta sala?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, actualizar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
</x-app-layout>
