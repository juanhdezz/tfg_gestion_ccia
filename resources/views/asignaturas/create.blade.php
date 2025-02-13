<!-- filepath: /c:/xampp/htdocs/laravel/tfg_gestion_ccia/resources/views/asignaturas/create.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full">
            <h1 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Crear Asignatura</h1>

            @if (session('error'))
                <div class="bg-red-500 text-white p-2 rounded mb-4">{{ session('error') }}</div>
            @endif

            <form action="{{ route('asignaturas.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="codigo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Código:</label>
                    <input type="text" id="codigo" name="codigo" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>

                <div class="mb-4">
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>

                <div class="mb-4">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white"></textarea>
                </div>

                <div class="mb-4">
                    <label for="creditos" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Créditos:</label>
                    <input type="number" id="creditos" name="creditos" required min="1" max="10" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>

                <div class="mb-4">
                    <label for="curso" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Curso:</label>
                    <input type="text" id="curso" name="curso" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>

                <div class="mb-4">
                    <label for="tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Asignatura:</label>
                    <select id="tipo" name="tipo" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        <option value="Obligatoria">Obligatoria</option>
                        <option value="Optativa">Optativa</option>
                    </select>
                </div>

                

                <div class="mb-4">
                    <label for="horarios" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Horarios:</label>
                    <input type="text" id="horarios" name="horarios" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>

                <div class="mb-4">
                    <label for="aula" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Aula:</label>
                    <input type="text" id="aula" name="aula" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>

                <div class="mb-4">
                    <label for="semestre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Semestre:</label>
                    <select id="semestre" name="semestre" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        <option value="1">Primer Semestre</option>
                        <option value="2">Segundo Semestre</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado:</label>
                    <select id="estado" name="estado" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        <option value="Activa">Activa</option>
                        <option value="A extinguir">A extinguir</option>
                        <option value="Extinta">Extinta</option>
                    </select>
                </div>

                <!-- Selección de Titulación -->
            <div>
                <label for="id_titulacion" class="block text-sm font-medium text-gray-700">Titulación</label>
                <select id="id_titulacion" name="id_titulacion"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm">
                    <option value="">Seleccione una titulación</option>
                    @foreach($titulaciones as $titulacion)
                        <option value="{{ $titulacion->id_titulacion }}">{{ $titulacion->nombre_titulacion }}</option>
                    @endforeach
                </select>
                @error('id_titulacion')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>


                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">Crear Asignatura</button>
            </form>
        </div>
    </div>
</x-app-layout>
