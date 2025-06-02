<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @if($estado == 'Aceptado')
            Solicitud de libro aprobada
        @elseif($estado == 'Biblioteca')
            Libro disponible en biblioteca
        @else
            Solicitud de libro denegada
        @endif
    </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }        .header {
            background-color: 
                @if($estado == 'Aceptado')
                    #4CAF50
                @elseif($estado == 'Biblioteca')
                    #2196F3
                @else
                    #F44336
                @endif
            ;
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
<body>    <div class="header">
        <h1>
            @if($estado == 'Aceptado')
                Su solicitud de libro ha sido aprobada
            @elseif($estado == 'Biblioteca')
                Su libro está disponible en biblioteca
            @else
                Su solicitud de libro ha sido denegada
            @endif
        </h1>
    </div>
    
    <div class="content">
        <p>Estimado/a {{ $usuario->nombre }} {{ $usuario->apellidos }},</p>
          <p>
            Le informamos que su solicitud para el libro <strong>"{{ $libro->titulo }}"</strong> ha sido 
            <strong>
                @if($estado == 'Aceptado')
                    APROBADA
                @elseif($estado == 'Biblioteca')
                    PROCESADA y el libro está DISPONIBLE EN BIBLIOTECA
                @else
                    DENEGADA
                @endif
            </strong>.
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
        @elseif ($estado == 'Biblioteca')
            <p>
                <strong>¡Buenas noticias!</strong> El libro que solicitó ya está disponible en la biblioteca del departamento 
                y puede pasar a recogerlo cuando guste durante el horario de atención.
            </p>
            <p>
                Recuerde traer su identificación para poder retirar el libro.
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