<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6 text-center">Gestión del Departamento</h1>
        
        @role('admin')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="{{ route('usuarios.index') }}" class="flex flex-col items-center p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-blue-500 dark:text-blue-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <h5 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">Gestión de usuarios</h5>
                <p class="text-gray-700 dark:text-gray-400 text-center">Lista, edición y eliminación de usuarios.</p>
            </a>
            
            <a href="{{ route('asignaturas.index') }}" class="flex flex-col items-center p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-green-500 dark:text-green-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <h5 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">Gestión de asignaturas</h5>
                <p class="text-gray-700 dark:text-gray-400 text-center">Administración de asignaturas.</p>
            </a>
            
            <a href="{{ route('usuario_asignatura.index') }}" class="flex flex-col items-center p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-red-500 dark:text-red-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <h5 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">Gestión de asignaciones</h5>
                <p class="text-gray-700 dark:text-gray-400 text-center">Administración de asignaciones a asignaturas.</p>
            </a>
        </div>
        @else
        <p class="text-center text-gray-500 dark:text-gray-400 mt-6">Nada por aquí todavía...</p>
        @endrole
    </div>
</x-app-layout>
