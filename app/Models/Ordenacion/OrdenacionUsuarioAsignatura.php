<?php

namespace App\Models\Ordenacion;

use App\Models\BaseModel;
use App\Models\Usuario;
use App\Models\Asignatura;
use App\Models\Titulacion;

class OrdenacionUsuarioAsignatura extends BaseModel
{
    protected $table = 'ordenacion_usuario_asignatura';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = [
        'id_usuario',
        'id_asignatura',
        'tipo',
        'grupo',
        'creditos',
        'antiguedad',
        'en_primera_fase'
    ];
    
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
    
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'id_asignatura', 'id_asignatura');
    }

    public function titulacion()
    {
        return $this->belongsTo(Titulacion::class, 'id_titulacion', 'id_titulacion');
    }
}