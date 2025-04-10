<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $estado == 'Aceptado' ? 'Solicitud de libro aprobada' : 'Solicitud de libro denegada' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: {{ $estado == 'Aceptado' ? '#4CAF50' : '#F44336' }};
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
        }
        .content {
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .book-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $estado == 'Aceptado' ? 'Su solicitud de libro ha sido aprobada' : 'Su solicitud de libro ha sido denegada' }}</h1>
    </div>
    
    <div class="content">
        <p>Estimado/a {{ $usuario->nombre }} {{ $usuario->apellidos }},</p>
        
        <p>
            Le informamos que su solicitud para el libro <strong>"{{ $libro->titulo }}"</strong> ha sido 
            <strong>{{ $estado == 'Aceptado' ? 'APROBADA' : 'DENEGADA' }}</strong>.
        </p>
        
        <div class="book-details">
            <h3>Detalles del libro:</h3>
            <p><strong>Título:</strong> {{ $libro->titulo }}</p>
            <p><strong>Autor:</strong> {{ $libro->autor }}</p>
            <p><strong>Editorial:</strong> {{ $libro->editorial }}</p>
            <p><strong>ISBN:</strong> {{ $libro->isbn }}</p>
        </div>
        
        @if ($estado == 'Aceptado')
            <p>
                El libro ha sido solicitado y se le notificará cuando esté disponible para su recogida.
            </p>
        @else
            <p>
                Si tiene alguna pregunta sobre esta decisión, por favor contacte con el director del departamento.
            </p>
        @endif
        
        <p>Gracias por utilizar nuestro sistema de gestión de libros.</p>
    </div>
    
    <div class="footer">
        <p>Este es un mensaje automático, por favor no responda a este correo.</p>
        <p>Departamento de Ciencias de la Computación e Inteligencia Artificial, Universidad de Granada</p>
    </div>
</body>
</html>