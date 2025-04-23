<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Comprobante A4</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 6px;
            border: 1px solid #000;
            text-align: left;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .section {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>PopeyeCargos SAC</h2>
        <p>RUC: 20123456789</p>
        <strong>{{ strtoupper($comprobante->tipo_comprobante) }}
            {{ $comprobante->serie }}-{{ $comprobante->correlativo }}</strong>
    </div>

    <div class="section">
        <strong>Fecha de Emisión:</strong>
        {{ \Carbon\Carbon::parse($comprobante->fecha_emision)->format('d/m/Y H:i') }}<br>
        <strong>Moneda:</strong> {{ $comprobante->moneda }}
    </div>

    <div class="section">
        <strong>Cliente:</strong><br>
        {{ $comprobante->cliente_nombre }}<br>
        {{ $comprobante->cliente_tipo_documento }}: {{ $comprobante->cliente_numero_documento }}
    </div>

    <div class="section">
        <strong>Detalle del Servicio:</strong><br>
        Servicio de traslado relacionado al envío #{{ $comprobante->envio_id }}
    </div>

    <div class="section">
        <strong>Total:</strong> S/ {{ number_format($comprobante->total, 2) }}
    </div>

    <p style="text-align: right; font-size: 10px;">Generado el {{ now()->format('d/m/Y H:i') }}</p>

</body>

</html>
