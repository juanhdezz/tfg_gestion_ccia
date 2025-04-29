<?php

namespace App\Models\Ordenacion;

use App\Models\BaseModel;

class TesisDpto extends BaseModel
{
    protected $table = 'tesis_dpto';
    protected $primaryKey = 'id_tesis';
    public $timestamps = false;
    
    protected $fillable = [
        'doctorando',
        'fecha_lectura'
    ];
    
    public function compensaciones()
    {
        return $this->hasMany(CompensacionTesis::class, 'id_tesis', 'id_tesis');
    }
}