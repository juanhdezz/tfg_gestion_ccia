<!-- filepath: c:\xampp\htdocs\laravel\tfg_gestion_ccia\resources\views\configuracion_ordenacion\create.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-indigo-800 dark:text-indigo-300 border-b-2 border-indigo-500 pb-2">
            Nueva Configuración de Ordenación Docente
        </h1>

        <div class="mb-4">
            <a href="{{ route('configuracion_ordenacion.index') }}" class="flex items-center text-indigo-600 hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Volver al listado
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <div class="flex">
                    <div class="py-1">
                        <svg class="h-6 w-6 text-red-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold">Por favor corrige los siguientes errores:</p>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <form action="{{ route('configuracion_ordenacion.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Clave de configuración -->
                    <div class="col-span-2 md:col-span-1">
                        <label for="clave" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Clave <span class="text-red-500">*</span></label>
                        <input type="text" id="clave" name="clave" value="{{ old('clave') }}" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                            placeholder="creditos_menos_permitidos">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Identificador único para esta configuración (sin espacios).</p>
                    </div>

                    <!-- Valor de configuración -->
                    <div class="col-span-2 md:col-span-1">
                        <label for="valor" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Valor <span class="text-red-500">*</span></label>
                        <input type="text" id="valor" name="valor" value="{{ old('valor') }}" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                            placeholder="0.5">
                    </div>

                    <!-- Descripción -->
                    <div class="col-span-2">
                        <label for="descripcion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="3"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                            placeholder="Descripción detallada de la configuración...">{{ old('descripcion') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Una breve explicación sobre el propósito de esta configuración.</p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <a href="{{ route('configuracion_ordenacion.index') }}"
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600 mr-2">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800">
                        Guardar Configuración
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>