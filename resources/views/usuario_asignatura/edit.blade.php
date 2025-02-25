<!-- filepath: /c:/xampp/htdocs/laravel/tfg_gestion_ccia/resources/views/usuario_asignatura/edit.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Editar Asignación</h1>
        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('usuario_asignatura.update', ['id_asignatura' => $asignacion->id_asignatura, 'id_usuario' => $asignacion->id_usuario, 'tipo' => $asignacion->tipo, 'grupo' => $asignacion->grupo]) }}" method="POST" class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-white font-semibold mb-2">Usuario</label>
                    <select name="id_usuario" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded px-3 py-2">
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id_usuario }}" {{ $asignacion->id_usuario == $usuario->id_usuario ? 'selected' : '' }}>
                                {{ $usuario->nombre }} {{ $usuario->apellidos }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-white font-semibold mb-2">Asignatura</label>
                    <select name="id_asignatura" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded px-3 py-2" disabled>
                        @foreach($asignaturas as $asignatura)
                            <option value="{{ $asignatura->id_asignatura }}" {{ $asignacion->id_asignatura == $asignatura->id_asignatura ? 'selected' : '' }}>
                                {{ $asignatura->nombre_asignatura }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="id_asignatura" value="{{ $asignacion->id_asignatura }}">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-white font-semibold mb-2">Tipo</label>
                    <select name="tipo" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded px-3 py-2">
                        <option value="Teoría" {{ $asignacion->tipo == 'Teoría' ? 'selected' : '' }}>Teoría</option>
                        <option value="Prácticas" {{ $asignacion->tipo == 'Prácticas' ? 'selected' : '' }}>Prácticas</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-white font-semibold mb-2">Grupo</label>
                    <input type="text" name="grupo" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded px-3 py-2" value="{{ $asignacion->grupo }}">
                </div>
                
                <div class="mb-4">
                    <label for="creditos" class="block text-gray-700 dark:text-white font-semibold mb-2">Créditos</label>
                    <input type="number" step="0.1" id="creditos" name="creditos" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded px-3 py-2" value="{{ $asignacion->creditos }}" required>
                </div>
                
                <div class="mb-4">
                    <label for="antiguedad" class="block text-gray-700 dark:text-white font-semibold mb-2">Antigüedad</label>
                    <input type="number" id="antiguedad" name="antiguedad" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded px-3 py-2" value="{{ $asignacion->antiguedad }}" required>
                </div>
                
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="en_primera_fase" class="mr-2" {{ $asignacion->en_primera_fase ? 'checked' : '' }}>
                        <span class="text-gray-700 dark:text-white">En primera fase</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-between mt-6">
                <a href="{{ route('usuario_asignatura.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</x-app-layout>