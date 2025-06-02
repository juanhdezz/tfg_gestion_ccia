<!-- filepath: /resources/views/ordenacion/partials/perfil_academico.blade.php -->

@if(is_null($perfil))
    <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-6 text-center">
        <div class="flex justify-center mb-4">
            <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-red-800 dark:text-red-300 mb-2">
            Perfil no encontrado
        </h3>
        <p class="text-sm text-red-700 dark:text-red-400 mb-4">
            No se ha encontrado el perfil académico del usuario. Por favor, contacte con el administrador para crear un perfil.
        </p>
        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver al panel principal
        </a>
    </div>
@elseif(!is_object($perfil))
    <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6 text-center">
        <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-300 mb-2">
            Error en el perfil
        </h3>
        <p class="text-sm text-yellow-700 dark:text-yellow-400 mb-4">
            El perfil no tiene un formato válido. Por favor, contacte con el administrador.
        </p>
    </div>
@else
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
                       value="{{ optional($perfil)->palabras_clave ?? '' }}" 
                       placeholder="{{ is_null(optional($perfil)->palabras_clave) ? 'No se han definido palabras clave' : '' }}"
                       class="w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600">
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    @if(is_null(optional($perfil)->palabras_clave))
                        <span class="text-amber-600 dark:text-amber-400">⚠️ No hay palabras clave configuradas.</span>
                    @else
                        Estas palabras se utilizarán para filtrar las asignaturas según sus preferencias.
                    @endif
                </p>
            </div>
            
            <div class="flex items-start mb-4">
                <div class="flex items-center h-5">
                    <input type="checkbox" name="sin_palabras_clave" id="sin_palabras_clave" 
                           {{ (optional($perfil)->sin_palabras_clave ?? false) ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                </div>
                <label for="sin_palabras_clave" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                    No utilizar filtro de palabras clave (mostrar todas las asignaturas)
                    @if(is_null(optional($perfil)->sin_palabras_clave))
                        <span class="text-gray-500 italic"> - (no configurado)</span>
                    @endif
                </label>
            </div>
            
            <div class="flex items-start mb-4">
                <div class="flex items-center h-5">
                    <input type="checkbox" name="teoria" id="teoria" 
                           {{ (optional($perfil)->teoria ?? false) ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                </div>
                <label for="teoria" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                    Interesado en asignaturas de teoría
                    @if(is_null(optional($perfil)->teoria))
                        <span class="text-gray-500 italic"> - (no configurado)</span>
                    @endif
                </label>
            </div>
            
            <div class="flex items-start mb-4">
                <div class="flex items-center h-5">
                    <input type="checkbox" name="practicas" id="practicas" 
                           {{ (optional($perfil)->practicas ?? false) ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                </div>
                <label for="practicas" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                    Interesado en asignaturas de prácticas
                    @if(is_null(optional($perfil)->practicas))
                        <span class="text-gray-500 italic"> - (no configurado)</span>
                    @endif
                </label>
            </div>
            
            <!-- Campo para pasar_turno -->
            <div class="flex items-start mb-4">
                <div class="flex items-center h-5">
                    <input type="checkbox" name="pasar_turno" id="pasar_turno" 
                           {{ (optional($perfil)->pasar_turno ?? false) ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                </div>
                <label for="pasar_turno" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                    Pasar turno (no participar en la ordenación)
                    @if(is_null(optional($perfil)->pasar_turno))
                        <span class="text-gray-500 italic"> - (no configurado)</span>
                    @endif
                </label>
            </div>
        </div>
        
        <div>
            <h3 class="font-semibold text-lg border-b border-gray-200 pb-2 mb-3">Titulaciones de interés</h3>
              @if(is_null(optional($perfil)->titulaciones) || optional($perfil)->titulaciones->isEmpty())
                <div class="mb-3 p-3 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 rounded-md">
                    <p class="text-sm text-amber-800 dark:text-amber-300">
                        ⚠️ No hay titulaciones seleccionadas en el perfil.
                    </p>
                </div>
            @endif
            
            <div class="mb-4 max-h-64 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded p-3">
                @forelse($titulaciones ?? [] as $titulacion)
                    <div class="flex items-start mb-2">
                        <div class="flex items-center h-5">                            <input type="checkbox" name="titulaciones[]" id="titulacion_{{ optional($titulacion)->id_titulacion ?? 'unknown' }}" 
                                   value="{{ optional($titulacion)->id_titulacion ?? '' }}"
                                   {{ (optional($perfil)->titulaciones && optional($perfil)->titulaciones->contains('id_titulacion', optional($titulacion)->id_titulacion)) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        </div>
                        <label for="titulacion_{{ optional($titulacion)->id_titulacion ?? 'unknown' }}" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                            {{ optional($titulacion)->nombre_titulacion ?? 'Titulación sin nombre' }}
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
@endif