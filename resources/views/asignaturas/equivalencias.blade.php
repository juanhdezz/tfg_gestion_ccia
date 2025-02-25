<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-blue-500">
            Equivalencias para: {{ $asignatura->nombre_asignatura }}
            <span class="text-sm font-normal text-gray-600 dark:text-gray-400 ml-2">({{ $asignatura->siglas_asignatura }})</span>
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Información de la asignatura -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white border-b pb-2 border-gray-200 dark:border-gray-700">
                    Detalles de la asignatura
                </h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Código:</p>
                        <p class="font-medium text-gray-800 dark:text-white">{{ $asignatura->id_asignatura }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Curso:</p>
                        <p class="font-medium text-gray-800 dark:text-white">{{ $asignatura->curso }}º</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Titulación:</p>
                        <p class="font-medium text-gray-800 dark:text-white">{{ $asignatura->titulacion->nombre_titulacion }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Estado:</p>
                        <p class="font-medium text-gray-800 dark:text-white">{{ $asignatura->estado }}</p>
                    </div>
                </div>
            </div>

            <!-- Formulario para añadir nueva equivalencia -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white border-b pb-2 border-gray-200 dark:border-gray-700">
                    Añadir nueva equivalencia
                </h2>
                <form action="{{ route('asignaturas.establecer-equivalencia') }}" method="POST">
                    @csrf
                    <input type="hidden" name="asignatura_id" value="{{ $asignatura->id_asignatura }}">
                    
                    <div class="mb-4">
                        <label for="equivalente_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Seleccione asignatura equivalente:
                        </label>
                        <select name="equivalente_id" id="equivalente_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">-- Seleccione una asignatura --</option>
                            @foreach($asignaturas as $otraAsignatura)
                                <option value="{{ $otraAsignatura->id_asignatura }}">
                                    {{ $otraAsignatura->nombre_asignatura }} ({{ $otraAsignatura->siglas_asignatura }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Establecer equivalencia &#8644;
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Tabla de equivalencias actuales -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white border-b pb-2 border-gray-200 dark:border-gray-700">
                Equivalencias actuales
            </h2>
            
            @if($equivalenciasActuales->count() > 0)
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Código</th>
                                <th scope="col" class="px-6 py-3">Nombre</th>
                                <th scope="col" class="px-6 py-3">Siglas</th>
                                <th scope="col" class="px-6 py-3">Titulación</th>
                                <th scope="col" class="px-6 py-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($equivalenciasActuales as $equiv)
                                <tr class="border-b dark:border-gray-700 odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800">
                                    <td class="px-6 py-4">{{ $equiv->id_asignatura }}</td>
                                    <td class="px-6 py-4 font-medium">{{ $equiv->nombre_asignatura }}</td>
                                    <td class="px-6 py-4">{{ $equiv->siglas_asignatura }}</td>
                                    <td class="px-6 py-4">{{ $equiv->titulacion->nombre_titulacion ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <form class="delete-equiv-form" action="{{ route('asignaturas.eliminar-equivalencia') }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="asignatura_id" value="{{ $asignatura->id_asignatura }}">
                                                <input type="hidden" name="equivalente_id" value="{{ $equiv->id_asignatura }}">
                                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                                                    Eliminar &#10060;
                                                </button>
                                            </form>
                                            <a href="{{ route('asignaturas.show', $equiv->id_asignatura) }}" class="font-medium text-green-600 dark:text-green-500 hover:underline">
                                                Ver &#128270;
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-yellow-50 dark:bg-yellow-900/30 border-l-4 border-yellow-400 p-4 mb-4">
                    <div class="flex">
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                No hay equivalencias registradas para esta asignatura.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Botones de acción -->
        <div class="flex mt-6">
            <a href="{{ route('asignaturas.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                &#8592; Volver a asignaturas
            </a>
        </div>
    </div>

    @push('scripts')
    <script>
        // Añadir confirmación para eliminar equivalencias
        document.querySelectorAll('.delete-equiv-form').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "Esta acción eliminará la equivalencia entre estas asignaturas.",
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
    </script>
    @endpush
</x-app-layout> 