<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
    <style>
        /* Estilos para el sidebar */
        #logo-sidebar {
            transition: width 0.3s ease;
            width: 80px; /* Inicialmente colapsado */
        }
        
        #logo-sidebar.expanded {
            width: 256px;
        }
        
        /* Estilos para el contenido principal */
        #main-content {
            transition: margin-left 0.3s ease;
            margin-left: 80px; /* Inicialmente con sidebar colapsado */
        }
        
        #main-content.expanded {
            margin-left: 256px;
        }
        
        /* Ocultar texto cuando el sidebar está colapsado */
        #logo-sidebar:not(.expanded) .sidebar-text {
            display: none;
        }

        /* Nuevo botón toggle minimalista */
        .sidebar-toggle {
            position: absolute;
            top: 70px;
            right: -12px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: #f3f4f6;
            border: 1px solid #e5e7eb;
            color: #6b7280;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 100;
            transform: rotate(0deg);
            transition: transform 0.3s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .sidebar-toggle:hover {
            background-color: #e5e7eb;
        }
        
        #logo-sidebar.expanded .sidebar-toggle {
            transform: rotate(180deg);
        }
        
        /* Estilos mejorados para el header con imágenes */
        .header-container {
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            position: relative;
        }
        
        .header-logo-left {
            display: flex;
            align-items: center;
            height: 100%;
            width: 80px;
            margin-left: 10px;
        }
        
        .header-logo-center {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Mejor adaptación de imágenes */
        .header-logo-left img {
            max-height: 80%;
            max-width: 100%;
            object-fit: contain;
        }
        
        .header-logo-center img {
            max-height: 95%;
            max-width: 100%;
            object-fit: contain;
        }
        
        /* Media queries para responsividad */
        @media (max-width: 768px) {
            .header-logo-center {
                display: none;
            }
            
            .header-container {
                justify-content: space-between;
            }
        }

        /* Animación suave para íconos del sidebar */
        #logo-sidebar .nav-icon {
            transition: margin 0.3s ease;
        }
        
        #logo-sidebar:not(.expanded) .nav-icon {
            margin-left: 8px;
        }
    </style>
