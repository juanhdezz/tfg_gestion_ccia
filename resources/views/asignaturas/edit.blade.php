<!-- filepath: /c:/xampp/htdocs/laravel/tfg_gestion_ccia/resources/views/asignaturas/edit.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full">
            <h1 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Editar Asignatura</h1>
            @if (session('error'))
                <div class="bg-red-500 text-white p-2 rounded mb-4">{{ session('error') }}</div>
            @endif
            <form action="{{ route('asignaturas.update', $asignatura->id_asignatura) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="nombre_asignatura" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre de la Asignatura:</label>
                    <input type="text" id="nombre_asignatura" name="nombre_asignatura" value="{{ $asignatura->nombre_asignatura }}" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>

                <div class="mb-4">
                    <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado:</label>
                    <select id="estado" name="estado" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        <option value="Activa" {{ $asignatura->estado == 'Activa' ? 'selected' : '' }}>Activa</option>
                        <option value="A extinguir" {{ $asignatura->estado == 'A extinguir' ? 'selected' : '' }}>A extinguir</option>
                        <option value="Extinta" {{ $asignatura->estado == 'Extinta' ? 'selected' : '' }}>Extinta</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="id_titulacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ID Titulación:</label>
                    <input type="text" id="id_titulacion" name="id_titulacion" value="{{ $asignatura->id_titulacion }}" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>

                <div class="mb-4">
                    <label for="curso" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Curso:</label>
                    <input type="number" id="curso" name="curso" value="{{ $asignatura->curso }}" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>

                <div class="mb-4">
                    <label for="cuatrimetre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cuatrimestre:</label>
                    <input type="text" id="cuatrimetre" name="cuatrimetre" value="{{ $asignatura->cuatrimetre }}" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>

                <div class="mb-4">
                    <label for="creditos_teoria" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Créditos Teoría:</label>
                    <input type="number" id="creditos_teoria" name="creditos_teoria" value="{{ $asignatura->creditos_teoria }}" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>

                <div class="mb-4">
                    <label for="creditos_practicas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Créditos Prácticas:</label>
                    <input type="number" id="creditos_practicas" name="creditos_practicas" value="{{ $asignatura->creditos_practicas }}" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>

                <div class="mb-4">
                    <label for="ects_teoria" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ECTS Teoría:</label>
                    <input type="number" id="ects_teoria" name="ects_teoria" value="{{ $asignatura->ects_teoria }}" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>

                <div class="mb-4">
                    <label for="ects_practicas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ECTS Prácticas:</label>
                    <input type="number" id="ects_practicas" name="ects_practicas" value="{{ $asignatura->ects_practicas }}" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>

                <div class="mb-4">
                    <label for="id_coordinador" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ID Coordinador:</label>
                    <input type="text" id="id_coordinador" name="id_coordinador" value="{{ $asignatura->id_coordinador }}" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Actualizar</button>
            </form>
            <a href="{{ route('asignaturas.index') }}" class="block text-center mt-4 text-blue-600 hover:underline">Volver a la lista de asignaturas</a>
        </div>
    </div>
</x-app-layout>
