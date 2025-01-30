<x-app-layout>
    <div class="min-h-screen flex bg-gray-100">
        <!-- Contenido principal -->
        <div class="flex-1 flex flex-col">
            <!-- Barra de navegación superior -->
            <header class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
                <div class="text-xl font-semibold text-gray-800">Dashboard</div>
                <div class="flex items-center space-x-4">
                    <!-- Nombre del usuario -->
                    <span class="text-gray-700 font-medium hidden sm:inline">
                        {{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}
                    </span>

                    <!-- Menú desplegable al hacer clic en la foto -->
                    <div class="relative">
                        <button onclick="toggleDropdown()" class="focus:outline-none">
                            <img src="{{ Auth::user()->foto ?? 'https://via.placeholder.com/40' }}" 
                                 class="w-10 h-10 rounded-full border" 
                                 alt="Foto de perfil">
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
            </header>

            <!-- Contenido principal -->
            <main class="p-6">
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">
                        Bienvenido, {{ Auth::user()->nombre_abreviado }}
                    </h2>
                    <p class="text-gray-600">
                        Último acceso: {{ Auth::user()->user_last_login ?? 'No registrado' }}
                    </p>
                </div>
            </main>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            document.getElementById("profileDropdown").classList.toggle("hidden");
        }
    </script>
</x-app-layout>