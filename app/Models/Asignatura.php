<?php
namespace App\Models;

use Faker\Provider\Base;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Asignatura extends BaseModel 
{
    use Notifiable;
    protected $table = 'asignatura';
    protected $primaryKey = 'id_asignatura';
    protected $keyType = 'string';  // Esto es crucial para IDs alfanuméricos
    public $incrementing = false;   // Desactivar autoincremento
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
        'grupos_teoria',
        'grupos_practicas',  
        'web_asignatura', 
        'tipo',
        'fraccionable',
        'estado'  
    ];

    public function titulacion()
    {
        return $this->belongsTo(Titulacion::class, 'id_titulacion', 'id_titulacion');
    }

    // Relación con el coordinador
    public function coordinador()
    {
        return $this->belongsTo(Usuario::class, 'id_coordinador', 'id_usuario');
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
        )->withPivot([]);
    }
    
    /**
     * Obtiene todas las equivalencias para una asignatura,
     * incluyendo las directas y las inversas
     */
    public function todasLasEquivalencias()
    {
        // Obtener IDs de todas las equivalencias directas
        $directas = $this->equivalencias()->pluck('id_asignatura')->toArray();
        
        // Obtener IDs de todas las equivalencias inversas
        $inversas = Asignatura::whereHas('equivalencias', function($query) {
            $query->where('equivalente_id', $this->id_asignatura);
        })->pluck('id_asignatura')->toArray();
        
        // Combinar y eliminar duplicados
        $idsEquivalencias = array_unique(array_merge($directas, $inversas));
        
        // Excluir la propia asignatura si está en la lista
        $idsEquivalencias = array_diff($idsEquivalencias, [$this->id_asignatura]);
        
        // Si no hay equivalencias, devolver colección vacía
        if (empty($idsEquivalencias)) {
            return collect();
        }
        
        // Devolver colección de asignaturas equivalentes
        return Asignatura::whereIn('id_asignatura', $idsEquivalencias)->get();
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
    

}