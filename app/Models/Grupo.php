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
    ];    /**
     * Obtiene el usuario responsable del grupo.
     */
    public function responsable()
    {
        return $this->belongsTo(Usuario::class, 'id_responsable', 'id_usuario');
    }

    /**
     * Relación con miembros.
     * Un grupo puede tener múltiples miembros.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function miembros()
    {
        return $this->hasMany(Miembro::class, 'id_grupo', 'id_grupo');
    }

    /**
     * Relación con Usuario a través de la tabla miembro.
     * Un grupo puede tener múltiples usuarios.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function usuarios()
    {
        return $this->belongsToMany(
            Usuario::class,
            'miembro',
            'id_grupo',
            'id_usuario',
            'id_grupo',
            'id_usuario'
        )->withPivot([
            'id_categoria',
            'web',
            'numero_orden',
            'tramos_investigacion',
            'anio_ultimo_tramo',
            'fecha_entrada',
            'n_orden_becario'
        ]);
    }

    /**
     * Relación con CategoriaDocente a través de la tabla miembro.
     * Un grupo puede tener múltiples categorías docentes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categoriasDocentes()
    {
        return $this->belongsToMany(
            CategoriaDocente::class,
            'miembro',
            'id_grupo',
            'id_categoria',
            'id_grupo',
            'id_categoria'
        )->withPivot([
            'id_usuario',
            'web',
            'numero_orden',
            'tramos_investigacion',
            'anio_ultimo_tramo',
            'fecha_entrada',
            'n_orden_becario'
        ]);
    }

    /**
     * Método para obtener miembros ordenados por número de orden.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function miembrosOrdenados()
    {
        return $this->miembros()->ordenadoPorNumero()->get();
    }

    /**
     * Método para obtener miembros de una categoría específica.
     *
     * @param int $categoriaId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function miembrosPorCategoria($categoriaId)
    {
        return $this->miembros()->porCategoria($categoriaId)->get();
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