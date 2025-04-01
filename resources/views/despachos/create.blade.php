<!-- filepath: /c:/xampp/htdocs/laravel/tfg_gestion_ccia/resources/views/despachos/create.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full">
            <h1 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Crear Nuevo Despacho</h1>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <strong>¡Atención!</strong> Hay errores en el formulario:<br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('despachos.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Centro -->
                    <div class="mb-4">
                        <label for="id_centro" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Centro:</label>
                        <select id="id_centro" name="id_centro" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            <option value="">Seleccione un centro</option>
                            @foreach($centros as $centro)
                                <option value="{{ $centro->id_centro }}" {{ old('id_centro') == $centro->id_centro ? 'selected' : '' }}>
                                    {{ $centro->nombre_centro }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nombre del despacho -->
                    <div class="mb-4">
                        <label for="nombre_despacho" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del despacho:</label>
                        <input type="text" id="nombre_despacho" name="nombre_despacho" value="{{ old('nombre_despacho') }}" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                    </div>

                    <!-- Siglas del despacho -->
                    <div class="mb-4">
                        <label for="siglas_despacho" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Siglas:</label>
                        <input type="text" id="siglas_despacho" name="siglas_despacho" value="{{ old('siglas_despacho') }}" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                    </div>

                    <!-- Teléfono del despacho -->
                    <div class="mb-4">
                        <label for="telefono_despacho" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teléfono:</label>
                        <input type="text" id="telefono_despacho" name="telefono_despacho" value="{{ old('telefono_despacho') }}" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                    </div>

                    <!-- Número de puestos -->
                    <div class="mb-4">
                        <label for="numero_puestos" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Número de puestos:</label>
                        <input type="number" min="1" id="numero_puestos" name="numero_puestos" value="{{ old('numero_puestos', 1) }}" required class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                    </div>

                    <!-- Descripción -->
                    <div class="mb-4 md:col-span-2">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción:</label>
                        <textarea id="descripcion" name="descripcion" rows="3" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">{{ old('descripcion') }}</textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-between">
                    <a href="{{ route('despachos.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-700">Cancelar</a>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>