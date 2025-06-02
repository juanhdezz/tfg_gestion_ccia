<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-purple-500">
            Gestión de Categorías de Usuarios
        </h1>
        
        <!-- Filtros -->
        <form method="GET" action="{{ route('usuarios.gestion-categorias') }}" class="mb-6 bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Grupo</label>
                    <select name="grupo" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Todos los grupos</option>
                        @foreach($grupos as $grupo)
                            <option value="{{ $grupo->id_grupo }}" {{ $grupoId == $grupo->id_grupo ? 'selected' : '' }}>
                                {{ $grupo->nombre_grupo }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoría</label>
                    <select name="categoria" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id_categoria }}" {{ $categoriaId == $categoria->id_categoria ? 'selected' : '' }}>
                                {{ $categoria->nombre_categoria ?? 'Categoría ' . $categoria->id_categoria }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Filtrar
                    </button>
                </div>
            </div>
        </form>

        <!-- Botón para asignar nueva categoría -->
        <div class="mb-4">
            <button onclick="openAssignModal()" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                Asignar Nueva Categoría
            </button>
            <a href="{{ route('usuarios.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
                Volver a Usuarios
            </a>
        </div>

        <!-- Tabla de usuarios con categorías -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Usuario</th>
                        <th scope="col" class="px-6 py-3">Categorías Asignadas</th>
                        <th scope="col" class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usuarios as $usuario)
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            {{ $usuario->nombre }} {{ $usuario->apellidos }}
                            <div class="text-sm text-gray-500">{{ $usuario->correo }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($usuario->miembros->count() > 0)
                                <div class="space-y-2">
                                    @foreach($usuario->miembros as $miembro)                                        <div class="flex items-center justify-between bg-blue-50 dark:bg-blue-900 p-2 rounded">
                                            <div>
                                                <span class="font-medium">{{ $miembro->grupo->nombre_grupo ?? 'Grupo ' . $miembro->id_grupo }}</span>
                                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                                    - {{ $miembro->categoriaDocente->nombre_categoria ?? 'Cat. ' . $miembro->id_categoria }}
                                                </span>
                                                @if($miembro->numero_orden)
                                                    <span class="text-xs bg-green-100 dark:bg-green-800 px-2 py-1 rounded ml-2">
                                                        Orden: {{ $miembro->numero_orden }}
                                                    </span>
                                                @endif
                                            </div>
                                            <button onclick="removeCategory({{ $usuario->id_usuario }}, {{ $miembro->id_grupo }}, {{ $miembro->id_categoria }})" 
                                                    class="text-red-600 hover:text-red-900 text-sm">
                                                Remover
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-500">Sin categorías asignadas</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <button onclick="openAssignModal({{ $usuario->id_usuario }})" 
                                    class="text-purple-600 hover:text-purple-900 font-medium">
                                Asignar Categoría
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                            No se encontraron usuarios
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para asignar categoría -->
    <div id="assignModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl max-w-md w-full mx-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Asignar Categoría</h3>
            
            <form id="assignForm">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Usuario</label>
                        <select id="modal_usuario" name="id_usuario" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="">Seleccionar usuario...</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id_usuario }}">{{ $usuario->nombre }} {{ $usuario->apellidos }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Grupo</label>
                        <select id="modal_grupo" name="id_grupo" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="">Seleccionar grupo...</option>
                            @foreach($grupos as $grupo)
                                <option value="{{ $grupo->id_grupo }}">{{ $grupo->nombre_grupo }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoría</label>
                        <select id="modal_categoria" name="id_categoria" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="">Seleccionar categoría...</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre_categoria ?? 'Categoría ' . $categoria->id_categoria }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Número de Orden</label>
                        <input type="number" id="modal_orden" name="numero_orden" min="1" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                               placeholder="Opcional - se asignará automáticamente si está vacío">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Web Personal</label>
                        <input type="url" id="modal_web" name="web" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                               placeholder="https://...">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" onclick="closeAssignModal()" 
                            class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                        Asignar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAssignModal(userId = null) {
            if (userId) {
                document.getElementById('modal_usuario').value = userId;
            }
            document.getElementById('assignModal').classList.remove('hidden');
            document.getElementById('assignModal').classList.add('flex');
        }

        function closeAssignModal() {
            document.getElementById('assignModal').classList.add('hidden');
            document.getElementById('assignModal').classList.remove('flex');
            document.getElementById('assignForm').reset();
        }

        function removeCategory(userId, groupId, categoryId) {
            if (confirm('¿Está seguro de que desea remover esta categoría del usuario?')) {
                fetch('{{ route("usuarios.remover-categoria") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        id_usuario: userId,
                        id_grupo: groupId,
                        id_categoria: categoryId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al remover la categoría');
                });
            }
        }

        document.getElementById('assignForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            fetch('{{ route("usuarios.asignar-categoria") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeAssignModal();
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al asignar la categoría');
            });
        });
    </script>
</x-app-layout>
