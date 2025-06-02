<x-app-layout>
    

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <!-- Encabezado -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Crear Nueva Sala</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Añade una nueva sala al sistema de reservas
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

        <!-- Formulario de creación -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden p-6">
            <form action="{{ route('salas.store') }}" method="POST" id="form-crear-sala">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nombre -->
                    <div class="col-span-1">
                        <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Nombre de la Sala: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required
                            class="w-full px-4 py-2 border rounded-lg shadow-sm dark:bg-gray-700 dark:text-white @error('nombre') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror"
                            placeholder="Ej: Aula 101">
                        @error('nombre')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Localización -->
                    <div class="col-span-1">
                        <label for="localizacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Localización: <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="localizacion" id="localizacion" value="{{ old('localizacion') }}" required
                            class="w-full px-4 py-2 border rounded-lg shadow-sm dark:bg-gray-700 dark:text-white @error('localizacion') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror"
                            placeholder="Ej: Planta 1, Ala Norte">
                        @error('localizacion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Días de anticipación para reserva -->
                    <div class="col-span-2">
                        <label for="dias_anticipacion_reserva" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Días de Anticipación para Reserva:
                        </label>
                        <input type="number" name="dias_anticipacion_reserva" id="dias_anticipacion_reserva" 
                            value="{{ old('dias_anticipacion_reserva') }}" min="1" max="365"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('dias_anticipacion_reserva') border-red-500 @enderror"
                            placeholder="Ej: 30">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Opcional. Límite de días con antelación para hacer reservas en esta sala. Si se deja vacío, no habrá límite específico.
                        </p>
                        @error('dias_anticipacion_reserva')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-wrap gap-3 justify-end mt-6">
                    <a href="{{ route('salas.index') }}" 
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        Cancelar
                    </a>
                    <button type="submit" 
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Crear Sala
                    </button>
                </div>
            </form>
        </div>

        <!-- Información adicional -->
        <div class="mt-6 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-2">
                Información sobre la gestión de salas
            </h3>
            <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                <li>• El nombre de la sala debe ser único en el sistema</li>
                <li>• La localización ayuda a los usuarios a encontrar físicamente la sala</li>
                <li>• Los días de anticipación establecen un límite específico para esta sala (opcional)</li>
                <li>• Una vez creada, la sala estará disponible para reservas inmediatamente</li>
            </ul>
        </div>    </div>
</div>

@push('scripts')
<script>
    // Validación del formulario
    document.getElementById('form-crear-sala').addEventListener('submit', function(e) {
        const nombre = document.getElementById('nombre').value.trim();
        const localizacion = document.getElementById('localizacion').value.trim();
        
        if (!nombre || !localizacion) {
            e.preventDefault();
            Swal.fire({
                title: 'Campos requeridos',
                text: 'Por favor, complete todos los campos obligatorios.',
                icon: 'warning',
                confirmButtonText: 'Entendido'
            });
            return false;
        }
    });
</script>
@endpush
</x-app-layout>
