<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Despacho extends BaseModel
{
    use HasFactory;

    protected $table = 'despacho'; // Nombre exacto de la tabla
    protected $primaryKey = 'id_despacho'; // Definir la clave primaria
    public $timestamps = false; // Si la tabla no tiene created_at y updated_at

    protected $fillable = [
        'id_centro',
        'nombre_despacho',
        'siglas_despacho',
        'telefono_despacho',
        'numero_puestos',
        'descripcion',
    ];

    // Relación con la tabla Centro (un despacho pertenece a un centro)
    public function centro()
    {
        return $this->belongsTo(Centro::class, 'id_centro');
    }

    // Relación con la tabla Usuario (un despacho puede tener muchos usuarios)
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_despacho', 'id_despacho');
    }
}
