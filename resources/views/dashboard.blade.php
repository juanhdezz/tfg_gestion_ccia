<x-app-layout>
    <div class="min-h-screen flex bg-gray-100">
        <!-- Contenido principal -->
        <div class="flex-1 flex flex-col">
            <!-- Barra de navegación superior -->
            <header class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
                <div class="text-xl font-semibold text-gray-800">Dashboard</div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700 font-medium hidden sm:inline">{{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}</span>
                    <img src="{{ Auth::user()->foto ?? 'https://via.placeholder.com/40' }}" class="w-10 h-10 rounded-full border" alt="User Photo">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Logout</button>
                    </form>
                </div>
            </header>

            <!-- Contenido -->
            <main class="p-6">
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Bienvenido, {{ Auth::user()->nombre_abreviado }}</h2>
                    <p class="text-gray-600">Último acceso: {{ Auth::user()->user_last_login ?? 'No registrado' }}</p>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>