<!-- filepath: /resources/views/ordenacion/turno.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-blue-500">
            Elección de Ordenación Docente - Su Turno
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
                        <strong>Es su turno</strong> para seleccionar asignaturas para el curso {{ $curso_siguiente }}.
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
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4">
                    Para hacer efectivo el cambio de grupo debe pulsar sobre el botón <strong>Cambiar</strong> tras elegir el grupo de la lista desplegable
                </div>
                
                @include('ordenacion.partials.asignaciones_actuales')
            </div>
        </div>
        
        <!-- Sección de asignaturas disponibles -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden">
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Asignaturas Disponibles</h2>
            </div>
            <div class="p-4">
                <form method="post" action="{{ route('ordenacion.asignar') }}">
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
                                                        {{ $profesor[0]->nombre }} {{ $profesor[0]->apellidos }} ({{ $key }})<br>
                                                    @endforeach
                                                </td>
                                                <td class="px-6 py-4">
                                                    @foreach ($asignatura->grupos_teoria_disponibles as $grupo)
                                                        <div class="flex items-center mb-2">
                                                            <input type="checkbox" name="asignaturas[]" 
                                                                   value="Cr_{{ $asignatura->id_asignatura }}_{{ $grupo['grupo'] }}_Teoría"
                                                                   id="teoria_{{ $asignatura->id_asignatura }}_{{ $grupo['grupo'] }}"
                                                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded">
                                                            <label for="teoria_{{ $asignatura->id_asignatura }}_{{ $grupo['grupo'] }}" class="ml-2">
                                                                Grupo {{ $grupo['grupo'] }}
                                                                ({{ $grupo['creditos_disponibles'] }} crd.)
                                                            </label>
                                                            
                                                            @if ($asignatura->fraccionable == 'Fraccionable')
                                                                <input type="hidden" 
                                                                       name="Cr_{{ $asignatura->id_asignatura }}_{{ $grupo['grupo'] }}_TeoriaMAX" 
                                                                       value="{{ $grupo['creditos_disponibles'] }}">
                                                                <input type="number" 
                                                                       name="Cr_{{ $asignatura->id_asignatura }}_{{ $grupo['grupo'] }}_Teoría" 
                                                                       step="0.1" 
                                                                       min="0" 
                                                                       max="{{ $grupo['creditos_disponibles'] }}" 
                                                                       class="ml-2 w-20 h-8 border border-gray-300 rounded">
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </td>
                                                <td class="px-6 py-4">
                                                    @foreach ($asignatura->grupos_practicas_disponibles as $grupo)
                                                        <div class="flex items-center mb-2">
                                                            <input type="checkbox" name="asignaturas[]" 
                                                                   value="Cr_{{ $asignatura->id_asignatura }}_{{ $grupo['grupo'] }}_Prácticas"
                                                                   id="practicas_{{ $asignatura->id_asignatura }}_{{ $grupo['grupo'] }}"
                                                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded">
                                                            <label for="practicas_{{ $asignatura->id_asignatura }}_{{ $grupo['grupo'] }}" class="ml-2">
                                                                Grupo {{ $grupo['grupo'] }}
                                                                ({{ $grupo['creditos_disponibles'] }} crd.)
                                                            </label>
                                                            
                                                            @if ($asignatura->fraccionable == 'Fraccionable')
                                                                <input type="hidden" 
                                                                       name="Cr_{{ $asignatura->id_asignatura }}_{{ $grupo['grupo'] }}_PrácticasMAX" 
                                                                       value="{{ $grupo['creditos_disponibles'] }}">
                                                                <input type="number" 
                                                                       name="Cr_{{ $asignatura->id_asignatura }}_{{ $grupo['grupo'] }}_Prácticas" 
                                                                       step="0.1" 
                                                                       min="0" 
                                                                       max="{{ $grupo['creditos_disponibles'] }}" 
                                                                       class="ml-2 w-20 h-8 border border-gray-300 rounded">
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @empty
                            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4">
                                No hay asignaturas disponibles que coincidan con su perfil académico.
                            </div>
                        @endforelse
                    </div>
                    
                    <div class="flex justify-between mt-6">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Guardar Asignaciones
                        </button>
                        <!-- Añadir esto después del botón de guardar asignaciones -->
