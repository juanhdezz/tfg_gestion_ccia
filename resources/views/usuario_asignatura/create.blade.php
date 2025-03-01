<x-app-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full">
            <h1 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Asignar Usuario a Asignatura</h1>

            @if (session('error'))
                <div class="bg-red-500 text-white p-2 rounded mb-4">{{ session('error') }}</div>
            @endif

            <form action="{{ route('usuario_asignatura.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="id_usuario" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Usuario:</label>
                        <select id="id_usuario" name="id_usuario" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            <option value="">Seleccione un usuario</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}">{{ $usuario->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="id_asignatura" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Asignatura:</label>
                        <select id="id_asignatura" name="id_asignatura" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            <option value="">Seleccione una asignatura</option>
                            @foreach($asignaturas as $asignatura)
                                <option value="{{ $asignatura->id_asignatura }}">{{ $asignatura->nombre_asignatura }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Participación:</label>
                        <select id="tipo" name="tipo" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            <option value="teoria">Teoría</option>
                            <option value="practica">Práctica</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">Asignar</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
