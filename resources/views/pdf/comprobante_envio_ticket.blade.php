<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ticket de Envío</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            width: 80mm;
        }

        .logo {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo img {
            width: 60px;
        }

        .titulo {
            text-align: center;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .seccion {
            margin-bottom: 10px;
        }

        .linea {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }
    </style>
</head>

<body>

    <div class="logo">
        <img src="{{ public_path('images/logo_popeyecargos.png') }}" alt="Logo">
    </div>

    <div class="titulo">ENVÍO: {{ $envio->codigo }}</div>

    <div class="seccion">
        <strong>Fecha:</strong> {{ $envio->created_at->format('d/m/Y H:i') }}<br>
        <strong>Remitente:</strong> {{ $envio->remitente->nombreCompleto ?? 'N/A' }}<br>
        <strong>Destinatario:</strong> {{ $envio->destinatario_nombre }}<br>
    </div>

    <div class="linea"></div>

    <div class="seccion">
        <strong>Origen:</strong><br>{{ $envio->direccion_origen }}<br>
        <strong>Destino:</strong><br>{{ $envio->direccion_destino }}
    </div>

    <div class="linea"></div>

    <div class="seccion">
        <strong>Peso:</strong> {{ $envio->peso_kg }} kg<br>
        <strong>Volumen:</strong> {{ $envio->volumen_m3 }} m³<br>
        <strong>Frágil:</strong> {{ $envio->es_fragil ? 'Sí' : 'No' }}
    </div>

    <div class="seccion" style="text-align: center;">
        Gracias por confiar en<br><strong>PopeyeCargos SAC</strong>
    </div>

</body>

</html>
