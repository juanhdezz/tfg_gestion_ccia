<x-app-layout>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white dark:bg-gray-800 p-10 rounded-xl shadow-lg">
            <!-- Animación del error -->
            <div class="flex justify-center">
                <div class="relative w-40 h-40">
                    <!-- Círculo animado exterior -->
                    <div class="absolute inset-0 border-4 border-red-500 rounded-full opacity-25 animate-ping"></div>
                    
                    <!-- Círculo con icono de error -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="bg-red-100 dark:bg-red-900 p-4 rounded-full">
                            <svg class="w-24 h-24 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mensaje de error -->
            <div class="text-center">
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white">
                    {{ $titulo ?? '¡Ups! Ha ocurrido un error' }}
                </h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                    {{ $mensaje ?? 'No se ha podido completar la operación solicitada. Por favor, inténtelo de nuevo más tarde.' }}
                </p>
                @if(isset($detalles) || isset($errorMessage))
                    <div class="mt-4 p-4 bg-red-50 dark:bg-red-900/30 rounded-lg">
                        <p class="text-sm text-red-800 dark:text-red-300">
                            {{ $detalles ?? $errorMessage ?? '' }}
                            {{ $errorCode ?? '' }}
                            {{ $errorDescription ?? '' }}
                        </p>
                    </div>
                @endif
            </div>
            
            <!-- Botón de volver -->
            <div class="flex justify-center mt-6">
                <a href="javascript:history.back()" class="group relative w-full md:w-auto flex justify-center py-3 px-6 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md transition-all duration-300 ease-in-out transform hover:-translate-y-1">
                    <span class="flex items-center">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver a la página anterior
                    </span>
                </a>
            </div>
            
            <!-- Enlace a la página de inicio -->
            <div class="text-center mt-4">
                <a href="{{ route('dashboard') }}" class="text-sm text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                    Ir al panel principal
                </a>
            </div>
        </div>
    </div>
</x-app-layout>