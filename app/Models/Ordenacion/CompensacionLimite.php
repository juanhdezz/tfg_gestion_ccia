<?php

namespace App\Models\Ordenacion;

use App\Models\BaseModel;

class CompensacionLimite extends BaseModel
{
    protected $table = 'compensacion_limite';
    protected $primaryKey = 'concepto';
    public $incrementing = false;
    public $keyType = 'string';
    public $timestamps = false;
    
    protected $fillable = [
        'concepto',
        'limite_creditos'
    ];
}