<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoTeoriaPractica extends BaseModel
{
    use HasFactory;

    protected $table = 'grupo_teoria_practica'; // Nombre de la tabla en la BD
    protected $primaryKey = 'id'; // Clave primaria

    public $timestamps = false; // La tabla no tiene timestamps

    protected $fillable = [
        'id_asignatura',
        'grupo_teoria',
        'grupo_practica',
    ];

    // Relación con la asignatura
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'id_asignatura', 'id_asignatura');
    }
}
