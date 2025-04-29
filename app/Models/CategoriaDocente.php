<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaDocente extends Model
{
    protected $table = 'categoria';
    protected $primaryKey = 'id_categoria';
    public $timestamps = false;

    /**
     * Relación con los usuarios que pertenecen a esta categoría
     */
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_categoria', 'id_categoria');
    }
}