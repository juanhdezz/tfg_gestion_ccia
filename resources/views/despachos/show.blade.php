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
                                <p class="font-medium text-gray-900 dark:text-white">{{ $despacho->centro->nombre_centro ?? 'Sin Asignar' }}</p>
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
                
                <!-- Reemplazamos la sección de usuarios asignados con esta implementación completa -->
<div class="mt-6">
    
    
    @if($despacho->usuarios && $despacho->usuarios->count() > 0)
        <div class="bg-white dark:bg-gray-700 overflow-hidden shadow-sm rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nombre
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Categoría
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Teléfono
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach($despacho->usuarios as $usuario)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-xs font-medium text-indigo-800 dark:text-indigo-200">
                                                {{ strtoupper(substr($usuario->nombre, 0, 1) . substr($usuario->apellidos, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $usuario->nombre }} {{ $usuario->apellidos }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-300">
                                                {{ $usuario->nombre_abreviado }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $usuario->correo }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $usuario->tipo_usuario == 'Catedrático' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 
                                           ($usuario->tipo_usuario == 'Contratado' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : 
                                           'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300') }}">
                                        {{ $usuario->tipo_usuario ?? 'No especificado' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $usuario->telefono ?? 'No especificado' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Total: <strong>{{ $despacho->usuarios->count() }}</strong> de <strong>{{ $despacho->numero_puestos }}</strong> puestos ocupados</span>
            </div>
        </div>
    @else
        <div class="bg-white dark:bg-gray-700 rounded-lg p-6 flex flex-col items-center justify-center text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">No hay usuarios asignados</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mt-2">
                Este despacho está actualmente sin ocupar. Dispone de {{ $despacho->numero_puestos }} puestos disponibles para asignación.
            </p>
        </div>
    @endif
</div>
            </div>
        </div>
    </div>
</x-app-layout>