<?php

namespace App\Models\Ordenacion;

use App\Models\BaseModel;
use App\Models\Usuario;

class CompensacionSexenio extends BaseModel
{
    protected $table = 'compensacion_sexenio';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = [
        'id_usuario',
        'creditos_compensacion'
    ];
    
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}