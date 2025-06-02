# Cambios Realizados en la Configuración de Ordenación Docente

## Resumen
Se han reemplazado las variables estáticas por parámetros configurables en el controlador `OrdenacionDocenteController.php`.

## Variables Reemplazadas

### 1. Variables estáticas eliminadas:
- `$docencia25` (calculada como 25% de los créditos de docencia)
- `$docencia50` (calculada como 50% de los créditos de docencia)

### 2. Nuevos métodos configurables:
- `calcularLimitesDocentes($creditosDocencia)` - Calcula límites basados en porcentajes configurables
- `getCreditosMenosPermitidos()` - Obtiene créditos permitidos por debajo del mínimo

## Claves de Configuración Utilizadas

### Implementadas en este cambio:
- `porcentaje_limite_menor` (predeterminado: 25) - Para calcular el límite del 25%
- `porcentaje_limite_mayor` (predeterminado: 50) - Para calcular el límite del 50%
- `creditos_menos_permitidos` (predeterminado: 0.5) - Créditos por debajo permitidos
- `identificador_tfm` (predeterminado: 'TFM') - Identificador para trabajos fin de máster

## Métodos Modificados

### En `OrdenacionDocenteController.php`:

1. **`obtenerReducciones()`**
   - Eliminado cálculo estático de `$docencia25` y `$docencia50`
   - Agregado `$limitesDocentes = $this->calcularLimitesDocentes($creditosDocencia)`
   - Reemplazadas referencias estáticas por `$limitesDocentes['menor']` y `$limitesDocentes['mayor']`

2. **`muestraReducciones()`**
   - Eliminado cálculo estático de porcentajes
   - Agregado uso de `$limitesDocentes` configurables
   - Actualizada llamada a `generarTablaPosgrado()` y `aplicarRestriccionesGlobales()`

3. **`generarTablaPosgrado()`**
   - Cambiado parámetro `$docencia25` por `$limitesDocentes`
   - Actualizada lógica de restricciones para usar límites configurables

4. **`aplicarRestriccionesGlobales()`**
   - Cambiados parámetros `$docencia25, $docencia50` por `$limitesDocentes`
   - Actualizada lógica de restricciones globales

5. **`calcularLimitesDocentes()` (nuevo)**
   - Método que utiliza `ConfiguracionOrdenacion::calcularLimitesDocentes()`
   - Mantiene consistencia con el modelo

6. **`getCreditosMenosPermitidos()` (actualizado)**
   - Ahora utiliza `ConfiguracionOrdenacion::getCreditosMenosPermitidos()`

## Imports Agregados
- `use App\Models\Ordenacion\ConfiguracionOrdenacion;`

## Beneficios

1. **Flexibilidad**: Los porcentajes ahora son configurables desde la base de datos
2. **Mantenibilidad**: Cambios de porcentajes no requieren modificaciones de código
3. **Consistencia**: Uso del modelo `ConfiguracionOrdenacion` para centralizar la lógica
4. **Escalabilidad**: Fácil agregar nuevos parámetros configurables

## Verificación
- ✅ Eliminadas todas las referencias a variables estáticas `$docencia25` y `$docencia50`
- ✅ Implementado uso de parámetros configurables
- ✅ Mantenida funcionalidad existente
- ✅ Consistencia con el modelo `ConfiguracionOrdenacion`
- ✅ Sin errores de sintaxis introducidos

## Archivos Modificados
- `app/Http/Controllers/OrdenacionDocenteController.php`