<div class="flex justify-center mt-6">
    <a href="{{ route('ordenacion.resumen') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
        Ver Resumen de Ordenación Docente
    </a>
</div>
                          <button type="button" id="btnPasarTurno"
                                class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                                onclick="validarYPasarTurno()">
                            Pasar Turno
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Función para evitar el envío de formularios al pulsar la tecla enter
        function stopRKey(evt) {
            var evt = (evt) ? evt : ((event) ? event : null);
            var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
            if ((evt.keyCode == 13) && (node.type == "text")) {
                return false;
            }
        }
        document.onkeypress = stopRKey;

        // Función ValidaTurnoFase2 traducida del sistema monolítico
        function ValidaTurnoFase2(T, CrCat, CrConPI, CrSinPI, docenciaTotal, CREDITOS_MENOS, Turno, txt_docencia, cc_falta_presencial) {
            var respuesta = true;
            
            // Lógica de validación idéntica al sistema monolítico
            if ((Turno >= 73 && T <= CrCat) || 
                (Turno < 73 && cc_falta_presencial <= 0 && T >= (CrCat - CREDITOS_MENOS) && 
                 CrConPI <= (CrCat + 3) && (CrSinPI >= 7.5) && 
                 ((CrConPI - CrSinPI) <= 3.0) && (docenciaTotal >= 12))) {
                
                respuesta = confirm(txt_docencia + "\n\nEl turno se va a pasar al siguiente miembro del departamento. ¿Está seguro de haber terminado su elección de asignaturas?");
            } else {
                if (cc_falta_presencial > 0) {
                    alert(" Te faltan por escoger " + cc_falta_presencial + " créditos presenciales");
                }
                if (T < (CrCat - CREDITOS_MENOS)) {
                    alert(" El número de créditos elegido " + T + " no alcanza el mínimo requerido " + (CrCat - CREDITOS_MENOS));
                }
                if (CrConPI > (CrCat + 3)) {
                    alert(" El número de créditos elegido excede el máximo de créditos establecido " + (CrCat + 3));
                }
                if (CrSinPI < 7.5) {
                    alert(" Número de créditos de primer y segundo ciclo insuficiente (menor de 9) " + (CrSinPI));
                }
                if ((CrConPI - CrSinPI) > 3.0) {
                    alert(" El número de créditos (" + (CrConPI - CrSinPI) + ") en proyectos fin de carrera es mayor de 3");
                }
                if (docenciaTotal < 12) {
                    alert(" El número de créditos de docencia, (docencia en grado/1º ciclo, docencia en posgrado y Trabajos Fin de máster) (" + docenciaTotal + " crt) es menor que 12");
                }
                respuesta = false;
            }
            return respuesta;
        }

        // Función para validar y pasar turno
        function validarYPasarTurno() {
            // Obtener los datos necesarios del backend
            fetch('{{ route("ordenacion.datos-validacion") }}')
                .then(response => response.json())
                .then(data => {
                    const resultado = ValidaTurnoFase2(
                        data.creditosT,
                        data.credCategoría,
                        data.creditosConPI,
                        data.creditosSinPI,
                        data.docenciaTotal,
                        data.creditosMenos,
                        data.turno,
                        data.txtDocencia,
                        data.ccFaltaPresencial
                    );
                    
                    if (resultado) {
                        // Si la validación es exitosa, redirigir para pasar turno
                        window.location.href = '{{ route("ordenacion.pasar-turno") }}';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al validar los datos. Por favor, inténtelo de nuevo.');
                });
        }

        // Validación de créditos fraccionables en tiempo real
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input[type="number"]');
            
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    const max = parseFloat(this.getAttribute('max'));
                    const value = parseFloat(this.value);
                    
                    if (value > max) {
                        this.value = max;
                        alert('El número de créditos no puede exceder el máximo permitido (' + max + ')');
                    }
                    
                    if (value < 0) {
                        this.value = 0;
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>