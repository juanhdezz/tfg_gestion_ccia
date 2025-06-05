<?php
/**
 * Script de prueba para verificar el funcionamiento del sistema de restauración
 * automática de conexión de base de datos en el contexto de libros
 */

echo "=== PRUEBA DEL SISTEMA DE RESTAURACIÓN DE CONEXIÓN DB - LIBROS ===\n\n";

// Simular sesión de Laravel
$session = [];

// Función helper para simular Session::get
function session_get($key, $default = null) {
    global $session;
    return $session[$key] ?? $default;
}

// Función helper para simular Session::put  
function session_put($key, $value) {
    global $session;
    $session[$key] = $value;
}

// Función helper para simular Session::has
function session_has($key) {
    global $session;
    return isset($session[$key]);
}

// Función helper para simular Session::forget
function session_forget($key) {
    global $session;
    unset($session[$key]);
}

// Simular el método guardarConexionOriginal del LibroController
function guardarConexionOriginal() {
    if (!session_has('db_connection_original')) {
        session_put('db_connection_original', session_get('db_connection', 'mysql'));
        echo "✓ Conexión original guardada: " . session_get('db_connection_original') . "\n";
    } else {
        echo "- Conexión original ya existe: " . session_get('db_connection_original') . "\n";
    }
}

// Simular el middleware RestoreDatabaseConnection
function middlewareRestore($routeName) {
    $librosRoutes = [
        'libros.index', 'libros.create', 'libros.store', 'libros.aprobar',
        'libros.denegar', 'libros.recibir', 'libros.biblioteca', 
        'libros.agotado', 'libros.imprimir'
    ];
    
    $tutoriasRoutes = [
        'tutorias.gestion', 'tutorias.index', 'tutorias.ver',
        'tutorias.plazos', 'tutorias.actualizar'
    ];
    
    $contextRoutes = array_merge($tutoriasRoutes, $librosRoutes);
    
    if (!in_array($routeName, $contextRoutes) && session_has('db_connection_original')) {
        if ($routeName !== 'cambiar.base.datos') {
            $conexionOriginal = session_get('db_connection_original');
            session_put('db_connection', $conexionOriginal);
            session_forget('db_connection_original');
            echo "✓ Conexión restaurada automáticamente a: $conexionOriginal\n";
            return true;
        }
    }
    return false;
}

// Simular DatabaseController::cambiarBaseDatos
function cambiarBaseDatos($connection, $context = 'general') {
    if (in_array($connection, ['mysql', 'mysql_proximo'])) {
        if (in_array($context, ['tutorias', 'libros']) && !session_has('db_connection_original')) {
            session_put('db_connection_original', session_get('db_connection', 'mysql'));
            echo "✓ Conexión original guardada por DatabaseController: " . session_get('db_connection_original') . "\n";
        }
        session_put('db_connection', $connection);
        echo "✓ Conexión cambiada a: $connection\n";
    }
}

// === INICIO DE PRUEBAS ===

echo "1. Estado inicial:\n";
session_put('db_connection', 'mysql'); // Conexión por defecto
echo "   - Conexión actual: " . session_get('db_connection') . "\n";
echo "   - Conexión original guardada: " . (session_has('db_connection_original') ? session_get('db_connection_original') : 'No') . "\n\n";

echo "2. Usuario entra en libros.index:\n";
guardarConexionOriginal();
echo "   - Conexión actual: " . session_get('db_connection') . "\n";
echo "   - Conexión original guardada: " . session_get('db_connection_original') . "\n\n";

echo "3. Usuario cambia a próximo curso académico desde contexto libros:\n";
cambiarBaseDatos('mysql_proximo', 'libros');
echo "   - Conexión actual: " . session_get('db_connection') . "\n";
echo "   - Conexión original guardada: " . session_get('db_connection_original') . "\n\n";

echo "4. Usuario continúa en operaciones de libros (libros.aprobar):\n";
guardarConexionOriginal(); // No debería guardar de nuevo
echo "   - Conexión actual: " . session_get('db_connection') . "\n";
echo "   - Conexión original guardada: " . session_get('db_connection_original') . "\n\n";

echo "5. Usuario sale del contexto de libros (va a dashboard):\n";
$restored = middlewareRestore('dashboard');
echo "   - Restauración ejecutada: " . ($restored ? 'Sí' : 'No') . "\n";
echo "   - Conexión actual: " . session_get('db_connection') . "\n";
echo "   - Conexión original guardada: " . (session_has('db_connection_original') ? session_get('db_connection_original') : 'No') . "\n\n";

echo "6. Usuario entra nuevamente en libros con conexión ya restaurada:\n";
guardarConexionOriginal();
echo "   - Conexión actual: " . session_get('db_connection') . "\n";
echo "   - Conexión original guardada: " . (session_has('db_connection_original') ? session_get('db_connection_original') : 'No') . "\n\n";

echo "=== PRUEBA COMPLETADA ===\n";
echo "El sistema debería:\n";
echo "1. ✓ Guardar la conexión original al entrar en contexto libros\n";
echo "2. ✓ Mantener la conexión original durante operaciones en libros\n";
echo "3. ✓ Restaurar automáticamente al salir del contexto\n";
echo "4. ✓ Permitir nuevas operaciones sin interferencias\n";
