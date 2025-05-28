<!-- filepath: c:\xampp\htdocs\laravel\tfg_gestion_ccia\resources\views\plazos\edit.blade.php -->
<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <!-- Navegación y título -->
        <div class="mb-6">
            <div class="flex items-center">
                <div class="mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 p-2 rounded-lg bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Editar Plazo
                    </h1>
                    <div class="mt-1 flex items-center gap-2">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Modificando el plazo "{{ $plazo->nombre_plazo }}"
                        </p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($plazo->estaActivo())
                                bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                            @elseif($plazo->haTerminado())
                                bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300
                            @else
                                bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                            @endif
                        ">
                            {{ $plazo->estado }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Formulario de edición -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                    Información del plazo
                </h2>
            </div>
            
            <form action="{{ route('plazos.update', $plazo->id_plazo) }}" method="POST" class="p-6" id="editPlazoForm">
                @csrf
                @method('PUT')
                
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-300">
                                    Hay errores en el formulario:
                                </h3>
                                <ul class="mt-1 text-sm text-red-700 dark:text-red-400 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Nombre del plazo -->
                    <div>
                        <label for="nombre_plazo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Nombre del plazo <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="nombre_plazo" id="nombre_plazo" value="{{ old('nombre_plazo', $plazo->nombre_plazo) }}" 
                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md @error('nombre_plazo') border-red-300 dark:border-red-600 @enderror"
                            placeholder="Ej: Entrega de notas - 1er cuatrimestre" required maxlength="128">
                        @error('nombre_plazo')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- ID del plazo (solo lectura) -->
                    <div>
                        <label for="id_plazo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            ID del plazo
                        </label>
                        <input type="text" id="id_plazo" value="{{ $plazo->id_plazo }}" 
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 rounded-md cursor-not-allowed"
                            disabled readonly>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Este campo no se puede modificar</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Fecha de inicio -->
                    <div>
                        <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Fecha de inicio <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" 
                                value="{{ old('fecha_inicio', $plazo->fecha_inicio->format('Y-m-d')) }}"
                                class="pl-10 mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md @error('fecha_inicio') border-red-300 dark:border-red-600 @enderror"
                                required>
                        </div>
                        @error('fecha_inicio')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Fecha de finalización -->
                    <div>
                        <label for="fecha_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Fecha de finalización <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="date" name="fecha_fin" id="fecha_fin" 
                                value="{{ old('fecha_fin', $plazo->fecha_fin->format('Y-m-d')) }}"
                                class="pl-10 mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md @error('fecha_fin') border-red-300 dark:border-red-600 @enderror"
                                required>
                        </div>
                        @error('fecha_fin')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p id="fecha_error" class="hidden mt-1 text-sm text-red-600 dark:text-red-400">
                            La fecha de finalización debe ser igual o posterior a la fecha de inicio
                        </p>
                    </div>
                </div>
                
                <!-- Descripción -->
                <div class="mb-6">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Descripción
                    </label>
                    <textarea name="descripcion" id="descripcion" rows="4" 
                        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md @error('descripcion') border-red-300 dark:border-red-600 @enderror"
                        placeholder="Añade información detallada sobre este plazo...">{{ old('descripcion', $plazo->descripcion) }}</textarea>
                    @error('descripcion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Vista previa de cambios -->
                <div class="mb-6">
                    <h3 class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Vista previa de cambios</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Original -->
                        <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Valores originales</h4>
                            <div class="mb-2">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Nombre:</span>
                                <span class="ml-1 text-gray-900 dark:text-white">{{ $plazo->nombre_plazo }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Período:</span>
                                <span class="ml-1 text-gray-900 dark:text-white">
                                    {{ $plazo->fecha_inicio->format('d/m/Y') }} al {{ $plazo->fecha_fin->format('d/m/Y') }}
                                </span>
                            </div>
                            <div class="mb-2">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Duración:</span>
                                <span class="ml-1 text-gray-900 dark:text-white">
                                    {{ $plazo->fecha_inicio->diffInDays($plazo->fecha_fin) + 1 }} días
                                </span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700 dark:text-gray-300">Estado:</span>
                                <span class="ml-1">
                                    @if($plazo->estaActivo())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                            Activo
                                        </span>
                                    @elseif($plazo->haTerminado())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300">
                                            Finalizado
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                            Pendiente
                                        </span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        
                        <!-- Modificado -->
                        <div class="bg-blue-50 dark:bg-blue-900/10 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                            <h4 class="text-sm font-medium text-blue-700 dark:text-blue-300 mb-2">Con cambios aplicados</h4>
                            <div class="mb-2">
                                <span class="font-medium text-blue-700 dark:text-blue-300">Nombre:</span>
                                <span class="ml-1 text-gray-900 dark:text-white" id="preview_nombre">{{ $plazo->nombre_plazo }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="font-medium text-blue-700 dark:text-blue-300">Período:</span>
                                <span class="ml-1 text-gray-900 dark:text-white" id="preview_periodo">
                                    {{ $plazo->fecha_inicio->format('d/m/Y') }} al {{ $plazo->fecha_fin->format('d/m/Y') }}
                                </span>
                            </div>
                            <div class="mb-2">
                                <span class="font-medium text-blue-700 dark:text-blue-300">Duración:</span>
                                <span class="ml-1 text-gray-900 dark:text-white" id="preview_duracion">
                                    {{ $plazo->fecha_inicio->diffInDays($plazo->fecha_fin) + 1 }} días
                                </span>
                            </div>
                            <div>
                                <span class="font-medium text-blue-700 dark:text-blue-300">Estado:</span>
                                <span class="ml-1" id="preview_estado">
                                    @if($plazo->estaActivo())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                            Activo
                                        </span>
                                    @elseif($plazo->haTerminado())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300">
                                            Finalizado
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                            Pendiente
                                        </span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Botones de acción -->
                <div class="flex flex-col-reverse sm:flex-row sm:justify-between sm:space-x-3">
                    <div class="mt-3 sm:mt-0">
                        <button type="button" class="btn-subtle" id="cancel_button">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancelar
                        </button>
                    </div>
                    
                    <div class="flex space-x-3">
                        <button type="submit" class="btn-primary" id="submit_button">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Guardar Cambios
                        </button>
                        
                        <a href="{{ route('plazos.show', $plazo->id_plazo) }}" class="btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Ver detalles
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Estilos específicos -->
    <style>
        .btn-primary {
            @apply inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition;
        }
        
        .btn-secondary {
            @apply inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition;
        }
        
        .btn-subtle {
            @apply inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring focus:ring-gray-300 dark:focus:ring-gray-600 disabled:opacity-25 transition;
        }
    </style>
    
    <!-- Scripts para previsualización y validación -->
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nombreInput = document.getElementById('nombre_plazo');
            const fechaInicioInput = document.getElementById('fecha_inicio');
            const fechaFinInput = document.getElementById('fecha_fin');
            const descripcionInput = document.getElementById('descripcion');
            const fechaError = document.getElementById('fecha_error');
            const submitButton = document.getElementById('submit_button');
            const cancelButton = document.getElementById('cancel_button');
            const form = document.getElementById('editPlazoForm');
            
            // Referencias a elementos de previsualización
            const previewNombre = document.getElementById('preview_nombre');
            const previewPeriodo = document.getElementById('preview_periodo');
            const previewDuracion = document.getElementById('preview_duracion');
            const previewEstado = document.getElementById('preview_estado');
            
            // Estado original del formulario
            const formOriginalState = {
                nombre: nombreInput.value,
                fechaInicio: fechaInicioInput.value,
                fechaFin: fechaFinInput.value,
                descripcion: descripcionInput.value
            };
            
            let hasUnsavedChanges = false;
            
            // Función para verificar cambios
            function checkForChanges() {
                const currentState = {
                    nombre: nombreInput.value,
                    fechaInicio: fechaInicioInput.value,
                    fechaFin: fechaFinInput.value,
                    descripcion: descripcionInput.value
                };
                
                hasUnsavedChanges = JSON.stringify(currentState) !== JSON.stringify(formOriginalState);
                return hasUnsavedChanges;
            }
            
            // Función para actualizar la vista previa
            function actualizarVistaPrevia() {
                // Nombre
                previewNombre.textContent = nombreInput.value || "Sin nombre";
                
                // Fechas y período
                if (fechaInicioInput.value && fechaFinInput.value) {
                    const fechaInicio = new Date(fechaInicioInput.value);
                    const fechaFin = new Date(fechaFinInput.value);
                    
                    // Formato de fechas
                    const opcionesFecha = { day: '2-digit', month: '2-digit', year: 'numeric' };
                    const inicioStr = fechaInicio.toLocaleDateString('es-ES', opcionesFecha);
                    const finStr = fechaFin.toLocaleDateString('es-ES', opcionesFecha);
                    
                    previewPeriodo.textContent = `${inicioStr} al ${finStr}`;
                    
                    // Cálculo de duración
                    const diffTime = Math.abs(fechaFin - fechaInicio);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    previewDuracion.textContent = `${diffDays} día${diffDays !== 1 ? 's' : ''}`;
                    
                    // Determinar estado
                    const hoy = new Date();
                    hoy.setHours(0, 0, 0, 0);
                    
                    let estadoHTML = '';
                    if (fechaInicio > hoy) {
                        estadoHTML = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">Pendiente</span>';
                    } else if (fechaFin >= hoy) {
                        estadoHTML = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Activo</span>';
                    } else {
                        estadoHTML = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300">Finalizado</span>';
                    }
                    
                    previewEstado.innerHTML = estadoHTML;
                }
                
                // Verificar cambios
                checkForChanges();
            }
            
            // Validación de fechas
            function validarFechas() {
                if (fechaInicioInput.value && fechaFinInput.value) {
                    const fechaInicio = new Date(fechaInicioInput.value);
                    const fechaFin = new Date(fechaFinInput.value);
                    
                    if (fechaFin < fechaInicio) {
                        fechaError.classList.remove('hidden');
                        submitButton.disabled = true;
                        fechaFinInput.classList.add('border-red-300', 'dark:border-red-600');
                        fechaFinInput.classList.remove('border-gray-300', 'dark:border-gray-600');
                        return false;
                    } else {
                        fechaError.classList.add('hidden');
                        submitButton.disabled = false;
                        fechaFinInput.classList.remove('border-red-300', 'dark:border-red-600');
                        fechaFinInput.classList.add('border-gray-300', 'dark:border-gray-600');
                        return true;
                    }
                }
                return true;
            }
            
            // Eventos para actualizar vista previa
            nombreInput.addEventListener('input', actualizarVistaPrevia);
            fechaInicioInput.addEventListener('input', function() {
                actualizarVistaPrevia();
                validarFechas();
            });
            fechaFinInput.addEventListener('input', function() {
                actualizarVistaPrevia();
                validarFechas();
            });
            descripcionInput.addEventListener('input', actualizarVistaPrevia);
            
            // Manejo del envío del formulario con confirmación SweetAlert
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validar fechas antes de continuar
                if (!validarFechas()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error en las fechas',
                        text: 'Por favor, corrija los errores en las fechas antes de continuar.',
                        confirmButtonText: 'Entendido'
                    });
                    return;
                }
                
                // Verificar si hay cambios
                if (!checkForChanges()) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Sin cambios detectados',
                        text: 'No se han realizado modificaciones en el plazo.',
                        confirmButtonText: 'Entendido'
                    });
                    return;
                }
                
                // Mostrar resumen de cambios
                let cambiosHTML = '<div class="text-left">';
                
                if (nombreInput.value !== formOriginalState.nombre) {
                    cambiosHTML += `<p><strong>Nombre:</strong> "${formOriginalState.nombre}" → "${nombreInput.value}"</p>`;
                }
                
                if (fechaInicioInput.value !== formOriginalState.fechaInicio) {
                    const fechaOriginal = new Date(formOriginalState.fechaInicio).toLocaleDateString('es-ES');
                    const fechaNueva = new Date(fechaInicioInput.value).toLocaleDateString('es-ES');
                    cambiosHTML += `<p><strong>Fecha inicio:</strong> ${fechaOriginal} → ${fechaNueva}</p>`;
                }
                
                if (fechaFinInput.value !== formOriginalState.fechaFin) {
                    const fechaOriginal = new Date(formOriginalState.fechaFin).toLocaleDateString('es-ES');
                    const fechaNueva = new Date(fechaFinInput.value).toLocaleDateString('es-ES');
                    cambiosHTML += `<p><strong>Fecha fin:</strong> ${fechaOriginal} → ${fechaNueva}</p>`;
                }
                
                if (descripcionInput.value !== formOriginalState.descripcion) {
                    cambiosHTML += `<p><strong>Descripción:</strong> Modificada</p>`;
                }
                
                cambiosHTML += '</div>';
                
                // Confirmación con SweetAlert
                Swal.fire({
                    title: '¿Confirmar cambios del plazo?',
                    html: `
                        <div class="text-sm text-gray-600 mb-4">
                            Se aplicarán los siguientes cambios al plazo <strong>"${formOriginalState.nombre}"</strong>:
                        </div>
                        ${cambiosHTML}
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3b82f6',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="fas fa-save mr-2"></i>Sí, guardar cambios',
                    cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancelar',
                    customClass: {
                        popup: 'swal-wide'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Mostrar indicador de carga
                        Swal.fire({
                            title: 'Guardando cambios...',
                            text: 'Por favor, espere mientras se actualizan los datos.',
                            icon: 'info',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Enviar formulario
                        hasUnsavedChanges = false;
                        form.submit();
                    }
                });
            });
            
            // Manejo del botón cancelar
            cancelButton.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (checkForChanges()) {
                    Swal.fire({
                        title: '¿Descartar cambios?',
                        text: 'Tienes cambios sin guardar. ¿Estás seguro de que quieres salir sin guardar?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: '<i class="fas fa-trash mr-2"></i>Sí, descartar',
                        cancelButtonText: '<i class="fas fa-arrow-left mr-2"></i>Continuar editando'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            hasUnsavedChanges = false;
                            window.location.href = "{{ route('plazos.show', $plazo->id_plazo) }}";
                        }
                    });
                } else {
                    window.location.href = "{{ route('plazos.show', $plazo->id_plazo) }}";
                }
            });
            
            // Prevenir salida accidental con cambios no guardados
            window.addEventListener('beforeunload', function(e) {
                if (hasUnsavedChanges) {
                    e.preventDefault();
                    e.returnValue = '';
                }
            });
            
            // Inicialización
            actualizarVistaPrevia();
            validarFechas();
        });
    </script>
    
    <style>
        .swal-wide {
            width: 600px !important;
        }
    </style>
    @endpush
</x-app-layout>