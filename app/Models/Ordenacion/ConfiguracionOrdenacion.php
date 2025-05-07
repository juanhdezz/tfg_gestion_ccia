<?php

namespace App\Models\Ordenacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;


class ConfiguracionOrdenacion extends BaseModel
{
    use HasFactory;

    /**
     * Nombre de la tabla en la base de datos
     *
     * @var string
     */
    protected $table = 'configuracion_ordenacion';

    /**
     * Clave primaria de la tabla
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'clave',
        'valor',
        'descripcion'
    ];

    /**
     * Indica si los campos de tiempo (created_at y updated_at) deben ser automáticamente gestionados
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     * Obtener un valor de configuración específico
     *
     * @param string $clave
     * @param mixed $valorPredeterminado
     * @return mixed
     */
    public static function obtenerValor($clave, $valorPredeterminado = null)
    {
        $config = self::where('clave', $clave)->first();
        return $config ? $config->valor : $valorPredeterminado;
    }

    /**
     * Establecer un valor de configuración
     *
     * @param string $clave
     * @param string $valor
     * @param string|null $descripcion
     * @return ConfiguracionOrdenacion
     */
    public static function establecerValor($clave, $valor, $descripcion = null)
    {
        $config = self::updateOrCreate(
            ['clave' => $clave],
            [
                'valor' => $valor,
                'descripcion' => $descripcion
            ]
        );

        return $config;
    }

    /**
     * Verificar si existe una configuración
     *
     * @param string $clave
     * @return bool
     */
    public static function existeClave($clave)
    {
        return self::where('clave', $clave)->exists();
    }

    /**
     * Obtener un valor numérico (float) de configuración
     *
     * @param string $clave
     * @param float $valorPredeterminado
     * @return float
     */
    public static function obtenerValorNumerico($clave, $valorPredeterminado = 0.0)
    {
        $valor = self::obtenerValor($clave, $valorPredeterminado);
        return is_numeric($valor) ? floatval($valor) : $valorPredeterminado;
    }

    /**
     * Obtener un valor booleano de configuración
     *
     * @param string $clave
     * @param bool $valorPredeterminado
     * @return bool
     */
    public static function obtenerValorBooleano($clave, $valorPredeterminado = false)
    {
        $valor = strtolower(self::obtenerValor($clave, $valorPredeterminado ? '1' : '0'));
        return in_array($valor, ['1', 'true', 'si', 'yes', 'on']);
    }

    /**
     * Método específico para obtener los créditos permitidos por debajo
     *
     * @return float
     */
    public static function getCreditosMenosPermitidos()
    {
        return self::obtenerValorNumerico('creditos_menos_permitidos', 0.5);
    }

    /**
     * Método específico para obtener los porcentajes de límites docentes
     *
     * @param float $creditosDocencia
     * @return array
     */
    public static function calcularLimitesDocentes($creditosDocencia)
    {
        $porcentajeMenor = self::obtenerValorNumerico('porcentaje_limite_menor', 25) / 100;
        $porcentajeMayor = self::obtenerValorNumerico('porcentaje_limite_mayor', 50) / 100;
        
        return [
            'menor' => $creditosDocencia * $porcentajeMenor,
            'mayor' => $creditosDocencia * $porcentajeMayor
        ];
    }

    /**
     * Método específico para obtener el identificador de TFM
     *
     * @return string
     */
    public static function getIdentificadorTFM()
    {
        return self::obtenerValor('identificador_tfm', 'TFM');
    }
}