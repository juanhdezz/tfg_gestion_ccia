<!-- filepath: /resources/views/ordenacion/partials/reducciones.blade.php -->
<div class="p-2">
    @if($creditos_compensacion > 0)
        <div class="bg-blue-100 dark:bg-blue-900 border-l-4 border-blue-500 text-blue-700 dark:text-blue-100 p-4 mb-4">
            <p>Total de créditos de compensación: <strong>{{ $creditos_compensacion }}</strong></p>
        </div>
        <p class="text-gray-700 dark:text-gray-300 mb-4">
            Para ver el detalle de sus compensaciones docentes contacte con el responsable del departamento.
        </p>
    @else
        <div class="bg-yellow-100 dark:bg-yellow-900 border-l-4 border-yellow-500 text-yellow-700 dark:text-yellow-100 p-4">
            Actualmente no cuenta con ningún tipo de compensación docente.
        </div>
    @endif
</div>