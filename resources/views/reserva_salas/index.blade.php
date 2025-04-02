{{-- filepath: c:\xampp\htdocs\laravel\tfg_gestion_ccia\resources\views\reserva_salas\index.blade.php --}}
<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-blue-500">Gestión de
            Reservas de Salas</h1>

        <!-- Formulario de filtrado -->
        <form method="GET" action="{{ route('reserva_salas.index') }}" class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="fecha" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha:</label>
                <input type="date" name="fecha" id="fecha" value="{{ request('fecha') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600">
            </div>
            <div>
                <label for="id_sala" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sala:</label>
                <select name="id_sala" id="id_sala"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600">
                    <option value="">Todas las salas</option>
                    @foreach($salas as $sala)
                        <option value="{{ $sala->id_sala }}" {{ request('id_sala') == $sala->id_sala ? 'selected' : '' }}>
                            {{ $sala->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="id_usuario" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Usuario:</label>
                <select name="id_usuario" id="id_usuario"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600">
                    <option value="">Todos los usuarios</option>
                    @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id_usuario }}" {{ request('id_usuario') == $usuario->id_usuario ? 'selected' : '' }}>
                            {{ $usuario->apellidos }}, {{ $usuario->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado:</label>
                <select name="estado" id="estado"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600">
                    <option value="">Todos los estados</option>
                    <option value="Validada" {{ request('estado') == 'Validada' ? 'selected' : '' }}>Validada</option>
                    <option value="Pendiente Validación" {{ request('estado') == 'Pendiente Validación' ? 'selected' : '' }}>Pendiente Validación</option>
                    <option value="Rechazada" {{ request('estado') == 'Rechazada' ? 'selected' : '' }}>Rechazada</option>
                    <option value="Cancelada" {{ request('estado') == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>
            <div class="md:col-span-4 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Filtrar
                </button>
                <a href="{{ route('reserva_salas.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
                    Limpiar
                </a>
            </div>
        </form>

        <div class="flex justify-end mb-4">
            <a href="{{ route('reserva_salas.calendario') }}"
                class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded ml-2">
                Ver Calendario
            </a>
            <a href="{{ route('reserva_salas.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                Realizar Reserva
            </a>
        </div>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Fecha</th>
                        <th scope="col" class="px-6 py-3">Sala</th>
                        <th scope="col" class="px-6 py-3">Usuario</th>
                        <th scope="col" class="px-6 py-3">Horario</th>
                        <th scope="col" class="px-6 py-3">Motivo</th>
                        <th scope="col" class="px-6 py-3">Observaciones</th>
                        <th scope="col" class="px-6 py-3">Estado</th>
                        <th scope="col" class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservas as $reserva)
                        <tr class="border-b dark:border-gray-700 border-gray-200 
                            {{ $reserva->estado == 'Cancelada' || $reserva->estado == 'Rechazada' 
                                ? 'bg-gray-900 dark:bg-gray-800' 
                                : 'odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800' }}">
                            <td class="px-6 py-4">
                                {{ $reserva->fecha->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <strong>{{ $reserva->sala->nombre }}</strong>
                            </td>
                            <td class="px-6 py-4">
                                {{ $reserva->usuario->apellidos }}, {{ $reserva->usuario->nombre }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $reserva->hora_inicio->format('H:i') }} - {{ $reserva->hora_fin->format('H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $reserva->motivo->descripcion }}
                            </td>
                            <td class="px-6 py-4 max-w-xs truncate">
                                {{ $reserva->observaciones ?? '—' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-white 
                                    {{ $reserva->estado == 'Validada' ? 'bg-green-600' : 
                                       ($reserva->estado == 'Pendiente Validación' ? 'bg-yellow-600' : 
                                       ($reserva->estado == 'Rechazada' ? 'bg-red-600' : 'bg-gray-600')) }}">
                                    {{ $reserva->estado }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <a href="{{ route('reserva_salas.show', [
                                        'id_sala' => $reserva->id_sala,
                                        'fecha' => $reserva->fecha->format('Y-m-d'),
                                        'hora_inicio' => $reserva->hora_inicio->format('H:i:s'),
                                        'estado' => $reserva->estado
                                    ]) }}" class="font-medium text-green-600 dark:text-green-500 hover:underline">
                                        Ver &#128270;
                                    </a>
                                    <a href="{{ route('reserva_salas.edit', [
                                        'id_sala' => $reserva->id_sala,
                                        'fecha' => $reserva->fecha->format('Y-m-d'),
                                        'hora_inicio' => $reserva->hora_inicio->format('H:i:s'),
                                        'estado' => $reserva->estado
                                    ]) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                        Editar &#9999;
                                    </a>
                                    @if($reserva->estado != 'Cancelada' && $reserva->estado != 'Rechazada')
                                        <form class="change-status-form" action="{{ route('reserva_salas.cambiar-estado', [
                                            'id_sala' => $reserva->id_sala,
                                            'fecha' => $reserva->fecha->format('Y-m-d'),
                                            'hora_inicio' => $reserva->hora_inicio->format('H:i:s'),
                                            'estado' => $reserva->estado
                                        ]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="nuevo_estado" value="Cancelada">
                                            <button type="submit" class="font-medium text-yellow-600 dark:text-yellow-500 hover:underline">
                                                Cancelar &#9888;
                                            </button>
                                        </form>
                                    @endif
                                    <form class="delete-form" action="{{ route('reserva_salas.destroy', [
                                        'id_sala' => $reserva->id_sala,
                                        'fecha' => $reserva->fecha->format('Y-m-d'),
                                        'hora_inicio' => $reserva->hora_inicio->format('H:i:s'),
                                        'estado' => $reserva->estado
                                    ]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                                            Eliminar &#10060;
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-4">
            {{ $reservas->links() }}
        </div>
    </div>

    @push('scripts')
        <script>
            // Confirmación para eliminar reservas
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    Swal.fire({
                        title: "¿Estás seguro?",
                        text: "Esta acción no se puede deshacer.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Sí, eliminar",
                        cancelButtonText: "Cancelar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Confirmación para cancelar reservas
            document.querySelectorAll('.change-status-form').forEach(form => {
                form.addEventListener('submit', (e) => {
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
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>