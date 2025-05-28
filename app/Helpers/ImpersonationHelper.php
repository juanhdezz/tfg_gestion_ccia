<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Usuario;

class ImpersonationHelper
{
    public static function isImpersonating(): bool
    {
        return Session::has('impersonate_user_id');
    }

    public static function getOriginalUser(): ?Usuario
    {
        if (!self::isImpersonating()) {
            return null;
        }

        $originalUserId = Session::get('original_user_id');
        return Usuario::find($originalUserId);
    }

    public static function getCurrentDisplayUser(): Usuario
    {
        // Si estamos impersonando, devolver el usuario impersonado
        if (self::isImpersonating()) {
            $impersonatedUserId = Session::get('impersonate_user_id');
            $impersonatedUser = Usuario::find($impersonatedUserId);
            
            if ($impersonatedUser) {
                return $impersonatedUser;
            }
        }

        // Si no estamos impersonando, devolver el usuario autenticado
        return Auth::user();
    }

    public static function getImpersonationInfo(): ?array
    {
        if (!self::isImpersonating()) {
            return null;
        }

        $originalUser = self::getOriginalUser();
        $impersonatedUser = self::getCurrentDisplayUser();

        if (!$originalUser || !$impersonatedUser) {
            return null;
        }

        return [
            'original_user' => $originalUser,
            'impersonated_user' => $impersonatedUser,
            'start_time' => Session::get('impersonate_start_time')
        ];
    }
}