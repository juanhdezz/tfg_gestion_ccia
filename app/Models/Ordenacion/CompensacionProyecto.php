<?php

namespace App\Models\Ordenacion;

use App\Models\BaseModel;
use App\Models\Usuario;
use App\Models\Proyecto;

class CompensacionProyecto extends BaseModel
{
    protected $table = 'compensacion_proyecto';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = [
        'id_usuario',
        'id_proyecto',
        'creditos_compensacion'
    ];
    
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
    
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto', 'id_proyecto');
    }
}