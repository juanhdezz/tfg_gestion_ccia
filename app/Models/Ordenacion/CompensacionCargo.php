<?php

namespace App\Models\Ordenacion;

use App\Models\BaseModel;
use App\Models\Usuario;

class CompensacionCargo extends BaseModel
{
    protected $table = 'compensacion_cargo';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = [
        'id_usuario',
        'id_cargo',
        'creditos_compensacion'
    ];
    
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
    
    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'id_cargo', 'id_cargo');
    }
}