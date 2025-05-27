<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla No Encontrada</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            backdrop-filter: blur(10px);
        }
        .error-icon {
            font-size: 5rem;
            margin-bottom: 20px;
            animation: shake 0.5s ease-in-out;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        .error-title {
            color: #dc3545;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .error-message {
            color: #495057;
            font-size: 1.2rem;
            margin-bottom: 25px;
            line-height: 1.6;
        }
        .table-name {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            font-size: 1.1rem;
            display: inline-block;
            margin: 15px 0;
            box-shadow: 0 4px 15px rgba(238, 90, 36, 0.3);
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
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .solution-text {
            line-height: 1.6;
            font-size: 1rem;
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
        details {
            margin-top: 20px;
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
        <div class="error-icon">🚫</div>
        
        <h1 class="error-title">¡Tabla No Encontrada!</h1>
        
        <div class="error-message">
            La aplicación está intentando acceder a una tabla que no existe en tu base de datos:
        </div>
        
        <div class="table-name">{{ $tableName }}</div>
        
        <div class="solution-box">
            <div class="solution-title">
                💡 <span>Solución Rápida</span>
            </div>
            <div class="solution-text">
                <strong>Esta tabla es necesaria para el funcionamiento correcto de la aplicación.</strong><br><br>
                
                <strong>📋 Pasos a seguir:</strong><br>
                1️⃣ Abre <strong>phpMyAdmin</strong><br>
                2️⃣ Selecciona tu base de datos<br>
                3️⃣ Ve a la pestaña <strong>"SQL"</strong><br>
                4️⃣ Ejecuta las sentencias del <strong>archivo de migración SQL</strong><br>
                5️⃣ Recarga esta página<br><br>
                
                <em>El archivo de migración SQL se proporcionó junto con la aplicación.</em>
            </div>
        </div>
        
        <a href="javascript:history.back()" class="button">← Volver Atrás</a>
        <a href="javascript:location.reload()" class="button">🔄 Intentar de Nuevo</a>
        
        @if($fullError && config('app.debug'))
        <details>
            <summary>🔧 Ver Error Técnico Completo</summary>
            <div class="debug-info">
                {{ $fullError }}
            </div>
        </details>
        @endif
    </div>
</body>
</html>