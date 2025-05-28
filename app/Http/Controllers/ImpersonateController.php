<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\Usuario;

class ImpersonateController extends Controller
{
    /**
     * Iniciar impersonación de un usuario
     */
    public function start(Request $request, $userId)
    {
        try {
            Log::info('Intentando iniciar impersonación', [
                'admin_id' => Auth::id(),
                'admin_name' => Auth::user()->nombre . ' ' . Auth::user()->apellidos,
                'target_user_id' => $userId,
            ]);

            // Verificar que el usuario actual es administrador
            if (!Auth::user()->hasRole('admin')) {
                session()->flash('swal', [
                    'icon' => 'error',
                    'title' => 'Acceso denegado',
                    'text' => 'No tienes permisos para realizar esta acción'
                ]);
                
                return redirect()->back();
            }
            
            $userToImpersonate = Usuario::find($userId);
            
            if (!$userToImpersonate) {
                session()->flash('swal', [
                    'icon' => 'error',
                    'title' => 'Usuario no encontrado',
                    'text' => 'El usuario que intentas impersonar no existe'
                ]);
                
                return redirect()->back();
            }
            
            // Verificar que no se está impersonando a otro administrador
            if ($userToImpersonate->hasRole('admin')) {
                session()->flash('swal', [
                    'icon' => 'warning',
                    'title' => 'Impersonación no permitida',
                    'text' => 'No puedes impersonar a otro administrador'
                ]);
                
                return redirect()->back();
            }
            
            // Guardar información de impersonación en la sesión
            Session::put('original_user_id', Auth::id());
            Session::put('impersonate_user_id', $userId);
            Session::put('impersonate_start_time', now());
            Session::save();
            
            Log::info('Impersonación iniciada exitosamente', [
                'admin_id' => Auth::id(),
                'impersonated_id' => $userId,
                'session_data' => [
                    'original_user_id' => Session::get('original_user_id'),
                    'impersonate_user_id' => Session::get('impersonate_user_id')
                ]
            ]);
            
            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Impersonación iniciada',
                'text' => "Ahora estás actuando como {$userToImpersonate->nombre} {$userToImpersonate->apellidos}"
            ]);
            
            return redirect()->route('dashboard');
            
        } catch (\Exception $e) {
            Log::error('Error al iniciar impersonación', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al iniciar la impersonación'
            ]);
            
            return redirect()->back();
        }
    }
    
    /**
     * Terminar impersonación
     */
    public function stop(Request $request)
    {
        try {
            Log::info('Intentando finalizar impersonación', [
                'current_auth_id' => Auth::id(),
                'session_data' => [
                    'original_user_id' => Session::get('original_user_id'),
                    'impersonate_user_id' => Session::get('impersonate_user_id'),
                    'has_session' => Session::has('impersonate_user_id')
                ]
            ]);

            if (!Session::has('impersonate_user_id')) {
                session()->flash('swal', [
                    'icon' => 'warning',
                    'title' => 'No hay impersonación activa',
                    'text' => 'No se detectó ninguna impersonación en curso'
                ]);
                
                return redirect()->route('usuarios.index');
            }
            
            $originalUserId = Session::get('original_user_id');
            $impersonatedUserId = Session::get('impersonate_user_id');
            
            // IMPORTANTE: Limpiar sesión PRIMERO
            Session::forget(['impersonate_user_id', 'original_user_id', 'impersonate_start_time']);
            Session::save();
            
            Log::info('Sesión de impersonación limpiada', [
                'original_user_id' => $originalUserId,
                'impersonated_user_id' => $impersonatedUserId
            ]);
            
            // Luego restaurar el usuario original
            $originalUser = Usuario::find($originalUserId);
            if ($originalUser) {
                Auth::setUser($originalUser);
                
                Log::info('Usuario original restaurado', [
                    'user_id' => $originalUser->id_usuario,
                    'user_name' => $originalUser->nombre . ' ' . $originalUser->apellidos,
                    'current_auth_id' => Auth::id()
                ]);
            }
            
            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Impersonación finalizada',
                'text' => 'Has vuelto a tu cuenta de administrador correctamente'
            ]);
            
            return redirect()->route('usuarios.index');
            
        } catch (\Exception $e) {
            Log::error('Error al finalizar impersonación', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // En caso de error, limpiar la sesión de todos modos
            Session::forget(['impersonate_user_id', 'original_user_id', 'impersonate_start_time']);
            Session::save();
            
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Error al finalizar impersonación, pero se ha limpiado la sesión'
            ]);
            
            return redirect()->route('usuarios.index');
        }
    }
    
    /**
     * Verificar si el usuario actual está siendo impersonado
     */
    public function isImpersonating()
    {
        return Session::has('impersonate_user_id');
    }
    
    /**
     * Obtener información sobre la impersonación actual
     */
    public function getImpersonationInfo()
    {
        if (!$this->isImpersonating()) {
            return null;
        }
        
        try {
            $originalUser = Usuario::find(Session::get('original_user_id'));
            $impersonatedUser = Usuario::find(Session::get('impersonate_user_id'));
            
            if (!$originalUser || !$impersonatedUser) {
                // Si alguno de los usuarios no existe, limpiar la sesión
                Session::forget(['impersonate_user_id', 'original_user_id', 'impersonate_start_time']);
                return null;
            }
            
            return [
                'original_user' => $originalUser,
                'impersonated_user' => $impersonatedUser,
                'start_time' => Session::get('impersonate_start_time')
            ];
        } catch (\Exception $e) {
            Log::error('Error al obtener información de impersonación', [
                'error' => $e->getMessage()
            ]);
            
            // Limpiar sesión en caso de error
            Session::forget(['impersonate_user_id', 'original_user_id', 'impersonate_start_time']);
            return null;
        }
    }
}