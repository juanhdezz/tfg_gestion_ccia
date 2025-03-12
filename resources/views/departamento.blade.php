<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6 text-center">Gestión del Departamento</h1>
        
        @role('admin')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Gestión de usuarios -->
            <a href="{{ route('usuarios.index') }}" class="flex flex-col items-center p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-blue-500 dark:text-blue-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <h5 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">Gestión de usuarios</h5>
                <p class="text-gray-700 dark:text-gray-400 text-center">Lista, edición y eliminación de usuarios.</p>
            </a>
            
            <!-- Gestión de asignaturas -->
            <a href="{{ route('asignaturas.index') }}" class="flex flex-col items-center p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-green-500 dark:text-green-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
                <h5 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">Gestión de asignaturas</h5>
                <p class="text-gray-700 dark:text-gray-400 text-center">Administración de asignaturas.</p>
            </a>
            
            <!-- Gestión de asignaciones -->
            <a href="{{ route('usuario_asignatura.index') }}" class="flex flex-col items-center p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-red-500 dark:text-red-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                <h5 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">Gestión de asignaciones</h5>
                <p class="text-gray-700 dark:text-gray-400 text-center">Administración de asignaciones a asignaturas.</p>
            </a>

            <!-- Gestión de tutorías -->
            <a href="{{ route('tutorias.index') }}" class="flex flex-col items-center p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-yellow-500 dark:text-yellow-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
                <h5 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">Gestión de tutorías</h5>
                <p class="text-gray-700 dark:text-gray-400 text-center">Administración de horarios de las tutorías.</p>
            </a>

        </div>
        @else
        <a href="{{ route('tutorias.index') }}" class="flex flex-col items-center p-6 bg-white border border-gray-200 rounded-lg shadow-md hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-yellow-500 dark:text-yellow-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
            </svg>
            <h5 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">Gestión de tutorías</h5>
            <p class="text-gray-700 dark:text-gray-400 text-center">Administración de horarios de las tutorías.</p>
        </a>
        @endrole
    </div>
</x-app-layout>