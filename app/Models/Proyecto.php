<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ordenacion\CompensacionProyecto;

class Proyecto extends BaseModel
{
    protected $table = 'proyecto';
    protected $primaryKey = 'id_proyecto';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'creditos_compensacion_proyecto',
        'id_responsable',
        'titulo',
        'fecha_inicio',
        'fecha_fin',
        'activo',
        'nombre_corto',
        'web',
        'financiacion'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'activo' => 'boolean',
        'creditos_compensacion_proyecto' => 'float'
    ];

    /**
     * Obtiene el usuario responsable del proyecto (IP)
     */
    public function responsable()
    {
        return $this->belongsTo(Usuario::class, 'id_responsable', 'id_usuario');
    }

    /**
 * Obtiene todas las compensaciones asociadas a este proyecto
 */
public function compensaciones()
{
    return $this->hasMany(CompensacionProyecto::class, 'id_proyecto', 'id_proyecto');
}

    /**
     * Obtiene todos los usuarios vinculados al proyecto
     */
    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'usuario_proyecto', 'id_proyecto', 'id_usuario')
                    ->withPivot('rol', 'fecha_inicio', 'fecha_fin');
    }

    /**
     * Obtiene todas las solicitudes de libros asociadas a este proyecto
     */
    public function solicitudesLibro()
    {
        return $this->hasMany(LibroProyecto::class, 'id_proyecto');
    }

    /**
     * Comprueba si un proyecto está activo
     */
    public function estaActivo()
    {
        return $this->activo == true;
    }

    /**
     * Comprueba si un usuario es el responsable del proyecto
     */
    public function esResponsable($idUsuario)
    {
        return $this->id_responsable == $idUsuario;
    }

    public function responsableTieneCompensacion()
{
    if (!$this->id_responsable) {
        return false;
    }
    
    return $this->compensaciones()
                ->where('id_usuario', $this->id_responsable)
                ->exists();
}

    /**
     * Comprueba si un usuario es miembro del proyecto
     */
    public function esMiembro($idUsuario)
    {
        return $this->usuarios()->where('usuario.id_usuario', $idUsuario)->exists();
    }

    /**
     * Calcula si el proyecto está vigente basado en las fechas de inicio y fin
     */
    public function estaVigente()
    {
        $hoy = now();
        
        if ($this->fecha_inicio && $this->fecha_fin) {
            return $hoy->between($this->fecha_inicio, $this->fecha_fin);
        } elseif ($this->fecha_inicio) {
            return $hoy->gte($this->fecha_inicio);
        } elseif ($this->fecha_fin) {
            return $hoy->lte($this->fecha_fin);
        }
        
        return true; // Si no hay fechas, asumimos que está vigente
    }

    /**
     * Devuelve el estado del proyecto (Activo/No activo)
     */
    public function getEstadoAttribute()
    {
        return $this->activo ? 'Activo' : 'No activo';
    }

    /**
     * Obtiene la duración del proyecto en meses
     */
    public function getDuracionMesesAttribute()
    {
        if ($this->fecha_inicio && $this->fecha_fin) {
            return $this->fecha_inicio->diffInMonths($this->fecha_fin);
        }
        return null;
    }

    // Obtiene numero de creditos de compensación asignados al proyecto
    public function getCreditosCompensacionAttribute()
    {
        return $this->creditos_compensacion_proyecto;
    }
    
}