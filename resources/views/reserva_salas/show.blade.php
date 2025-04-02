{{-- filepath: c:\xampp\htdocs\laravel\tfg_gestion_ccia\resources\views\reserva_salas\show.blade.php --}}
<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-blue-500">
            Detalles de Reserva
        </h1>

        <div class="mb-4 flex justify-between items-center">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                            Inicio
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <a href="{{ route('reserva_salas.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">
                                Reservas de Salas
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">
                                Detalles de Reserva
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="flex space-x-2">
                <a href="{{ route('reserva_salas.edit', [
                    'id_sala' => $reserva->id_sala,
                    'fecha' => $reserva->fecha->format('Y-m-d'),
                    'hora_inicio' => $reserva->hora_inicio->format('H:i:s'),
                    'estado' => $reserva->estado
                ]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Editar Reserva
                </a>
                <a href="{{ route('reserva_salas.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Volver al Listado
                </a>
            </div>
        </div>

        <!-- Tarjeta de información de la reserva -->
        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
            <!-- Encabezado con estado -->
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                        Reserva de Sala
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                        Información detallada de la reserva
                    </p>
                </div>
                <span class="px-3 py-1 text-xs font-bold rounded-full
                    {{ $reserva->estado == 'Validada' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 
                       ($reserva->estado == 'Pendiente Validación' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : 
                       ($reserva->estado == 'Rechazada' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : 
                       'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300')) }}">
                    {{ $reserva->estado }}
                </span>
            </div>

            <!-- Detalles de la reserva -->
            <div class="border-t border-gray-200 dark:border-gray-700">
                <dl>
                    <!-- Sala -->
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                            Sala
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                            <span class="font-bold">{{ $reserva->sala->nombre }}</span>
                            <span class="text-gray-500 dark:text-gray-400 ml-2">
                                ({{ $reserva->sala->localizacion }})
                            </span>
                        </dd>
                    </div>

                    <!-- Fecha y Horario -->
                    <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                            Fecha y Horario
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                            <div class="flex flex-col md:flex-row md:items-center">
                                <span class="font-medium pr-2">{{ $reserva->fecha->format('d/m/Y') }}</span>
                                <span class="text-blue-600 dark:text-blue-400">
                                    {{ $reserva->hora_inicio->format('H:i') }} - {{ $reserva->hora_fin->format('H:i') }}
                                </span>
                                <span class="text-gray-500 dark:text-gray-400 ml-2">
                                    ({{ $reserva->hora_inicio->diffInHours($reserva->hora_fin) }} 
                                    {{ $reserva->hora_inicio->diffInHours($reserva->hora_fin) == 1 ? 'hora' : 'horas' }})
                                </span>
                            </div>
                        </dd>
                    </div>

                    <!-- Usuario -->
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                            Usuario
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                            {{ $reserva->usuario->apellidos }}, {{ $reserva->usuario->nombre }}
                        </dd>
                    </div>

                    <!-- Motivo -->
                    <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                            Motivo
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                            {{ $reserva->motivo->descripcion }}
                            <span class="text-gray-500 dark:text-gray-400 ml-2">
                                ({{ $reserva->motivo->tipo }})
                            </span>
                        </dd>
                    </div>

                    <!-- Observaciones -->
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                            Observaciones
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                            {{ $reserva->observaciones ?? 'No hay observaciones' }}
                        </dd>
                    </div>

                    <!-- Fecha de realización -->
                    <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                            Fecha de realización de la reserva
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                            {{ $reserva->fecha_realizada ? $reserva->fecha_realizada->format('d/m/Y H:i') : 'No disponible' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Acciones adicionales para la reserva -->
        <div class="flex flex-wrap gap-3 justify-end">
            @if($reserva->estado != 'Cancelada' && $reserva->estado != 'Rechazada')
                <form id="form-cancelar" action="{{ route('reserva_salas.cambiar-estado', [
                    'id_sala' => $reserva->id_sala,
                    'fecha' => $reserva->fecha->format('Y-m-d'),
                    'hora_inicio' => $reserva->hora_inicio->format('H:i:s'),
                    'estado' => $reserva->estado
                ]) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="nuevo_estado" value="Cancelada">
                    <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        Cancelar Reserva
                    </button>
                </form>
            @endif

            <form id="form-eliminar" action="{{ route('reserva_salas.destroy', [
                'id_sala' => $reserva->id_sala,
                'fecha' => $reserva->fecha->format('Y-m-d'),
                'hora_inicio' => $reserva->hora_inicio->format('H:i:s'),
                'estado' => $reserva->estado
            ]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Eliminar Reserva
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Confirmación para cancelar reserva
        document.getElementById('form-cancelar')?.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: "¿Cancelar reserva?",
                text: "La reserva será marcada como cancelada.",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, cancelar reserva",
                cancelButtonText: "No"
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });

        // Confirmación para eliminar reserva
        document.getElementById('form-eliminar').addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: "¿Eliminar reserva?",
                text: "Esta acción no se puede deshacer.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>