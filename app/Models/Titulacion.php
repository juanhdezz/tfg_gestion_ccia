<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Titulacion extends BaseModel
{
    use HasFactory;

    protected $table = 'titulacion'; // Nombre de la tabla en la BD

    // Si la clave primaria no se llama "id", defínela aquí:
    protected $primaryKey = 'id_titulacion';

    // Relación con Asignatura (Una titulación tiene muchas asignaturas)
    public function asignaturas()
    {
        return $this->hasMany(Asignatura::class, 'id_titulacion', 'id_titulacion');
    }
}
