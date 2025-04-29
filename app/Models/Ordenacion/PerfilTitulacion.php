<?php

namespace App\Models\Ordenacion;

use App\Models\BaseModel;
use App\Models\Usuario;
use App\Models\Titulacion;

class PerfilTitulacion extends BaseModel
{
    protected $table = 'perfil_titulacion';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = [
        'id_usuario',
        'id_titulacion'
    ];
    
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
    
    public function titulacion()
    {
        return $this->belongsTo(Titulacion::class, 'id_titulacion', 'id_titulacion');
    }
}