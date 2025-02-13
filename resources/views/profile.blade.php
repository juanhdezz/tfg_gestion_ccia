<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold mb-6 text-indigo-700">Editar Perfil</h1>

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Nombre -->
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" id="nombre" name="nombre" 
                       value="{{ old('nombre', Auth::user()->nombre) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm">
                @error('nombre')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Apellidos -->
            <div>
                <label for="apellidos" class="block text-sm font-medium text-gray-700">Apellidos</label>
                <input type="text" id="apellidos" name="apellidos" 
                       value="{{ old('apellidos', Auth::user()->apellidos) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm">
                @error('apellidos')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Nombre abreviado -->
            <div>
                <label for="nombre_abreviado" class="block text-sm font-medium text-gray-700">Nombre abreviado</label>
                <input type="text" id="nombre_abreviado" name="nombre_abreviado" 
                       value="{{ old('nombre_abreviado', Auth::user()->nombre_abreviado) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm">
                @error('nombre_abreviado')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>


            <!-- Correo -->
            <div>
                <label for="correo" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" 
                       value="{{ old('correo', Auth::user()->correo) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm">
                @error('correo')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Teléfono -->
            <div>
                <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                <input type="text" id="telefono" name="telefono" 
                       value="{{ old('telefono', Auth::user()->telefono) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm">
                @error('telefono')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Foto (URL) -->
            <div>
                <label for="foto" class="block text-sm font-medium text-gray-700">Foto de Perfil (URL)</label>
                <input type="text" id="foto" name="foto" 
                       value="{{ old('foto', Auth::user()->foto) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm">
                @error('foto')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
                <img src="{{ Auth::user()->foto ?? 'https://via.placeholder.com/100' }}" class="mt-4 w-20 h-20 rounded-full border">
            </div>

            <!-- Contraseña Actual -->
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700">Contraseña Actual</label>
                <input type="password" id="current_password" name="current_password" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm">
                @error('current_password')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Nueva Contraseña -->
            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700">Nueva Contraseña</label>
                <input type="password" id="new_password" name="new_password" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm">
                @error('new_password')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirmar Nueva Contraseña -->
            <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Nueva Contraseña</label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm">
                @error('new_password_confirmation')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" 
                    class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-indigo-700 transition">
                Guardar Cambios
            </button>
        </form>
    </div>
</x-app-layout>
