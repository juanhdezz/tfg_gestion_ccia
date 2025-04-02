<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class ReservaSala extends BaseModel
{
    use HasFactory;

    protected $table = 'reserva_sala'; // Nombre exacto de la tabla
    
    public $timestamps = false; // La tabla no tiene created_at y updated_at
    
    // Esta tabla tiene clave primaria compuesta
    public $incrementing = false; // No es autoincremental
    protected $primaryKey = null; // No definimos un único campo como PK
    
    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'id_sala',
        'id_usuario',
        'id_motivo',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'observaciones',
        'fecha_realizada',
        'estado',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'fecha' => 'date',
        'hora_inicio' => 'datetime',
        'hora_fin' => 'datetime',
        'fecha_realizada' => 'datetime',
    ];

    /**
     * Sobrescribir el método de eliminación para manejar claves primarias compuestas
     */
    public function delete()
    {
        // Para claves primarias compuestas, debemos hacer una consulta manual para eliminar
        return static::where('id_sala', $this->id_sala)
            ->where('fecha', $this->fecha)
            ->where('hora_inicio', $this->hora_inicio)
            ->where('estado', $this->estado)
            ->delete();
    }

    /**
     * Los valores por defecto para los atributos del modelo.
     *
     * @var array
     */
    protected $attributes = [
        'estado' => 'Validada',
    ];

    /**
     * Relación con la tabla sala (una reserva pertenece a una sala)
     */
    public function sala()
    {
        return $this->belongsTo(Sala::class, 'id_sala', 'id_sala');
    }

    /**
     * Relación con la tabla usuario (una reserva pertenece a un usuario)
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Relación con la tabla motivo (una reserva tiene un motivo)
     */
    public function motivo()
    {
        return $this->belongsTo(Motivo::class, 'id_motivo', 'id_motivo');
    }

    /**
     * Determina si la reserva está validada
     */
    public function estaValidada()
    {
        return $this->estado === 'Validada';
    }

    /**
     * Determina si la reserva está pendiente de validación
     */
    public function estaPendiente()
    {
        return $this->estado === 'Pendiente Validación';
    }

    /**
     * Determina si la reserva está rechazada
     */
    public function estaRechazada()
    {
        return $this->estado === 'Rechazada';
    }

    /**
     * Determina si la reserva está cancelada
     */
    public function estaCancelada()
    {
        return $this->estado === 'Cancelada';
    }
}

 