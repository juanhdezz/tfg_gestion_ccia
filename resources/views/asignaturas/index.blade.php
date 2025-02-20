<!-- filepath: /c:/xampp/htdocs/laravel/tfg_gestion_ccia/resources/views/asignaturas/index.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-blue-500">Gestión de Asignaturas</h1>
        <div class="flex justify-end mb-4">
            {{-- <a href="{{ route('asignaturas.export') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Exportar Asignaturas
            </a> --}}
            <a href="{{ route('asignaturas.grupos') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                Gestionar Grupos
            </a>
            <a href="{{ route('asignaturas.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                Añadir Asignatura
            </a>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Estado</th>
                        <th scope="col" class="px-6 py-3">ID Asignatura</th>
                        <th scope="col" class="px-6 py-3">Nombre Asignatura</th>
                        <th scope="col" class="px-6 py-3">Titulación</th>
                        <th scope="col" class="px-6 py-3">Curso</th>
                        <th scope="col" class="px-6 py-3">Cuatrimestre</th>
                        <th scope="col" class="px-6 py-3">Créditos Totales</th>
                        <th scope="col" class="px-6 py-3">ECTS Totales</th>
                        <th scope="col" class="px-6 py-3">Coordinador</th>
                        <th scope="col" class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($asignaturas as $asignatura)
                    <tr class="border-b dark:border-gray-700 border-gray-200 {{ $asignatura->estado == 'Extinta' ? 'bg-gray-900 dark:bg-gray-800' : 'odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800' }}">
                        <td class="px-6 py-4">
                            {{ $asignatura->estado }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $asignatura->id_asignatura }}
                        </td>
                        <td class="px-6 py-4">
                            <strong> {{ $asignatura->nombre_asignatura }} </strong>
                        </td>
                        <td class="px-6 py-4">
                            {{ $asignatura->titulacion->nombre_titulacion }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $asignatura->curso }}º
                        </td>
                        <td class="px-6 py-4">
                            {{ $asignatura->cuatrimestre }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $asignatura->creditos_teoria + $asignatura->creditos_practicas }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $asignatura->ects_teoria + $asignatura->ects_practicas }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $asignatura->id_coordinador }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('asignaturas.show', $asignatura->id_asignatura) }}" class="font-medium text-green-600 dark:text-green-500 hover:underline">
                                    Ver &#128270;
                                </a>
                                <a href="{{ route('asignaturas.edit', $asignatura->id_asignatura) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                    Editar &#9999;
                                </a>
                                <form class="delete-form" action="{{ route('asignaturas.destroy', $asignatura->id_asignatura) }}" method="POST" class="inline">
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
    </div>
    @push('scripts')
    <script>
        forms = document.querySelectorAll('.delete-form');
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                Swal.fire({
                    title: "Estás seguro?",
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
    </script>
@endpush
</x-app-layout>