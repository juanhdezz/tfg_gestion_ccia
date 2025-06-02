<x-app-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full">
            <h1 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Detalles de la Asignatura
                {{ $asignatura->nombre_asignatura }}</h1>

            @if (session('error'))
                <div class="bg-red-500 text-white p-2 rounded mb-4">{{ session('error') }}</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- ID Titulación -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Titulación</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $asignatura->titulacion->nombre_titulacion }}</p>
                </div>

                <!-- Especialidad -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Especialidad</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $asignatura->especialidad }}</p>
                </div>                <!-- Coordinador -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Coordinador</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">
                        {{ $asignatura->coordinador ? $asignatura->coordinador->nombre . ' ' . $asignatura->coordinador->apellidos : 'No asignado' }}
                    </p>
                </div>

                <!-- Nombre Asignatura -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Nombre Asignatura</h3>
                    <p class="mt-1 text-gray-900 dark:text-white"><strong>{{ $asignatura->nombre_asignatura }}</strong>
                    </p>
                </div>

                <!-- Siglas Asignatura -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Siglas Asignatura</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $asignatura->siglas_asignatura }}</p>
                </div>

                <!-- Curso -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Curso</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $asignatura->curso }}</p>
                </div>

                <!-- Cuatrimestre -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Cuatrimestre</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $asignatura->cuatrimestre }}</p>
                </div>

                <!-- Créditos Teoría -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Créditos Teoría</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $asignatura->creditos_teoria }}</p>
                </div>

                <!-- Créditos Prácticas -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Créditos Prácticas</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $asignatura->creditos_practicas }}</p>
                </div>

                <!-- ECTS Teoría -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">ECTS Teoría</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $asignatura->ects_teoria }}</p>
                </div>

                <!-- ECTS Prácticas -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">ECTS Prácticas</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $asignatura->ects_practicas }}</p>
                </div>

                <!-- Grupos Teoría y Práctica -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Grupos</h3>

                    @foreach ($asignatura->grupos->whereNotNull('grupo_teoria')->unique('grupo_teoria') as $grupoTeoria)
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-md mb-4">
                            <div class="flex items-center gap-2 mb-3">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Grupo de Teoría {{ $grupoTeoria->grupo_teoria }}
                                </h4>
                            </div>

                            <div class="ml-7">
                                <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Grupos de Práctica:
                                </h5>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                                    @foreach ($asignatura->grupos->where('grupo_teoria', $grupoTeoria->grupo_teoria)->whereNotNull('grupo_practica') as $grupoPractica)
                                        <div class="bg-blue-50 dark:bg-blue-900/30 rounded-md p-2 text-center">
                                            <span class="text-blue-700 dark:text-blue-300 font-medium">
                                                Grupo {{ $grupoPractica->grupo_practica }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- <!-- Web DECSAI -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Web DECSAI</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $asignatura->web_decsai }}</p>
                </div> --}}

                <!-- Web Asignatura -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Web Asignatura</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $asignatura->web_asignatura }}</p>
                </div>

                {{-- <!-- Enlace Temario -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Enlace Temario</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $asignatura->enlace_temario }}</p>
                </div>

                <!-- Temario Teoría -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Temario Teoría</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $asignatura->temario_teoria }}</p>
                </div>

                <!-- Temario Prácticas -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Temario Prácticas</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $asignatura->temario_practicas }}</p>
                </div>

                <!-- Bibliografía -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Bibliografía</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $asignatura->bibliografia }}</p>
                </div>

                <!-- Evaluación -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Evaluación</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $asignatura->evaluacion }}</p>
                </div>

                <!-- Recomendaciones -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Recomendaciones</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $asignatura->recomendaciones }}</p>
                </div> --}}

                <!-- Tipo -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Tipo</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $asignatura->tipo }}</p>
                </div>

                <!-- Fraccionable -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Fraccionable</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $asignatura->fraccionable }}</p>
                </div>

                <!-- Estado -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 shadow-md">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Estado</h3>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $asignatura->estado }}</p>
                </div>
            </div>

            <div class="mt-6 flex justify-between">
                <a href="{{ route('asignaturas.index') }}" class="text-blue-600 hover:underline">Volver a la lista de
                    asignaturas</a>
                <a href="{{ route('asignaturas.edit', $asignatura->id_asignatura) }}"
                    class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">Editar Asignatura</a>
            </div>
        </div>
    </div>
</x-app-layout>
