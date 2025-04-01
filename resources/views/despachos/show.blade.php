<!-- filepath: /c:/xampp/htdocs/laravel/tfg_gestion_ccia/resources/views/despachos/show.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-blue-500 px-6 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-white">Detalles del Despacho</h1>
                    <div class="flex space-x-2">
                        <a href="{{ route('despachos.edit', $despacho->id_despacho) }}" class="px-3 py-1 bg-white text-indigo-600 rounded-lg shadow hover:bg-gray-100 inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Editar
                        </a>
                        <a href="{{ route('despachos.index') }}" class="px-3 py-1 bg-gray-200 text-gray-800 rounded-lg shadow hover:bg-gray-300 inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Volver
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-3 border-b pb-2">Información Básica</h2>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Nombre:</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $despacho->nombre_despacho }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Centro:</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $despacho->centro->nombre_centro }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Siglas:</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $despacho->siglas_despacho ?? 'No especificado' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-3 border-b pb-2">Detalles de Contacto y Espacio</h2>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Teléfono:</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $despacho->telefono_despacho ?? 'No especificado' }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Número de puestos:</p>
                                <div class="flex items-center">
                                    <span class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 text-xs font-medium px-2.5 py-0.5 rounded mr-2">
                                        {{ $despacho->numero_puestos }}
                                    </span>
                                    <span class="text-gray-600 dark:text-gray-400">puestos disponibles</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($despacho->descripcion)
                <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-3 border-b pb-2">Descripción</h2>
                    <p class="text-gray-700 dark:text-gray-300">{{ $despacho->descripcion }}</p>
                </div>
                @endif
                
                <!-- Espacio para la posible lista de usuarios asignados -->
                <div class="mt-6">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Usuarios Asignados</h2>
                    
                    <!-- Aquí iría una tabla o lista de usuarios asignados a este despacho -->
                    <p class="text-gray-600 dark:text-gray-400 italic">Esta sección puede implementarse según la relación existente con los usuarios.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>