<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

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
}