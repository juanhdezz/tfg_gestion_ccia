<?php

namespace App\Models\Ordenacion;

use App\Models\BaseModel;
use App\Models\Usuario;
use App\Models\Posgrado;

class DocenciaPosgrado extends BaseModel
{
    protected $table = 'docencia_posgrado';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = [
        'id_usuario',
        'id_posgrado',
        'creditos'
    ];
    
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
    
    public function posgrado()
    {
        return $this->belongsTo(Posgrado::class, 'id_posgrado', 'id_posgrado');
    }
}