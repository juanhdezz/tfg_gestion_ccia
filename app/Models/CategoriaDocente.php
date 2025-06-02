<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaDocente extends Model
{
    protected $table = 'categoria';
    protected $primaryKey = 'id_categoria';    public $timestamps = false;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'nombre_categoria',
        'nombre_categoria_interno',
        'siglas_categoria',
        'miembro_consejo',
        'creditos_docencia',
        'categoria_visible',
        'orden',
        'numero_licencias'
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'miembro_consejo' => 'boolean',
        'categoria_visible' => 'boolean',
        'creditos_docencia' => 'integer',
        'orden' => 'integer',
        'numero_licencias' => 'integer'
    ];

    /**
     * Relación con miembros.
     * Una categoría puede tener múltiples miembros.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function miembros()
    {
        return $this->hasMany(Miembro::class, 'id_categoria', 'id_categoria');
    }

    /**
     * Relación con Usuario a través de la tabla miembro.
     * Una categoría puede tener múltiples usuarios.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function usuarios()
    {
        return $this->belongsToMany(
            Usuario::class,
            'miembro',
            'id_categoria',
            'id_usuario',
            'id_categoria',
            'id_usuario'
        )->withPivot([
            'id_grupo',
            'web',
            'numero_orden',
            'tramos_investigacion',
            'anio_ultimo_tramo',
            'fecha_entrada',
            'n_orden_becario'
        ]);
    }

    /**
     * Relación con Grupo a través de la tabla miembro.
     * Una categoría puede estar presente en múltiples grupos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function grupos()
    {
        return $this->belongsToMany(
            Grupo::class,
            'miembro',
            'id_categoria',
            'id_grupo',
            'id_categoria',
            'id_grupo'
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
    }    /**
     * Método para obtener miembros de un grupo específico.
     *
     * @param int $grupoId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function miembrosEnGrupo($grupoId)
    {
        return $this->miembros()->porGrupo($grupoId)->get();
    }

    /**
     * Scope para obtener solo categorías visibles.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisibles($query)
    {
        return $query->where('categoria_visible', true);
    }

    /**
     * Scope para ordenar por el campo orden.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdenadoPorOrden($query, $direction = 'asc')
    {
        return $query->orderBy('orden', $direction);
    }

    /**
     * Scope para filtrar categorías que son miembro del consejo.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMiembrosConsejo($query)
    {
        return $query->where('miembro_consejo', true);
    }

    /**
     * Accessor para obtener el nombre completo de la categoría.
     *
     * @return string
     */
    public function getNombreCompletoAttribute()
    {
        return $this->siglas_categoria ? "{$this->nombre_categoria} ({$this->siglas_categoria})" : $this->nombre_categoria;
    }

    /**
     * Método para verificar si la categoría es visible.
     *
     * @return bool
     */
    public function esVisible()
    {
        return $this->categoria_visible;
    }

    /**
     * Método para verificar si es miembro del consejo.
     *
     * @return bool
     */
    public function esMiembroConsejo()
    {
        return $this->miembro_consejo;
    }

    /**
     * Método para obtener los créditos de docencia permitidos.
     *
     * @return int
     */
    public function getCreditosDocencia()
    {
        return $this->creditos_docencia ?? 0;
    }
}