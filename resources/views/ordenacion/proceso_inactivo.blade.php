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
        </div>        <!-- Solo mostrar panel de admin si es administrador -->
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
                                puede cambiar la fase del sistema usando el botón de abajo.
                            </p>
                        </div>
                    </div>                </div>
                
                
                
                <!-- Mensajes de respuesta -->
                <div id="admin-messages" class="mb-4 hidden">
                    <div id="admin-success" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-2">
                        <span id="success-message"></span>
                    </div>
                    <div id="admin-error" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-2">
                        <span id="error-message"></span>
                    </div>
                </div>

                <!-- Panel de Administración Completo -->
                <div class="mb-6">
                    @include('ordenacion.partials.admin_panel')
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Acciones disponibles:</h3>
                        <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                            <li>• <strong>Fase 1:</strong> Mantener Asignaturas</li>
                            <li>• <strong>Fase 2:</strong> Asignación por Turnos</li>
                            <li>• <strong>Fase 3:</strong> Asignación Libre</li>
                            <li>• Verificar configuración del sistema</li>
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
    </div>    @push('scripts')
    <script>
        // Variables globales
        let isUpdating = false;
        
        // Auto-refresh cada 5 minutos para comprobar si el proceso se ha activado
        setTimeout(function() {
            location.reload();
        }, 300000); // 5 minutos
        
        // Mostrar notificación de auto-refresh
        console.log('Esta página se actualizará automáticamente cada 5 minutos para comprobar el estado del proceso.');        // Event listeners para el panel de administración
        document.addEventListener('DOMContentLoaded', function() {
            // Botón principal de cambiar fase
            const cambiarFaseBtn = document.getElementById('cambiar-fase-btn');
            if (cambiarFaseBtn) {
                cambiarFaseBtn.addEventListener('click', function() {
                    mostrarModalCambioFase();
                });
            }

            // Botón de avanzar turno
            const avanzarTurnoBtn = document.getElementById('avanzar-turno-btn');
            if (avanzarTurnoBtn) {
                avanzarTurnoBtn.addEventListener('click', function() {
                    avanzarTurno();
                });
            }

            // Botón de resetear proceso
            const resetearProcesoBtn = document.getElementById('resetear-proceso-btn');
            if (resetearProcesoBtn) {
                resetearProcesoBtn.addEventListener('click', function() {
                    resetearProceso();
                });
            }

            // Botón de generar reporte
            const generarReporteBtn = document.getElementById('generar-reporte-btn');
            if (generarReporteBtn) {
                generarReporteBtn.addEventListener('click', function() {
                    generarReporte();
                });
            }
        });

        function mostrarModalCambioFase() {
            const fases = ['1', '2', '3'];
            let opcionesHtml = fases.map(fase => 
                `<option value="${fase}">Fase ${fase}</option>`
            ).join('');

            Swal.fire({
                title: 'Cambiar Fase del Sistema',
                html: `
                    <div class="text-left">
                        <label for="nueva-fase" class="block text-sm font-medium text-gray-700 mb-2">
                            Seleccione la nueva fase:
                        </label>
                        <select id="nueva-fase" class="w-full p-2 border border-gray-300 rounded-md">
                            <option value="">Seleccionar fase...</option>
                            ${opcionesHtml}
                        </select>
                        <div class="mt-3 text-sm text-gray-600">
                            <p><strong>Fase 1:</strong> Mantener Asignaturas</p>
                            <p><strong>Fase 2:</strong> Asignación por Turnos</p>
                            <p><strong>Fase 3:</strong> Asignación Libre</p>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Cambiar Fase',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#059669',
                cancelButtonColor: '#6b7280',
                preConfirm: () => {
                    const nuevaFase = document.getElementById('nueva-fase').value;
                    if (!nuevaFase) {
                        Swal.showValidationMessage('Por favor seleccione una fase');
                        return false;
                    }
                    return nuevaFase;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    cambiarFase(result.value);
                }
            });
        }

        function cambiarFase(nuevaFase) {
            if (isUpdating) return;
            isUpdating = true;

            fetch('/admin/cambiar-fase', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    nueva_fase: nuevaFase
                })
            })
            .then(response => response.json())
            .then(data => {
                isUpdating = false;
                if (data.success) {
                    mostrarMensaje('success', data.message || `Fase cambiada exitosamente a Fase ${nuevaFase}`);
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    mostrarMensaje('error', data.message || 'Error al cambiar la fase');
                }
            })
            .catch(error => {
                isUpdating = false;
                console.error('Error:', error);
                mostrarMensaje('error', 'Error de conexión. Intente nuevamente.');
            });
        }

        function avanzarTurno() {
            if (isUpdating) return;

            Swal.fire({
                title: '¿Avanzar al siguiente turno?',
                text: 'Esta acción moverá el proceso al siguiente profesor en la cola.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, avanzar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6b7280'
            }).then((result) => {
                if (result.isConfirmed) {
                    isUpdating = true;

                    fetch('/admin/avanzar-turno', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        isUpdating = false;
                        if (data.success) {
                            mostrarMensaje('success', data.message || 'Turno avanzado exitosamente');
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            mostrarMensaje('error', data.message || 'Error al avanzar el turno');
                        }
                    })
                    .catch(error => {
                        isUpdating = false;
                        console.error('Error:', error);
                        mostrarMensaje('error', 'Error de conexión. Intente nuevamente.');
                    });
                }
            });
        }

        function resetearProceso() {
            if (isUpdating) return;

            Swal.fire({
                title: '¿Resetear todo el proceso?',
                text: 'Esta acción eliminará todas las asignaciones actuales y reiniciará el proceso desde el inicio. Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, resetear',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280'
            }).then((result) => {
                if (result.isConfirmed) {
                    isUpdating = true;

                    fetch('/admin/resetear-proceso', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        isUpdating = false;
                        if (data.success) {
                            mostrarMensaje('success', data.message || 'Proceso reseteado exitosamente');
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            mostrarMensaje('error', data.message || 'Error al resetear el proceso');
                        }
                    })
                    .catch(error => {
                        isUpdating = false;
                        console.error('Error:', error);
                        mostrarMensaje('error', 'Error de conexión. Intente nuevamente.');
                    });
                }
            });
        }

        function generarReporte() {
            window.open('/admin/reporte-asignaciones', '_blank');
        }

        function mostrarMensaje(tipo, mensaje) {
            const messagesContainer = document.getElementById('admin-messages');
            const successDiv = document.getElementById('admin-success');
            const errorDiv = document.getElementById('admin-error');
            const successSpan = document.getElementById('success-message');
            const errorSpan = document.getElementById('error-message');

            // Ocultar todos los mensajes primero
            successDiv.classList.add('hidden');
            errorDiv.classList.add('hidden');

            if (tipo === 'success') {
                successSpan.textContent = mensaje;
                successDiv.classList.remove('hidden');
            } else {
                errorSpan.textContent = mensaje;
                errorDiv.classList.remove('hidden');
            }

            messagesContainer.classList.remove('hidden');

            // Auto-hide después de 5 segundos
            setTimeout(() => {
                messagesContainer.classList.add('hidden');
            }, 5000);
        }
    </script>
    @endpush
</x-app-layout>
