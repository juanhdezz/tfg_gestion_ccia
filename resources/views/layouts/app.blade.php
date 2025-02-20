<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestión Interna</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white dark:bg-gray-900">
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="relative w-full">
            <!-- Full screen container with images -->
            <div class="w-full h-16 flex items-center justify-center absolute top-0 left-0 right-0">
                <!-- Top left image -->
                <div class="absolute top-0 left-5 bg-white dark:bg-gray-800 bg-opacity-75 p-1 rounded">
                    <div class="relative w-16 h-12">
                        <div class="absolute inset-0 bg-white dark:bg-gray-800 opacity-75 rounded"></div>
                        <img src="logo_ugr.png" alt="Left Image" class="relative w-16 h-16 object-contain">
                    </div>
                </div>
    
                <!-- Top center image -->
                <div class="absolute top-0 left-1/2 transform -translate-x-1/2">
                    <img src="departamento.png" alt="Center Image" class="w-80 h-20 object-contain">
                </div>
            </div>
    
            <!-- Navbar content (without "Gestión Interna" and right image) -->
            <div class="px-3 py-3 lg:px-5 lg:pl-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center justify-start rtl:justify-end">
                        <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-600 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-300 dark:hover:bg-gray-700">
                            <span class="sr-only">Open sidebar</span>
                            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center gap-4">
                        
    
                        <!-- User menu -->
                        <div class="relative">
                            <button onclick="toggleDropdown()" class="focus:outline-none">
                                {{-- <img src="{{ Auth::user()->foto }}" class="w-10 h-10 rounded-full border" alt="Foto de perfil"> --}}
                                Usuario : {{ Auth::user()->nombre }}
                            </button>
                            <!-- Menú desplegable -->
                            <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg hidden">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Editar Perfil</a>
                                <form method="POST" action="{{ route('logout') }} ">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-200">
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    

    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
        <div class="h-full px-4 pb-6 overflow-y-auto bg-white dark:bg-gray-800">
            <ul class="space-y-3 font-medium">
                <li>
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center p-2 rounded-lg group transition-all duration-300 transform 
                              {{ request()->routeIs('dashboard') 
                                 ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' 
                                 : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white hover:scale-105' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('departamento') }}" 
                       class="flex items-center p-2 rounded-lg group transition-all duration-300 transform 
                              {{ request()->routeIs('departamento') 
                                 ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' 
                                 : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white hover:scale-105' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z" />
                        </svg>
                        <span class="ms-3">Departamento</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center p-2 rounded-lg group transition-all duration-300 transform
                              {{ request()->routeIs('dodashboardcencia') 
                                 ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' 
                                 : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white hover:scale-105' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                        </svg>
                        <span class="ms-3">Docencia</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center p-2 rounded-lg group transition-all duration-300 transform
                              {{ request()->routeIs('dashboard') 
                                 ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' 
                                 : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white hover:scale-105' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                        </svg>
                        <span class="ms-3">Información</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center p-2 rounded-lg group transition-all duration-300 transform
                              {{ request()->routeIs('dashboard') 
                                 ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' 
                                 : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white hover:scale-105' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                        <span class="ms-3">Libros</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center p-2 rounded-lg group transition-all duration-300 transform
                              {{ request()->routeIs('dashboard-personales') 
                                 ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' 
                                 : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white hover:scale-105' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 9.75a7.5 7.5 0 1 0-15 0 7.5 7.5 0 0 0 15 0Zm-7.5 9.25c2.344 0 4.5.8 6.25 2.125M10.5 6a3 3 0 1 0 3 3 3 3 0 0 0-3-3Z" />
                        </svg>
                        <span class="ms-3">Datos personales</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
    
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 rounded-lg dark:border-gray-700 mt-14">
            {{ $slot }}
        </div>
    </div>
    <script>
        function toggleDropdown() {
            document.getElementById("profileDropdown").classList.toggle("hidden");
        }


        @if(session('swal'))
        Swal.fire({!! json_encode(session('swal')) !!});
        @endif
    </script>

    @stack('scripts')
</body>
</html>