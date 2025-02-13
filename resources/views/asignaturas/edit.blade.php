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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="id_asignatura" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ID Asignatura:</label>
                        <input type="text" id="id_asignatura" name="id_asignatura" value="{{ $asignatura->id_asignatura }}" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                    </div>

                    <div class="mb-4">
                        <label for="nombre_asignatura" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre:</label>
                        <input type="text" id="nombre_asignatura" name="nombre_asignatura" value="{{ $asignatura->nombre_asignatura }}" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                    </div>

                    <div class="mb-4">
                        <label for="siglas_asignatura" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Siglas:</label>
                        <input type="text" id="siglas_asignatura" name="siglas_asignatura" value="{{ $asignatura->siglas_asignatura }}" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                    </div>

                    <div class="mb-4">
                        <label for="especialidad" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Especialidad:</label>
                        <input type="text" id="especialidad" name="especialidad" value="{{ $asignatura->especialidad }}" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                    </div>

                    <div class="mb-4">
                        <label for="id_coordinador" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ID Coordinador:</label>
                        <input type="text" id="id_coordinador" name="id_coordinador" value="{{ $asignatura->id_coordinador }}" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                    </div>

                    <div class="mb-4">
                        <label for="curso" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Curso:</label>
                        <input type="number" id="curso" name="curso" value="{{ $asignatura->curso }}" required min="1" max="4" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                    </div>

                    <div class="mb-4">
                        <label for="tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Asignatura:</label>
                        <select id="tipo" name="tipo" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            <option value="Asignatura" {{ $asignatura->tipo == 'Asignatura' ? 'selected' : '' }}>Asignatura</option>
                            <option value="Proyecto Fin de Carrera" {{ $asignatura->tipo == 'Proyecto Fin de Carrera' ? 'selected' : '' }}>Proyecto Fin de Carrera</option>
                        </select>
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
                        <label for="id_titulacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Titulación:</label>
                        <select id="id_titulacion" name="id_titulacion" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            @foreach($titulaciones as $titulacion)
                                <option value="{{ $titulacion->id_titulacion }}" {{ $asignatura->id_titulacion == $titulacion->id_titulacion ? 'selected' : '' }}>{{ $titulacion->nombre_titulacion }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="fraccionable" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fraccionable:</label>
                        <input type="checkbox" id="fraccionable" name="fraccionable" value="1" {{ $asignatura->fraccionable ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-gray-700 dark:text-gray-300">Sí</span>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">Actualizar Asignatura</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
