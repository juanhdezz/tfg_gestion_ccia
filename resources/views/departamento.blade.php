<!-- filepath: /c:/xampp/htdocs/laravel/tfg_gestion_ccia/resources/views/departamento.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6 text-center">Gestión del Departamento</h1>
        
        <!-- Barra de búsqueda -->
        <div class="mb-6 max-w-xl mx-auto">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" id="search-modules" class="block w-full p-3 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500" placeholder="Buscar módulo de gestión...">
                <div id="search-reset" class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer hidden">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
            <div id="search-info" class="mt-2 text-sm text-gray-600 dark:text-gray-400 hidden">
                <span id="results-count">0</span> resultados encontrados
            </div>
        </div>

        <div id="modules-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            @role('admin|secretario')
            <!-- Gestión de usuarios -->
            <a href="{{ route('usuarios.index') }}" class="module-card flex flex-col items-center p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700" data-title="Gestión de usuarios" data-keywords="usuarios, lista, edición, eliminación">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-blue-500 dark:text-blue-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <h5 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">Gestión de usuarios</h5>
                <p class="text-gray-700 dark:text-gray-400 text-center">Lista, edición y eliminación de usuarios.</p>
            </a>
            
            <!-- Gestión de asignaturas -->
            <a href="{{ route('asignaturas.index') }}" class="module-card flex flex-col items-center p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700" data-title="Gestión de asignaturas" data-keywords="asignaturas, materias, cursos, administración">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-green-500 dark:text-green-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
                <h5 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">Gestión de asignaturas</h5>
                <p class="text-gray-700 dark:text-gray-400 text-center">Administración de asignaturas.</p>
            </a>

            <!-- Gestión de plazos -->
            <a href="{{ route('plazos.index') }}" class="module-card flex flex-col items-center p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700" data-title="Gestión de plazos" data-keywords="plazos, fechas límite, entregas, deadlines, calendario">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-indigo-500 dark:text-indigo-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5Z" />
                </svg>
                <h5 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">Gestión de plazos</h5>
                <p class="text-gray-700 dark:text-gray-400 text-center">Administración de plazos de entrega.</p>
            </a>

            <!-- Gestión de despachos -->
            <a href="{{ route('despachos.index') }}" class="module-card flex flex-col items-center p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700" data-title="Gestión de despachos" data-keywords="despachos, oficinas, salas, espacios, ubicaciones">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-purple-500 dark:text-purple-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5V21h18V7.5m0 0L12 3m6 4.5L12 3m6 4.5v9m0 0H6m6 0v9" />
                </svg>
                <h5 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">Gestión de despachos</h5>
                <p class="text-gray-700 dark:text-gray-400 text-center">Administración de despachos.</p>
            </a>
            
            <!-- Gestión de proyectos -->
            <a href="{{ route('proyectos.index') }}" class="module-card flex flex-col items-center p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700" data-title="Gestión de proyectos" data-keywords="proyectos, gestión, administración, seguimiento, evaluación">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-orange-500 dark:text-orange-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5V21h18V7.5m0 0L12 3m6 4.5L12 3m6 4.5v9m0 0H6m6 0v9" />
                </svg>
                <h5 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">Gestión de proyectos</h5>
                <p class="text-gray-700 dark:text-gray-400 text-center">Administración de proyectos.</p>
            </a>
            @endrole

            @role('admin|secretario|subdirectorDocente')
            <!-- Gestión de asignaciones -->
            <a href="{{ route('usuario_asignatura.index') }}" class="module-card flex flex-col items-center p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700" data-title="Gestión de asignaciones" data-keywords="asignaciones, profesores, distribución, horarios, carga docente">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-red-500 dark:text-red-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                <h5 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">Gestión de asignaciones</h5>
                <p class="text-gray-700 dark:text-gray-400 text-center">Administración de asignaciones a asignaturas.</p>
            </a>
            @endrole

            <!-- Gestión de tutorías - Accesible para todos -->
            <a href="{{ route('tutorias.index') }}" class="module-card flex flex-col items-center p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700" data-title="Gestión de tutorías" data-keywords="tutorías, horarios, atención a estudiantes, consultas">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-yellow-500 dark:text-yellow-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
                <h5 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">Gestión de tutorías</h5>
                <p class="text-gray-700 dark:text-gray-400 text-center">Administración de horarios de las tutorías.</p>
            </a>

            <!-- Gestión de reservas de salas - Accesible para todos -->
            <a href="{{ route('reserva_salas.index') }}" class="module-card flex flex-col items-center p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700" data-title="Gestión de reservas de salas" data-keywords="reservas, salas, gestión, administración, disponibilidad">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-teal-500 dark:text-teal-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5V21h18V7.5m0 0L12 3m6 4.5L12 3m6 4.5v9m0 0H6m6 0v9" />
                </svg>
                <h5 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">Gestión de reservas de salas</h5>
                <p class="text-gray-700 dark:text-gray-400 text-center">Administración de reservas de salas.</p>
            </a>

            <!-- Gestión de peticion de libros - Accesible para todos -->
            <a href="{{ route('libros.index') }}" class="module-card flex flex-col items-center p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700" data-title="Gestión de peticiones de libros" data-keywords="peticiones, libros, gestión, administración, solicitudes">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-pink-500 dark:text-pink-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5V21h18V7.5m0 0L12 3m6 4.5L12 3m6 4.5v9m0 0H6m6 0v9" />
                </svg>
                <h5 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">Gestión de peticiones de libros</h5>
                <p class="text-gray-700 dark:text-gray-400 text-center">Administración de peticiones de libros.</p>
            </a>

        </div>

        <!-- Mensaje cuando no hay resultados -->
        <div id="no-results" class="hidden bg-gray-50 dark:bg-gray-800 rounded-lg p-10 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-gray-600 dark:text-gray-300 text-xl font-medium mb-2">No se encontraron módulos</p>
            <p class="text-gray-500 dark:text-gray-400 mb-4">Intenta con otros términos de búsqueda</p>
            <button id="clear-search" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                Ver todos los módulos
            </button>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-modules');
            const searchReset = document.getElementById('search-reset');
            const clearSearch = document.getElementById('clear-search');
            const searchInfo = document.getElementById('search-info');
            const resultsCount = document.getElementById('results-count');
            const modulesGrid = document.getElementById('modules-grid');
            const moduleCards = document.querySelectorAll('.module-card');
            const noResults = document.getElementById('no-results');
            
            // Función para filtrar los módulos
            function filterModules(searchTerm) {
                searchTerm = searchTerm.toLowerCase().trim();
                let matchCount = 0;
                
                moduleCards.forEach(card => {
                    const title = card.getAttribute('data-title').toLowerCase();
                    const keywords = card.getAttribute('data-keywords').toLowerCase();
                    const desc = card.querySelector('p').textContent.toLowerCase();
                    
                    // Buscar coincidencias
                    const matches = title.includes(searchTerm) || 
                                   keywords.includes(searchTerm) || 
                                   desc.includes(searchTerm);
                    
                    if (matches) {
                        card.classList.remove('hidden');
                        // Añadir efecto de realce a los módulos coincidentes
                        card.classList.add('scale-105', 'border-indigo-300', 'dark:border-indigo-700');
                        card.classList.add('transition-all', 'duration-300');
                        matchCount++;
                    } else {
                        card.classList.add('hidden');
                        card.classList.remove('scale-105', 'border-indigo-300', 'dark:border-indigo-700');
                    }
                });
                
                // Actualizar contador de resultados
                resultsCount.textContent = matchCount;
                
                // Mostrar/ocultar elementos según resultados
                if (searchTerm === '') {
                    searchInfo.classList.add('hidden');
                    searchReset.classList.add('hidden');
                    noResults.classList.add('hidden');
                    moduleCards.forEach(card => {
                        card.classList.remove('scale-105', 'border-indigo-300', 'dark:border-indigo-700');
                    });
                    modulesGrid.classList.remove('hidden');
                } else {
                    searchInfo.classList.remove('hidden');
                    searchReset.classList.remove('hidden');
                    
                    if (matchCount === 0) {
                        modulesGrid.classList.add('hidden');
                        noResults.classList.remove('hidden');
                    } else {
                        modulesGrid.classList.remove('hidden');
                        noResults.classList.add('hidden');
                    }
                }
            }
            
            // Evento de búsqueda con debounce
            let debounceTimer;
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    filterModules(this.value);
                }, 300);
            });
            
            // Limpiar búsqueda con el botón X
            searchReset.addEventListener('click', function() {
                searchInput.value = '';
                filterModules('');
                searchInput.focus();
            });
            
            // Limpiar búsqueda con el botón "Ver todos los módulos"
            clearSearch.addEventListener('click', function() {
                searchInput.value = '';
                filterModules('');
                searchInput.focus();
            });
            
            // Añadir animación de entrada a los módulos al cargar
            moduleCards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('animate-fadeIn');
                }, index * 100);
            });
            
            // Permitir navegación rápida con teclado
            document.addEventListener('keydown', function(e) {
                // Alt+S para enfocar la búsqueda
                if (e.altKey && e.key === 's') {
                    e.preventDefault();
                    searchInput.focus();
                }
                
                // Escape para limpiar la búsqueda
                if (e.key === 'Escape' && document.activeElement === searchInput) {
                    searchInput.value = '';
                    filterModules('');
                }
            });
        });
    </script>
    
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        .module-card {
            opacity: 0;
        }
    </style>
    @endpush
</x-app-layout>