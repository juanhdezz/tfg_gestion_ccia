<?php

namespace App\Models\Ordenacion;

use App\Models\BaseModel;

class Turno extends BaseModel
{
    protected $table = 'turno';
    protected $primaryKey = 'turno';
    public $timestamps = false;
    
    protected $fillable = [
        'turno',
        'fase',
        'estado',
        'cursos_con_preferencia'
    ];
}