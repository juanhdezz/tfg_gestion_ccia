<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Usuario;


class ListaUsuariosExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        //return Usuario::all();
        return Usuario::select(
        'nombre', 
        'apellidos', 
        'nombre_abreviado', 
        'dni_pasaporte', 
        'correo', 
        'foto', 
        'id_despacho', 
        'telefono_despacho', 
        'telefono', 
        )->get();
    }
}
