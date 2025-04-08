<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SolicitudLibroTrait;

class LibroGrupo extends Model
{
    use SolicitudLibroTrait;
    
    protected $table = 'libro_grupo';
    protected $primaryKey = ['id_libro', 'id_usuario', 'fecha_solicitud'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_libro',
        'id_usuario',
        'id_grupo',
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
     * Obtiene el grupo de investigación para el que se solicitó el libro
     */
     public function grupo()
     {
         return $this->belongsTo(Grupo::class, 'id_grupo');
     }

    /**
     * Verifica si el usuario puede aprobar esta solicitud
     */
    public function puedeSerAprobadaPor($idUsuario)
    {
        // Verificar si el usuario es el director del grupo de investigación
        $grupo = $this->grupo;
        return $grupo && $grupo->id_director == $idUsuario;
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
        $primaryKeys = is_array($this->primaryKey) ? $this->primaryKey : [$this->primaryKey];
        foreach ($primaryKeys as $keyName) {
            $key[$keyName] = $this->getAttribute($keyName);
        }
        return $key;
    }
}