<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SolicitudLibroTrait;

class LibroProyecto extends Model
{
    use SolicitudLibroTrait;
    
    protected $table = 'libro_proyecto';
    //protected $primaryKey = ['id_libro', 'id_usuario', 'fecha_solicitud'];
    protected $primaryKey = null; // Cambia esto si es necesario
    public $incrementing = false;
    public $timestamps = false;    protected $fillable = [
        'id_libro',
        'id_usuario',
        'id_proyecto',
        'precio',
        'num_ejemplares',
        'estado',
        'observaciones',
        'fecha_solicitud',
        'fecha_aceptado_denegado',
        'fecha_pedido',
        'fecha_recepcion',
        'justificacion'
    ];    protected $casts = [
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
     * Obtiene el proyecto para el que se solicitó el libro
     */
     public function proyecto()
     {
         return $this->belongsTo(Proyecto::class, 'id_proyecto');
     }

    /**
     * Verifica si el usuario puede aprobar esta solicitud
     */
    public function puedeSerAprobadaPor($idUsuario)
    {
        // Verificar si el usuario es el IP (Investigador Principal) del proyecto
        $proyecto = $this->proyecto;
        return $proyecto && $proyecto->id_ip == $idUsuario;
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
        $keyNames = $this->getKeyName();
        
        if (is_array($keyNames)) {
            foreach ($keyNames as $keyName) {
                $key[$keyName] = $this->getAttribute($keyName);
            }
            return $key;
        }
        
        return $this->getAttribute($keyNames);
    }
}