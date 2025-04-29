<?php

namespace App\Models\Ordenacion;

use App\Models\BaseModel;
use App\Models\Usuario;

class Perfil extends BaseModel
{
    protected $table = 'perfil';
    protected $primaryKey = 'id_usuario';
    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = [
        'id_usuario',
        'palabras_clave',
        'sin_palabras_clave',
        'teoria',
        'practicas',
        'pasar_turno'
    ];
    
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
    
    public function titulaciones()
    {
        return $this->hasMany(PerfilTitulacion::class, 'id_usuario', 'id_usuario');
    }
}