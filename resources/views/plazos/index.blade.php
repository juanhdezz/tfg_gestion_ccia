<!-- filepath: c:\xampp\htdocs\laravel\tfg_gestion_ccia\resources\views\plazos\index.blade.php -->
<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Gestión de Plazos
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Administra los plazos y períodos del sistema
                </p>
            </div>
            <div class="mt-4 lg:mt-0">
                <a href="{{ route('plazos.create') }}" class="btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Crear Nuevo Plazo
                </a>
            </div>
        </div>

        <!-- Mensajes de alertas -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- Filtros y búsqueda -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 mb-6">
            <form action="{{ route('plazos.index') }}" method="GET" class="flex flex-col md:flex-row md:items-center md:space-x-4">
                <div class="flex-grow mb-4 md:mb-0">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                    <div class="relative">
                        <input type="text" name="search" id="search" value="{{ $busqueda ?? '' }}" 
                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                            placeholder="Buscar por nombre o descripción...">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="w-full md:w-48 mb-4 md:mb-0">
                    <label for="filtro" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                    <select name="filtro" id="filtro" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="todos" {{ ($filtro ?? '') == 'todos' ? 'selected' : '' }}>Todos</option>
                        <option value="activos" {{ ($filtro ?? '') == 'activos' ? 'selected' : '' }}>Activos</option>
                        <option value="pendientes" {{ ($filtro ?? '') == 'pendientes' ? 'selected' : '' }}>Pendientes</option>
                        <option value="finalizados" {{ ($filtro ?? '') == 'finalizados' ? 'selected' : '' }}>Finalizados</option>
                    </select>
                </div>

                <div class="flex items-end space-x-2">
                    <button type="submit" class="btn-secondary">
                        Filtrar
                    </button>
                    <a href="{{ route('plazos.index') }}" class="btn-subtle">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        <!-- Lista de Plazos -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            @if ($plazos->isEmpty())
                <div class="p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">No se encontraron plazos</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $busqueda ? 'No hay plazos que coincidan con tu búsqueda.' : 'No hay plazos registrados en el sistema.' }}
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('plazos.create') }}" class="btn-primary">
                            Crear nuevo plazo
                        </a>
                    </div>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Plazo
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Período
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Estado
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Progreso
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($plazos as $plazo)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $plazo->nombre_plazo }}
                                            </div>
                                            @if($plazo->descripcion)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 max-w-md truncate">
                                                    {{ $plazo->descripcion }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $plazo->fecha_inicio->format('d/m/Y') }} - {{ $plazo->fecha_fin->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        @if($plazo->estaActivo())
                                            @if($plazo->diasRestantes() > 0)
                                                Quedan {{ $plazo->diasRestantes() }} días
                                            @else
                                                Último día
                                            @endif
                                        @elseif($plazo->haTerminado())
                                            Terminó hace {{ now()->diffInDays($plazo->fecha_fin) }} días
                                        @else
                                            Inicia en {{ now()->diffInDays($plazo->fecha_inicio) }} días
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($plazo->estaActivo())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                            Activo
                                        </span>
                                    @elseif($plazo->haTerminado())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            Finalizado
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                            Pendiente
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                        @php
                                            $porcentaje = $plazo->porcentajeTranscurrido();
                                            $colorBarra = 'bg-blue-600';
                                            
                                            if($plazo->estaActivo()) {
                                                if($porcentaje <= 30) $colorBarra = 'bg-green-600';
                                                elseif($porcentaje <= 75) $colorBarra = 'bg-yellow-600';
                                                else $colorBarra = 'bg-red-600';
                                            } elseif($plazo->haTerminado()) {
                                                $colorBarra = 'bg-gray-600';
                                            } else {
                                                $colorBarra = 'bg-purple-600';
                                                $porcentaje = 0;
                                            }
                                        @endphp
                                        
                                        <div class="{{ $colorBarra }} h-2.5 rounded-full" style="width: {{ $porcentaje }}%"></div>
                                    </div>
                                    <div class="text-xs mt-1 text-gray-500 dark:text-gray-400 text-right">
                                        {{ $porcentaje }}%
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('plazos.show', $plazo->id_plazo) }}" 
                                           class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                           title="Ver detalles">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('plazos.edit', $plazo->id_plazo) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                           title="Editar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('plazos.destroy', $plazo->id_plazo) }}" method="POST" class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" 
                                                    title="Eliminar">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Estilos específicos -->
    <style>
        .btn-primary {
            @apply inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition;
        }
        
        .btn-secondary {
            @apply inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-800 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition;
        }
        
        .btn-subtle {
            @apply inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring focus:ring-gray-300 dark:focus:ring-gray-600 disabled:opacity-25 transition;
        }
    </style>

    <!-- Scripts para SweetAlert -->
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // SweetAlert para confirmación de eliminación
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Esta acción no se puede revertir",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Cambio automático al seleccionar filtro
            document.getElementById('filtro').addEventListener('change', function() {
                this.closest('form').submit();
            });
            
            // Mostrar notificaciones SweetAlert
            @if(session('swal'))
                Swal.fire({
                    icon: "{{ session('swal.icon') }}",
                    title: "{{ session('swal.title') }}",
                    text: "{{ session('swal.text') }}",
                    timer: 3000
                });
            @endif
        });
    </script>
    @endpush
</x-app-layout>



