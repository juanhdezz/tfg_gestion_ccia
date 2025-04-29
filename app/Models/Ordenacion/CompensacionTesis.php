<?php

namespace App\Models\Ordenacion;

use App\Models\BaseModel;
use App\Models\Usuario;

class CompensacionTesis extends BaseModel
{
    protected $table = 'compensacion_tesis';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = [
        'id_usuario',
        'id_tesis',
        'creditos_compensacion'
    ];
    
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
    
    public function tesis()
    {
        return $this->belongsTo(TesisDpto::class, 'id_tesis', 'id_tesis');
    }
}