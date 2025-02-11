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

}