<?php

namespace App\Models\Ordenacion;

use App\Models\BaseModel;
use App\Models\Usuario;

class CompensacionOtros extends BaseModel
{
    protected $table = 'compensacion_otros';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = [
        'id_usuario',
        'id_concepto',
        'creditos_compensacion'
    ];
    
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
    
    public function concepto()
    {
        return $this->belongsTo(CompensacionOtrosConcepto::class, 'id_concepto', 'id_concepto');
    }
}