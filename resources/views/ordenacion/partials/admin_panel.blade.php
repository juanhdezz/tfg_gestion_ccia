<!-- Panel de Administración -->
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Información del Turno Actual -->
    <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg border border-blue-200 dark:border-blue-700">
        <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-3">Turno Actual</h3>
        
        @if(isset($info_admin['turno_actual']) && $info_admin['turno_actual'])
            <div class="space-y-2">
                <p class="text-sm text-blue-700 dark:text-blue-300">
                    <span class="font-medium">Fase:</span> {{ $info_admin['turno_actual']->fase }}
                </p>
                <p class="text-sm text-blue-700 dark:text-blue-300">
                    <span class="font-medium">Turno:</span> {{ $info_admin['turno_actual']->turno }}
                </p>
                <p class="text-sm text-blue-700 dark:text-blue-300">
                    <span class="font-medium">Estado:</span> 
                    <span class="px-2 py-1 rounded-full text-xs 
                        {{ $info_admin['turno_actual']->estado == 'activo' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                        {{ ucfirst($info_admin['turno_actual']->estado) }}
                    </span>
                </p>
                
                @if(isset($info_admin['usuario_actual']) && $info_admin['usuario_actual'])
                    <div class="mt-3 pt-3 border-t border-blue-200 dark:border-blue-700">
                        <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Profesor en turno:</p>
                        <p class="text-sm text-blue-700 dark:text-blue-300">
                            {{ $info_admin['usuario_actual']->apellidos }}, {{ $info_admin['usuario_actual']->nombres }}
                        </p>
                    </div>
                @else
                    <div class="mt-3 pt-3 border-t border-blue-200 dark:border-blue-700">
                        <p class="text-sm text-blue-700 dark:text-blue-300">No hay profesor activo en turno</p>
                    </div>
                @endif
            </div>
        @else
            <p class="text-sm text-red-600 dark:text-red-400">No se encontró información del turno</p>
        @endif
    </div>

    <!-- Estadísticas de Profesores -->
    <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg border border-green-200 dark:border-green-700">
        <h3 class="text-lg font-semibold text-green-800 dark:text-green-200 mb-3">Estadísticas de Profesores</h3>
        
        <div class="space-y-2">
            <div class="flex justify-between">
                <span class="text-sm text-green-700 dark:text-green-300">Total:</span>
                <span class="text-sm font-medium text-green-800 dark:text-green-200">
                    {{ $info_admin['total_profesores'] ?? 0 }}
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-green-700 dark:text-green-300">Con perfil:</span>
                <span class="text-sm font-medium text-green-800 dark:text-green-200">
                    {{ $info_admin['profesores_con_perfil'] ?? 0 }}
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-green-700 dark:text-green-300">Sin perfil:</span>
                <span class="text-sm font-medium text-red-600 dark:text-red-400">
                    {{ $info_admin['profesores_sin_perfil'] ?? 0 }}
                </span>
            </div>
            
            @if(($info_admin['profesores_sin_perfil'] ?? 0) > 0)
                <div class="mt-3 pt-3 border-t border-green-200 dark:border-green-700">
                    <p class="text-xs text-yellow-600 dark:text-yellow-400">
                        ⚠️ Hay profesores sin perfil académico configurado
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Estadísticas de Asignaciones -->
    <div class="bg-purple-50 dark:bg-purple-900 p-4 rounded-lg border border-purple-200 dark:border-purple-700">
        <h3 class="text-lg font-semibold text-purple-800 dark:text-purple-200 mb-3">Asignaciones</h3>
        
        <div class="space-y-2">
            <div class="flex justify-between">
                <span class="text-sm text-purple-700 dark:text-purple-300">Realizadas:</span>
                <span class="text-sm font-medium text-purple-800 dark:text-purple-200">
                    {{ $info_admin['asignaciones_realizadas'] ?? 0 }}
                </span>
            </div>
            
            <div class="mt-3 pt-3 border-t border-purple-200 dark:border-purple-700">
                <p class="text-xs text-purple-600 dark:text-purple-400">
                    📊 Total de asignaciones docentes activas
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Lista de Profesores -->
@if(isset($info_admin['profesores_lista']) && $info_admin['profesores_lista']->count() > 0)
    <div class="mt-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Lista de Profesores por Prioridad</h3>
        
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 max-h-60 overflow-y-auto">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($info_admin['profesores_lista'] as $index => $profesor)
                    <div class="flex items-center space-x-2 p-2 rounded 
                        {{ $index + 1 == ($info_admin['turno_actual']->turno ?? 0) ? 'bg-blue-100 dark:bg-blue-800 border border-blue-300' : 'bg-white dark:bg-gray-600' }}">
                        <span class="text-xs font-mono text-gray-500 dark:text-gray-400 w-6">
                            {{ $index + 1 }}.
                        </span>
                        <span class="text-sm text-gray-800 dark:text-gray-200 truncate">
                            {{ $profesor->apellidos }}, {{ $profesor->nombres }}
                        </span>
                        @if($index + 1 == ($info_admin['turno_actual']->turno ?? 0))
                            <span class="text-xs bg-blue-500 text-white px-2 py-1 rounded-full">Activo</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

<!-- Controles de Administración -->
<div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-600">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Controles del Sistema</h3>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
        <button id="avanzar-turno-btn" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded text-sm transition-colors">
            🔄 Avanzar Turno
        </button>
        <button id="cambiar-fase-btn" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded text-sm transition-colors">
            🔧 Cambiar Fase
        </button>        <button id="exportar-datos-btn" class="bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded text-sm transition-colors">
            📊 Exportar Datos
        </button>
        <button id="reiniciar-sistema-btn" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded text-sm transition-colors">
            🔥 Reiniciar Sistema
        </button>
    </div>
    
    <!-- Mensajes de respuesta -->
    <div id="admin-messages" class="mt-4 hidden">
        <div id="admin-success" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-2">
            <span id="success-message"></span>
        </div>
        <div id="admin-error" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-2">
            <span id="error-message"></span>
        </div>
    </div>
</div>

<!-- Modal para cambiar fase -->
<div id="cambiar-fase-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Cambiar Fase del Sistema</h3>
            <div class="mt-4">
                <label for="nueva-fase" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Seleccionar nueva fase:
                </label>
                <select id="nueva-fase" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="-1">Fase -1: Proceso Inactivo</option>
                    <option value="0">Fase 0: Configuración Inicial</option>
                    <option value="1">Fase 1: Mantener Asignaturas</option>
                    <option value="2">Fase 2: Asignación por Turnos</option>
                    <option value="3">Fase 3: Asignación Libre</option>
                    <option value="4">Fase 4: Proceso Finalizado</option>
                </select>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button id="cancelar-cambio-fase" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                    Cancelar
                </button>
                <button id="confirmar-cambio-fase" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Cambiar Fase
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para exportar datos -->
<div id="exportar-datos-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Exportar Datos del Sistema</h3>
            <div class="mt-4">
                <label for="tipo-exportacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Seleccionar tipo de exportación:
                </label>
                <select id="tipo-exportacion" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="asignaciones">Asignaciones Docentes</option>
                    <option value="profesores">Información de Profesores</option>
                    <option value="turnos">Histórico de Turnos</option>
                    <option value="completo">Exportación Completa</option>
                </select>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Los datos se exportarán en formato CSV.
                </p>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button id="cancelar-exportacion" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                    Cancelar
                </button>
                <button id="confirmar-exportacion" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                    Exportar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Token CSRF para las peticiones
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Función para mostrar mensajes
    function mostrarMensaje(tipo, mensaje) {
        const messagesContainer = document.getElementById('admin-messages');
        const successDiv = document.getElementById('admin-success');
        const errorDiv = document.getElementById('admin-error');
        
        // Ocultar todos los mensajes primero
        successDiv.classList.add('hidden');
        errorDiv.classList.add('hidden');
        
        if (tipo === 'success') {
            document.getElementById('success-message').textContent = mensaje;
            successDiv.classList.remove('hidden');
        } else {
            document.getElementById('error-message').textContent = mensaje;
            errorDiv.classList.remove('hidden');
        }
        
        messagesContainer.classList.remove('hidden');
        
        // Auto-ocultar después de 5 segundos
        setTimeout(() => {
            messagesContainer.classList.add('hidden');
        }, 5000);
    }
    
    // Avanzar Turno
    document.getElementById('avanzar-turno-btn')?.addEventListener('click', function() {
        if (confirm('¿Estás seguro de que quieres avanzar al siguiente turno?')) {
            fetch('{{ route("ordenacion.admin.avanzar-turno") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarMensaje('success', data.message);
                    // Recargar la página después de 2 segundos
                    setTimeout(() => location.reload(), 2000);
                } else {
                    mostrarMensaje('error', data.message || 'Error al avanzar turno');
                }
            })
            .catch(error => {
                mostrarMensaje('error', 'Error de conexión: ' + error.message);
            });
        }
    });
    
    // Cambiar Fase - Mostrar modal
    document.getElementById('cambiar-fase-btn')?.addEventListener('click', function() {
        document.getElementById('cambiar-fase-modal').classList.remove('hidden');
    });
    
    // Cancelar cambio de fase
    document.getElementById('cancelar-cambio-fase')?.addEventListener('click', function() {
        document.getElementById('cambiar-fase-modal').classList.add('hidden');
    });
    
    // Confirmar cambio de fase
    document.getElementById('confirmar-cambio-fase')?.addEventListener('click', function() {
        const nuevaFase = document.getElementById('nueva-fase').value;
        
        if (confirm(`¿Estás seguro de que quieres cambiar a la Fase ${nuevaFase}?`)) {
            fetch('{{ route("ordenacion.admin.cambiar-fase") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ fase: parseInt(nuevaFase) })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('cambiar-fase-modal').classList.add('hidden');
                
                if (data.success) {
                    mostrarMensaje('success', data.message);
                    // Recargar la página después de 2 segundos
                    setTimeout(() => location.reload(), 2000);
                } else {
                    mostrarMensaje('error', data.message || 'Error al cambiar fase');
                }
            })
            .catch(error => {
                document.getElementById('cambiar-fase-modal').classList.add('hidden');
                mostrarMensaje('error', 'Error de conexión: ' + error.message);
            });        }
    });
    
    // Exportar Datos - Mostrar modal
    document.getElementById('exportar-datos-btn')?.addEventListener('click', function() {
        document.getElementById('exportar-datos-modal').classList.remove('hidden');
    });
    
    // Cancelar exportación
    document.getElementById('cancelar-exportacion')?.addEventListener('click', function() {
        document.getElementById('exportar-datos-modal').classList.add('hidden');
    });
    
    // Confirmar exportación
    document.getElementById('confirmar-exportacion')?.addEventListener('click', function() {
        const tipoExportacion = document.getElementById('tipo-exportacion').value;
        
        // Crear enlace de descarga con el tipo de exportación
        const url = new URL('{{ route("ordenacion.admin.exportar-datos") }}');
        url.searchParams.append('tipo_exportacion', tipoExportacion);
        
        // Crear enlace temporal para descargar
        const a = document.createElement('a');
        a.href = url.toString();
        a.style.display = 'none';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        
        // Cerrar modal
        document.getElementById('exportar-datos-modal').classList.add('hidden');
        
        // Mostrar mensaje informativo
        mostrarMensaje('success', `Iniciando descarga de exportación: ${tipoExportacion}`);
    });
    
    // Reiniciar Sistema
    document.getElementById('reiniciar-sistema-btn')?.addEventListener('click', function() {
        const confirmacion = prompt('Para confirmar el reinicio del sistema, escribe: REINICIAR');
        
        if (confirmacion === 'REINICIAR') {
            if (confirm('⚠️ ÚLTIMA CONFIRMACIÓN: ¿Estás completamente seguro de reiniciar todo el sistema? Esta acción no se puede deshacer.')) {
                fetch('{{ route("ordenacion.admin.reiniciar-sistema") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarMensaje('success', data.message);
                        // Recargar la página después de 3 segundos
                        setTimeout(() => location.reload(), 3000);
                    } else {
                        mostrarMensaje('error', data.message || 'Error al reiniciar sistema');
                    }
                })
                .catch(error => {
                    mostrarMensaje('error', 'Error de conexión: ' + error.message);
                });
            }
        } else if (confirmacion !== null) {
            alert('Confirmación incorrecta. Reinicio cancelado.');
        }
    });
      // Cerrar modal al hacer clic fuera
    document.getElementById('cambiar-fase-modal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
    
    document.getElementById('exportar-datos-modal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
});
</script>
