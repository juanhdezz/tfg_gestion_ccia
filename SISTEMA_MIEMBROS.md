# Sistema de Miembros - Documentación

## Descripción General

El sistema de miembros maneja la relación many-to-many entre `Usuario`, `Grupo` y `CategoriaDocente` a través de la tabla `miembro`. Esta tabla intermedia permite que un usuario pueda pertenecer a múltiples grupos con diferentes categorías, y almacena información adicional importante para el proceso de ordenación docente.

## Estructura de la Tabla Miembro

```sql
CREATE TABLE miembro (
    id_usuario INT,
    id_grupo INT,
    id_categoria INT,
    web VARCHAR(255),
    numero_orden INT,
    tramos_investigacion INT,
    anio_ultimo_tramo INT,
    fecha_entrada DATE,
    n_orden_becario INT,
    PRIMARY KEY (id_usuario, id_grupo, id_categoria)
);
```

## Modelos y Relaciones

### 1. Modelo Miembro

El modelo `Miembro` es el núcleo del sistema que conecta usuarios con grupos y categorías.

**Relaciones principales:**
- `usuario()`: Pertenece a un Usuario
- `grupo()`: Pertenece a un Grupo  
- `categoriaDocente()`: Pertenece a una CategoriaDocente

**Scopes útiles:**
- `porGrupo($grupoId)`: Filtra miembros por grupo
- `porCategoria($categoriaId)`: Filtra miembros por categoría
- `ordenadoPorNumero($direction)`: Ordena por número de orden
- `conWeb()`: Filtra miembros que tienen web

### 2. Modelo Usuario (Actualizado)

**Nuevas relaciones agregadas:**
- `miembros()`: Todas las membresías del usuario
- `categoriasDocentes()`: Categorías a través de la tabla miembro
- `grupos()`: Grupos a través de la tabla miembro

**Métodos útiles:**
- `miembroEnGrupo($grupoId)`: Obtiene la membresía en un grupo específico
- `miembrosOrdenados()`: Obtiene membresías ordenadas por número

### 3. Modelo CategoriaDocente (Actualizado)

**Nuevas relaciones agregadas:**
- `miembros()`: Todos los miembros de esta categoría
- `usuarios()`: Usuarios a través de la tabla miembro  
- `grupos()`: Grupos a través de la tabla miembro

**Métodos útiles:**
- `miembrosOrdenados()`: Miembros ordenados por número
- `miembrosEnGrupo($grupoId)`: Miembros de la categoría en un grupo específico

### 4. Modelo Grupo (Actualizado)

**Nuevas relaciones agregadas:**
- `miembros()`: Todos los miembros del grupo
- `usuarios()`: Usuarios a través de la tabla miembro
- `categoriasDocentes()`: Categorías a través de la tabla miembro

**Métodos útiles:**
- `miembrosOrdenados()`: Miembros ordenados por número
- `miembrosPorCategoria($categoriaId)`: Miembros del grupo por categoría

## Ejemplos de Uso

### Crear un nuevo miembro

```php
use App\Models\Miembro;

$miembro = Miembro::create([
    'id_usuario' => 1,
    'id_grupo' => 2,
    'id_categoria' => 3,
    'numero_orden' => 1,
    'tramos_investigacion' => 2,
    'anio_ultimo_tramo' => 2023,
    'fecha_entrada' => '2020-01-15',
    'web' => 'https://ejemplo.com',
    'n_orden_becario' => null
]);
```

### Obtener miembros de un grupo ordenados

```php
use App\Models\Grupo;

$grupo = Grupo::find(1);
$miembrosOrdenados = $grupo->miembrosOrdenados();

// O usando el modelo Miembro directamente
$miembros = Miembro::porGrupo(1)->ordenadoPorNumero()->get();
```

### Obtener todas las categorías de un usuario

