<?php

namespace App\Models\Ordenacion;

use App\Models\BaseModel;

class CompensacionOtrosConcepto extends BaseModel
{
    protected $table = 'compensacion_otros_concepto';
    protected $primaryKey = 'id_concepto';
    public $timestamps = false;
    
    protected $fillable = [
        'nombre_concepto',
        'tipo'
    ];
    
    public function compensaciones()
    {
        return $this->hasMany(CompensacionOtros::class, 'id_concepto', 'id_concepto');
    }
}