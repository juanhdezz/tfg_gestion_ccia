<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error de Base de Datos</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #a29bfe 0%, #6c5ce7 100%);
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .error-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            padding: 40px;
            text-align: center;
            max-width: 600px;
            width: 100%;
        }
        .error-icon {
            font-size: 5rem;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        .error-title {
            color: #6c5ce7;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .error-message {
            color: #495057;
            font-size: 1.2rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .button {
            background: linear-gradient(45deg, #00b894, #00a085);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 184, 148, 0.3);
        }
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 184, 148, 0.4);
        }
        .debug-info {
            background-color: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-top: 25px;
            text-align: left;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            color: #6c757d;
            max-height: 300px;
            overflow-y: auto;
        }
        summary {
            cursor: pointer;
            color: #6c757d;
            font-weight: 600;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        summary:hover {
            background: #e9ecef;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">🔧</div>
        
        <h1 class="error-title">Error de Base de Datos</h1>
        
        <div class="error-message">
            Se ha producido un error al acceder a la base de datos.<br>
            Por favor, verifica la configuración y estructura de tu base de datos.
        </div>
        
        <a href="javascript:history.back()" class="button">← Volver Atrás</a>
        <a href="javascript:location.reload()" class="button">🔄 Intentar de Nuevo</a>
        
        @if($fullError && config('app.debug'))
        <details style="margin-top: 20px;">
            <summary>🔧 Ver Error Técnico Completo</summary>
            <div class="debug-info">
                {{ $fullError }}
            </div>
        </details>
        @endif
    </div>
</body>
</html>