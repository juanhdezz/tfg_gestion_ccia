<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SolicitudLibroTrait;

class LibroOtro extends Model
{
    use SolicitudLibroTrait;
    
    protected $table = 'libro_otro';
    protected $primaryKey = ['id_libro', 'id_usuario', 'fecha_solicitud'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_libro',
        'id_usuario',
        'precio',
        'num_ejemplares',
        'estado',
        'observaciones',
        'fecha_solicitud',
        'fecha_aceptado_denegado',
        'fecha_pedido',
        'fecha_recepcion',
        'justificacion'
    ];

    protected $casts = [
        'fecha_solicitud' => 'date',
        'fecha_aceptado_denegado' => 'date',
        'fecha_pedido' => 'date',
        'fecha_recepcion' => 'date',
    ];

    /**
     * Obtiene el libro asociado a esta solicitud
     */
    public function libro()
    {
        return $this->belongsTo(Libro::class, 'id_libro');
    }

    /**
     * Obtiene el usuario que solicitó el libro
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    /**
     * Verifica si el usuario puede aprobar esta solicitud
     */
    public function puedeSerAprobadaPor($idUsuario)
    {
        // Verificar si el usuario es miembro de la dirección del departamento
        $usuario = Usuario::find($idUsuario);
        return $usuario && $usuario->esDirectorDepartamento();
    }
    
    /**
     * Método para permitir usar múltiples campos como clave primaria en Laravel
     */
    public function getKeyName()
    {
        return $this->primaryKey;
    }
    
    /**
     * Obtiene el valor de la clave primaria
     */
    public function getKey()
    {
        $key = [];
        foreach ($this->getKeyNames() as $keyName) {
            $key[$keyName] = $this->getAttribute($keyName);
        }
        return $key;
    }
    
}