<!-- filepath: /resources/views/ordenacion/proceso_inactivo.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-red-500">
            Proceso de Ordenación Docente
        </h1>
        
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 dark:bg-red-200">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        Proceso no disponible
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>El proceso de ordenación docente no está disponible en este momento.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del estado actual -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden">
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Estado del Proceso</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                            Proceso Inactivo
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            El proceso de ordenación docente para el {{ $curso_siguiente ?? 'próximo curso' }} 
                            aún no ha comenzado o ha sido suspendido temporalmente.
                        </p>
                    </div>
                    
                    <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            Estado del Sistema
                        </h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Fase:</span>
                                <span class="font-medium text-red-600 dark:text-red-400">
                                    {{ $fase == -1 ? 'Inactivo' : $fase }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Estado:</span>
                                <span class="font-medium text-red-600 dark:text-red-400">
                                    {{ $estado ?? 'No disponible' }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Curso actual:</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ $curso_actual ?? 'No definido' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información para el usuario -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden">
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">¿Qué significa esto?</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-yellow-100 dark:bg-yellow-900">
                                <span class="text-yellow-600 dark:text-yellow-400 font-medium">1</span>
                            </span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">El proceso aún no ha comenzado</h3>
                            <p class="text-gray-600 dark:text-gray-300">
                                Es posible que el administrador del sistema aún no haya iniciado el proceso de ordenación docente 
                                para el próximo curso académico.
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-yellow-100 dark:bg-yellow-900">
                                <span class="text-yellow-600 dark:text-yellow-400 font-medium">2</span>
                            </span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Suspensión temporal</h3>
                            <p class="text-gray-600 dark:text-gray-300">
                                El proceso podría estar suspendido temporalmente por motivos administrativos o técnicos.
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-yellow-100 dark:bg-yellow-900">
                                <span class="text-yellow-600 dark:text-yellow-400 font-medium">3</span>
                            </span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Período de mantenimiento</h3>
                            <p class="text-gray-600 dark:text-gray-300">
                                El sistema podría estar en período de mantenimiento o actualización de datos.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Solo mostrar panel de admin si es administrador -->
        @role('admin')
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 overflow-hidden">
            <div class="bg-red-50 dark:bg-red-900 px-4 py-2 border-b border-red-200 dark:border-red-600">
                <h2 class="text-xl font-semibold text-red-800 dark:text-red-200">
                    <i class="fas fa-user-shield mr-2"></i>Panel de Administración
                </h2>
            </div>
            <div class="p-6">
                <div class="bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700 dark:text-yellow-200">
                                <strong>Administrador:</strong> Para activar el proceso de ordenación docente, 
                                debe configurar la fase en el panel de administración correspondiente.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Acciones recomendadas:</h3>
                        <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                            <li>• Verificar configuración de fases</li>
                            <li>• Revisar fechas del proceso</li>
                            <li>• Comprobar estado del sistema</li>
                            <li>• Validar datos de profesores</li>
                        </ul>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Información del sistema:</h3>
                        <div class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                            @if(isset($info_admin))
                                <div>Profesores registrados: <span class="font-medium">{{ $info_admin['total_profesores'] ?? 'N/A' }}</span></div>
                                <div>Con perfil configurado: <span class="font-medium">{{ $info_admin['profesores_con_perfil'] ?? 'N/A' }}</span></div>
                                <div>Sin perfil: <span class="font-medium text-red-600">{{ $info_admin['profesores_sin_perfil'] ?? 'N/A' }}</span></div>
                            @else
                                <div class="text-gray-500 italic">Información no disponible</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endrole

        <!-- Información de contacto -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                    <i class="fas fa-question-circle mr-2"></i>¿Necesita ayuda?
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Para profesores:</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">
                            Si tiene dudas sobre cuándo estará disponible el proceso, contacte con:
                        </p>
                        <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-2">
                            <li>
                                <i class="fas fa-envelope text-blue-500 mr-2"></i>
                                <strong>Secretaría del Departamento</strong>
                            </li>
                            <li>
                                <i class="fas fa-phone text-green-500 mr-2"></i>
                                <strong>Coordinación Docente</strong>
                            </li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Mientras tanto:</h3>
                        <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-2">
                            <li>
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Puede revisar la información de cursos anteriores
                            </li>
                            <li>
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Verificar que sus datos están actualizados
                            </li>
                            <li>
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Consultar el calendario académico
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón para volver al dashboard -->
        <div class="flex justify-center mt-6">
            <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <i class="fas fa-home mr-2"></i>
                Volver al Panel Principal
            </a>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-refresh cada 5 minutos para comprobar si el proceso se ha activado
        setTimeout(function() {
            location.reload();
        }, 300000); // 5 minutos
        
        // Mostrar notificación de auto-refresh
        console.log('Esta página se actualizará automáticamente cada 5 minutos para comprobar el estado del proceso.');
    </script>
    @endpush
</x-app-layout>
