<x-app-layout>
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white underline decoration-indigo-500">
                Seleccionar Grupo para Gestión de Orden
            </h1>
            <a href="{{ route('usuarios.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver a Usuarios
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Seleccione el grupo y opcionalmente la categoría para gestionar el orden de selección docente.
            </p>

            <form method="GET" action="{{ route('usuarios.gestion-orden') }}" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="grupo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Grupo <span class="text-red-500">*</span>
                        </label>
                        <select name="grupo" id="grupo" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Seleccionar grupo...</option>
                            @foreach($grupos as $grupo)
                                <option value="{{ $grupo->id_grupo }}">
                                    {{ $grupo->nombre_grupo }}
                                    @if($grupo->siglas_grupo)
                                        ({{ $grupo->siglas_grupo }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Seleccione el grupo cuyos miembros desea ordenar
                        </p>
                    </div>

                    <div>
                        <label for="categoria" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Categoría (opcional)
                        </label>
                        <select name="categoria" id="categoria" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id_categoria }}">
                                    {{ $categoria->nombre_categoria ?? 'Categoría ' . $categoria->id_categoria }}
                                    @if($categoria->siglas_categoria)
                                        ({{ $categoria->siglas_categoria }})
                                    @endif
                                    @if($categoria->creditos_docencia)
                                        - {{ $categoria->creditos_docencia }} créditos
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Filtrar por categoría específica (opcional)
                        </p>
                    </div>
                </div>

                <!-- Preview de miembros (se cargará dinámicamente) -->
                <div id="preview-container" class="hidden">
                    <div class="border-t border-gray-200 dark:border-gray-600 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Vista previa de miembros</h3>
                        <div id="preview-content" class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <!-- Contenido se cargará dinámicamente -->
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="button" id="preview-btn" 
                            class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                            disabled>
                        Vista Previa
                    </button>
                    <button type="submit" id="manage-btn"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                            disabled>
                        Gestionar Orden
                    </button>
                </div>
            </form>
        </div>

        <!-- Información adicional -->
        <div class="mt-6 bg-blue-50 dark:bg-blue-900 rounded-lg p-4">
            <h3 class="text-lg font-medium text-blue-900 dark:text-blue-100 mb-2">
                Información sobre la gestión de orden
            </h3>
            <div class="text-sm text-blue-700 dark:text-blue-300 space-y-2">
                <p><strong>Propósito:</strong> Establecer el orden de selección para el proceso de asignación docente.</p>
                <p><strong>Orden automático:</strong> Se puede generar automáticamente basado en criterios predefinidos.</p>
                <p><strong>Orden manual:</strong> Permite reorganizar manualmente arrastrando y soltando los elementos.</p>
                <p><strong>Persistencia:</strong> Los cambios se guardan automáticamente en la base de datos.</p>
            </div>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $grupos->count() }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Grupos disponibles</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $categorias->count() }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Categorías docentes</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 text-center">
                @php
                    $totalMiembros = \App\Models\Miembro::count();
                @endphp
                <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $totalMiembros }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Total de miembros</div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const grupoSelect = document.getElementById('grupo');
            const categoriaSelect = document.getElementById('categoria');
            const previewBtn = document.getElementById('preview-btn');
            const manageBtn = document.getElementById('manage-btn');
            const previewContainer = document.getElementById('preview-container');
            const previewContent = document.getElementById('preview-content');

            // Habilitar botones cuando se selecciona un grupo
            function toggleButtons() {
                const grupoSelected = grupoSelect.value !== '';
                previewBtn.disabled = !grupoSelected;
                manageBtn.disabled = !grupoSelected;
            }

            grupoSelect.addEventListener('change', toggleButtons);
            categoriaSelect.addEventListener('change', toggleButtons);

            // Vista previa de miembros
            previewBtn.addEventListener('click', function() {
                const grupo = grupoSelect.value;
                const categoria = categoriaSelect.value;

                if (!grupo) {
                    alert('Por favor seleccione un grupo');
                    return;
                }

                // Mostrar loading
                previewContent.innerHTML = '<div class="text-center py-4"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div><p class="mt-2 text-gray-600 dark:text-gray-400">Cargando...</p></div>';
                previewContainer.classList.remove('hidden');

                // Hacer petición AJAX
                fetch(`{{ route('usuarios.miembros-ajax') }}?grupo=${grupo}&categoria=${categoria}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            let html = '';
                            if (data.miembros.length > 0) {
                                html = '<div class="space-y-2">';
                                data.miembros.forEach((miembro, index) => {
                                    html += `
                                        <div class="flex items-center justify-between bg-white dark:bg-gray-800 p-3 rounded border">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-800 rounded-full flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-bold text-sm">
                                                    ${miembro.numero_orden || index + 1}
                                                </div>
                                                <div>
                                                    <div class="font-medium text-gray-900 dark:text-white">${miembro.usuario.nombre} ${miembro.usuario.apellidos}</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">${miembro.categoria_docente.nombre_categoria}</div>
                                                </div>
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                ${miembro.categoria_docente.creditos_docencia || 0} créditos
                                            </div>
                                        </div>
                                    `;
                                });
                                html += '</div>';
                                html += `<div class="mt-4 text-sm text-gray-600 dark:text-gray-400">Total: ${data.miembros.length} miembro(s)</div>`;
                            } else {
                                html = '<div class="text-center py-8 text-gray-500 dark:text-gray-400">No se encontraron miembros con los criterios seleccionados</div>';
                            }
                            previewContent.innerHTML = html;
                        } else {
                            previewContent.innerHTML = '<div class="text-center py-4 text-red-600 dark:text-red-400">Error al cargar los miembros</div>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        previewContent.innerHTML = '<div class="text-center py-4 text-red-600 dark:text-red-400">Error al cargar los miembros</div>';
                    });
            });
        });
    </script>
</x-app-layout>
