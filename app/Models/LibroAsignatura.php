<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SolicitudLibroTrait;

class LibroAsignatura extends Model
{
    use SolicitudLibroTrait;
    
    protected $table = 'libro_asignatura';
    // Clave primaria compuesta que incluye fecha_solicitud
    //protected $primaryKey = ['id_libro', 'id_usuario', 'fecha_solicitud'];
    protected $primaryKey = null; // Cambia esto si es necesario
    public $incrementing = false;
    public $timestamps = false;    protected $fillable = [
        'id_libro',
        'id_usuario',
        'id_asignatura',
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
     * Establece los atributos que no pueden asignarse masivamente.
     */
    protected $guarded = [];

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
     * Obtiene la asignatura para la que se solicitó el libro
     */
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'id_asignatura');
    }

    /**
     * Verifica si el usuario puede aprobar esta solicitud
     */
    
    
    /**
     * Método para permitir usar múltiples campos como clave primaria en Laravel
     * ya que por defecto Eloquent no maneja bien las claves primarias compuestas.
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
        $keyNames = (array)$this->getKeyName();
        foreach ($keyNames as $keyName) {
            $key[$keyName] = $this->getAttribute($keyName);
        }
        return $key;
    }
}