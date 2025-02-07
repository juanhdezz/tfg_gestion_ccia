<x-app-layout>
    <div class="min-h-screen flex bg-gray-100">
        <!-- Contenido principal -->
        <div class="flex-1 flex flex-col">
            

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

</x-app-layout>