</head>
<body class="bg-white dark:bg-gray-900">

    <!-- Banner de impersonación - ANTES del navbar -->
    <x-impersonation-banner />


    <!-- Navbar mejorado con mejor estructura para las imágenes -->
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700" style="margin-top: var(--impersonation-banner-height, 0);">
        <div class="header-container">
            <!-- Logo UGR - Izquierda -->
            <div class="header-logo-left">
                <img src="{{ asset('logo_ugr.png') }}" alt="Logo UGR">
            </div>
            
            <!-- Logo departamento - Centro -->
            <div class="header-logo-center">
                <img src="{{ asset('departamento.png') }}" alt="Logo Departamento">
            </div>
            
            <!-- Área de usuario - Derecha -->
            <div class="flex items-center gap-4">
                <!-- Botón móvil para sidebar -->
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-600 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-300 dark:hover:bg-gray-700">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                    </svg>
                </button>

                <!-- User menu -->
                <div class="relative">
                    @php
                        $impersonateController = new \App\Http\Controllers\ImpersonateController();
                        $isImpersonating = $impersonateController->isImpersonating();
                        $displayName = Auth::user()->nombre;
                        $displayClass = $isImpersonating ? 'text-yellow-600 font-bold' : '';
                    @endphp
                    
                    <button onclick="toggleDropdown()" class="focus:outline-none {{ $displayClass }}">
                        {{ $isImpersonating ? '🎭 ' : '' }}Usuario: {{ $displayName }}
                    </button>
                    
                    <!-- Menú desplegable -->
                    <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg hidden dark:bg-gray-700">
                        @if($isImpersonating)
                        <div class="px-4 py-2 text-sm text-yellow-600 border-b border-gray-200 dark:border-gray-600 bg-yellow-50 dark:bg-yellow-900/20">
                            🎭 Modo Impersonación
                        </div>
                        @endif
                        
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                            Editar Perfil
                        </a>
                        
                        @if($isImpersonating)
                        <form method="POST" action="{{ route('impersonate.stop') }}" class="border-t border-gray-200 dark:border-gray-600">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-yellow-600 hover:bg-gray-200 dark:hover:bg-gray-600">
                                🔚 Finalizar Impersonación
                            </button>
                        </form>
                        @endif
                        
                        <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-200 dark:border-gray-600">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-200 dark:hover:bg-gray-600">
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 h-screen pt-20 bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
        <!-- Botón de toggle dentro del sidebar -->
        <div class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 18l-6-6 6-6"/>
            </svg>
        </div>
        
        <div class="h-full px-3 pb-6 overflow-y-auto bg-white dark:bg-gray-800">
            <ul class="space-y-3 font-medium">
                <li>
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center p-2 rounded-lg group 
                              {{ request()->routeIs('dashboard') 
                                 ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' 
                                 : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 nav-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        <span class="ms-3 sidebar-text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('departamento') }}" 
                       class="flex items-center p-2 rounded-lg group 
                              {{ request()->routeIs('departamento') 
                                 ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' 
                                 : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 nav-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z" />
                        </svg>
                        <span class="ms-3 sidebar-text">Departamento</span>
                    </a>
                </li>
                <!-- Resto de los elementos del menú igual que antes -->
                <li>
                    <a href="{{ route('ordenacion.index') }}" 
                       class="flex items-center p-2 rounded-lg group 
                              {{ request()->routeIs('ordenacion.*') 
                                 ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' 
                                 : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 nav-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                        </svg>
                        <span class="ms-3 sidebar-text">Docencia</span>
                    </a>
                </li>
                <!-- Más elementos del menú... -->
            </ul>
        </div>
    </aside>

    <!-- Main content -->
    <div id="main-content" class="p-4">
        <div class="p-4 border-2 border-gray-200 rounded-lg dark:border-gray-700 mt-14">
            <!-- Breadcrumb -->
            @if(isset($breadcrumbs))
            <nav class="text-sm text-gray-600 mb-4 bg-gray-50 dark:bg-gray-800 p-2 rounded-lg shadow-sm">
                <ol class="flex flex-wrap items-center space-x-2">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center text-blue-600 hover:underline">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Inicio
                        </a>
                    </li>
                    <li class="text-gray-400">/</li>
                    @foreach($breadcrumbs as $breadcrumb)
                        @if (!$loop->last)
                            <li>
                                <a href="{{ $breadcrumb['url'] }}" class="text-blue-600 hover:underline">{{ $breadcrumb['name'] }}</a>
                            </li>
                            <li class="text-gray-400">/</li>
                        @else
                            <li class="text-gray-800 dark:text-gray-300 font-medium">{{ $breadcrumb['name'] }}</li>
                        @endif
                    @endforeach
                </ol>
            </nav>
            @endif
    
            <!-- Page content -->
            {{ $slot }}
        </div>
    </div>

    <script>
        // Función para el dropdown del perfil
        function toggleDropdown() {
            document.getElementById("profileDropdown").classList.toggle("hidden");
        }

        

        // Toggle del sidebar con localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const impersonationBanner = document.querySelector('[class*="bg-yellow-500"]');
            if (impersonationBanner) {
                const bannerHeight = impersonationBanner.offsetHeight;
                document.documentElement.style.setProperty('--impersonation-banner-height', bannerHeight + 'px');
                
                // Ajustar el margen superior del sidebar y contenido principal
                const sidebar = document.getElementById('logo-sidebar');
                const mainContent = document.getElementById('main-content');
                
                if (sidebar) {
                    sidebar.style.paddingTop = (80 + bannerHeight) + 'px';
                }
                if (mainContent) {
                    mainContent.style.marginTop = bannerHeight + 'px';
                }
            }
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('logo-sidebar');
            const mainContent = document.getElementById('main-content');
            
            // Comprobar si hay un estado guardado
            const sidebarState = localStorage.getItem('sidebarState');
            
            // Por defecto el sidebar está colapsado (no es expanded)
            if (sidebarState === 'expanded') {
                sidebar.classList.add('expanded');
                mainContent.classList.add('expanded');
            }
            
            // Evento para cambiar el estado del sidebar
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('expanded');
                mainContent.classList.toggle('expanded');
                
                // Guardar el estado actual
                if (sidebar.classList.contains('expanded')) {
                    localStorage.setItem('sidebarState', 'expanded');
                } else {
                    localStorage.setItem('sidebarState', 'collapsed');
                }
            });
            
            // Cerrar dropdown al hacer clic fuera
            document.addEventListener('click', function(event) {
                const profileDropdown = document.getElementById('profileDropdown');
                const profileButton = document.querySelector('button[onclick="toggleDropdown()"]');
                
                if (profileButton && profileDropdown && !profileButton.contains(event.target) && !profileDropdown.contains(event.target)) {
                    profileDropdown.classList.add('hidden');
                }
            });
        });

        // SweetAlert
        @if(session('swal'))
        Swal.fire({!! json_encode(session('swal')) !!});
        @endif
    </script>
    @stack('scripts')
</body>
</html>