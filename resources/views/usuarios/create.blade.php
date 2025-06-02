{{-- filepath: c:\xampp\htdocs\laravel\tfg_gestion_ccia\resources\views\usuarios\create.blade.php --}}
<x-app-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full">
            <h1 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Crear Usuario</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                <span class="text-red-500">*</span> Indica campos obligatorios
            </p>

            @if (session('error'))
                <div class="bg-red-500 text-white p-2 rounded mb-4">{{ session('error') }}</div>
            @endif

            <form action="{{ route('usuarios.store') }}" method="POST" enctype="multipart/form-data" id="createUsuarioForm">
                @csrf

                <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 mb-6">
                    <h2 class="text-lg font-semibold text-blue-800 dark:text-blue-300">Información Personal</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Columna 1: Datos personales básicos -->
                    <div>
                        <div class="mb-4">
                            <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nombre <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nombre" name="nombre" required 
                                placeholder="Nombre del usuario" 
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        </div>

                        <div class="mb-4">
                            <label for="apellidos" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Apellidos <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="apellidos" name="apellidos" required 
                                placeholder="Apellidos del usuario" 
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        </div>

                        <div class="mb-4">
                            <label for="nombre_abreviado" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nombre Abreviado <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nombre_abreviado" name="nombre_abreviado" required 
                                placeholder="Ej: J. García" 
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        </div>

                        <div class="mb-4">
                            <label for="dni_pasaporte" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                DNI/Pasaporte <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="dni_pasaporte" name="dni_pasaporte" required 
                                placeholder="12345678A o número de pasaporte" 
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        </div>

                        <div class="mb-4">
                            <label for="correo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Correo Electrónico <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="correo" name="correo" required
                                placeholder="usuario@dominio.com" 
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        </div>

                        <div class="mb-4">
                            <label for="foto" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Foto
                            </label>
                            <input type="text" id="foto" name="foto"
                                placeholder="Ruta de la foto" 
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        </div>
                    </div>

                    <!-- Columna 2: Acceso al sistema -->
                    <div>
                        <div class="mb-4">
                            <label for="login" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Login <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="login" name="login" required 
                                placeholder="Nombre de usuario para acceso" 
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        </div>

                        <div class="mb-4">
                            <label for="passwd" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="passwd" name="passwd" required 
                                placeholder="Contraseña segura"
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        </div>

                        <div class="mb-4">
                            <label for="passwd_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Confirmar Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="passwd_confirmation" name="passwd_confirmation" required 
                                placeholder="Repite la contraseña"
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        </div>

                        <div class="mb-4">
                            <label for="roles" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Asignar Rol <span class="text-red-500">*</span>
                            </label>
                            <select id="roles" name="roles[]" multiple required
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Mantén presionada la tecla Ctrl (Cmd en Mac) para seleccionar múltiples roles.</p>
                        </div>

                        <div class="mb-4">
                            <label for="tipo_usuario" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tipo Usuario <span class="text-red-500">*</span>
                            </label>
                            <select id="tipo_usuario" name="tipo_usuario" required 
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                                <option value="">-- Selecciona un tipo --</option>
                                <option value="Administrador">Administrador</option>
                                <option value="Profesor">Profesor</option>
                                <option value="Estudiante">Estudiante</option>
                                <option value="Contratado">Contratado</option>
                                <option value="Administrativo">Administrativo</option>
                                <option value="Invitado">Invitado</option>
                                <option value="InvitadoP">InvitadoP</option>
                                <option value="NoAccess">NoAccess</option>
                                <option value="Profesor Externo">Profesor Externo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-4 mt-8 mb-6">
                    <h2 class="text-lg font-semibold text-green-800 dark:text-green-300">Información Laboral</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Columna 1: Despacho y teléfono -->
                    <div>
                        <div class="mb-4">
                            <label for="id_despacho" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Despacho <span class="text-red-500">*</span>
                            </label>
                            <select id="id_despacho" name="id_despacho" required 
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                                <option value="">Seleccionar Despacho</option>
                                @foreach ($despachos as $despacho)
                                    <option value="{{ $despacho->id_despacho }}">{{ $despacho->nombre_despacho }} ({{ $despacho->siglas_despacho }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="telefono_despacho" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Teléfono Despacho <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="telefono_despacho" name="telefono_despacho" required 
                                placeholder="Ej: 966658123" maxlength="9"
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        </div>

                        <div class="mb-4">
                            <label for="telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Teléfono <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="telefono" name="telefono" required 
                                placeholder="Número de teléfono personal" 
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="ip_asociada" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    IP Asociada
                                </label>
                                <input type="text" id="ip_asociada" name="ip_asociada" 
                                    placeholder="Ej: 192.168.1.100" 
                                    class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            </div>

                            <div class="mb-4">
                                <label for="toma_red" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Toma de Red
                                </label>
                                <input type="text" id="toma_red" name="toma_red" 
                                    placeholder="Identificador de toma" 
                                    class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="uid_fotocopy" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    UID Fotocopy
                                </label>
                                <input type="text" id="uid_fotocopy" name="uid_fotocopy" 
                                    placeholder="ID de usuario" 
                                    class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            </div>

                            <div class="mb-4">
                                <label for="clave_fotocopy" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Clave Fotocopy
                                </label>
                                <input type="text" id="clave_fotocopy" name="clave_fotocopy" 
                                    placeholder="PIN o clave" 
                                    class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            </div>
                        </div>
                    </div>

                    <!-- Columna 2: Propiedades adicionales -->
                    <div>
                        <div class="mb-4">
                            <label for="mantiene_numero" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Mantiene Número
                            </label>
                            <input type="text" id="mantiene_numero" name="mantiene_numero" 
                                placeholder="0 (No) o 1 (Sí)" 
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        </div>

                        <div class="mb-4">
                            <label for="imparte_docencia" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Imparte Docencia
                            </label>
                            <select id="imparte_docencia" name="imparte_docencia" 
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                                <option value="Si">Si</option>
                                <option value="No" selected>No</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="miembro_actual" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Miembro Actual
                            </label>
                            <select id="miembro_actual" name="miembro_actual" 
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                                <option value="Si">Si</option>
                                <option value="No" selected>No</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="miembro_total" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Miembro Total
                            </label>
                            <select id="miembro_total" name="miembro_total" 
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                                <option value="Si">Si</option>
                                <option value="No" selected>No</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="miembro_consejo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Miembro Consejo <span class="text-red-500">*</span>
                            </label>
                            <select id="miembro_consejo" name="miembro_consejo" required 
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                                <option value="Si">Si</option>
                                <option value="No" selected>No</option>
                            </select>
                        </div>                        <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-info-circle text-blue-500 mr-1"></i> Acerca de este formulario:
                            </p>
                            <ul class="text-xs text-gray-600 dark:text-gray-400 list-disc ml-6 space-y-1">
                                <li>Los campos marcados con <span class="text-red-500">*</span> son obligatorios.</li>
                                <li>Selecciona los roles adecuados según las funciones del usuario.</li>
                                <li>Para seleccionar múltiples roles, mantén presionada la tecla Ctrl mientras haces clic.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="bg-purple-50 dark:bg-purple-900/20 border-l-4 border-purple-500 p-4 mt-8 mb-6">
                    <h2 class="text-lg font-semibold text-purple-800 dark:text-purple-300">Asignación Académica</h2>
                    <p class="text-sm text-purple-600 dark:text-purple-400 mt-1">Asignación de categoría docente y grupo (opcional)</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-4">
                            <label for="id_categoria" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Categoría Docente
                            </label>
                            <select id="id_categoria" name="id_categoria" 
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                                <option value="">Sin categoría asignada</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id_categoria }}" 
                                            data-creditos="{{ $categoria->creditos_docencia ?? 0 }}"
                                            data-siglas="{{ $categoria->siglas_categoria ?? '' }}">
                                        {{ $categoria->nombre_categoria ?? 'Categoría ' . $categoria->id_categoria }}
                                        @if($categoria->siglas_categoria) ({{ $categoria->siglas_categoria }}) @endif
                                        @if($categoria->creditos_docencia) - {{ $categoria->creditos_docencia }} créditos @endif
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Opcional: Se puede asignar después de crear el usuario
                            </p>
                        </div>

                        <div class="mb-4">
                            <label for="id_grupo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Grupo
                            </label>
                            <select id="id_grupo" name="id_grupo" 
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                                <option value="">Sin grupo asignado</option>
                                @foreach ($grupos as $grupo)
                                    <option value="{{ $grupo->id_grupo }}">
                                        {{ $grupo->nombre_grupo }}
                                        @if($grupo->siglas_grupo) ({{ $grupo->siglas_grupo }}) @endif
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Opcional: Se puede asignar después de crear el usuario
                            </p>
                        </div>
                    </div>

                    <div>
                        <div class="mb-4">
                            <label for="numero_orden" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Número de Orden
                            </label>
                            <input type="number" id="numero_orden" name="numero_orden" min="1" 
                                placeholder="Orden en el proceso de selección docente"
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Opcional: Se asignará automáticamente si se deja vacío
                            </p>
                        </div>

                        <div class="mb-4">
                            <label for="web" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Web Personal
                            </label>
                            <input type="url" id="web" name="web" 
                                placeholder="https://ejemplo.com"
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Opcional: Página web personal del usuario
                            </p>
                        </div>

                        <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-800/20 rounded-lg border border-blue-200 dark:border-blue-600">
                            <h4 class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-2">
                                <i class="fas fa-graduation-cap mr-2"></i>Información sobre categorías:
                            </h4>
                            <p class="text-xs text-blue-700 dark:text-blue-400">
                                Si asignas una categoría docente y un grupo, se creará automáticamente un registro de membresía 
                                que permitirá gestionar el orden de selección docente y los créditos asignados.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                    <button type="submit" id="submitBtn" class="px-5 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition shadow-sm">
                        <i class="fas fa-user-plus mr-2"></i>Crear Usuario
                    </button>
                    <a href="{{ route('usuarios.index') }}" class="px-5 py-2 bg-gray-500 text-white font-semibold rounded-md hover:bg-gray-600 transition shadow-sm text-center">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

        @push('scripts')
    <script>
        document.getElementById('createUsuarioForm').addEventListener('submit', function(event) {
            // Desactivar el botón para evitar múltiples envíos
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Procesando...';
            
            // Validar que las contraseñas coinciden
            const password = document.getElementById('passwd').value;
            const passwordConfirmation = document.getElementById('passwd_confirmation').value;
            
            if (password !== passwordConfirmation) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Las contraseñas no coinciden',
                    text: 'Por favor, verifique que ambas contraseñas sean idénticas'
                });
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-user-plus mr-2"></i>Crear Usuario';
                return false;
            }
            
            // Validar que se ha seleccionado al menos un rol
            const rolesSelect = document.getElementById('roles');
            let rolesSeleccionados = false;
            
            for (let i = 0; i < rolesSelect.options.length; i++) {
                if (rolesSelect.options[i].selected) {
                    rolesSeleccionados = true;
                    break;
                }
            }
            
            if (!rolesSeleccionados) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Roles requeridos',
                    text: 'Debe seleccionar al menos un rol para el usuario'
                });
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-user-plus mr-2"></i>Crear Usuario';
                return false;
            }

            // Validar unicidad de login y correo antes del envío
            event.preventDefault();
            const login = document.getElementById('login').value;
            const correo = document.getElementById('correo').value;
            const dni = document.getElementById('dni_pasaporte').value;

            // Validar que los campos requeridos no estén vacíos
            if (!login || !correo || !dni) {
                Swal.fire({
                    icon: 'error',
                    title: 'Campos obligatorios',
                    text: 'Por favor, complete todos los campos obligatorios'
                });
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-user-plus mr-2"></i>Crear Usuario';
                return false;
            }

            // Realizar validaciones AJAX
            Promise.all([
                checkUniqueness('login', login),
                checkUniqueness('correo', correo),
                checkUniqueness('dni_pasaporte', dni)
            ]).then(results => {
                const [loginExists, correoExists, dniExists] = results;
                
                if (loginExists) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login ya existe',
                        text: 'El nombre de usuario ya está en uso. Por favor, elija otro.'
                    });
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-user-plus mr-2"></i>Crear Usuario';
                    return;
                }
                
                if (correoExists) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Correo ya existe',
                        text: 'Este correo electrónico ya está registrado.'
                    });
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-user-plus mr-2"></i>Crear Usuario';
                    return;
                }
                
                if (dniExists) {
                    Swal.fire({
                        icon: 'error',
                        title: 'DNI/Pasaporte ya existe',
                        text: 'Este DNI/Pasaporte ya está registrado.'
                    });
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-user-plus mr-2"></i>Crear Usuario';
                    return;
                }
                
                // Si todas las validaciones pasan, enviar el formulario
                document.getElementById('createUsuarioForm').submit();
                
            }).catch(error => {
                console.error('Error en la validación:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de validación',
                    text: 'Ocurrió un error al validar los datos. Por favor, inténtelo de nuevo.'
                });
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-user-plus mr-2"></i>Crear Usuario';
            });
        });

       // Función para verificar unicidad via AJAX
        function checkUniqueness(field, value) {
            return fetch('{{ route("usuarios.check-uniqueness") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    field: field,
                    value: value
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Respuesta del servidor:', data); // Para debug
                return data.exists;
            })
            .catch(error => {
                console.error('Error en checkUniqueness:', error);
                return false; // En caso de error, asumir que no existe
            });
        }

        // Validaciones en tiempo real mientras el usuario escribe
        document.getElementById('login').addEventListener('blur', function() {
            const login = this.value;
            if (login) {
                checkUniqueness('login', login).then(exists => {
                    if (exists) {
                        this.classList.add('border-red-500');
                        showFieldError(this, 'Este login ya está en uso');
                    } else {
                        this.classList.remove('border-red-500');
                        this.classList.add('border-green-500');
                        clearFieldError(this);
                    }
                });
            }
        });

        document.getElementById('correo').addEventListener('blur', function() {
            const correo = this.value;
            if (correo) {
                checkUniqueness('correo', correo).then(exists => {
                    if (exists) {
                        this.classList.add('border-red-500');
                        showFieldError(this, 'Este correo ya está registrado');
                    } else {
                        this.classList.remove('border-red-500');
                        this.classList.add('border-green-500');
                        clearFieldError(this);
                    }
                });
            }
        });

        document.getElementById('dni_pasaporte').addEventListener('blur', function() {
            const dni = this.value;
            if (dni) {
                checkUniqueness('dni_pasaporte', dni).then(exists => {
                    if (exists) {
                        this.classList.add('border-red-500');
                        showFieldError(this, 'Este DNI/Pasaporte ya está registrado');
                    } else {
                        this.classList.remove('border-red-500');
                        this.classList.add('border-green-500');
                        clearFieldError(this);
                    }
                });
            }
        });        // Funciones auxiliares para mostrar/ocultar errores de campo
        function showFieldError(field, message) {
            clearFieldError(field);
            const errorDiv = document.createElement('div');
            errorDiv.className = 'text-red-500 text-xs mt-1 field-error';
            errorDiv.textContent = message;
            field.parentNode.appendChild(errorDiv);
        }

        function clearFieldError(field) {
            const existingError = field.parentNode.querySelector('.field-error');
            if (existingError) {
                existingError.remove();
            }
            field.classList.remove('border-red-500', 'border-green-500');
        }

        // Funcionalidad para categoría y grupo - mostrar información de miembro
        const categoriaSelect = document.getElementById('id_categoria');
        const grupoSelect = document.getElementById('id_grupo');
        const numeroOrdenInput = document.getElementById('numero_orden');
        
        function updateMemberInfo() {
            const categoriaId = categoriaSelect.value;
            const grupoId = grupoSelect.value;
            
            // Limpiar mensajes anteriores
            const existingInfo = document.getElementById('member-info');
            if (existingInfo) {
                existingInfo.remove();
            }
            
            if (categoriaId && grupoId) {
                // Crear elemento de información
                const infoDiv = document.createElement('div');
                infoDiv.id = 'member-info';
                infoDiv.className = 'mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-md';
                
                const categoriaOption = categoriaSelect.options[categoriaSelect.selectedIndex];
                const grupoOption = grupoSelect.options[grupoSelect.selectedIndex];
                const creditos = categoriaOption.dataset.creditos || '0';
                const siglas = categoriaOption.dataset.siglas || '';
                
                infoDiv.innerHTML = `
                    <div class="flex items-center space-x-2 text-blue-700 dark:text-blue-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="font-medium">Se creará registro de miembro automáticamente</p>
                            <p class="text-sm">
                                Categoría: <strong>${categoriaOption.text}</strong><br>
                                Grupo: <strong>${grupoOption.text}</strong><br>
                                Créditos docencia: <strong>${creditos}</strong>
                                ${numeroOrdenInput.value ? `<br>Orden asignado: <strong>${numeroOrdenInput.value}</strong>` : '<br><em>Orden se asignará automáticamente</em>'}
                            </p>
                        </div>
                    </div>
                `;
                
                // Insertar después del grupo select
                grupoSelect.parentNode.parentNode.appendChild(infoDiv);
            }
        }
        
        // Event listeners para categoría y grupo
        if (categoriaSelect && grupoSelect) {
            categoriaSelect.addEventListener('change', updateMemberInfo);
            grupoSelect.addEventListener('change', updateMemberInfo);
            if (numeroOrdenInput) {
                numeroOrdenInput.addEventListener('input', updateMemberInfo);
            }
        }
    </script>
    @endpush
</x-app-layout>