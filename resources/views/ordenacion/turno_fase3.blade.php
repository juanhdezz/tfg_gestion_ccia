<!-- filepath: /resources/views/ordenacion/turno_fase3.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-purple-500">
            Elección de Ordenación Docente - Su Turno (Tercera Fase)
        </h1>

        <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 dark:bg-green-200">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        <strong>Es su turno</strong> para seleccionar asignaturas adicionales para el curso {{ $curso_siguiente }}.
                        <br><strong>Tercera Fase:</strong> Las asignaciones existentes de otros profesores no se eliminarán.
                    </p>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Sección de reducciones -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden">
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Compensaciones Docentes</h2>
            </div>
            <div class="p-4">
                @include('ordenacion.partials.reducciones')
            </div>
        </div>
        
        <!-- Sección de asignaciones actuales -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden">
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Asignaturas asignadas hasta el momento</h2>
            </div>
            <div class="p-4">
                <div class="bg-purple-100 border-l-4 border-purple-500 text-purple-700 p-4 mb-4">
                    <strong>Nota de la Tercera Fase:</strong> Para hacer efectivo el cambio de grupo debe pulsar sobre el botón <strong>Cambiar</strong> tras elegir el grupo de la lista desplegable.
                    En esta fase no se eliminan asignaciones de otros profesores.
                </div>
                
                @include('ordenacion.partials.asignaciones_actuales')
            </div>
        </div>
        
        <!-- Sección de asignaturas disponibles -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden">
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Asignaturas Disponibles (Tercera Fase)</h2>
            </div>
            <div class="p-4">
                <div class="bg-purple-50 border-l-4 border-purple-400 text-purple-700 p-4 mb-4 dark:bg-purple-200">
                    <strong>Importante:</strong> En la tercera fase solo se muestran los créditos realmente disponibles.
                    Las asignaciones de otros profesores no se eliminan automáticamente.
                </div>
                
                <form method="post" action="{{ route('ordenacion.asignar-fase3') }}">
                    @csrf
                    <div class="overflow-x-auto relative">
                        @forelse ($asignaturas_disponibles as $titulacion => $asignaturasGrupo)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-white">{{ $titulacion }}</h3>
                                
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Asignatura</th>
                                            <th scope="col" class="px-6 py-3">Curso</th>
                                            <th scope="col" class="px-6 py-3">Cuatr.</th>
                                            <th scope="col" class="px-6 py-3">Profesor Anterior</th>
                                            <th scope="col" class="px-6 py-3">Grupos Teoría</th>
                                            <th scope="col" class="px-6 py-3">Grupos Prácticas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($asignaturasGrupo as $asignatura)
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                <td class="px-6 py-4 font-bold">
                                                    {{ $asignatura->nombre_asignatura }}
                                                </td>
                                                <td class="px-6 py-4">{{ $asignatura->curso }}º</td>
                                                <td class="px-6 py-4">{{ $asignatura->cuatrimestre }}</td>
                                                <td class="px-6 py-4">
                                                    @foreach ($asignatura->profesores_anteriores as $key => $profesor)
                                                        <div class="text-xs text-gray-600 dark:text-gray-400">
                                                            {{ $profesor[0]->nombre }} {{ $profesor[0]->apellidos }} ({{ $key }})
                                                        </div>
                                                    @endforeach
                                                </td>
                                                <td class="px-6 py-4">
                                                    @foreach ($asignatura->grupos_teoria_disponibles as $grupo)
                                                        <div class="flex items-center mb-2">
                                                            <input type="checkbox" name="asignaturas[]" 
                                                                   value="Cr_{{ $asignatura->id_asignatura }}_{{ $grupo['grupo'] }}_Teoría"
                                                                   id="teoria_{{ $asignatura->id_asignatura }}_{{ $grupo['grupo'] }}"
                                                                   class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded">
                                                            <label for="teoria_{{ $asignatura->id_asignatura }}_{{ $grupo['grupo'] }}" 
                                                                   class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                Grupo {{ $grupo['grupo'] }}
                                                            </label>
                                                            <input type="number" 
                                                                   name="creditos_Cr_{{ $asignatura->id_asignatura }}_{{ $grupo['grupo'] }}_Teoría" 
                                                                   min="0" 
                                                                   max="{{ $grupo['creditos_disponibles'] }}" 
                                                                   step="0.5" 
                                                                   placeholder="0"
                                                                   class="ml-2 w-20 px-2 py-1 text-sm border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                            <span class="ml-1 text-xs text-gray-500">
                                                                (máx: {{ $grupo['creditos_disponibles'] }})
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                    @if(empty($asignatura->grupos_teoria_disponibles))
                                                        <span class="text-gray-500 text-sm">No disponible</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4">
                                                    @foreach ($asignatura->grupos_practicas_disponibles as $grupo)
                                                        <div class="flex items-center mb-2">
                                                            <input type="checkbox" name="asignaturas[]" 
                                                                   value="Cr_{{ $asignatura->id_asignatura }}_{{ $grupo['grupo'] }}_Prácticas"
                                                                   id="practicas_{{ $asignatura->id_asignatura }}_{{ $grupo['grupo'] }}"
                                                                   class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded">
                                                            <label for="practicas_{{ $asignatura->id_asignatura }}_{{ $grupo['grupo'] }}" 
                                                                   class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                Grupo {{ $grupo['grupo'] }}
                                                            </label>
                                                            <input type="number" 
                                                                   name="creditos_Cr_{{ $asignatura->id_asignatura }}_{{ $grupo['grupo'] }}_Prácticas" 
                                                                   min="0" 
                                                                   max="{{ $grupo['creditos_disponibles'] }}" 
                                                                   step="0.5" 
                                                                   placeholder="0"
                                                                   class="ml-2 w-20 px-2 py-1 text-sm border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                            <span class="ml-1 text-xs text-gray-500">
                                                                (máx: {{ $grupo['creditos_disponibles'] }})
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                    @if(empty($asignatura->grupos_practicas_disponibles))
                                                        <span class="text-gray-500 text-sm">No disponible</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-gray-500 dark:text-gray-400">No hay asignaturas disponibles según su perfil académico.</p>
                            </div>
                        @endforelse
                    </div>
                    
                    @if(!empty($asignaturas_disponibles))
                        <div class="mt-6 flex justify-center">
                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-lg">
                                Asignar Asignaturas Seleccionadas
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
        
        <!-- Sección de finalizar turno -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden">
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Finalizar Turno</h2>
            </div>
            <div class="p-4">
                <div class="bg-gray-50 dark:bg-gray-900/30 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        Una vez que haya terminado de seleccionar asignaturas, puede finalizar su turno para 
                        que el siguiente profesor pueda realizar su selección.
                    </p>
                    <form method="post" action="{{ route('ordenacion.finalizar-turno') }}">
                        @csrf
                        <button type="submit" 
                                class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                                onclick="return confirm('¿Está seguro de que desea finalizar su turno? Esta acción no se puede deshacer.')">
                            Finalizar Turno
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Información sobre la tercera fase -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden">
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Información sobre la Tercera Fase</h2>
            </div>
            <div class="p-4">
                <div class="prose dark:prose-invert max-w-none">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-purple-50 dark:bg-purple-900/30 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                            <h3 class="font-semibold text-purple-800 dark:text-purple-300 mb-2">Características de la Tercera Fase</h3>
                            <ul class="list-disc list-inside text-sm text-purple-700 dark:text-purple-400 space-y-1">
                                <li>No se eliminan asignaciones existentes</li>
                                <li>Solo créditos realmente disponibles</li>
                                <li>Respeta el perfil académico</li>
                                <li>Permite asignaciones parciales</li>
                            </ul>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <h3 class="font-semibold text-blue-800 dark:text-blue-300 mb-2">Instrucciones</h3>
                            <ul class="list-disc list-inside text-sm text-blue-700 dark:text-blue-400 space-y-1">
                                <li>Seleccione las asignaturas deseadas</li>
                                <li>Indique los créditos a asignar</li>
                                <li>Respete los límites máximos</li>
                                <li>Finalice su turno cuando termine</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Funcionalidad para sincronizar checkboxes con inputs de créditos
            const checkboxes = document.querySelectorAll('input[type="checkbox"][name="asignaturas[]"]');
            
            checkboxes.forEach(function(checkbox) {
                const creditosInput = document.querySelector('input[name="creditos_' + checkbox.value + '"]');
                
                if (creditosInput) {
                    // Habilitar/deshabilitar input de créditos basado en checkbox
                    checkbox.addEventListener('change', function() {
                        creditosInput.disabled = !this.checked;
                        if (!this.checked) {
                            creditosInput.value = '';
                        }
                    });
                    
                    // Inicializar estado
                    creditosInput.disabled = !checkbox.checked;
                    
                    // Auto-marcar checkbox cuando se introduce un valor
                    creditosInput.addEventListener('input', function() {
                        if (this.value && parseFloat(this.value) > 0) {
                            checkbox.checked = true;
                        }
                    });
                }
            });
            
            // Validación del formulario
            const form = document.querySelector('form[action*="asignar-fase3"]');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const checkedBoxes = document.querySelectorAll('input[type="checkbox"][name="asignaturas[]"]:checked');
                    let hasValidCredits = false;
                    
                    checkedBoxes.forEach(function(checkbox) {
                        const creditosInput = document.querySelector('input[name="creditos_' + checkbox.value + '"]');
                        if (creditosInput && parseFloat(creditosInput.value) > 0) {
                            hasValidCredits = true;
                        }
                    });
                    
                    if (!hasValidCredits) {
                        e.preventDefault();
                        alert('Debe seleccionar al menos una asignatura y especificar los créditos a asignar.');
                    }
                });
            }
        });
    </script>
</x-app-layout>
