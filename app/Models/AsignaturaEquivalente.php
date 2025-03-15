<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignaturaEquivalente extends BaseModel
{
    use HasFactory;

    protected $table = 'asignaturas_equivalentes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'asignatura_id',
        'equivalente_id',
    ];

    public $timestamps = false;

    /**
     * Relación con la asignatura original.
     */
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'asignatura_id');
    }

    /**
     * Relación con la asignatura equivalente.
     */
    public function equivalente()
    {
        return $this->belongsTo(Asignatura::class, 'equivalente_id');
    }
}
