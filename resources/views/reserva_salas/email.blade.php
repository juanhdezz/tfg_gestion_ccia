@php
// Primero definimos el contenido del mensaje según el estado
if ($estado === 'Validada') {
    $mensaje = "
        <h2>Su reserva de sala ha sido aprobada</h2>
        <p>Estimado/a {$usuario->nombre} {$usuario->apellidos},</p>
        <p>Nos complace informarle que su solicitud de reserva ha sido <strong>aprobada</strong>.</p>
        <h3>Detalles de la reserva:</h3>
        <ul>
            <li><strong>Sala:</strong> {$reserva->sala->nombre}</li>
            <li><strong>Fecha:</strong> {$reserva->fecha->format('d/m/Y')}</li>
            <li><strong>Hora:</strong> De {$reserva->hora_inicio->format('H:i')} a {$reserva->hora_fin->format('H:i')}</li>
            <li><strong>Motivo:</strong> {$reserva->motivo->descripcion}</li>
        </ul>
        <p>Si necesita realizar algún cambio, por favor contacte con administración.</p>
        <p>Saludos cordiales,<br>Administración de Salas</p>
    ";
} else {
    $mensaje = "
        <h2>Su reserva de sala ha sido rechazada</h2>
        <p>Estimado/a {$usuario->nombre} {$usuario->apellidos},</p>
        <p>Lamentamos informarle que su solicitud de reserva ha sido <strong>rechazada</strong>.</p>
        <h3>Detalles de la reserva solicitada:</h3>
        <ul>
            <li><strong>Sala:</strong> {$reserva->sala->nombre}</li>
            <li><strong>Fecha:</strong> {$reserva->fecha->format('d/m/Y')}</li>
            <li><strong>Hora:</strong> De {$reserva->hora_inicio->format('H:i')} a {$reserva->hora_fin->format('H:i')}</li>
            <li><strong>Motivo:</strong> {$reserva->motivo->descripcion}</li>
        </ul>
        <p><strong>Motivo del rechazo:</strong> {$reserva->observaciones}</p>
        <p>Si necesita más información, por favor contacte con administración.</p>
        <p>Saludos cordiales,<br>Administración de Salas</p>
    ";
}
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Sala</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            color: #333;
        }
        h2 {
            color: #2c5282;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 10px;
        }
        h3 {
            color: #4a5568;
            margin-top: 20px;
        }
        p {
            color: #555;
            margin-bottom: 15px;
        }
        ul {
            list-style-type: none;
            padding: 0;
            margin: 15px 0;
        }
        li {
            margin-bottom: 10px;
            padding-left: 10px;
            border-left: 3px solid #e2e8f0;
        }
        .container {
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            font-size: 0.9em;
            color: #718096;
        }
        .aprobada {
            color: #48bb78;
            font-weight: bold;
        }
        .rechazada {
            color: #f56565;
            font-weight: bold;
        }
        .motivo-rechazo {
            background-color: #fff5f5;
            border-left: 4px solid #f56565;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Sistema de Gestión de Reservas</h1>
        </div>
        
        {!! $mensaje !!}
        
        <div class="footer">
            <p>Este es un mensaje automático, por favor no responda a este correo.</p>
            <p>© {{ date('Y') }} Centro de Ciencias de la Ingeniería y Aplicadas</p>
        </div>
    </div>
</body>
</html>