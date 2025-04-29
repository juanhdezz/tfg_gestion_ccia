<!-- filepath: /resources/views/ordenacion/partials/perfil_academico.blade.php -->
<form action="{{ route('ordenacion.actualizar-perfil') }}" method="POST">
    @csrf
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="font-semibold text-lg border-b border-gray-200 pb-2 mb-3">Preferencias</h3>
            
            <div class="mb-4">
                <label for="palabras_clave" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Palabras Clave (separadas por espacios)
                </label>
                <input type="text" name="palabras_clave" id="palabras_clave" 
                       value="{{ $perfil->palabras_clave }}" 
                       class="w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600">
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Estas palabras se utilizarán para filtrar las asignaturas según sus preferencias.
                </p>
            </div>
            
            <div class="flex items-start mb-4">
                <div class="flex items-center h-5">
                    <input type="checkbox" name="sin_palabras_clave" id="sin_palabras_clave" 
                           {{ $perfil->sin_palabras_clave ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                </div>
                <label for="sin_palabras_clave" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                    No utilizar filtro de palabras clave (mostrar todas las asignaturas)
                </label>
            </div>
            
            <div class="flex items-start mb-4">
                <div class="flex items-center h-5">
                    <input type="checkbox" name="teoria" id="teoria" 
                           {{ $perfil->teoria ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                </div>
                <label for="teoria" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                    Interesado en asignaturas de teoría
                </label>
            </div>
            
            <div class="flex items-start mb-4">
                <div class="flex items-center h-5">
                    <input type="checkbox" name="practicas" id="practicas" 
                           {{ $perfil->practicas ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                </div>
                <label for="practicas" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                    Interesado en asignaturas de prácticas
                </label>
            </div>
        </div>
        
        <div>
            <h3 class="font-semibold text-lg border-b border-gray-200 pb-2 mb-3">Titulaciones de interés</h3>
            
            <div class="mb-4 max-h-64 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded p-3">
                @forelse($titulaciones as $titulacion)
                    <div class="flex items-start mb-2">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="titulaciones[]" id="titulacion_{{ $titulacion->id_titulacion }}" 
                                   value="{{ $titulacion->id_titulacion }}"
                                   {{ in_array($titulacion->id_titulacion, $perfil->titulacionesIds ?? []) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        </div>
                        <label for="titulacion_{{ $titulacion->id_titulacion }}" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                            {{ $titulacion->nombre_titulacion }}
                        </label>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400">No hay titulaciones disponibles</p>
                @endforelse
            </div>
            
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                Si no selecciona ninguna titulación, se mostrarán todas las disponibles.
            </p>
        </div>
    </div>
    
    <div class="flex justify-end mt-4">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Guardar Cambios
        </button>
    </div>
</form>