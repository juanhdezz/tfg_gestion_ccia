{{-- filepath: c:\xampp\htdocs\laravel\tfg_gestion_ccia\resources\views\usuarios\show.blade.php --}}
<x-app-layout>
    <div class="container mx-auto p-4 space-y-6">
        <!-- Encabezado con foto de perfil -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-700 rounded-lg shadow-lg p-8 text-white">
            <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-6">
                <!-- Foto de perfil -->
                <div class="flex-shrink-0">
                    @if ($usuario->foto)
                        <img src="{{ $usuario->foto }}" alt="Foto de {{ $usuario->nombre }}" 
                             class="w-32 h-32 rounded-full border-4 border-white shadow-lg object-cover">
                    @else
                        <div class="w-32 h-32 rounded-full border-4 border-white shadow-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                            <span class="text-white text-3xl font-bold">
                                {{ strtoupper(substr($usuario->nombre, 0, 1)) }}{{ strtoupper(substr($usuario->apellidos, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                </div>
                
                <!-- Información básica -->
                <div class="flex-grow text-center md:text-left">
                    <h1 class="text-3xl font-bold mb-2">{{ $usuario->nombre }} {{ $usuario->apellidos }}</h1>
                    <p class="text-xl font-medium opacity-90 mb-2">{{ $usuario->nombre_abreviado }}</p>
                    <div class="flex flex-wrap justify-center md:justify-start gap-2 mb-4">
                        <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm font-medium">
                            {{ $usuario->tipo_usuario }}
                        </span>
                        @if($usuario->imparte_docencia == 'Si')
                            <span class="px-3 py-1 bg-green-500 bg-opacity-80 rounded-full text-sm font-medium">
                                <i class="fas fa-chalkboard-teacher mr-1"></i>Imparte Docencia
                            </span>
                        @endif
                        @if($usuario->miembro_actual == 'Si')
                            <span class="px-3 py-1 bg-blue-500 bg-opacity-80 rounded-full text-sm font-medium">
                                <i class="fas fa-users mr-1"></i>Miembro Actual
                            </span>
                        @endif
                        @if($usuario->miembro_consejo == 'Si')
                            <span class="px-3 py-1 bg-purple-500 bg-opacity-80 rounded-full text-sm font-medium">
                                <i class="fas fa-crown mr-1"></i>Miembro Consejo
                            </span>
                        @endif
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center space-y-2 md:space-y-0 md:space-x-4 text-sm opacity-90">
                        <div class="flex items-center justify-center md:justify-start">
                            <i class="fas fa-envelope mr-2"></i>
                            <a href="mailto:{{ $usuario->correo }}" class="hover:underline">{{ $usuario->correo }}</a>
                        </div>
                        @if($usuario->telefono)
                        <div class="flex items-center justify-center md:justify-start">
                            <i class="fas fa-phone mr-2"></i>
                            <span>{{ $usuario->telefono }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex-shrink-0 space-y-2">
                    <a href="{{ route('usuarios.edit', $usuario->id_usuario) }}" 
                       class="block w-full bg-white text-blue-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition duration-300 text-center">
                        <i class="fas fa-edit mr-2"></i>Editar Usuario
                    </a>
                    <a href="{{ route('usuarios.index') }}" 
                       class="block w-full bg-white bg-opacity-20 text-white px-4 py-2 rounded-lg font-medium hover:bg-opacity-30 transition duration-300 text-center">
                        <i class="fas fa-arrow-left mr-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>

        @if (session('error'))
            <div class="bg-red-500 text-white p-4 rounded-lg shadow">{{ session('error') }}</div>
        @endif

        <!-- Información Académica y de Membresía -->
        @if($usuario->miembros->isNotEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-6">
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-full p-3 mr-4">
                    <i class="fas fa-graduation-cap text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Información Académica</h2>
                    <p class="text-gray-600 dark:text-gray-400">Categorías docentes y grupos de investigación</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($usuario->miembros as $miembro)
                <div class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-700 dark:to-gray-800 rounded-lg p-6 border border-blue-200 dark:border-gray-600">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <div class="bg-blue-500 rounded-full p-2 mr-3">
                                <i class="fas fa-user-graduate text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Membresía Académica</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Registro activo</p>
                            </div>
                        </div>
                        <span class="bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 px-2 py-1 rounded-full text-xs font-medium">
                            #{{ $miembro->numero_orden }}
                        </span>
                    </div>

                    <div class="space-y-3">
                        <!-- Categoría Docente -->
                        <div class="flex items-center p-3 bg-white dark:bg-gray-700 rounded-lg">
                            <div class="bg-purple-100 dark:bg-purple-800 rounded-lg p-2 mr-3">
                                <i class="fas fa-chalkboard text-purple-600 dark:text-purple-300"></i>
                            </div>
                            <div class="flex-grow">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Categoría Docente</p>
                                <p class="text-gray-900 dark:text-white font-semibold">
                                    {{ $miembro->categoriaDocente->nombre_categoria ?? 'No definida' }}
                                    @if($miembro->categoriaDocente && $miembro->categoriaDocente->siglas_categoria)
                                        <span class="text-sm text-gray-500 dark:text-gray-400">({{ $miembro->categoriaDocente->siglas_categoria }})</span>
                                    @endif
                                </p>
                                @if($miembro->categoriaDocente && $miembro->categoriaDocente->creditos_docencia)
                                    <p class="text-xs text-blue-600 dark:text-blue-400">
                                        <i class="fas fa-clock mr-1"></i>{{ $miembro->categoriaDocente->creditos_docencia }} créditos docencia
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Grupo -->
                        <div class="flex items-center p-3 bg-white dark:bg-gray-700 rounded-lg">
                            <div class="bg-green-100 dark:bg-green-800 rounded-lg p-2 mr-3">
                                <i class="fas fa-users text-green-600 dark:text-green-300"></i>
                            </div>
                            <div class="flex-grow">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Grupo</p>
                                <p class="text-gray-900 dark:text-white font-semibold">
                                    {{ $miembro->grupo->nombre_grupo ?? 'No definido' }}
                                    @if($miembro->grupo && $miembro->grupo->siglas_grupo)
                                        <span class="text-sm text-gray-500 dark:text-gray-400">({{ $miembro->grupo->siglas_grupo }})</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Información adicional -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @if($miembro->fecha_entrada)
                            <div class="bg-white dark:bg-gray-700 rounded-lg p-3">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Entrada</p>
                                <p class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($miembro->fecha_entrada)->format('d/m/Y') }}</p>
                            </div>
                            @endif

                            @if($miembro->web)
                            <div class="bg-white dark:bg-gray-700 rounded-lg p-3">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Web Personal</p>
                                <a href="{{ $miembro->web }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline text-sm break-all">
                                    <i class="fas fa-external-link-alt mr-1"></i>{{ $miembro->web }}
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Información Personal y Laboral -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Información Personal -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-r from-green-500 to-teal-500 rounded-full p-3 mr-4">
                        <i class="fas fa-user text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Información Personal</h2>
                        <p class="text-gray-600 dark:text-gray-400">Datos personales del usuario</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">DNI/Pasaporte</h3>
                            <p class="text-gray-900 dark:text-white font-semibold">{{ $usuario->dni_pasaporte }}</p>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Login</h3>
                            <p class="text-gray-900 dark:text-white font-semibold">{{ $usuario->login }}</p>
                        </div>
                    </div>

                    @if($usuario->user_last_login)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Último Inicio de Sesión</h3>
                        <p class="text-gray-900 dark:text-white font-semibold">
                            <i class="fas fa-clock mr-2 text-blue-500"></i>
                            {{ $usuario->user_last_login->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    @endif

                    <!-- Estados del usuario -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Estados</h3>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $usuario->miembro_actual == 'Si' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-300' }}">
                                <i class="fas fa-user-check mr-1"></i>Miembro Actual: {{ $usuario->miembro_actual }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $usuario->miembro_total == 'Si' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-300' }}">
                                <i class="fas fa-users mr-1"></i>Miembro Total: {{ $usuario->miembro_total }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $usuario->miembro_consejo == 'Si' ? 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-300' }}">
                                <i class="fas fa-crown mr-1"></i>Miembro Consejo: {{ $usuario->miembro_consejo }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Laboral -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-full p-3 mr-4">
                        <i class="fas fa-briefcase text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Información Laboral</h2>
                        <p class="text-gray-600 dark:text-gray-400">Despacho y contacto</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Despacho -->
                    @if($usuario->despacho)
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800 rounded-lg p-4 border border-blue-200 dark:border-gray-600">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-building mr-2 text-blue-500"></i>Despacho
                        </h3>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $usuario->despacho->nombre_despacho }}
                            @if($usuario->despacho->siglas_despacho)
                                <span class="text-sm text-gray-500 dark:text-gray-400">({{ $usuario->despacho->siglas_despacho }})</span>
                            @endif
                        </p>
                    </div>
                    @endif

                    <!-- Teléfonos -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @if($usuario->telefono_despacho)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-phone-office mr-2 text-green-500"></i>Teléfono Despacho
                            </h3>
                            <p class="text-gray-900 dark:text-white font-semibold">{{ $usuario->telefono_despacho }}</p>
                        </div>
                        @endif

                        @if($usuario->telefono)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-mobile-alt mr-2 text-blue-500"></i>Teléfono Personal
                            </h3>
                            <p class="text-gray-900 dark:text-white font-semibold">{{ $usuario->telefono }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Información técnica -->
                    @if($usuario->ip_asociada || $usuario->toma_red)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            <i class="fas fa-network-wired mr-2 text-purple-500"></i>Información Técnica
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @if($usuario->ip_asociada)
                            <div>
                                <p class="text-xs text-gray-600 dark:text-gray-400">IP Asociada</p>
                                <p class="text-gray-900 dark:text-white font-mono text-sm">{{ $usuario->ip_asociada }}</p>
                            </div>
                            @endif
                            @if($usuario->toma_red)
                            <div>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Toma de Red</p>
                                <p class="text-gray-900 dark:text-white font-mono text-sm">{{ $usuario->toma_red }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Información adicional -->
                    @if($usuario->uid_fotocopy || $usuario->clave_fotocopy || $usuario->mantiene_numero)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            <i class="fas fa-cogs mr-2 text-gray-500"></i>Información Adicional
                        </h3>
                        <div class="space-y-2 text-sm">
                            @if($usuario->uid_fotocopy)
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">UID Fotocopy:</span>
                                <span class="text-gray-900 dark:text-white font-mono">{{ $usuario->uid_fotocopy }}</span>
                            </div>
                            @endif
                            @if($usuario->clave_fotocopy)
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Clave Fotocopy:</span>
                                <span class="text-gray-900 dark:text-white">****</span>
                            </div>
                            @endif
                            @if($usuario->mantiene_numero)
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Mantiene Número:</span>
                                <span class="text-gray-900 dark:text-white">{{ $usuario->mantiene_numero == '1' ? 'Sí' : 'No' }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Información sobre roles (si existen) -->
        @if($usuario->roles && $usuario->roles->isNotEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-6">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full p-3 mr-4">
                    <i class="fas fa-user-shield text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Roles del Sistema</h2>
                    <p class="text-gray-600 dark:text-gray-400">Permisos y funciones asignadas</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                @foreach($usuario->roles as $role)
                <div class="bg-gradient-to-r from-indigo-100 to-purple-100 dark:from-indigo-800 dark:to-purple-800 border border-indigo-200 dark:border-indigo-600 rounded-lg px-4 py-2">
                    <div class="flex items-center">
                        <i class="fas fa-shield-alt text-indigo-600 dark:text-indigo-300 mr-2"></i>
                        <span class="font-semibold text-indigo-800 dark:text-indigo-200">{{ ucfirst($role->name) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Mensaje si no hay información académica -->
        @if($usuario->miembros->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <div class="text-center py-8">
                <div class="bg-gray-100 dark:bg-gray-700 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Sin Información Académica</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Este usuario no tiene asignadas categorías docentes o grupos de investigación.</p>
                <a href="{{ route('usuarios.edit', $usuario->id_usuario) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">
                    <i class="fas fa-plus mr-2"></i>Asignar Categoría y Grupo
                </a>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
