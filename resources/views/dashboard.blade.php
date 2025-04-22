<x-app-layout>
    <div class="min-h-screen bg-gray-100 py-6 px-4 sm:px-6 lg:px-8">
        <!-- Encabezado con saludo personalizado -->
        <div class="max-w-full px-4 sm:px-6 lg:px-8 mx-auto">
            <h2 class="text-2xl font-bold text-indigo-800 mb-6">
                Bienvenido, {{ Auth::user()->nombre_abreviado }}
            </h2>

            <!-- Grid de mosaico principal -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Tarjeta de perfil de usuario - Ocupa 2 columnas en dispositivos medianos y grandes -->
                <div class="md:col-span-2 bg-white rounded-xl shadow-md overflow-hidden transition-transform duration-300 transform hover:scale-[1.01] hover:shadow-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="h-16 w-16 bg-indigo-600 rounded-full flex items-center justify-center mr-4">
                                <span class="text-xl font-bold text-white">{{ strtoupper(substr(Auth::user()->nombre, 0, 1) . substr(Auth::user()->apellidos, 0, 1)) }}</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">{{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}</h3>
                                <p class="text-sm text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                    {{ Auth::user()->correo }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Información de acceso</h4>
                                <p class="text-xs text-gray-600">
                                    <span class="font-medium">Último acceso:</span> {{ Auth::user()->user_last_login ? \Carbon\Carbon::parse(Auth::user()->user_last_login)->format('d/m/Y H:i') : 'No registrado' }}
                                </p>
                                <p class="text-xs text-gray-600 mt-1">
                                    <span class="font-medium">Rol:</span> 
                                    @role('admin')
                                        <span class="px-2 py-0.5 rounded-full text-xs bg-indigo-100 text-indigo-800">Administrador</span>
                                    @endrole
                                    
                                </p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Datos adicionales</h4>
                                <p class="text-xs text-gray-600">
                                    <span class="font-medium">Categoría:</span> {{ Auth::user()->tipo_usuario ?? 'No especificado' }}
                                </p>
                                <p class="text-xs text-gray-600 mt-1">
                                    <span class="font-medium">Teléfono:</span> {{ Auth::user()->telefono ?? 'No especificado' }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex space-x-4">
                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Editar perfil
                            </a>
                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Cambiar contraseña
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Calendario - Col-span-1 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden" x-data="calendar()">
                    <div class="p-4 bg-indigo-700 text-white flex justify-between items-center">
                        <button @click="previousMonth()" class="p-1 hover:bg-indigo-600 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <h3 class="text-base font-semibold" x-text="monthYear"></h3>
                        <button @click="nextMonth()" class="p-1 hover:bg-indigo-600 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-7 gap-1 text-xs text-center font-semibold text-gray-700 mb-1">
                            <div>L</div>
                            <div>M</div>
                            <div>X</div>
                            <div>J</div>
                            <div>V</div>
                            <div>S</div>
                            <div>D</div>
                        </div>
                        <div class="grid grid-cols-7 gap-1 text-sm">
                            <template x-for="(day, index) in days" :key="index">
                                <div 
                                    :class="{
                                        'py-1 text-center rounded-full w-8 h-8 mx-auto flex items-center justify-center': true,
                                        'text-gray-500': !day.isCurrentMonth,
                                        'bg-indigo-600 text-white': day.isToday,
                                        'hover:bg-gray-100 cursor-pointer': day.isCurrentMonth && !day.isToday
                                    }"
                                    x-text="day.day"></div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Accesos rápidos -->
                <div class="bg-white rounded-xl shadow-md p-6 transition-transform duration-300 transform hover:scale-[1.01] hover:shadow-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
                        </svg>
                        Accesos rápidos
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('libros.create') }}" class="block p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13c-1.168-.775-2.754-1.253-4.5-1.253-1.746 0-3.332.478-4.5 1.253" />
                            </svg>
                            Solicitar libro
                        </a>
                        <a href="{{ route('reserva_salas.create') }}" class="block p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Reservar sala
                        </a>
                        <a href="{{ route('tutorias.index') }}" class="block p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Gestionar tutorías
                        </a>
                    </div>
                </div>

                <!-- Actividad reciente - Ocupa 2 columnas -->
                <div class="md:col-span-2 bg-white rounded-xl shadow-md p-6 transition-transform duration-300 transform hover:scale-[1.01] hover:shadow-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                        Actividad reciente
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center mr-3 flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Tu solicitud de libro "Inteligencia Artificial: Un enfoque moderno" ha sido aprobada</p>
                                <p class="text-xs text-gray-500 mt-1">Hace 2 días</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center mr-3 flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Has reservado la sala 1.7 para el próximo lunes</p>
                                <p class="text-xs text-gray-500 mt-1">Hace 3 días</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center mr-3 flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Has sido asignado a la asignatura "Programación Web"</p>
                                <p class="text-xs text-gray-500 mt-1">Hace 1 semana</p>
                            </div>
                        </div>
                        <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors flex items-center mt-2">
                            Ver todo el historial
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Estado solicitudes - Col-span-1 -->
                <div class="bg-white rounded-xl shadow-md p-6 transition-transform duration-300 transform hover:scale-[1.01] hover:shadow-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm2 10a1 1 0 10-2 0v3a1 1 0 102 0v-3zm4-1a1 1 0 011 1v3a1 1 0 11-2 0v-3a1 1 0 011-1zm2-5a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                        </svg>
                        Estado de solicitudes
                    </h3>
                    <div class="space-y-4">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="text-sm font-medium text-gray-700">Solicitudes de libros</h4>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">2 pendientes</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-yellow-500 h-2.5 rounded-full" style="width: 65%"></div>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="text-sm font-medium text-gray-700">Reservas de salas</h4>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Completadas</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-green-500 h-2.5 rounded-full" style="width: 100%"></div>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="text-sm font-medium text-gray-700">Tutorías</h4>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">3 programadas</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-500 h-2.5 rounded-full" style="width: 40%"></div>
                            </div>
                        </div>
                        <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors flex items-center mt-2">
                            Ver detalles
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sección de enlaces útiles -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Enlaces útiles</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="https://decsai.ugr.es" target="_blank" class="group flex flex-col items-center p-4 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="h-12 w-12 bg-indigo-100 rounded-full flex items-center justify-center group-hover:bg-indigo-200 transition-colors mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-600">Web DECSAI</span>
                    </a>
                    <a href="https://prado.ugr.es" target="_blank" class="group flex flex-col items-center p-4 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="h-12 w-12 bg-green-100 rounded-full flex items-center justify-center group-hover:bg-green-200 transition-colors mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13c-1.168-.775-2.754-1.253-4.5-1.253-1.746 0-3.332.478-4.5 1.253" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-green-600">PRADO</span>
                    </a>
                    <a href="https://oficinavirtual.ugr.es" target="_blank" class="group flex flex-col items-center p-4 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center group-hover:bg-blue-200 transition-colors mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-blue-600">Oficina Virtual</span>
                    </a>
                    <a href="https://www.ugr.es/universidad/servicios" target="_blank" class="group flex flex-col items-center p-4 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="h-12 w-12 bg-purple-100 rounded-full flex items-center justify-center group-hover:bg-purple-200 transition-colors mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-purple-600">Servicios UGR</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts para la funcionalidad del calendario -->
    <script>
        function calendar() {
            return {
                currentDate: new Date(),
                month: new Date().getMonth(),
                year: new Date().getFullYear(),
                days: [],
                
                // Computed property para mostrar el mes y año actual
                get monthYear() {
                    const months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                    return `${months[this.month]} ${this.year}`;
                },
                
                // Método para inicializar el calendario
                init() {
                    this.generateDays();
                },
                
                // Método para generar los días del mes
                generateDays() {
                    this.days = [];
                    const daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
                    const firstDayOfMonth = new Date(this.year, this.month, 1).getDay();
                    
                    // Ajustar para que la semana comience en lunes (0 = lunes, 6 = domingo)
                    const startDay = firstDayOfMonth === 0 ? 6 : firstDayOfMonth - 1;
                    
                    // Días del mes anterior
                    const prevMonthDays = new Date(this.year, this.month, 0).getDate();
                    for (let i = startDay - 1; i >= 0; i--) {
                        this.days.push({
                            day: prevMonthDays - i,
                            isCurrentMonth: false,
                            isToday: false
                        });
                    }
                    
                    // Días del mes actual
                    const today = new Date();
                    for (let i = 1; i <= daysInMonth; i++) {
                        this.days.push({
                            day: i,
                            isCurrentMonth: true,
                            isToday: i === today.getDate() && this.month === today.getMonth() && this.year === today.getFullYear()
                        });
                    }
                    
                    // Días del próximo mes para completar la cuadrícula
                    const totalDaysShown = Math.ceil((startDay + daysInMonth) / 7) * 7;
                    const nextMonthDays = totalDaysShown - (startDay + daysInMonth);
                    for (let i = 1; i <= nextMonthDays; i++) {
                        this.days.push({
                            day: i,
                            isCurrentMonth: false,
                            isToday: false
                        });
                    }
                },
                
                // Método para avanzar al mes siguiente
                nextMonth() {
                    this.month++;
                    if (this.month > 11) {
                        this.month = 0;
                        this.year++;
                    }
                    this.generateDays();
                },
                
                // Método para retroceder al mes anterior
                previousMonth() {
                    this.month--;
                    if (this.month < 0) {
                        this.month = 11;
                        this.year--;
                    }
                    this.generateDays();
                }
            };
        }
    </script>
</x-app-layout>