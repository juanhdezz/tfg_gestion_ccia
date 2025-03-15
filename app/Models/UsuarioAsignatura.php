<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioAsignatura extends BaseModel
{
    use HasFactory;

    protected $table = 'usuario_asignatura';
    
    // Usar un enfoque diferente para claves compuestas
    protected $primaryKey = null;
    public $incrementing = false;
    
    // Inhabilitamos las marcas de tiempo si no las usas
    public $timestamps = false;
    
    protected $fillable = [
        'id_asignatura',
        'id_usuario',
        'tipo',
        'grupo',
        'creditos',
        'antiguedad',
        'en_primera_fase'
    ];
    
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
    
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'id_asignatura', 'id_asignatura');
    }
}