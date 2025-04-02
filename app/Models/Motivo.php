<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motivo extends BaseModel
{
    use HasFactory;

    protected $table = 'motivo'; // Nombre exacto de la tabla
    protected $primaryKey = 'id_motivo'; // Definir la clave primaria
    public $timestamps = false; // Si la tabla no tiene created_at y updated_at

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'descripcion',
        'tipo',
    ];

    /**
     * Los valores permitidos para el campo tipo.
     */
    const TIPO_TODOS = 'Todos';
    const TIPO_COORDINADOR_PROYECTOS = 'Coordinador Proyectos';
    const TIPO_DIRECCION = 'Direccion';
    const TIPO_COORDINADOR_MASTER = 'Coordinador Master';

    /**
     * Relación con las reservas de sala (un motivo puede tener muchas reservas)
     */
    public function reservasSala()
    {
        return $this->hasMany(ReservaSala::class, 'id_motivo', 'id_motivo');
    }

    /**
     * Determina si el motivo es para todos los usuarios
     */
    public function esParaTodos()
    {
        return $this->tipo === self::TIPO_TODOS;
    }

    /**
     * Determina si el motivo es solo para coordinadores de proyectos
     */
    public function esParaCoordinadorProyectos()
    {
        return $this->tipo === self::TIPO_COORDINADOR_PROYECTOS;
    }

    /**
     * Determina si el motivo es solo para dirección
     */
    public function esParaDireccion()
    {
        return $this->tipo === self::TIPO_DIRECCION;
    }

    /**
     * Determina si el motivo es solo para coordinadores de máster
     */
    public function esParaCoordinadorMaster()
    {
        return $this->tipo === self::TIPO_COORDINADOR_MASTER;
    }
}