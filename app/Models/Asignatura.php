<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Asignatura extends Model 
{
    use Notifiable;
    protected $table = 'asignatura';
    protected $primaryKey = 'id_asignatura';
    public $timestamps = false;

    protected $fillable = [
        'id_asignatura',
        'id_titulacion', 
        'especialidad', 
        'id_coordinador', 
        'nombre_asignatura', 
        'siglas_asignatura', 
        'curso', 
        'cuatrimestre', 
        'creditos_teoria', 
        'creditos_practicas', 
        'ects_teoria', 
        'ects_practicas',  
        'web_decsai', 
        'web_asignatura', 
        'enlace_temario', 
        'temario_teoria', 
        'temario_practicas', 
        'bibliografia', 
        'evaluacion', 
        'recomendaciones', 
        'tipo',
        'fraccionable',
        'estado'  
    ];

    public function titulacion()
    {
        return $this->belongsTo(Titulacion::class, 'id_titulacion', 'id_titulacion');
    }

    // Relación con los grupos de teoría y práctica
    public function grupos()
    {
        return $this->hasMany(GrupoTeoriaPractica::class, 'id_asignatura', 'id_asignatura');
    }

    /**
 * Las asignaturas equivalentes a esta asignatura
 */
public function equivalencias()
{
    return $this->belongsToMany(
        Asignatura::class,
        'asignaturas_equivalentes',
        'asignatura_id',
        'equivalente_id'
    );
}

/**
 * Las asignaturas que tienen esta asignatura como equivalente
 */
public function equivalenteDe()
{
    return $this->belongsToMany(
        Asignatura::class,
        'asignaturas_equivalentes',
        'equivalente_id',
        'asignatura_id'
    );
}

    /**
     * Obtener todas las asignaturas equivalentes (en ambas direcciones)
     */
    public function todasLasEquivalencias()
    {
        return $this->equivalencias->merge($this->equivalenteDe);
    }

}