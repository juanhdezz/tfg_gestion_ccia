<?php

namespace App\Models\Ordenacion;

use App\Models\BaseModel;

class Cargo extends BaseModel
{
    protected $table = 'cargo';
    protected $primaryKey = 'id_cargo';
    public $timestamps = false;
    
    protected $fillable = [
        'nombre_cargo',
        'comision',
        'tipo'
    ];
    
    public function compensaciones()
    {
        return $this->hasMany(CompensacionCargo::class, 'id_cargo', 'id_cargo');
    }
}