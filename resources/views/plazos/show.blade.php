<!-- filepath: c:\xampp\htdocs\laravel\tfg_gestion_ccia\resources\views\plazos\show.blade.php -->
<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <!-- Navegación y título -->
        <div class="mb-6">
            

            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-center">
                    <div class="mr-4">
                        @php
                            $iconClass = "h-12 w-12 p-2 rounded-lg";
                            if($plazo->estaActivo()) {
                                $iconClass .= " bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300";
                                $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />';
                            } elseif($plazo->haTerminado()) {
                                $iconClass .= " bg-gray-100 text-gray-600 dark:bg-gray-900 dark:text-gray-300";
                                $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />';
                            } else {
                                $iconClass .= " bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300";
                                $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />';
                            }
                        @endphp
                        <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            {!! $icon !!}
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ $plazo->nombre_plazo }}
                        </h1>
                        <div class="mt-1 flex flex-wrap items-center gap-2">
                            @if($plazo->estaActivo())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                    Activo
                                </span>
                            @elseif($plazo->haTerminado())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300">
                                    Finalizado
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                    Pendiente
                                </span>
                            @endif
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                ID: {{ $plazo->id_plazo }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
                    <a href="{{ route('plazos.edit', $plazo->id_plazo) }}" class="btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar
                    </a>
                    <form action="{{ route('plazos.destroy', $plazo->id_plazo) }}" method="POST" class="inline delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Contenido -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Información principal -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                            Información del Plazo
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Detalles generales -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nombre</h3>
                                <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $plazo->nombre_plazo }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Estado</h3>
                                <p class="mt-1 text-base text-gray-900 dark:text-white">
                                    @if($plazo->estaActivo())
                                        <span class="text-green-600 dark:text-green-400">Activo</span>
                                    @elseif($plazo->haTerminado())
                                        <span class="text-gray-600 dark:text-gray-400">Finalizado</span>
                                    @else
                                        <span class="text-yellow-600 dark:text-yellow-400">Pendiente</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha de inicio</h3>
                                <p class="mt-1 text-base text-gray-900 dark:text-white">
                                    {{ $plazo->fecha_inicio->format('d/m/Y') }}
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        ({{ $plazo->fecha_inicio->diffForHumans() }})
                                    </span>
                                </p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha de finalización</h3>
                                <p class="mt-1 text-base text-gray-900 dark:text-white">
                                    {{ $plazo->fecha_fin->format('d/m/Y') }}
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        ({{ $plazo->fecha_fin->diffForHumans() }})
                                    </span>
                                </p>
                            </div>
                            <div class="md:col-span-2">
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Descripción</h3>
                                <div class="mt-1 text-base text-gray-900 dark:text-white prose dark:prose-invert max-w-none">
                                    @if($plazo->descripcion)
                                        <p>{!! nl2br(e($plazo->descripcion)) !!}</p>
                                    @else
                                        <p class="text-gray-500 dark:text-gray-400 italic">Sin descripción</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Duración -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Duración</h3>
                            <div class="mt-1">
                                <div class="text-base text-gray-900 dark:text-white">
                                    <span class="font-medium">{{ $plazo->fecha_inicio->diffInDays($plazo->fecha_fin) + 1 }} días</span>
                                    ({{ $plazo->fecha_inicio->diffForHumans($plazo->fecha_fin, ['parts' => 2]) }})
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barra lateral -->
            <div>
                <!-- Estado y progreso -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                        Progreso
                    </h2>
                    
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
                    
                    <div class="mb-4">
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4">
                            <div class="{{ $colorBarra }} h-4 rounded-full transition-all duration-500" style="width: {{ $porcentaje }}%"></div>
                        </div>
                        <div class="mt-2 flex justify-between text-sm text-gray-600 dark:text-gray-400">
                            <div>{{ $plazo->fecha_inicio->format('d/m/Y') }}</div>
                            <div class="font-medium">{{ $porcentaje }}%</div>
                            <div>{{ $plazo->fecha_fin->format('d/m/Y') }}</div>
                        </div>
                    </div>

                    <!-- Mensaje según estado -->
                    <div class="mt-6 p-4 rounded-md 
                        @if($plazo->estaActivo())
                            bg-green-50 dark:bg-green-900/20 text-green-800 dark:text-green-300
                        @elseif($plazo->haTerminado())
                            bg-gray-50 dark:bg-gray-900/20 text-gray-800 dark:text-gray-300
                        @else
                            bg-yellow-50 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-300
                        @endif
                    ">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                @if($plazo->estaActivo())
                                    <svg class="h-5 w-5 text-green-400 dark:text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                @elseif($plazo->haTerminado())
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <svg class="h-5 w-5 text-yellow-400 dark:text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium 
                                    @if($plazo->estaActivo())
                                        text-green-800 dark:text-green-300
                                    @elseif($plazo->haTerminado())
                                        text-gray-800 dark:text-gray-300
                                    @else
                                        text-yellow-800 dark:text-yellow-300
                                    @endif
                                ">
                                    @if($plazo->estaActivo())
                                        Plazo en curso
                                    @elseif($plazo->haTerminado())
                                        Plazo finalizado
                                    @else
                                        Plazo pendiente
                                    @endif
                                </h3>
                                <div class="mt-2 text-sm 
                                    @if($plazo->estaActivo())
                                        text-green-700 dark:text-green-400
                                    @elseif($plazo->haTerminado())
                                        text-gray-700 dark:text-gray-400
                                    @else
                                        text-yellow-700 dark:text-yellow-400
                                    @endif
                                ">
                                    @if($plazo->estaActivo())
                                        @if($plazo->diasRestantes() > 0)
                                            Quedan <strong>{{ $plazo->diasRestantes() }} días</strong> para que finalice este plazo.
                                        @else
                                            <strong>Último día</strong> de este plazo.
                                        @endif
                                    @elseif($plazo->haTerminado())
                                        Este plazo finalizó hace <strong>{{ now()->diffInDays($plazo->fecha_fin) }} días</strong>.
                                    @else
                                        Este plazo comenzará dentro de <strong>{{ now()->diffInDays($plazo->fecha_inicio) }} días</strong>.
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información adicional -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                        Información adicional
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Fecha de inicio
                            </div>
                            <div class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ $plazo->fecha_inicio->isoFormat('LL') }}
                            </div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Fecha de fin
                            </div>
                            <div class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ $plazo->fecha_fin->isoFormat('LL') }}
                            </div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                ID del plazo
                            </div>
                            <div class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ $plazo->id_plazo }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                        <a href="{{ route('plazos.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Volver a la lista de plazos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estilos específicos -->
    <style>
        .btn-primary {
            @apply inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition;
        }
        
        .btn-secondary {
            @apply inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition;
        }
        
        .btn-danger {
            @apply inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300 disabled:opacity-25 transition;
        }
    </style>
    
    <!-- Scripts para SweetAlert -->
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // SweetAlert para confirmación de eliminación
            const deleteForm = document.querySelector('.delete-form');
            if (deleteForm) {
                deleteForm.addEventListener('submit', function(event) {
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
                            deleteForm.submit();
                        }
                    });
                });
            }
        });
    </script>
    @endpush
</x-app-layout>