<!-- filepath: /resources/views/ordenacion/partials/reducciones.blade.php -->
<div class="p-2">
    @if($creditos_compensacion > 0)
        <div class="bg-blue-100 dark:bg-blue-900 border-l-4 border-blue-500 text-blue-700 dark:text-blue-100 p-4 mb-4">
            <p>Total de créditos de compensación: <strong>{{ $creditos_compensacion }}</strong></p>
        </div>
        
        <!-- Botón para mostrar/ocultar detalles -->
        <div class="mb-4">
            <button type="button" 
                    onclick="toggleDetalleCompensaciones()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <span id="btn-texto">Mostrar detalle de compensaciones</span>
                <span id="btn-icon">▼</span>
            </button>
        </div>
        
        <!-- Detalle de compensaciones (inicialmente oculto) -->
        <div id="detalle-compensaciones" style="display: none;">
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border">
                <h4 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Detalle de Compensaciones Docentes</h4>
                
                <!-- Las tablas HTML generadas por MuestraReducciones se insertarán aquí via AJAX -->
                <div id="compensaciones-content">
                    <div class="flex justify-center items-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <span class="ml-2 text-gray-600 dark:text-gray-400">Cargando detalles...</span>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
        let detallesCargados = false;
        
        function toggleDetalleCompensaciones() {
            const detalle = document.getElementById('detalle-compensaciones');
            const btnTexto = document.getElementById('btn-texto');
            const btnIcon = document.getElementById('btn-icon');
            const content = document.getElementById('compensaciones-content');
            
            if (detalle.style.display === 'none') {
                detalle.style.display = 'block';
                btnTexto.textContent = 'Ocultar detalle de compensaciones';
                btnIcon.textContent = '▲';
                
                // Cargar detalles solo la primera vez
                if (!detallesCargados) {                fetch('{{ route("ordenacion.detalle-compensaciones") }}')
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.tablas_html) {
                                content.innerHTML = data.tablas_html;
                            } else {
                                content.innerHTML = '<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4">No se encontraron detalles de compensaciones docentes.</div>';
                            }
                            detallesCargados = true;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            content.innerHTML = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4">Error al cargar los detalles de compensaciones.</div>';
                        });
                }
            } else {
                detalle.style.display = 'none';
                btnTexto.textContent = 'Mostrar detalle de compensaciones';
                btnIcon.textContent = '▼';
            }
        }
        </script>
    @else
        <div class="bg-yellow-100 dark:bg-yellow-900 border-l-4 border-yellow-500 text-yellow-700 dark:text-yellow-100 p-4">
            Actualmente no cuenta con ningún tipo de compensación docente.
        </div>
    @endif
</div>