<!-- filepath: /resources/views/ordenacion/fase2.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-blue-500">
            Elección de Ordenación Docente - Segunda Fase
        </h1>

        <div class="mb-4 bg-blue-50 border-l-4 border-blue-400 p-4 dark:bg-blue-200">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        Actualmente estamos en la <strong>segunda fase</strong>. Cuando le llegue su turno podrá seleccionar asignaturas disponibles.
                        El turno actual es <strong>{{ $turno }}</strong>.
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
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Asignaturas asignadas en el {{ $curso_siguiente }}</h2>
            </div>
            <div class="p-4">
                @include('ordenacion.partials.asignaciones_actuales')
            </div>
        </div>
        
        <!-- Sección de preferencia de pasar turno -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden">
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Pasar Turno</h2>
            </div>
            <div class="p-4">
                <form method="post" action="{{ route('ordenacion.pasar-turno-preferencia') }}">
                    @csrf
                    <div class="mb-4">
                        <p class="text-gray-700 dark:text-gray-300 mb-2">
                            Si esta opción está activada, cuando llegue su turno se pasará al siguiente usuario sin realizar ningún cambio en su ordenación docente
                        </p>
                        <div class="flex items-center">
                            <input type="checkbox" name="pasar_turno" id="pasar_turno" 
                                   {{ $perfil->pasar_turno ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded">
                            <label for="pasar_turno" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Pasar Turno
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Guardar Cambios
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Sección de perfil académico -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden">
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Perfil Académico</h2>
                <button id="toggle-perfil" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                    Mostrar/Ocultar
                </button>
            </div>
            <div id="perfil-content" class="p-4 hidden">
                @include('ordenacion.partials.perfil_academico')
            </div>
        </div>
        <!-- Resumen -->
<div class="flex justify-center mt-6">
    <a href="{{ route('ordenacion.resumen') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
        Ver Resumen de Ordenación Docente
    </a>
</div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('toggle-perfil').addEventListener('click', function() {
            const perfilContent = document.getElementById('perfil-content');
            perfilContent.classList.toggle('hidden');
        });
    </script>
    @endpush
</x-app-layout>