```php
use App\Models\Usuario;

$usuario = Usuario::find(1);
$categorias = $usuario->categoriasDocentes()->get();

// Acceder a datos del pivot
foreach ($categorias as $categoria) {
    echo "Número de orden: " . $categoria->pivot->numero_orden;
    echo "Grupo ID: " . $categoria->pivot->id_grupo;
}
```

### Obtener miembros de una categoría en un grupo específico

```php
use App\Models\CategoriaDocente;

$categoria = CategoriaDocente::find(1);
$miembrosEnGrupo = $categoria->miembrosEnGrupo(2);
```

### Buscar un miembro específico

```php
use App\Models\Miembro;

$miembro = Miembro::where('id_usuario', 1)
                 ->where('id_grupo', 2)
                 ->where('id_categoria', 3)
                 ->with(['usuario', 'grupo', 'categoriaDocente'])
                 ->first();

if ($miembro) {
    echo $miembro->nombreCompleto; // Accessor definido en el modelo
    echo $miembro->fechaEntradaFormateada; // Accessor para fecha formateada
}
```

### Actualizar el orden de miembros

```php
use App\Models\Miembro;

// Actualizar número de orden de un miembro específico
Miembro::where('id_usuario', 1)
      ->where('id_grupo', 2)
      ->where('id_categoria', 3)
      ->update(['numero_orden' => 5]);
```

### Obtener miembros con eager loading

```php
use App\Models\Miembro;

// Cargar todas las relaciones de una vez
$miembros = Miembro::with(['usuario', 'grupo', 'categoriaDocente'])
                  ->ordenadoPorNumero()
                  ->get();

foreach ($miembros as $miembro) {
    echo $miembro->usuario->nombre . ' - ';
    echo $miembro->grupo->nombre_grupo . ' - ';
    echo $miembro->categoriaDocente->nombre_categoria;
}
```

## Controlador de Ejemplo

Se ha creado `MiembroController` que incluye:

- **CRUD completo** para gestionar miembros
- **Filtrado por grupo y categoría**
- **Actualización de orden** de miembros
- **Validaciones** apropiadas
- **Manejo de claves compuestas**

## Consideraciones Importantes

1. **Clave Primaria Compuesta**: La tabla miembro usa una clave primaria compuesta (id_usuario, id_grupo, id_categoria), lo que significa que un usuario no puede tener la misma categoría dos veces en el mismo grupo.

2. **Número de Orden**: Es crucial para el proceso de ordenación docente. Debe ser único dentro de cada grupo-categoría.

3. **Validaciones**: Se deben validar que:
   - No existan duplicados de usuario-grupo-categoría
   - Los números de orden sean coherentes
   - Las fechas sean válidas

4. **Performance**: Al trabajar con relaciones many-to-many, usar eager loading (`with()`) para evitar el problema N+1.

5. **Integridad**: Considerar el uso de transacciones al crear/actualizar múltiples miembros simultáneamente.

## Migraciones Recomendadas

Si necesitas crear la tabla miembro, aquí está la migración sugerida:

```php
Schema::create('miembro', function (Blueprint $table) {
    $table->unsignedBigInteger('id_usuario');
    $table->unsignedBigInteger('id_grupo');
    $table->unsignedBigInteger('id_categoria');
    $table->string('web')->nullable();
    $table->integer('numero_orden')->nullable();
    $table->integer('tramos_investigacion')->nullable();
    $table->integer('anio_ultimo_tramo')->nullable();
    $table->date('fecha_entrada')->nullable();
    $table->integer('n_orden_becario')->nullable();
    
    $table->primary(['id_usuario', 'id_grupo', 'id_categoria']);
    
    $table->foreign('id_usuario')->references('id_usuario')->on('usuario');
    $table->foreign('id_grupo')->references('id_grupo')->on('grupo');
    $table->foreign('id_categoria')->references('id_categoria')->on('categoria');
    
    $table->index(['id_grupo', 'numero_orden']);
    $table->index(['id_categoria', 'numero_orden']);
});
```
