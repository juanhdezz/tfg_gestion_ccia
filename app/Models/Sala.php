<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sala extends BaseModel
{
    use HasFactory;

    protected $table = 'sala'; // Nombre exacto de la tabla
    protected $primaryKey = 'id_sala'; // Definir la clave primaria
    public $timestamps = false; // Si la tabla no tiene created_at y updated_at

    protected $fillable = [
        'nombre',
        'localizacion',
        'dias_anticipacion_reserva',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'dias_anticipacion_reserva' => 'integer',
    ];

    /**
     * Relación con las reservas (una sala puede tener muchas reservas)
     * Descomenta y ajusta este método si existe la relación con reservas
     */
    
    public function reservas()
    {
        return $this->hasMany(ReservaSala::class, 'id_sala', 'id_sala');
    }
    
}