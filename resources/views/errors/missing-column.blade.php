<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Columna No Encontrada</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%);
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
            animation: bounce 1s infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .error-title {
            color: #e17055;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .error-message {
            color: #495057;
            font-size: 1.2rem;
            margin-bottom: 25px;
            line-height: 1.6;
        }
        .column-name {
            background: linear-gradient(45deg, #fdcb6e, #e17055);
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            font-size: 1.1rem;
            display: inline-block;
            margin: 15px 0;
            box-shadow: 0 4px 15px rgba(225, 112, 85, 0.3);
        }
        .solution-box {
            background: linear-gradient(45deg, #74b9ff, #0984e3);
            color: white;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            text-align: left;
            box-shadow: 0 4px 15px rgba(116, 185, 255, 0.3);
        }
        .solution-title {
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 15px;
        }
        .solution-text {
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
            max-height: 200px;
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
        <div class="error-icon">⚠️</div>
        
        <h1 class="error-title">Columna Faltante</h1>
        
        <div class="error-message">
            La aplicación está intentando acceder a una columna que no existe en la base de datos:
        </div>
        
        <div class="column-name">{{ $columnName }}</div>
        
        <div class="solution-box">
            <div class="solution-title">💡 Solución</div>
            <div class="solution-text">
                <strong>Esta columna es necesaria para el funcionamiento de la aplicación.</strong><br><br>
                
                Probablemente esta columna se añadió en una actualización reciente.<br>
                <strong>Ejecuta el script SQL de migración</strong> que se proporcionó con la aplicación para añadir todas las columnas faltantes.
            </div>
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