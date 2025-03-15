<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Centro extends BaseModel
{
    use HasFactory;

    // Especificamos el nombre de la tabla
    protected $table = 'centro';

    // Especificamos los campos que se pueden asignar masivamente (fillable)
    protected $fillable = [
        'nombre_centro',
        'siglas_centro',
        'direccion_centro',
    ];

    // Si usas un nombre de clave primaria diferente a 'id', puedes definirlo aquí
    protected $primaryKey = 'id_centro';

    // Si la clave primaria no es autoincremental, puedes definirlo aquí
    //public $incrementing = true;

    // Definir el tipo de la clave primaria
    //protected $keyType = 'int';

    // Si no utilizas las marcas de tiempo (created_at, updated_at), desactívalas
    public $timestamps = false;
}
