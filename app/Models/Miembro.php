<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Miembro extends Model
{
    /**
     * La tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'miembro';    /**
     * La clave primaria de la tabla.
     * Como la tabla tiene clave compuesta, la desactivamos para Laravel
     *
     * @var string|null
     */
    protected $primaryKey = 'id_usuario';

    /**
     * Indica que la clave primaria no es auto-incremental
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indica si el modelo debe tener timestamps (created_at/updated_at).
     *
     * @var bool
     */
    public $timestamps = false;    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array
     */    protected $fillable = [
        'id_usuario',
        'id_grupo',
        'id_categoria',
        'web',
        'numero_orden',
        'tramos_investigacion',
        'anio_ultimo_tramo',
        'fecha_entrada',
        'n_orden_becario'
    ];/**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array
     */    protected $casts = [
        
        'numero_orden' => 'integer',
        'tramos_investigacion' => 'integer',
        'anio_ultimo_tramo' => 'integer',
        'n_orden_becario' => 'integer'
    ];

    /**
     * Relación con el modelo Usuario.
     * Un miembro pertenece a un usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Relación con el modelo Grupo.
     * Un miembro pertenece a un grupo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'id_grupo', 'id_grupo');
    }

    /**
     * Relación con el modelo CategoriaDocente.
     * Un miembro pertenece a una categoría docente.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoriaDocente()
    {
        return $this->belongsTo(CategoriaDocente::class, 'id_categoria', 'id_categoria');
    }

    /**
     * Scope para filtrar miembros por grupo.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $grupoId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePorGrupo($query, $grupoId)
    {
        return $query->where('id_grupo', $grupoId);
    }

    /**
     * Scope para filtrar miembros por categoría.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $categoriaId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePorCategoria($query, $categoriaId)
    {
        return $query->where('id_categoria', $categoriaId);
    }

    /**
     * Scope para ordenar por número de orden.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdenadoPorNumero($query, $direction = 'asc')
    {
        return $query->orderBy('numero_orden', $direction);
    }

    /**
     * Scope para filtrar miembros con web.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeConWeb($query)
    {
        return $query->whereNotNull('web')->where('web', '!=', '');
    }

    /**
     * Accessor para formatear la fecha de entrada.
     *
     * @return string|null
     */
    public function getFechaEntradaFormateadaAttribute()
    {
        return $this->fecha_entrada ? $this->fecha_entrada : null;
    }    /**
     * Método para obtener el nombre completo del usuario.
     *
     * @return string
     */
    public function getNombreCompletoAttribute()
    {
        return $this->usuario ? $this->usuario->nombre . ' ' . $this->usuario->apellidos : '';
    }

    /**
     * Scope para obtener miembros con despacho asignado (para selección de admin).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeConDespacho($query)
    {
        return $query->whereHas('usuario', function($q) {
            $q->whereNotNull('id_despacho');
        });
    }
}
