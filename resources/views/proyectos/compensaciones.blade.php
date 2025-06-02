<!-- filepath: c:\xampp\htdocs\laravel\tfg_gestion_ccia\resources\views\proyectos\compensaciones.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Compensaciones del Proyecto</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $proyecto->codigo }} - {{ $proyecto->titulo }}</p>
                </div>
                <a href="{{ route('proyectos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded transition duration-300">
                    Volver a Proyectos
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Información del proyecto -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 dark:text-gray-300">Responsable:</strong>
                        <span class="text-gray-800 dark:text-gray-200">
                            {{ $proyecto->responsable ? $proyecto->responsable->nombre . ' ' . $proyecto->responsable->apellidos : 'Sin asignar' }}
                        </span>
                    </div>
                    <div>
                        <strong class="text-gray-700 dark:text-gray-300">Créditos totales del proyecto:</strong>
                        <span class="text-gray-800 dark:text-gray-200">
                            {{ $proyecto->creditos_compensacion_proyecto ?? 'No definido' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Miembros del proyecto con compensaciones -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Miembros del Proyecto</h2>
                
                @if($miembros->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Usuario</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Compensación Actual</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
                                @foreach($miembros as $miembro)
                                    <tr>
                                        <td class="px-6 py-4 text-gray-800 dark:text-gray-200">
                                            {{ $miembro->nombre }} {{ $miembro->apellidos }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-800 dark:text-gray-200">
                                            @php
                                                $compensacion = $miembro->compensacionesProyecto->first();
                                            @endphp
                                            {{ $compensacion ? $compensacion->creditos_compensacion . ' créditos' : 'Sin asignar' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <form action="{{ route('proyectos.asignarCompensacion', $proyecto->id_proyecto) }}" method="POST" class="flex items-center gap-2">
                                                @csrf
                                                <input type="hidden" name="id_usuario" value="{{ $miembro->id_usuario }}">
                                                <input type="number" name="creditos_compensacion" 
                                                       value="{{ $compensacion ? $compensacion->creditos_compensacion : '' }}"
                                                       min="0" max="100" step="0.01" placeholder="0.00"
                                                       class="w-24 px-2 py-1 border border-gray-300 dark:border-gray-600 rounded text-sm text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800">
                                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                                    {{ $compensacion ? 'Actualizar' : 'Asignar' }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">No hay miembros asignados a este proyecto.</p>
                @endif
            </div>

            <!-- Asignar compensación a usuarios no miembros -->
            @if($usuariosDisponibles->count() > 0)
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Asignar Compensación a Usuario Externo</h2>
                    
                    <form action="{{ route('proyectos.asignarCompensacion', $proyecto->id_proyecto) }}" method="POST" class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                        @csrf
                        <div class="flex flex-wrap items-end gap-4">
                            <div class="flex-grow min-w-[200px]">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Usuario</label>
                                <select name="id_usuario" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800">
                                    <option value="">Seleccionar usuario...</option>
                                    @foreach($usuariosDisponibles as $usuario)
                                        <option value="{{ $usuario->id_usuario }}">{{ $usuario->nombre }} {{ $usuario->apellidos }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="min-w-[120px]">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Créditos</label>
                                <input type="number" name="creditos_compensacion" min="0" max="100" step="0.01" placeholder="0.00" required
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800">
                            </div>
                            
                            <div>
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded transition duration-300">
                                    Asignar Compensación
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>