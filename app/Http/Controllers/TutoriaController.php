<?php

namespace App\Http\Controllers;

use App\Models\Tutoria;
use App\Models\Despacho;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TutoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Determinar el cuatrimestre actual basado en la fecha
        $mes = Carbon::now()->month;
        $cuatrimestreActual = ($mes >= 9 || $mes <= 2) ? 1 : 2;

        // Obtener el cuatrimestre seleccionado (o usar el actual)
        $cuatrimestreSeleccionado = $request->input('cuatrimestre', $cuatrimestreActual);

        // Obtener el despacho seleccionado (o usar el del usuario actual si existe)
        $despachoSeleccionado = $request->input('despacho', Auth::user()->id_despacho ?? null);

        $tutorias = collect(); // Esto hará que no se muestren tutorías preseleccionadas

        // Obtener todos los despachos (para el selector)
        $despachos = Despacho::all();

        // Definir las horas del día (de 08:00 a 21:30 en intervalos de 30 min)
        $horas = [];
        $horaInicio = 8 * 60; // 8:00 en minutos
        $horaFin = 21 * 60 + 30; // 21:30 en minutos

        for ($minutos = $horaInicio; $minutos < $horaFin; $minutos += 30) {
            $inicio = sprintf('%02d:%02d', floor($minutos / 60), $minutos % 60);
            $fin = sprintf('%02d:%02d', floor(($minutos + 30) / 60), ($minutos + 30) % 60);

            $horas[] = [
                'inicio' => $inicio,
                'fin' => $fin
            ];
        }

        // Definir los días de la semana
        // Definir los días de la semana
        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

        return view('tutorias.index', compact(
            'tutorias',
            'despachos',
            'horas',
            'diasSemana',
            'cuatrimestreActual',
            'cuatrimestreSeleccionado',
            'despachoSeleccionado'
        ));
    }

    /**
     * Actualizar todas las tutorías de una vez
     */
    public function actualizar(Request $request)
    {
        $idDespacho = $request->input('id_despacho');
        $cuatrimestre = $request->input('cuatrimestre');
        $tutoriasData = $request->input('tutorias', []);

        // Primero, eliminar todas las tutorías existentes para este despacho y cuatrimestre
        Tutoria::where('id_despacho', $idDespacho)
            ->where('cuatrimestre', $cuatrimestre)
            ->delete();

        // Luego, crear las nuevas tutorías seleccionadas
        foreach ($tutoriasData as $dia => $horarios) {
            foreach ($horarios as $inicio => $fines) {
                foreach ($fines as $fin => $seleccionada) {
                    if ($seleccionada == '1') {
                        Tutoria::create([
                            'id_usuario' => Auth::id(),
                            'id_despacho' => $idDespacho,
                            'cuatrimestre' => $cuatrimestre,
                            'dia' => $dia, // Aquí $dia ya es "Lunes", "Martes", etc.
                            'inicio' => $inicio,
                            'fin' => $fin
                        ]);
                    }
                }
            }
        }

        // Redirigir a la vista de visualización
        return redirect()->route('tutorias.ver', [
            'despacho' => $idDespacho,
            'cuatrimestre' => $cuatrimestre
        ])->with('success', 'Horario de tutorías actualizado correctamente');
    }

    /**
     * Ver las tutorías guardadas (vista de solo lectura)
     */
    public function verTutorias(Request $request)
    {
        // Determinar el cuatrimestre actual basado en la fecha
        $mes = Carbon::now()->month;
        $cuatrimestreActual = ($mes >= 9 || $mes <= 2) ? 1 : 2;

        // Obtener el cuatrimestre seleccionado (o usar el actual)
        $cuatrimestreSeleccionado = $request->input('cuatrimestre', $cuatrimestreActual);

        // Obtener el despacho seleccionado (o usar el del usuario actual si existe)
        $despachoSeleccionado = $request->input('despacho', Auth::user()->id_despacho ?? null);

        // Obtener tutorias para el despacho y cuatrimestre seleccionados
        $tutorias = Tutoria::where('id_despacho', $despachoSeleccionado)
            ->where('cuatrimestre', $cuatrimestreSeleccionado)
            ->get();

        // Formatear las horas y normalizar los nombres de los días
        foreach ($tutorias as $tutoria) {
            // Si la hora tiene formato HH:MM:SS, quitar los segundos
            if (strlen($tutoria->inicio) > 5) {
                $tutoria->inicio = substr($tutoria->inicio, 0, 5);
            }
            if (strlen($tutoria->fin) > 5) {
                $tutoria->fin = substr($tutoria->fin, 0, 5);
            }

            // Normalizar el nombre del día
            $tutoria->dia = ucfirst(strtolower(trim($tutoria->dia)));
        }

        // Obtener todos los despachos (para el selector)
        $despachos = Despacho::all();

        // Definir las horas del día (de 08:00 a 21:30 en intervalos de 30 min)
        $horas = [];
        $horaInicio = 8 * 60; // 8:00 en minutos
        $horaFin = 21 * 60 + 30; // 21:30 en minutos

        for ($minutos = $horaInicio; $minutos < $horaFin; $minutos += 30) {
            $inicio = sprintf('%02d:%02d', floor($minutos / 60), $minutos % 60);
            $fin = sprintf('%02d:%02d', floor(($minutos + 30) / 60), ($minutos + 30) % 60);

            $horas[] = [
                'inicio' => $inicio,
                'fin' => $fin
            ];
        }

        // Definir los días de la semana
        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

        return view('tutorias.ver', compact(
            'tutorias',
            'despachos',
            'horas',
            'diasSemana',
            'cuatrimestreActual',
            'cuatrimestreSeleccionado',
            'despachoSeleccionado'
        ));
    }



    // Los métodos store y destroy ya no son necesarios con este nuevo enfoque,
    // pero puedes mantenerlos por si acaso o para compatibilidad
}
