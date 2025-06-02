{{-- filepath: c:\xampp\htdocs\laravel\tfg_gestion_ccia\resources\views\reserva_salas\edit.blade.php --}}
<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-blue-500">
            Editar Reserva de Sala
        </h1>

        

        <!-- Formulario de edición -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden p-6">
            <form action="{{ route('reserva_salas.update', [
                'id_sala' => $reserva->id_sala,
                'fecha' => $reserva->fecha->format('Y-m-d'),
                'hora_inicio' => $reserva->hora_inicio->format('H:i:s'),
                'estado' => $reserva->estado
            ]) }}" method="POST" id="form-editar">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Sala -->
                    <div class="col-span-1">
                        <label for="id_sala" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sala:</label>
                        <select name="id_sala" id="id_sala" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('id_sala') border-red-500 @enderror">
                            <option value="">Seleccione una sala</option>
                            @foreach($salas as $sala)
                                <option value="{{ $sala->id_sala }}" {{ old('id_sala', $reserva->id_sala) == $sala->id_sala ? 'selected' : '' }}>
                                    {{ $sala->nombre }} - {{ $sala->localizacion }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_sala')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Usuario -->
                    <div class="col-span-1">
                        <label for="id_usuario" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Usuario:</label>
                        <select name="id_usuario" id="id_usuario" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('id_usuario') border-red-500 @enderror">
                            <option value="">Seleccione un usuario</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id_usuario }}" {{ old('id_usuario', $reserva->id_usuario) == $usuario->id_usuario ? 'selected' : '' }}>
                                    {{ $usuario->apellidos }}, {{ $usuario->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_usuario')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Motivo -->
                    <div class="col-span-1">
                        <label for="id_motivo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Motivo:</label>
                        <select name="id_motivo" id="id_motivo" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('id_motivo') border-red-500 @enderror">
                            <option value="">Seleccione un motivo</option>
                            @foreach($motivos as $motivo)
                                <option value="{{ $motivo->id_motivo }}" {{ old('id_motivo', $reserva->id_motivo) == $motivo->id_motivo ? 'selected' : '' }}>
                                    {{ $motivo->descripcion }} ({{ $motivo->tipo }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_motivo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha -->
                    <div class="col-span-1">
                        <label for="fecha" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha:</label>
                        <input type="date" name="fecha" id="fecha" required value="{{ old('fecha', $reserva->fecha->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('fecha') border-red-500 @enderror">
                        @error('fecha')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hora Inicio -->
                    <div class="col-span-1">
                        <label for="hora_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hora de inicio:</label>
                        <input type="time" name="hora_inicio" id="hora_inicio" required value="{{ old('hora_inicio', $reserva->hora_inicio->format('H:i')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('hora_inicio') border-red-500 @enderror">
                        @error('hora_inicio')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hora Fin -->
                    <div class="col-span-1">
                        <label for="hora_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hora de fin:</label>
                        <input type="time" name="hora_fin" id="hora_fin" required value="{{ old('hora_fin', $reserva->hora_fin->format('H:i')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('hora_fin') border-red-500 @enderror">
                        @error('hora_fin')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estado -->
                    {{-- <div class="col-span-1">
                        <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado:</label>
                        <select name="estado" id="estado" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('estado') border-red-500 @enderror">
                            <option value="Validada" {{ old('estado', $reserva->estado) == 'Validada' ? 'selected' : '' }}>Validada</option>
                            <option value="Pendiente Validación" {{ old('estado', $reserva->estado) == 'Pendiente Validación' ? 'selected' : '' }}>Pendiente Validación</option>
                            <option value="Rechazada" {{ old('estado', $reserva->estado) == 'Rechazada' ? 'selected' : '' }}>Rechazada</option>
                            <option value="Cancelada" {{ old('estado', $reserva->estado) == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                        @error('estado')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div> --}}

                    <!-- Observaciones -->
                    <div class="col-span-2">
                        <label for="observaciones" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observaciones:</label>
                        <textarea name="observaciones" id="observaciones" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('observaciones') border-red-500 @enderror">{{ old('observaciones', $reserva->observaciones) }}</textarea>
                        @error('observaciones')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-end mt-6 gap-3">
                    <a href="{{ route('reserva_salas.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded-md text-white">
                        Cancelar
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-md text-white">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Validación básica para fechas y horas
        document.getElementById('form-editar').addEventListener('submit', function(e) {
            const horaInicio = document.getElementById('hora_inicio').value;
            const horaFin = document.getElementById('hora_fin').value;
            
            if (horaInicio >= horaFin) {
                e.preventDefault();
                Swal.fire({
                    title: "Error de validación",
                    text: "La hora de fin debe ser posterior a la hora de inicio",
                    icon: "error",
                    confirmButtonColor: "#3085d6",
                });
                return false;
            }
            
            // Confirmación antes de enviar
            e.preventDefault();
            Swal.fire({
                title: "¿Guardar cambios?",
                text: "Se aplicarán los cambios a la reserva",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, guardar",
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