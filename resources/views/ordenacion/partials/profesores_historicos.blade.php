<!-- Histórico de Profesores y Asignaturas -->
<div class="space-y-4">
    @if(isset($profesores_cursos_anteriores['anios_consultados']) && count($profesores_cursos_anteriores['anios_consultados']) > 0)
        <div class="bg-blue-50 dark:bg-blue-900 p-3 rounded-lg border border-blue-200 dark:border-blue-700">
            <p class="text-sm text-blue-800 dark:text-blue-200">
                <span class="font-medium">Cursos consultados:</span>
                {{ implode(', ', $profesores_cursos_anteriores['anios_consultados']) }}
            </p>
        </div>
    @endif

    @if(isset($profesores_cursos_anteriores['profesores']) && $profesores_cursos_anteriores['profesores']->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600">
            <!-- Controles de búsqueda y filtros -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-600">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-2 md:space-y-0">
                    <div class="flex items-center space-x-4">
                        <input type="text" 
                               id="buscar-profesor" 
                               placeholder="Buscar profesor..." 
                               class="px-3 py-2 border border-gray-300 dark:border-gray-500 rounded-md text-sm dark:bg-gray-700 dark:text-white">
                        <input type="text" 
                               id="buscar-asignatura" 
                               placeholder="Buscar asignatura..." 
                               class="px-3 py-2 border border-gray-300 dark:border-gray-500 rounded-md text-sm dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Total: <span class="font-medium">{{ $profesores_cursos_anteriores['profesores']->count() }}</span> profesores
                    </div>
                </div>
            </div>

            <!-- Lista de profesores con sus asignaturas -->
            <div class="max-h-96 overflow-y-auto" id="lista-profesores">
                @foreach($profesores_cursos_anteriores['profesores'] as $nombreProfesor => $asignaturas)
                    <div class="border-b border-gray-100 dark:border-gray-700 profesor-item" data-profesor="{{ strtolower($nombreProfesor) }}">
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-medium text-gray-900 dark:text-white">
                                    {{ $nombreProfesor }}
                                </h4>
                                <span class="text-xs bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-2 py-1 rounded-full">
                                    {{ $asignaturas->count() }} asignatura{{ $asignaturas->count() != 1 ? 's' : '' }}
                                </span>
                            </div>
                            
                            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($asignaturas as $asignatura)
                                    <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded border asignatura-item" 
                                         data-asignatura="{{ strtolower($asignatura->nombre_asignatura) }}">
                                        <div class="space-y-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $asignatura->nombre_asignatura }}
                                            </p>
                                            
                                            @if($asignatura->nombre_titulacion)
                                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                                    📚 {{ $asignatura->nombre_titulacion }}
                                                </p>
                                            @endif
                                            
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ ucfirst($asignatura->tipo) }} - Grupo {{ $asignatura->grupo }}
                                                </span>
                                                <span class="text-xs bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200 px-2 py-1 rounded">
                                                    {{ $asignatura->creditos }} cr.
                                                </span>
                                            </div>
                                            
                                            @if($asignatura->veces_impartida > 1)
                                                <div class="flex items-center space-x-1">
                                                    <span class="text-xs text-green-600 dark:text-green-400">
                                                        📊 Impartida {{ $asignatura->veces_impartida }} veces
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Estadísticas adicionales -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-blue-50 dark:bg-blue-900 p-3 rounded border border-blue-200 dark:border-blue-700 text-center">
                <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">Total Profesores</p>
                <p class="text-lg font-bold text-blue-800 dark:text-blue-200">
                    {{ $profesores_cursos_anteriores['profesores']->count() }}
                </p>
            </div>
            
            <div class="bg-green-50 dark:bg-green-900 p-3 rounded border border-green-200 dark:border-green-700 text-center">
                <p class="text-sm text-green-600 dark:text-green-400 font-medium">Total Asignaciones</p>
                <p class="text-lg font-bold text-green-800 dark:text-green-200">
                    {{ $profesores_cursos_anteriores['total_asignaciones'] }}
                </p>
            </div>
            
            <div class="bg-purple-50 dark:bg-purple-900 p-3 rounded border border-purple-200 dark:border-purple-700 text-center">
                <p class="text-sm text-purple-600 dark:text-purple-400 font-medium">Asignaturas Únicas</p>
                <p class="text-lg font-bold text-purple-800 dark:text-purple-200">
                    {{ $profesores_cursos_anteriores['profesores']->flatten()->unique('nombre_asignatura')->count() }}
                </p>
            </div>
            
            <div class="bg-orange-50 dark:bg-orange-900 p-3 rounded border border-orange-200 dark:border-orange-700 text-center">
                <p class="text-sm text-orange-600 dark:text-orange-400 font-medium">Años Analizados</p>
                <p class="text-lg font-bold text-orange-800 dark:text-orange-200">
                    {{ count($profesores_cursos_anteriores['anios_consultados']) }}
                </p>
            </div>
        </div>
    @else
        <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700 dark:text-yellow-200">
                        No se encontraron datos históricos de profesores y asignaturas para los cursos anteriores.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const buscarProfesor = document.getElementById('buscar-profesor');
    const buscarAsignatura = document.getElementById('buscar-asignatura');
    
    function filtrarProfesores() {
        const terminoProfesor = buscarProfesor.value.toLowerCase();
        const terminoAsignatura = buscarAsignatura.value.toLowerCase();
        const profesores = document.querySelectorAll('.profesor-item');
        
        profesores.forEach(profesor => {
            const nombreProfesor = profesor.dataset.profesor || '';
            const asignaturas = profesor.querySelectorAll('.asignatura-item');
            
            let profesorVisible = false;
            
            // Si hay término de búsqueda para profesor, verificar coincidencia
            if (terminoProfesor && !nombreProfesor.includes(terminoProfesor)) {
                // No coincide el nombre del profesor
                if (!terminoAsignatura) {
                    profesor.style.display = 'none';
                    return;
                }
            } else if (terminoProfesor && nombreProfesor.includes(terminoProfesor)) {
                profesorVisible = true;
            }
            
            // Filtrar asignaturas si hay término de búsqueda
            if (terminoAsignatura) {
                let asignaturaVisible = false;
                asignaturas.forEach(asignatura => {
                    const nombreAsignatura = asignatura.dataset.asignatura || '';
                    if (nombreAsignatura.includes(terminoAsignatura)) {
                        asignatura.style.display = 'block';
                        asignaturaVisible = true;
                    } else {
                        asignatura.style.display = 'none';
                    }
                });
                
                // Mostrar profesor solo si tiene asignaturas visibles o coincide su nombre
                profesor.style.display = (asignaturaVisible || profesorVisible) ? 'block' : 'none';
            } else {
                // Sin filtro de asignatura, mostrar todas
                asignaturas.forEach(asignatura => {
                    asignatura.style.display = 'block';
                });
                
                // Mostrar profesor si coincide o no hay filtro de profesor
                profesor.style.display = (profesorVisible || !terminoProfesor) ? 'block' : 'none';
            }
        });
    }
    
    if (buscarProfesor) {
        buscarProfesor.addEventListener('input', filtrarProfesores);
    }
    
    if (buscarAsignatura) {
        buscarAsignatura.addEventListener('input', filtrarProfesores);
    }
});
</script>
@endpush
