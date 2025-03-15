<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Plazo extends BaseModel
{
    use HasFactory;

    /**
     * Nombre de la tabla en la base de datos
     */
    protected $table = 'plazo';

    /**
     * Clave primaria
     */
    protected $primaryKey = 'id_plazo';

    /**
     * Indica si la clave primaria es auto-incremental
     */
    public $incrementing = true;

    /**
     * Indica si el modelo debe registrar timestamps (created_at, updated_at)
     */
    public $timestamps = false;

    /**
     * Atributos que pueden ser asignados masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre_plazo',
        'fecha_inicio',
        'fecha_fin',
        'descripcion'
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    /**
     * Determina si el plazo está actualmente en vigencia
     *
     * @return bool
     */
    public function estaActivo()
    {
        $hoy = Carbon::now()->startOfDay();
        return $this->fecha_inicio->startOfDay()->lte($hoy) && 
               $this->fecha_fin->endOfDay()->gte($hoy);
    }

    /**
     * Determina si el plazo ya ha pasado
     *
     * @return bool
     */
    public function haTerminado()
    {
        return $this->fecha_fin->endOfDay()->lt(Carbon::now());
    }

    /**
     * Determina si el plazo aún no ha comenzado
     *
     * @return bool
     */
    public function aNoHaComenzado()
    {
        return $this->fecha_inicio->startOfDay()->gt(Carbon::now());
    }

    /**
     * Obtiene el número de días restantes del plazo
     *
     * @return int|null
     */
    public function diasRestantes()
    {
        if ($this->haTerminado()) {
            return 0;
        }
        
        if ($this->aNoHaComenzado()) {
            return null; // Plazo aún no iniciado
        }
        
        return Carbon::now()->startOfDay()->diffInDays($this->fecha_fin->endOfDay(), false);
    }

    /**
     * Obtiene el porcentaje de tiempo transcurrido del plazo
     *
     * @return float|null
     */
    public function porcentajeTranscurrido()
    {
        if ($this->aNoHaComenzado()) {
            return 0;
        }
        
        if ($this->haTerminado()) {
            return 100;
        }
        
        $totalDias = $this->fecha_inicio->diffInDays($this->fecha_fin);
        $diasTranscurridos = $this->fecha_inicio->diffInDays(Carbon::now());
        
        if ($totalDias == 0) {
            return 100;
        }
        
        return min(100, round(($diasTranscurridos / $totalDias) * 100, 1));
    }

    /**
     * Obtiene el estado del plazo en formato de texto
     *
     * @return string
     */
    public function getEstadoAttribute()
    {
        if ($this->estaActivo()) {
            return 'Activo';
        } elseif ($this->haTerminado()) {
            return 'Finalizado';
        } else {
            return 'Pendiente';
        }
    }

    /**
     * Scope para filtrar plazos activos
     */
    public function scopeActivos($query)
    {
        $hoy = Carbon::now()->format('Y-m-d');
        return $query->where('fecha_inicio', '<=', $hoy)
                     ->where('fecha_fin', '>=', $hoy);
    }

    /**
     * Scope para filtrar plazos finalizados
     */
    public function scopeFinalizados($query)
    {
        $hoy = Carbon::now()->format('Y-m-d');
        return $query->where('fecha_fin', '<', $hoy);
    }

    /**
     * Scope para filtrar plazos pendientes
     */
    public function scopePendientes($query)
    {
        $hoy = Carbon::now()->format('Y-m-d');
        return $query->where('fecha_inicio', '>', $hoy);
    }

    /**
     * Scope para buscar plazos por nombre
     */
    public function scopeBuscar($query, $termino)
    {
        if ($termino) {
            return $query->where('nombre_plazo', 'like', "%{$termino}%")
                         ->orWhere('descripcion', 'like', "%{$termino}%");
        }
        return $query;
    }
}