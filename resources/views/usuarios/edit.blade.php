<!-- filepath: /c:/xampp/htdocs/laravel/tfg_gestion_ccia/resources/views/usuarios/edit.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full">
            <h1 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Editar Usuario</h1>
            @if (session('error'))
                <div class="bg-red-500 text-white p-2 rounded mb-4">{{ session('error') }}</div>
            @endif
            <form action="{{ route('usuarios.update', $usuario->id_usuario) }}" method="POST"
                enctype="multipart/form-data" id="editUsuarioForm">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="nombre"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="{{ $usuario->nombre }}" required
                        class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="apellidos"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" value="{{ $usuario->apellidos }}" required
                        class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="nombre_abreviado"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre Abreviado:</label>
                    <input type="text" id="nombre_abreviado" name="nombre_abreviado"
                        value="{{ $usuario->nombre_abreviado }}" required
                        class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="dni_pasaporte"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">DNI/Pasaporte:</label>
                    <input type="text" id="dni_pasaporte" name="dni_pasaporte" value="{{ $usuario->dni_pasaporte }}"
                        required
                        class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="correo"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo:</label>
                    <input type="email" id="correo" name="correo" value="{{ $usuario->correo }}" required
                        class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="foto"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Foto:</label>
                    <input type="text" id="foto" name="foto" value="{{ $usuario->foto }}"
                        class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="id_despacho"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Despacho:</label>
                    <select id="id_despacho" name="id_despacho" required
                        class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        <option value="">Seleccionar Despacho</option>
                        @foreach ($despachos as $despacho)
                            <option value="{{ $despacho->id_despacho }}" {{ $usuario->id_despacho == $despacho->id_despacho ? 'selected' : '' }}>
                                {{ $despacho->nombre_despacho }} ({{ $despacho->siglas_despacho }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="telefono_despacho"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teléfono Despacho:</label>
                    <input type="text" id="telefono_despacho" name="telefono_despacho"
                        value="{{ $usuario->telefono_despacho }}"
                        class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="telefono"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" value="{{ $usuario->telefono }}"
                        class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="ip_asociada" class="block text-sm font-medium text-gray-700 dark:text-gray-300">IP
                        Asociada:</label>
                    <input type="text" id="ip_asociada" name="ip_asociada" value="{{ $usuario->ip_asociada }}"
                        class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="toma_red" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Toma de
                        Red:</label>
                    <input type="text" id="toma_red" name="toma_red" value="{{ $usuario->toma_red }}"
                        class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="mantiene_numero"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mantiene Número:</label>
                    <input type="text" id="mantiene_numero" name="mantiene_numero"
                        value="{{ $usuario->mantiene_numero }}"
                        class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="uid_fotocopy" class="block text-sm font-medium text-gray-700 dark:text-gray-300">UID
                        Fotocopy:</label>
                    <input type="text" id="uid_fotocopy" name="uid_fotocopy"
                        value="{{ $usuario->uid_fotocopy }}"
                        class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="clave_fotocopy"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Clave Fotocopy:</label>
                    <input type="text" id="clave_fotocopy" name="clave_fotocopy"
                        value="{{ $usuario->clave_fotocopy }}"
                        class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="login"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Login:</label>
                    <input type="text" id="login" name="login" value="{{ $usuario->login }}" required
                        class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                </div>
                <div class="mb-4">
                    <label for="passwd" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nueva
                        Contraseña:</label>
                    <input type="password" id="passwd" name="passwd"
                        class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Déjalo en blanco si no deseas cambiar la
                        contraseña.</p>
                </div>

                <div class="mb-4">
                    <label for="passwd_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirmar Nueva Contraseña:</label>
                    <input type="password" id="passwd_confirmation" name="passwd_confirmation"
                        class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Solo necesario si vas a cambiar la contraseña.</p>
                </div>

                <div class="mb-4">
                    <label for="imparte_docencia"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Imparte Docencia:</label>
                    <select id="imparte_docencia" name="imparte_docencia"
                        class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        <option value="Si" {{ $usuario->imparte_docencia == 'Si' ? 'selected' : '' }}>Si</option>
                        <option value="No" {{ $usuario->imparte_docencia == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="miembro_actual"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Miembro Actual:</label>
                    <select id="miembro_actual" name="miembro_actual"
                        class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        <option value="Si" {{ $usuario->miembro_actual == 'Si' ? 'selected' : '' }}>Si</option>
                        <option value="No" {{ $usuario->miembro_actual == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="miembro_total"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Miembro Total:</label>
                    <select id="miembro_total" name="miembro_total"
                        class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        <option value="Si" {{ $usuario->miembro_total == 'Si' ? 'selected' : '' }}>Si</option>
                        <option value="No" {{ $usuario->miembro_total == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="miembro_consejo"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Miembro Consejo:</label>
                    <select id="miembro_consejo" name="miembro_consejo" required
                        class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        <option value="Si" {{ $usuario->miembro_consejo == 'Si' ? 'selected' : '' }}>Si</option>
                        <option value="No" {{ $usuario->miembro_consejo == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="tipo_usuario" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo
                        de Usuario:</label>
                    <select id="tipo_usuario" name="tipo_usuario" required
                        class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        <option value="Administrador"
                            {{ $usuario->tipo_usuario == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                        <option value="Profesor" {{ $usuario->tipo_usuario == 'Profesor' ? 'selected' : '' }}>Profesor
                        </option>
                        <option value="Contratado" {{ $usuario->tipo_usuario == 'Contratado' ? 'selected' : '' }}>
                            Contratado</option>
                        <option value="Administrativo"
                            {{ $usuario->tipo_usuario == 'Administrativo' ? 'selected' : '' }}>Administrativo</option>
                        <option value="Invitado" {{ $usuario->tipo_usuario == 'Invitado' ? 'selected' : '' }}>Invitado
                        </option>
                        <option value="InvitadoP" {{ $usuario->tipo_usuario == 'InvitadoP' ? 'selected' : '' }}>
                            InvitadoP</option>
                        <option value="NoAccess" {{ $usuario->tipo_usuario == 'NoAccess' ? 'selected' : '' }}>NoAccess
                        </option>
                        <option value="Estudiante" {{ $usuario->tipo_usuario == 'Estudiante' ? 'selected' : '' }}>
                            Estudiante</option>
                        <option value="Profesor Externo"
                            {{ $usuario->tipo_usuario == 'Profesor Externo' ? 'selected' : '' }}>Profesor Externo
                        </option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="roles" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Asignar
                        Roles:</label>
                    <select id="roles" name="roles[]" multiple
                        class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-white">
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}"
                                {{ $usuario->hasRole($role->name) ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Mantén presionada la tecla Ctrl (Cmd en Mac)
                        para seleccionar múltiples roles.</p>
                </div>

                <div class="mt-8 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                    <button type="submit" id="submitBtn"
                        class="px-5 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition shadow-sm">
                        <i class="fas fa-save mr-2"></i>Actualizar Usuario
                    </button>
                    <a href="{{ route('usuarios.index') }}"
                        class="px-5 py-2 bg-gray-500 text-white font-semibold rounded-md hover:bg-gray-600 transition shadow-sm text-center">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const CURRENT_USER_ID = {{ $usuario->id_usuario }}; // ID del usuario actual que se está editando
        
        document.getElementById('editUsuarioForm').addEventListener('submit', function(event) {
            // Desactivar el botón para evitar múltiples envíos
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Procesando...';
            
            // Validar contraseñas solo si se está cambiando
            const password = document.getElementById('passwd').value;
            const passwordConfirmation = document.getElementById('passwd_confirmation').value;
            
            if (password || passwordConfirmation) {
                if (password !== passwordConfirmation) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Las contraseñas no coinciden',
                        text: 'Por favor, verifique que ambas contraseñas sean idénticas'
                    });
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Actualizar Usuario';
                    return false;
                }
            }
            
            // Validar unicidad de campos antes del envío
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
                submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Actualizar Usuario';
                return false;
            }

            // Realizar validaciones AJAX (excluyendo el usuario actual)
            Promise.all([
                checkUniqueness('login', login, CURRENT_USER_ID),
                checkUniqueness('correo', correo, CURRENT_USER_ID),
                checkUniqueness('dni_pasaporte', dni, CURRENT_USER_ID)
            ]).then(results => {
                const [loginExists, correoExists, dniExists] = results;
                
                if (loginExists) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login ya existe',
                        text: 'El nombre de usuario ya está en uso. Por favor, elija otro.'
                    });
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Actualizar Usuario';
                    return;
                }
                
                if (correoExists) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Correo ya existe',
                        text: 'Este correo electrónico ya está registrado.'
                    });
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Actualizar Usuario';
                    return;
                }
                
                if (dniExists) {
                    Swal.fire({
                        icon: 'error',
                        title: 'DNI/Pasaporte ya existe',
                        text: 'Este DNI/Pasaporte ya está registrado.'
                    });
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Actualizar Usuario';
                    return;
                }
                
                // Si todas las validaciones pasan, enviar el formulario
                document.getElementById('editUsuarioForm').submit();
                
            }).catch(error => {
                console.error('Error en la validación:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de validación',
                    text: 'Ocurrió un error al validar los datos. Por favor, inténtelo de nuevo.'
                });
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Actualizar Usuario';
            });
        });

        // Función para verificar unicidad via AJAX (incluyendo user_id para excluir)
        function checkUniqueness(field, value, userId = null) {
            const requestData = {
                field: field,
                value: value
            };
            
            if (userId) {
                requestData.user_id = userId;
            }
            
            return fetch('{{ route("usuarios.check-uniqueness") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(requestData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Respuesta del servidor:', data);
                return data.exists;
            })
            .catch(error => {
                console.error('Error en checkUniqueness:', error);
                return false;
            });
        }

        // Validaciones en tiempo real mientras el usuario escribe
        document.getElementById('login').addEventListener('blur', function() {
            const login = this.value;
            if (login) {
                checkUniqueness('login', login, CURRENT_USER_ID).then(exists => {
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
                checkUniqueness('correo', correo, CURRENT_USER_ID).then(exists => {
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
                checkUniqueness('dni_pasaporte', dni, CURRENT_USER_ID).then(exists => {
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
        });

        // Funciones auxiliares para mostrar/ocultar errores de campo
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
    </script>
    @endpush
</x-app-layout>