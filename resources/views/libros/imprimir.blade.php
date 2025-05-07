
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
    <style>
        @media print {
            @page {
                size: portrait;
                margin: 1cm;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 24px;
            color: #4F46E5;
            margin: 5px 0;
        }
        
        .header p {
            font-size: 14px;
            color: #666;
            margin: 5px 0;
        }
        
        .section-title {
            font-size: 18px;
            margin-top: 30px;
            background-color: #f3f4f6;
            padding: 10px;
            border-radius: 4px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 14px;
        }
        
        table, th, td {
            border: 1px solid #ddd;
        }
        
        th {
            background-color: #f9fafb;
            padding: 10px;
            text-align: left;
        }
        
        td {
            padding: 8px;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
        
        .estado-pendiente {
            background-color: #FEF3C7;
        }
        
        .estado-aceptado {
            background-color: #D1FAE5;
        }
        
        .estado-denegado {
            background-color: #FEE2E2;
        }
        
        .estado-recibido {
            background-color: #DBEAFE;
        }

        .btn-imprimir {
            display: inline-block;
            margin: 20px 0;
            padding: 10px 15px;
            background-color: #4F46E5;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $titulo }}</h1>
        <p>Departamento de Ciencias de la Computación e Inteligencia Artificial</p>
        <p>Fecha de generación: {{ $fechaActual }}</p>
    </div>

    <button class="btn-imprimir no-print" onclick="window.print()">Imprimir documento</button>
    
    @if(count($librosAsignatura) > 0)
    <h2 class="section-title">Libros para Asignaturas</h2>
    <table>
        <thead>
            <tr>
                <th>Libro</th>
                <th>Autor</th>
                <th>ISBN</th>
                <th>Asignatura</th>
                <th>Solicitante</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($librosAsignatura as $solicitud)
            <tr class="estado-{{ strtolower(str_replace(' ', '-', $solicitud->estado ?? 'pendiente')) }}">
                <td>{{ $solicitud->libro->titulo ?? 'No disponible' }}</td>
                <td>{{ $solicitud->libro->autor ?? 'No disponible' }}</td>
                <td>{{ $solicitud->libro->isbn ?? 'No disponible' }}</td>
                <td>{{ $solicitud->asignatura->nombre_asignatura ?? 'No especificado' }}</td>
                <td>{{ ($solicitud->usuario->nombre ?? 'Usuario') . ' ' . ($solicitud->usuario->apellidos ?? '') }}</td>
                <td>{{ $solicitud->fecha_solicitud ? $solicitud->fecha_solicitud->format('d/m/Y') : 'No disponible' }}</td>
                <td>{{ $solicitud->estado ?? 'Pendiente' }}</td>
                <td>{{ $solicitud->precio ?? '0' }}€ ({{ $solicitud->num_ejemplares ?? '1' }} uds.)</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if(count($librosProyecto) > 0)
    <h2 class="section-title">Libros para Proyectos</h2>
    <table>
        <thead>
            <tr>
                <th>Libro</th>
                <th>Autor</th>
                <th>ISBN</th>
                <th>Proyecto</th>
                <th>Solicitante</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($librosProyecto as $solicitud)
            <tr class="estado-{{ strtolower(str_replace(' ', '-', $solicitud->estado ?? 'pendiente')) }}">
                <td>{{ $solicitud->libro->titulo ?? 'No disponible' }}</td>
                <td>{{ $solicitud->libro->autor ?? 'No disponible' }}</td>
                <td>{{ $solicitud->libro->isbn ?? 'No disponible' }}</td>
                <td>{{ $solicitud->proyecto->titulo ?? 'No especificado' }}</td>
                <td>{{ ($solicitud->usuario->nombre ?? 'Usuario') . ' ' . ($solicitud->usuario->apellidos ?? '') }}</td>
                <td>{{ $solicitud->fecha_solicitud ? $solicitud->fecha_solicitud->format('d/m/Y') : 'No disponible' }}</td>
                <td>{{ $solicitud->estado ?? 'Pendiente' }}</td>
                <td>{{ $solicitud->precio ?? '0' }}€ ({{ $solicitud->num_ejemplares ?? '1' }} uds.)</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    @if(count($librosGrupo) > 0)
    <h2 class="section-title">Libros para Grupos de Investigación</h2>
    <table>
        <thead>
            <tr>
                <th>Libro</th>
                <th>Autor</th>
                <th>ISBN</th>
                <th>Grupo</th>
                <th>Solicitante</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($librosGrupo as $solicitud)
            <tr class="estado-{{ strtolower(str_replace(' ', '-', $solicitud->estado ?? 'pendiente')) }}">
                <td>{{ $solicitud->libro->titulo ?? 'No disponible' }}</td>
                <td>{{ $solicitud->libro->autor ?? 'No disponible' }}</td>
                <td>{{ $solicitud->libro->isbn ?? 'No disponible' }}</td>
                <td>{{ $solicitud->grupo->nombre_grupo ?? 'No especificado' }}</td>
                <td>{{ ($solicitud->usuario->nombre ?? 'Usuario') . ' ' . ($solicitud->usuario->apellidos ?? '') }}</td>
                <td>{{ $solicitud->fecha_solicitud ? $solicitud->fecha_solicitud->format('d/m/Y') : 'No disponible' }}</td>
                <td>{{ $solicitud->estado ?? 'Pendiente' }}</td>
                <td>{{ $solicitud->precio ?? '0' }}€ ({{ $solicitud->num_ejemplares ?? '1' }} uds.)</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    @if(count($librosPosgrado) > 0)
    <h2 class="section-title">Libros para Posgrados</h2>
    <table>
        <thead>
            <tr>
                <th>Libro</th>
                <th>Autor</th>
                <th>ISBN</th>
                <th>Posgrado</th>
                <th>Solicitante</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($librosPosgrado as $solicitud)
            <tr class="estado-{{ strtolower(str_replace(' ', '-', $solicitud->estado ?? 'pendiente')) }}">
                <td>{{ $solicitud->libro->titulo ?? 'No disponible' }}</td>
                <td>{{ $solicitud->libro->autor ?? 'No disponible' }}</td>
                <td>{{ $solicitud->libro->isbn ?? 'No disponible' }}</td>
                <td>{{ $solicitud->posgrado->nombre ?? 'No especificado' }}</td>
                <td>{{ ($solicitud->usuario->nombre ?? 'Usuario') . ' ' . ($solicitud->usuario->apellidos ?? '') }}</td>
                <td>{{ $solicitud->fecha_solicitud ? $solicitud->fecha_solicitud->format('d/m/Y') : 'No disponible' }}</td>
                <td>{{ $solicitud->estado ?? 'Pendiente' }}</td>
                <td>{{ $solicitud->precio ?? '0' }}€ ({{ $solicitud->num_ejemplares ?? '1' }} uds.)</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    @if(count($librosOtros) > 0)
    <h2 class="section-title">Libros para Otros Fondos</h2>
    <table>
        <thead>
            <tr>
                <th>Libro</th>
                <th>Autor</th>
                <th>ISBN</th>
                <th>Solicitante</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($librosOtros as $solicitud)
            <tr class="estado-{{ strtolower(str_replace(' ', '-', $solicitud->estado ?? 'pendiente')) }}">
                <td>{{ $solicitud->libro->titulo ?? 'No disponible' }}</td>
                <td>{{ $solicitud->libro->autor ?? 'No disponible' }}</td>
                <td>{{ $solicitud->libro->isbn ?? 'No disponible' }}</td>
                <td>{{ ($solicitud->usuario->nombre ?? 'Usuario') . ' ' . ($solicitud->usuario->apellidos ?? '') }}</td>
                <td>{{ $solicitud->fecha_solicitud ? $solicitud->fecha_solicitud->format('d/m/Y') : 'No disponible' }}</td>
                <td>{{ $solicitud->estado ?? 'Pendiente' }}</td>
                <td>{{ $solicitud->precio ?? '0' }}€ ({{ $solicitud->num_ejemplares ?? '1' }} uds.)</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    @if(count($librosAsignatura) === 0 && count($librosProyecto) === 0 && count($librosGrupo) === 0 && count($librosPosgrado) === 0 && count($librosOtros) === 0)
    <div style="text-align: center; padding: 40px;">
        <p>No se encontraron resultados para los criterios de búsqueda especificados.</p>
    </div>
    @endif
    
    <div class="footer">
        <p>Universidad de Granada - Departamento de Ciencias de la Computación e Inteligencia Artificial</p>
        <p>Sistema de Gestión de Solicitudes de Libros</p>
    </div>
    
    <script>
        // Script para auto-imprimir
        document.addEventListener('DOMContentLoaded', function() {
            // Añadir un pequeño retraso para asegurar que todo está cargado
            setTimeout(function() {
                // Si estamos en una vista específica para imprimir, mostrar el diálogo de impresión automáticamente
                if (window.location.href.includes('imprimir')) {
                    // window.print();
                }
            }, 1000);
        });
    </script>
</body>
</html>