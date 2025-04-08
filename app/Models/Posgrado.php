<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posgrado extends BaseModel
{
    /**
     * La tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'posgrado';

    /**
     * La clave primaria asociada a la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'id_posgrado';

    /**
     * Indica si el modelo debe tener timestamps (created_at/updated_at).
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'id_coordinador',
        'id_centro',
        'nombre',
        'codigo',
        'creditos',
        'web'
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'creditos' => 'float',
    ];

    /**
     * Obtiene el usuario coordinador del posgrado.
     */
    public function coordinador()
    {
        return $this->belongsTo(Usuario::class, 'id_coordinador', 'id_usuario');
    }

    /**
     * Obtiene el centro al que pertenece el posgrado.
     */
    public function centro()
    {
        return $this->belongsTo(Centro::class, 'id_centro', 'id_centro');
    }

    /**
     * Obtiene las solicitudes de libros asociadas a este posgrado.
     */
    public function solicitudesLibros()
    {
        return $this->hasMany(LibroPosgrado::class, 'id_posgrado');
    }

    /**
     * Verifica si un usuario es el coordinador del posgrado.
     *
     * @param int $idUsuario
     * @return bool
     */
    public function esCoordinador($idUsuario)
    {
        return $this->id_coordinador == $idUsuario;
    }

    
}