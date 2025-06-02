<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-indigo-500">
            Gestión del Orden de Selección Docente
        </h1>
        
        <!-- Selector de Grupo -->
        <form method="GET" action="{{ route('usuarios.gestion-orden') }}" class="mb-6 bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Grupo *</label>
                    <select name="grupo" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                        <option value="">Seleccionar grupo...</option>
                        @foreach($grupos as $grupo)
                            <option value="{{ $grupo->id_grupo }}" {{ request('grupo') == $grupo->id_grupo ? 'selected' : '' }}>
                                {{ $grupo->nombre_grupo }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoría (opcional)</label>
                    <select name="categoria" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id_categoria }}" {{ request('categoria') == $categoria->id_categoria ? 'selected' : '' }}>
                                {{ $categoria->nombre_categoria ?? 'Categoría ' . $categoria->id_categoria }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Mostrar Miembros
                    </button>
                </div>
            </div>
        </form>

        @if(request('grupo'))
        <!-- Información del grupo seleccionado -->
        <div class="mb-6 bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
            <h2 class="text-xl font-semibold text-blue-900 dark:text-blue-100">
                {{ $grupo->nombre_grupo }}
            </h2>
            <p class="text-blue-700 dark:text-blue-300">
                Gestión del orden de selección para el proceso de ordenación docente
            </p>
        </div>

        <!-- Controles -->
        <div class="mb-4 flex space-x-2">
            <button onclick="saveOrder()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Guardar Orden
            </button>
            <button onclick="autoSort()" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                Ordenar Automáticamente
            </button>
            <a href="{{ route('usuarios.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver a Usuarios
            </a>
        </div>

        <!-- Lista de miembros ordenable -->
        @if($miembros->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Miembros del Grupo 
                    @if(request('categoria'))
                        - Categoría: {{ $categorias->find(request('categoria'))->nombre_categoria ?? 'Categoría ' . request('categoria') }}
                    @endif
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Arrastra y suelta para cambiar el orden de selección
                </p>
            </div>
            
            <div id="sortable-members" class="p-4">
                @foreach($miembros as $index => $miembro)
                <div class="member-item flex items-center justify-between bg-gray-50 dark:bg-gray-700 p-4 mb-2 rounded-lg cursor-move hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                     data-usuario="{{ $miembro->id_usuario }}" 
                     data-grupo="{{ $miembro->id_grupo }}" 
                     data-categoria="{{ $miembro->id_categoria }}"
                     data-orden="{{ $miembro->numero_orden }}">
                    <div class="flex items-center space-x-4">
                        <div class="handle cursor-move text-gray-400 hover:text-gray-600">
                            &#8801;&#8801;
                        </div>
                        <div class="order-number bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold text-sm">
                            {{ $miembro->numero_orden ?? $index + 1 }}
                        </div>
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ $miembro->usuario->nombre }} {{ $miembro->usuario->apellidos }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $miembro->categoriaDocente->nombre_categoria ?? 'Categoría ' . $miembro->id_categoria }}
                                @if($miembro->tramos_investigacion)
                                    - {{ $miembro->tramos_investigacion }} tramos
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if($miembro->web)
                            <a href="{{ $miembro->web }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                Web
                            </a>
                        @endif
                        <input type="number" class="order-input w-16 px-2 py-1 border border-gray-300 rounded text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-white" 
                               value="{{ $miembro->numero_orden ?? $index + 1 }}" min="1" max="{{ $miembros->count() }}">
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
            <p class="text-yellow-800 dark:text-yellow-200">
                No se encontraron miembros para el grupo seleccionado
                @if(request('categoria'))
                    y la categoría especificada
                @endif
                .
            </p>
        </div>
        @endif
        
        @else
        <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-8 text-center">
            <p class="text-gray-600 dark:text-gray-400 text-lg">
                Selecciona un grupo para gestionar el orden de selección docente
            </p>
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        let sortable;
        
        document.addEventListener('DOMContentLoaded', function() {
            const sortableContainer = document.getElementById('sortable-members');
            
            if (sortableContainer) {
                sortable = Sortable.create(sortableContainer, {
                    handle: '.handle',
                    animation: 150,
                    onEnd: function(evt) {
                        updateOrderNumbers();
                    }
                });
            }

            // Event listeners para inputs de orden
            document.querySelectorAll('.order-input').forEach(input => {
                input.addEventListener('change', function() {
                    reorderByInput(this);
                });
            });
        });

        function updateOrderNumbers() {
            const items = document.querySelectorAll('.member-item');
            items.forEach((item, index) => {
                const orderNumber = item.querySelector('.order-number');
                const orderInput = item.querySelector('.order-input');
                const newOrder = index + 1;
                
                orderNumber.textContent = newOrder;
                orderInput.value = newOrder;
                item.dataset.orden = newOrder;
            });
        }

        function reorderByInput(input) {
            const newOrder = parseInt(input.value);
            const memberItem = input.closest('.member-item');
            const container = document.getElementById('sortable-members');
            const items = Array.from(container.children);
            
            if (newOrder < 1 || newOrder > items.length) {
                alert('El orden debe estar entre 1 y ' + items.length);
                input.value = memberItem.dataset.orden;
                return;
            }
            
            // Remover el elemento actual
            memberItem.remove();
            
            // Insertar en la nueva posición
            if (newOrder <= 1) {
                container.insertBefore(memberItem, container.firstChild);
            } else if (newOrder >= items.length) {
                container.appendChild(memberItem);
            } else {
                const referenceNode = container.children[newOrder - 1];
                container.insertBefore(memberItem, referenceNode);
            }
            
            updateOrderNumbers();
        }

        function autoSort() {
            if (confirm('¿Está seguro de que desea ordenar automáticamente por categoría y tramos de investigación?')) {
                const items = Array.from(document.querySelectorAll('.member-item'));
                const container = document.getElementById('sortable-members');
                
                // Ordenar por categoría y luego por tramos de investigación
                items.sort((a, b) => {
                    const aCategory = a.dataset.categoria;
                    const bCategory = b.dataset.categoria;
                    
                    if (aCategory !== bCategory) {
                        return aCategory - bCategory;
                    }
                    
                    // Si son de la misma categoría, ordenar por tramos (descendente)
                    const aTramos = parseInt(a.querySelector('.text-sm').textContent.match(/(\d+) tramos/)?.[1] || 0);
                    const bTramos = parseInt(b.querySelector('.text-sm').textContent.match(/(\d+) tramos/)?.[1] || 0);
                    
                    return bTramos - aTramos;
                });
                
                // Reordenar en el DOM
                items.forEach(item => container.appendChild(item));
                updateOrderNumbers();
            }
        }

        function saveOrder() {
            const items = document.querySelectorAll('.member-item');
            const miembros = [];
            
            items.forEach((item, index) => {
                miembros.push({
                    id_usuario: parseInt(item.dataset.usuario),
                    id_grupo: parseInt(item.dataset.grupo),
                    id_categoria: parseInt(item.dataset.categoria),
                    numero_orden: index + 1
                });
            });
            
            fetch('{{ route("usuarios.actualizar-orden") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ miembros: miembros })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Orden guardado exitosamente');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al guardar el orden');
            });
        }
    </script>
</x-app-layout>
