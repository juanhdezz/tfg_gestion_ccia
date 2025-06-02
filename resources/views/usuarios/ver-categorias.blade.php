<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white underline decoration-blue-500">
            Categorías de {{ $usuario->nombre }} {{ $usuario->apellidos }}
        </h1>
        
        <div class="mb-6 bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">Información del Usuario</h3>
                    <p><strong>Email:</strong> {{ $usuario->correo }}</p>
                    <p><strong>DNI/Pasaporte:</strong> {{ $usuario->dni_pasaporte }}</p>
                    <p><strong>Tipo:</strong> {{ $usuario->tipo_usuario }}</p>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">Estadísticas</h3>
                    <p><strong>Total de membresías:</strong> {{ $usuario->miembros->count() }}</p>
                    <p><strong>Grupos distintos:</strong> {{ $usuario->miembros->pluck('id_grupo')->unique()->count() }}</p>
                    <p><strong>Categorías distintas:</strong> {{ $usuario->miembros->pluck('id_categoria')->unique()->count() }}</p>
                </div>
            </div>
        </div>

        @if($usuario->miembros->count() > 0)
        <!-- Membresías por grupo -->
        @foreach($usuario->miembros->groupBy('id_grupo') as $grupoId => $miembrosGrupo)
            @php $grupo = $miembrosGrupo->first()->grupo; @endphp
            <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-t-lg border-b border-blue-200 dark:border-blue-700">
                    <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100">
                        {{ $grupo->nombre_grupo }}
                        @if($grupo->siglas_grupo)
                            ({{ $grupo->siglas_grupo }})
                        @endif
                    </h3>
                    <p class="text-sm text-blue-700 dark:text-blue-300">
                        {{ $miembrosGrupo->count() }} categoría(s) en este grupo
                    </p>
                </div>
                
                <div class="p-4">
                    <div class="grid gap-3">
                        @foreach($miembrosGrupo->sortBy('numero_orden') as $miembro)
                        <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 p-3 rounded">
                            <div class="flex items-center space-x-3">
                                @if($miembro->numero_orden)
                                <div class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold text-sm">
                                    {{ $miembro->numero_orden }}
                                </div>
                                @endif
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        {{ $miembro->categoriaDocente->nombre_categoria ?? 'Categoría ' . $miembro->id_categoria }}
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400 space-x-4">
                                        @if($miembro->tramos_investigacion)
                                            <span>🔬 {{ $miembro->tramos_investigacion }} tramos de investigación</span>
                                        @endif
                                        @if($miembro->anio_ultimo_tramo)
                                            <span>📅 Último tramo: {{ $miembro->anio_ultimo_tramo }}</span>
                                        @endif
                                        @if($miembro->fecha_entrada)
                                            <span>📍 Entrada: {{ $miembro->fecha_entrada_formateada }}</span>
                                        @endif
                                        @if($miembro->n_orden_becario)
                                            <span>🎓 Orden becario: {{ $miembro->n_orden_becario }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($miembro->web)
                                    <a href="{{ $miembro->web }}" target="_blank" 
                                       class="bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200 px-2 py-1 rounded text-xs hover:bg-green-200 dark:hover:bg-green-700">
                                        Ver Web
                                    </a>
                                @endif
                                <span class="text-xs text-gray-500">
                                    ID: {{ $miembro->id_categoria }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Resumen de orden de selección -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                Resumen de Orden de Selección Docente
            </h3>
            <div class="space-y-2">
                @foreach($usuario->miembros->whereNotNull('numero_orden')->sortBy('numero_orden') as $miembro)
                <div class="flex items-center justify-between text-sm">                    <span>
                        <strong>Posición {{ $miembro->numero_orden }}:</strong> 
                        {{ $miembro->grupo ? ($miembro->grupo->siglas_grupo ?? $miembro->grupo->nombre_grupo) : 'Grupo ' . $miembro->id_grupo }} - 
                        {{ $miembro->categoriaDocente->nombre_categoria ?? 'Cat. ' . $miembro->id_categoria }}
                    </span>
                    <span class="text-gray-600 dark:text-gray-400">
                        @if($miembro->tramos_investigacion)
                            {{ $miembro->tramos_investigacion }} tramos
                        @endif
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        @else
        <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-6 text-center">
            <p class="text-yellow-800 dark:text-yellow-200 text-lg">
                Este usuario no tiene categorías asignadas en ningún grupo
            </p>
            <a href="{{ route('usuarios.gestion-categorias') }}" 
               class="mt-4 inline-block bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                Asignar Categorías
            </a>
        </div>
        @endif

        <div class="mt-6 flex space-x-2">
            <a href="{{ route('usuarios.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver a Usuarios
            </a>
            <a href="{{ route('usuarios.gestion-categorias') }}" 
               class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                Gestionar Categorías
            </a>
            @if($usuario->miembros->count() > 0)
            <a href="{{ route('usuarios.gestion-orden') }}?grupo={{ $usuario->miembros->first()->id_grupo }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Gestionar Orden
            </a>
            @endif
        </div>
    </div>
</x-app-layout>
