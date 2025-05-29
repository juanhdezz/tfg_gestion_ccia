<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutoria extends BaseModel
{
    use HasFactory;
    
    protected $table = 'tutoria';
    protected $primaryKey = 'id_tutoria';
    //Pon los timestamps en false
    public $timestamps = false;
    
    protected $fillable = [
        'id_usuario',
        'cuatrimestre',
        'inicio',
        'fin',
        'dia',
        'id_despacho',
    ];
      /**
     * Relación con usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
    
    /**
     * Relación con despacho
     */
    public function despacho()
    {
        return $this->belongsTo(Despacho::class, 'id_despacho', 'id_despacho');
    }
}