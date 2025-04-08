<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends BaseModel
{
    /**
     * La tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'grupo';

    /**
     * La clave primaria asociada a la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'id_grupo';

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
        'id_responsable',
        'nombre_grupo',
        'siglas_grupo',
        'web',
        'logo',
        'publicaciones'
    ];

    /**
     * Obtiene el usuario responsable del grupo.
     */
    public function responsable()
    {
        return $this->belongsTo(Usuario::class, 'id_responsable', 'id_usuario');
    }


    /**
     * Obtiene las solicitudes de libros asociadas a este grupo.
     */
    public function solicitudesLibros()
    {
        return $this->hasMany(LibroGrupo::class, 'id_grupo');
    }

    /**
     * Verifica si un usuario es el responsable del grupo.
     *
     * @param int $idUsuario
     * @return bool
     */
    public function esResponsable($idUsuario)
    {
        return $this->id_responsable == $idUsuario;
    }
}