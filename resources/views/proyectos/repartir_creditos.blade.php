<x-app-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Distribución de Créditos</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $proyecto->codigo }} - {{ $proyecto->titulo }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('proyectos.compensaciones', $proyecto->id_proyecto) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition duration-300">
                        Ver Compensaciones
                    </a>
                    <a href="{{ route('proyectos.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded transition duration-300">
                        Volver a Proyectos
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Información del proyecto -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 dark:text-gray-300">Responsable:</strong>
                        <span class="text-gray-800 dark:text-gray-200">
                            {{ $proyecto->responsable ? $proyecto->responsable->nombre . ' ' . $proyecto->responsable->apellidos : 'Sin asignar' }}
                        </span>
                    </div>
                    <div>
                        <strong class="text-gray-700 dark:text-gray-300">Créditos totales disponibles:</strong>
                        <span class="text-gray-800 dark:text-gray-200 font-semibold">
                            {{ $proyecto->creditos_compensacion_proyecto ?? 0 }}
                        </span>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Los créditos se distribuirán de manera equitativa entre los usuarios seleccionados.
                    </p>
                </div>
            </div>            <!-- Formulario de selección de usuarios -->
            <form action="{{ route('proyectos.guardarReparto', $proyecto->id_proyecto) }}" method="POST" id="formulario-reparto">
                @csrf
                
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Seleccionar Usuarios para Compensación</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Seleccione los usuarios que recibirán compensación. Los {{ $proyecto->creditos_compensacion_proyecto ?? 0 }} créditos se distribuirán de manera equitativa entre los usuarios seleccionados.
                    </p>
                    
                    @if($usuarios->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($usuarios as $usuario)
                                <div class="bg-white dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <label class="flex items-start space-x-3 cursor-pointer">
                                        <input type="checkbox" 
                                               name="usuarios_seleccionados[]" 
                                               value="{{ $usuario->id_usuario }}"
                                               class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded usuario-checkbox">
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-900 dark:text-gray-100">
                                                {{ $usuario->nombre }} {{ $usuario->apellidos }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $usuario->email }}
                                            </div>
                                            @if($usuario->categoriaDocente)
                                                <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                    {{ $usuario->categoriaDocente->nombre_categoria }}
                                                </div>
                                            @endif
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Información de distribución -->
                        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <h3 class="font-medium text-gray-800 dark:text-white mb-2">Información de Distribución:</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400" id="info-distribucion">
                                Seleccione al menos un usuario para ver cómo se distribuirán los créditos.
                            </p>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No hay usuarios disponibles para asignar compensaciones.</p>
                    @endif
                </div>

                <!-- Botones de acción -->
                <div class="flex gap-3">
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed" 
                            id="btn-guardar" 
                            disabled>
                        Asignar Compensaciones
                    </button>
                    <a href="{{ route('proyectos.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded transition duration-300">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const creditosTotales = parseFloat({{ $proyecto->creditos_compensacion_proyecto ?? 0 }});
            const checkboxes = document.querySelectorAll('.usuario-checkbox');
            const btnGuardar = document.getElementById('btn-guardar');
            const infoDistribucion = document.getElementById('info-distribucion');
            
            function actualizarInformacion() {
                const usuariosSeleccionados = document.querySelectorAll('.usuario-checkbox:checked').length;
                
                if (usuariosSeleccionados === 0) {
                    btnGuardar.disabled = true;
                    infoDistribucion.textContent = 'Seleccione al menos un usuario para ver cómo se distribuirán los créditos.';
                } else {
                    btnGuardar.disabled = false;
                    const creditosPorUsuario = (creditosTotales / usuariosSeleccionados).toFixed(2);
                    infoDistribucion.innerHTML = `
                        <strong>${usuariosSeleccionados}</strong> usuario(s) seleccionado(s). 
                        Cada usuario recibirá <strong>${creditosPorUsuario}</strong> créditos 
                        (Total: ${creditosTotales} créditos).
                    `;
                }
            }
            
            // Agregar event listeners a todos los checkboxes
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', actualizarInformacion);
            });
            
            // Inicializar
            actualizarInformacion();
        });
    </script>
</x-app-layout>
