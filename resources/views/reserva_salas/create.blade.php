{{-- filepath: c:\xampp\htdocs\laravel\tfg_gestion_ccia\resources\views\reserva_salas\create.blade.php --}}
<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-blue-500">
            Realizar Reserva de Sala
        </h1>

        
        <!-- Cuadro de información -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 dark:bg-blue-900/20 dark:border-blue-500/40">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.75.75 0 00.736-.686A3.49 3.49 0 0112.5 9.5a3.5 3.5 0 01-6 2.43.75.75 0 10-1.28-.79A5 5 0 009.5 16a5 5 0 00-4.225-4.943A5 5 0 009 9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700 dark:text-blue-400">
                        <strong>Información importante</strong>
                        <br>
                        Las resrevas realizadas a través de la plataforma tienen caracter meramente informativo.La dirección del Departamento se reserva el derecho
                        de cancelar o modificar unas reserva , por mootivos justificados, previa comuicación al usuario.
                        <br>
                        El plazo de antelación para realizar una reserva es de 28 días naturales,
                        <br>
                        Las reservas efectuadas con menos de 24 horas de antelacion serán aceptadas automaticamente .
                        <br>
                        Las reservas que exceden las 4 horas , con una antelación superior a 24 horas, , requerirán ser validadas, respetándose las horas reservadas hasta la resolución final.
                        <br>
                        El plazo de validación será inferior a 48 horas
                    </p>
                </div>
            </div>
        </div>

        <!-- Formulario de nueva reserva -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden p-6">
            <form action="{{ route('reserva_salas.store') }}" method="POST" id="form-reserva">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Sala -->
                    <div class="col-span-1">
                        <label for="id_sala" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sala: <span class="text-red-500">*</span></label>
                        <select name="id_sala" id="id_sala" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('id_sala') border-red-500 @enderror">
                            <option value="">Seleccione una sala</option>
                            @foreach($salas as $sala)
                                <option value="{{ $sala->id_sala }}" {{ old('id_sala') == $sala->id_sala ? 'selected' : '' }}>
                                    {{ $sala->nombre }} - {{ $sala->localizacion }} ({{ $sala->capacidad }} personas)
                                </option>
                            @endforeach
                        </select>
                        @error('id_sala')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Motivo -->
                    <div class="col-span-1">
                        <label for="id_motivo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Motivo: <span class="text-red-500">*</span></label>
                        <select name="id_motivo" id="id_motivo" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('id_motivo') border-red-500 @enderror">
                            <option value="">Seleccione un motivo</option>
                            @foreach($motivos as $motivo)
                                <option value="{{ $motivo->id_motivo }}" {{ old('id_motivo') == $motivo->id_motivo ? 'selected' : '' }}>
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
                        <label for="fecha" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha: <span class="text-red-500">*</span></label>
                        <input type="date" name="fecha" id="fecha" required value="{{ old('fecha', date('Y-m-d')) }}"
                            min="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('fecha') border-red-500 @enderror">
                        @error('fecha')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Usuario (oculto - asignamos el usuario actual) -->
                    {{-- Añade este campo al formulario para garantizar que siempre se envíe el ID del usuario --}}
                    <input type="hidden" name="id_usuario" value="{{ auth()->user()->id_usuario }}">
                    <!-- Hora Inicio -->
                    <div class="col-span-1 md:col-span-1">
                        <label for="hora_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hora de inicio: <span class="text-red-500">*</span></label>
                        <input type="time" name="hora_inicio" id="hora_inicio" required value="{{ old('hora_inicio', '09:00') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('hora_inicio') border-red-500 @enderror">
                        @error('hora_inicio')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hora Fin -->
                    <div class="col-span-1 md:col-span-1">
                        <label for="hora_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hora de fin: <span class="text-red-500">*</span></label>
                        <input type="time" name="hora_fin" id="hora_fin" required value="{{ old('hora_fin', '11:00') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('hora_fin') border-red-500 @enderror">
                        @error('hora_fin')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estado (oculto - por defecto es "Pendiente Validación") -->
                    <input type="hidden" name="estado" value="Pendiente Validación">

                    <!-- Observaciones -->
                    <div class="col-span-2">
                        <label for="observaciones" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observaciones:</label>
                        <textarea name="observaciones" id="observaciones" rows="4"
                            placeholder="Añade cualquier información adicional relevante para esta reserva"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('observaciones') border-red-500 @enderror">{{ old('observaciones') }}</textarea>
                        @error('observaciones')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Información sobre campos obligatorios -->
                <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                    <p><span class="text-red-500">*</span> Campos obligatorios</p>
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-end mt-6 gap-3">
                    <a href="{{ route('reserva_salas.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded-md text-white">
                        Cancelar
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-md text-white">
                        Realizar Reserva
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Validación para fechas y horas
        document.getElementById('form-reserva').addEventListener('submit', function(e) {
            const horaInicio = document.getElementById('hora_inicio').value;
            const horaFin = document.getElementById('hora_fin').value;
            const fecha = document.getElementById('fecha').value;
            const fechaActual = new Date().toISOString().split('T')[0];
            
            // Validar que la fecha no sea anterior a hoy
            if (fecha < fechaActual) {
                e.preventDefault();
                Swal.fire({
                    title: "Error de validación",
                    text: "No puedes realizar reservas para fechas pasadas",
                    icon: "error",
                    confirmButtonColor: "#3085d6",
                });
                return false;
            }
            
            // Validar que la hora de fin sea posterior a la de inicio
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
            
            // Si todo está correcto, mostrar confirmación
            e.preventDefault();
            Swal.fire({
                title: "¿Realizar reserva?",
                text: "Se comprobará la disponibilidad de la sala para el horario seleccionado",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, realizar reserva",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Verificar disponibilidad mediante AJAX
                    const formData = new FormData();
                    formData.append('id_sala', document.getElementById('id_sala').value);
                    formData.append('fecha', fecha);
                    formData.append('hora_inicio', horaInicio);
                    formData.append('hora_fin', horaFin);
                    formData.append('_token', document.querySelector('input[name="_token"]').value);
                    
                    // Mostrar indicador de carga
                    Swal.fire({
                        title: 'Verificando disponibilidad...',
                        text: 'Por favor espere',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Reemplaza la sección del fetch por este código:
fetch('{{ route('reserva_salas.verificar-disponibilidad') }}', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        id_sala: document.getElementById('id_sala').value,
        fecha: fecha,
        hora_inicio: horaInicio,
        hora_fin: horaFin
    })
})
.then(response => {
    if (!response.ok) {
        throw new Error('Error en la respuesta del servidor: ' + response.status);
    }
    return response.json();
})
.then(data => {
    if (data.disponible) {
        // Si está disponible, enviar el formulario
        document.getElementById('form-reserva').submit();
    } else {
        // Si no está disponible, mostrar mensaje de error
        Swal.fire({
            title: "Sala no disponible",
            html: `La sala ya está reservada para el horario seleccionado.<br><br>
                   <strong>Reserva actual:</strong> ${data.reserva_actual}`,
            icon: "error",
            confirmButtonColor: "#3085d6",
        });
    }
})
.catch(error => {
    console.error('Error:', error);
    Swal.fire({
        title: "Error",
        text: "Ha ocurrido un error al verificar la disponibilidad: " + error.message,
        icon: "error",
        confirmButtonColor: "#3085d6",
    });
});
                }
            });
        });

        // Establecer hora mínima y máxima según horario de apertura del centro
        document.getElementById('hora_inicio').min = "08:00";
        document.getElementById('hora_inicio').max = "21:30";
        document.getElementById('hora_fin').min = "08:30";
        document.getElementById('hora_fin').max = "22:00";
    </script>
    @endpush
</x-app-layout>