<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'nombre_abreviado' => 'nullable|string|max:255',
            'correo' => 'required|email|max:255|unique:usuario,correo,' . $user->id_usuario . ',id_usuario',
            'telefono' => 'nullable|string|max:20',
            'foto' => 'nullable|url|max:255',
            'passwd' => 'nullable|string',
        ]);

        if ($validatedData['passwd']) {
            $validatedData['passwd'] = bcrypt($validatedData['passwd']);
        } else {
            unset($validatedData['passwd']);
        }

        $user->update($validatedData);

        return redirect()->route('dashboard')->with('success', 'Perfil actualizado correctamente.');
    }
}
