<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->boot();

use App\Models\Asignatura;

echo "Verificando ordenamiento de asignaturas:\n";
echo "=====================================\n\n";

$asignaturas = Asignatura::with(['titulacion', 'coordinador'])
    ->where('estado', 'Activa')
    ->join('titulacion', 'asignatura.id_titulacion', '=', 'titulacion.id_titulacion')
    ->orderByRaw("CASE WHEN titulacion.nombre_titulacion LIKE 'Master%' THEN 1 ELSE 0 END")
    ->orderBy('asignatura.nombre_asignatura')
    ->select('asignatura.*', 'titulacion.nombre_titulacion')
    ->get();

$grados = [];
$masters = [];

foreach($asignaturas as $asignatura) {
    $titulacion = $asignatura->titulacion ? $asignatura->titulacion->nombre_titulacion : 'Sin titulación';
    if (strpos($titulacion, 'Master') === 0) {
        $masters[] = $titulacion . ' - ' . $asignatura->nombre_asignatura;
    } else {
        $grados[] = $titulacion . ' - ' . $asignatura->nombre_asignatura;
    }
}

echo "GRADOS (primeros 10):\n";
foreach(array_slice($grados, 0, 10) as $grado) {
    echo "  " . $grado . "\n";
}

echo "\nMÁSTERES (primeros 10):\n";
foreach(array_slice($masters, 0, 10) as $master) {
    echo "  " . $master . "\n";
}

echo "\nResumen:\n";
echo "- Grados: " . count($grados) . "\n";
echo "- Másteres: " . count($masters) . "\n";
echo "- Total: " . ($asignaturas->count()) . "\n";
