<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-indigo-500">
            Gestión del Orden de Selección Docente
        </h1>
        
        <!-- Mensaje de información -->
        <div class="mb-6 bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
            <h2 class="text-xl font-semibold text-blue-900 dark:text-blue-100">
                Lista Completa de Miembros
            </h2>
            <p class="text-blue-700 dark:text-blue-300">
                Gestión del orden de selección para todos los miembros del centro
            </p>
        </div>

        <!-- Controles -->
        <div class="mb-4 flex flex-wrap gap-2">
            <button onclick="saveOrder()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors">
                <span class="inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Guardar Cambios
                </span>
            </button>
            <button onclick="autoSort()" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded transition-colors">
                <span class="inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                    </svg>
                    Ordenar Automáticamente
                </span>
            </button>
            <button onclick="resetOrder()" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded transition-colors">
                <span class="inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Restablecer
                </span>
            </button>
            <a href="{{ route('usuarios.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition-colors">
                <span class="inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver a Usuarios
                </span>
            </a>
        </div>

        <!-- Lista de miembros -->
        @if($miembros->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Miembros del Centro ({{ $miembros->count() }})
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Arrastra y suelta para cambiar el orden, o edita los números directamente. Los cambios se resaltan automáticamente.
                </p>
            </div>
            
            <div id="sortable-members" class="p-4 space-y-2">
                @foreach($miembros as $index => $miembro)
                <div class="member-item flex items-center justify-between bg-gray-50 dark:bg-gray-700 p-4 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200 border border-gray-200 dark:border-gray-600"
                     data-usuario="{{ $miembro->id_usuario }}" 
                     data-original-orden="{{ $miembro->numero_orden }}">
                    <div class="flex items-center space-x-4 flex-1">
                        <div class="handle cursor-move text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors p-1">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 15.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path>
                            </svg>
                        </div>
                        <div class="order-number bg-blue-500 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold text-sm min-w-[2.5rem]">
                            {{ $miembro->numero_orden ?? $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-gray-900 dark:text-white truncate">
                                {{ $miembro->usuario->nombre }} {{ $miembro->usuario->apellidos }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 flex flex-wrap gap-2">
                                <span class="bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded-full text-xs">
                                    {{ $miembro->categoriaDocente->nombre_categoria ?? 'Sin categoría' }}
                                </span>
                                <span class="bg-green-200 dark:bg-green-700 text-green-800 dark:text-green-200 px-2 py-1 rounded-full text-xs">
                                    {{ $miembro->grupo->nombre_grupo ?? 'Sin grupo' }}
                                </span>
                                @if($miembro->tramos_investigacion)
                                    <span class="bg-purple-200 dark:bg-purple-700 text-purple-800 dark:text-purple-200 px-2 py-1 rounded-full text-xs">
                                        {{ $miembro->tramos_investigacion }} {{ $miembro->tramos_investigacion == 1 ? 'tramo' : 'tramos' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        @if($miembro->web)
                            <a href="{{ $miembro->web }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium transition-colors">
                                Web ↗
                            </a>
                        @endif
                        <input type="number" class="order-input w-16 px-2 py-1 border border-gray-300 dark:border-gray-500 rounded text-sm dark:bg-gray-600 dark:text-white text-center font-medium focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                               value="{{ $miembro->numero_orden ?? $index + 1 }}" min="1" max="{{ $miembros->count() }}"
                               onchange="validateOrderInput(this)">
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-8 text-center">
            <svg class="w-16 h-16 text-yellow-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <p class="text-yellow-800 dark:text-yellow-200 text-lg font-medium">
                No se encontraron miembros en la base de datos
            </p>
            <p class="text-yellow-600 dark:text-yellow-400 mt-2">
                Asegúrate de que existan miembros registrados en el sistema.
            </p>
        </div>
        @endif
    </div>    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center" style="display: none;">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl">
            <div class="flex items-center space-x-3">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                <span class="text-gray-900 dark:text-white font-medium">Guardando cambios...</span>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        let sortable;
        let hasChanges = false;
        
        document.addEventListener('DOMContentLoaded', function() {
            const sortableContainer = document.getElementById('sortable-members');
            
            if (sortableContainer) {
                sortable = Sortable.create(sortableContainer, {
                    handle: '.handle',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    onStart: function(evt) {
                        evt.item.style.opacity = '0.5';
                    },
                    onEnd: function(evt) {
                        evt.item.style.opacity = '1';
                        updateOrderNumbers();
                        markAsChanged();
                    }
                });
            }

            // Event listeners para inputs de orden
            document.querySelectorAll('.order-input').forEach(input => {
                input.addEventListener('input', function() {
                    validateOrderInput(this);
                });
            });

            // Advertir sobre cambios no guardados
            window.addEventListener('beforeunload', function(e) {
                if (hasChanges) {
                    e.preventDefault();
                    e.returnValue = '';
                }
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
                
                // Resaltar si hay cambios
                highlightChanges(item, newOrder);
            });
            
            checkForDuplicates();
        }

        function validateOrderInput(input) {
            const newOrder = parseInt(input.value);
            const memberItem = input.closest('.member-item');
            const container = document.getElementById('sortable-members');
            const items = Array.from(container.children);
            
            if (isNaN(newOrder) || newOrder < 1 || newOrder > items.length) {
                input.classList.add('border-red-500', 'bg-red-50', 'dark:bg-red-900');
                return;
            }
            
            input.classList.remove('border-red-500', 'bg-red-50', 'dark:bg-red-900');
            
            // Reordenar elementos
            reorderByInput(input, newOrder);
            markAsChanged();
        }

        function reorderByInput(input, newOrder) {
            const memberItem = input.closest('.member-item');
            const container = document.getElementById('sortable-members');
            const items = Array.from(container.children);
            
            // Remover el elemento actual
            memberItem.remove();
            
            // Insertar en la nueva posición
            if (newOrder <= 1) {
                container.insertBefore(memberItem, container.firstChild);
            } else if (newOrder >= items.length) {
                container.appendChild(memberItem);
            } else {
                const targetIndex = newOrder - 1;
                const remainingItems = Array.from(container.children);
                if (remainingItems[targetIndex]) {
                    container.insertBefore(memberItem, remainingItems[targetIndex]);
                } else {
                    container.appendChild(memberItem);
                }
            }
            
            updateOrderNumbers();
        }

        function highlightChanges(item, newOrder) {
            const originalOrder = parseInt(item.dataset.originalOrden);
            if (originalOrder !== newOrder) {
                item.classList.add('bg-yellow-100', 'dark:bg-yellow-800', 'border-yellow-300', 'dark:border-yellow-600');
                item.classList.remove('bg-gray-50', 'dark:bg-gray-700');
            } else {
                item.classList.remove('bg-yellow-100', 'dark:bg-yellow-800', 'border-yellow-300', 'dark:border-yellow-600');
                item.classList.add('bg-gray-50', 'dark:bg-gray-700');
            }
        }

        function checkForDuplicates() {
            const orders = [];
            const inputs = document.querySelectorAll('.order-input');
            
            inputs.forEach(input => {
                const value = parseInt(input.value);
                input.classList.remove('border-red-500', 'bg-red-100', 'dark:bg-red-900');
                
                if (orders.includes(value)) {
                    input.classList.add('border-red-500', 'bg-red-100', 'dark:bg-red-900');
                }
                orders.push(value);
            });
        }

        function autoSort() {
            if (confirm('¿Está seguro de que desea ordenar automáticamente por categoría docente y tramos de investigación?\n\nEsto reorganizará todos los miembros según criterios académicos.')) {
                const items = Array.from(document.querySelectorAll('.member-item'));
                const container = document.getElementById('sortable-members');
                
                items.sort((a, b) => {
                    // Obtener información de las etiquetas
                    const aCategory = a.querySelector('.bg-gray-200').textContent.trim().toLowerCase();
                    const bCategory = b.querySelector('.bg-gray-200').textContent.trim().toLowerCase();
                    
                    // Orden de prioridad para categorías (más específico)
                    const categoryOrder = {
                        'catedrático de universidad': 1,
                        'catedrático': 1,
                        'titular de universidad': 2,
                        'titular': 2,
                        'contratado doctor': 3,
                        'ayudante doctor': 4,
                        'profesor asociado': 5,
                        'asociado': 5
                    };
                    
                    const aPriority = categoryOrder[aCategory] || 999;
                    const bPriority = categoryOrder[bCategory] || 999;
                    
                    if (aPriority !== bPriority) {
                        return aPriority - bPriority;
                    }
                    
                    // Si son de la misma categoría, ordenar por tramos (descendente)
                    const tramosElements = a.querySelectorAll('.bg-purple-200, .bg-purple-700');
                    const aTramos = tramosElements.length > 0 ? 
                        parseInt(tramosElements[0].textContent.match(/(\d+)/)?.[1] || 0) : 0;
                    
                    const tramosElementsB = b.querySelectorAll('.bg-purple-200, .bg-purple-700');
                    const bTramos = tramosElementsB.length > 0 ? 
                        parseInt(tramosElementsB[0].textContent.match(/(\d+)/)?.[1] || 0) : 0;
                    
                    if (aTramos !== bTramos) {
                        return bTramos - aTramos; // Descendente (más tramos primero)
                    }
                    
                    // Si todo es igual, ordenar alfabéticamente por apellidos
                    const aName = a.querySelector('.font-medium').textContent.trim();
                    const bName = b.querySelector('.font-medium').textContent.trim();
                    return aName.localeCompare(bName);
                });
                
                // Reordenar en el DOM
                items.forEach(item => container.appendChild(item));
                updateOrderNumbers();
                markAsChanged();
            }
        }

        function resetOrder() {
            if (confirm('¿Está seguro de que desea restablecer el orden original?\n\nEsto deshará todos los cambios no guardados.')) {
                location.reload();
            }
        }

        function markAsChanged() {
            hasChanges = true;
        }

        function saveOrder() {
            const items = document.querySelectorAll('.member-item');
            const miembros = [];
            
            // Verificar duplicados antes de guardar
            const orders = [];
            let hasDuplicates = false;
            
            items.forEach((item, index) => {
                const orderValue = parseInt(item.querySelector('.order-input').value);
                if (orders.includes(orderValue)) {
                    hasDuplicates = true;
                }
                orders.push(orderValue);
                
                miembros.push({
                    id_usuario: parseInt(item.dataset.usuario),
                    numero_orden: orderValue
                });
            });
            
            if (hasDuplicates) {
                alert('Error: Existen números de orden duplicados. Por favor, corrígelos antes de guardar.');
                return;
            }
              // Mostrar loading
            const overlay = document.getElementById('loading-overlay');
            overlay.style.display = 'flex';
            
            fetch('{{ route("usuarios.actualizar-orden") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ miembros: miembros })
            })
            .then(response => response.json())            .then(data => {
                document.getElementById('loading-overlay').style.display = 'none';
                
                if (data.success) {
                    hasChanges = false;
                    
                    // Actualizar los datos originales
                    items.forEach((item, index) => {
                        item.dataset.originalOrden = index + 1;
                        item.classList.remove('bg-yellow-100', 'dark:bg-yellow-800', 'border-yellow-300', 'dark:border-yellow-600');
                        item.classList.add('bg-gray-50', 'dark:bg-gray-700');
                    });
                    
                    // Mostrar mensaje de éxito
                    const successDiv = document.createElement('div');
                    successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white p-4 rounded-lg shadow-lg z-50 transform transition-all duration-300';
                    successDiv.innerHTML = `
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Orden guardado exitosamente</span>
                        </div>
                    `;
                    document.body.appendChild(successDiv);
                    
                    setTimeout(() => {
                        successDiv.style.transform = 'translateX(100%)';
                        setTimeout(() => successDiv.remove(), 300);
                    }, 3000);
                } else {
                    alert('Error: ' + (data.message || 'No se pudo guardar el orden'));
                }
            })            .catch(error => {
                document.getElementById('loading-overlay').style.display = 'none';
                console.error('Error:', error);
                alert('Error de conexión. Verifica tu conexión a internet e inténtalo de nuevo.');
            });
        }
    </script>

    <style>
        .sortable-ghost {
            opacity: 0.4;
            background: #f3f4f6;
        }
        
        .member-item:hover .handle {
            color: #6b7280;
        }
        
        @media (max-width: 640px) {
            .member-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .member-item .flex:first-child {
                width: 100%;
            }
            
            .order-input {
                width: 4rem;
            }
        }
    </style>
</x-app-layout>
