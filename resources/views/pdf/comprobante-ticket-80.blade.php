<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ticket 80mm</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
        }

        .center {
            text-align: center;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 4px 0;
        }
    </style>
</head>

<body>

    <div class="center">
        <strong>PopeyeCargos SAC</strong><br>
        RUC: 20123456789<br>
        {{ strtoupper($comprobante->tipo_comprobante) }}<br>
        {{ $comprobante->serie }}-{{ $comprobante->correlativo }}
    </div>

    <div class="line"></div>
    Fecha: {{ \Carbon\Carbon::parse($comprobante->fecha_emision)->format('d/m/Y H:i') }}<br>
    Cliente: {{ $comprobante->cliente_nombre }}<br>
    {{ $comprobante->cliente_tipo_documento }}: {{ $comprobante->cliente_numero_documento }}<br>

    <div class="line"></div>
    Servicio de traslado #{{ $comprobante->envio_id }}<br>
    Total: S/ {{ number_format($comprobante->total, 2) }}

    <div class="line"></div>
    <div class="center">{{ now()->format('d/m/Y H:i') }}</div>

</body>

</html>
