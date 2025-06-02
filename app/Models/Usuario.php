<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Config;
use App\Models\Ordenacion\CompensacionProyecto;


class Usuario extends Authenticatable
{
    use Notifiable, HasRoles; // añadimos hasRoles para que spatie maneje los roles de usuario
    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre', 
        'apellidos', 
        'nombre_abreviado', 
        'dni_pasaporte', 
        'correo', 
        'foto', 
        'id_despacho', 
        'telefono_despacho', 
        'telefono', 
        'ip_asociada', 
        'toma_red', 
        'mantiene_numero', 
        'uid_fotocopy', 
        'clave_fotocopy', 
        'login', 
        'passwd', 
        'imparte_docencia', 
        'miembro_actual', 
        'miembro_total', 
        'miembro_consejo', 
        'tipo_usuario', 
        'user_last_login'
    ];

    protected $hidden = ['passwd', 'remember_token'];  
    protected $casts = [
        'user_last_login' => 'datetime',
    ]; 

    public function username()
    {
        return 'login';
    }

    public function getAuthPassword()
    {
        //return bcrypt($this->passwd); // encriptamos la contraseña ya que en la base de datos esta guardada sin encriptar
        return $this->passwd;
    }

    public function despacho()
{
    return $this->belongsTo(Despacho::class, 'id_despacho');
}

public function getConnectionName()
    {
        return Config::get('database.default');
    }    /**
     * Relación con miembros.
     * Un usuario puede tener múltiples membresías (en diferentes grupos y categorías).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function miembros()
    {
        return $this->hasMany(Miembro::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Relación con CategoriaDocente a través de la tabla miembro.
     * Un usuario puede tener múltiples categorías según sus membresías.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categoriasDocentes()
    {
        return $this->belongsToMany(
            CategoriaDocente::class,
            'miembro',
            'id_usuario',
            'id_categoria',
            'id_usuario',
            'id_categoria'
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
     * Un usuario puede pertenecer a múltiples grupos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function grupos()
    {
        return $this->belongsToMany(
            Grupo::class,
            'miembro',
            'id_usuario',
            'id_grupo',
            'id_usuario',
            'id_grupo'
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
     * Método para obtener la membresía actual en un grupo específico.
     *
     * @param int $grupoId
     * @return \App\Models\Miembro|null
     */
    public function miembroEnGrupo($grupoId)
    {
        return $this->miembros()->where('id_grupo', $grupoId)->first();
    }

    /**
     * Método para obtener todas las membresías ordenadas por número de orden.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function miembrosOrdenados()
    {
        return $this->miembros()->ordenadoPorNumero()->get();
    }

    public function compensacionesProyecto()
{
    return $this->hasMany(CompensacionProyecto::class, 'id_usuario', 'id_usuario');
}
}