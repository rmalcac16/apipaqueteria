<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Guía de Remisión (Ticket)</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            width: 100%;
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
        <strong>GUÍA DE REMISIÓN</strong><br>
    </div>

    <div class="line"></div>
    <strong>Conductor:</strong><br>
    {{ $guia->conductor_nombre }} ({{ $guia->conductor_dni }})<br>
    Licencia: {{ $guia->conductor_licencia }}<br>

    <strong>Vehículo:</strong><br>
    Placa: {{ $guia->vehiculo_placa }}<br>

    <strong>Traslado:</strong><br>
    {{ \Carbon\Carbon::parse($guia->fecha_inicio_traslado)->format('d/m/Y H:i') }}<br>
    De: {{ $guia->punto_partida_direccion }}<br>
    A: {{ $guia->punto_llegada_direccion }}<br>

    <strong>Carga:</strong><br>
    {{ $guia->descripcion_carga }}<br>
    Peso: {{ $guia->peso_total }} {{ $guia->unidad_medida }}<br>

    <strong>Remitente:</strong><br>
    {{ $guia->remitente_nombre }}<br>
    <strong>Destinatario:</strong><br>
    {{ $guia->destinatario_nombre }}<br>

    <strong>Pagador:</strong><br>
    @if ($guia->pagador_tipo === 'tercero')
        {{ $guia->pagador_nombre_razon_social }}<br>
    @else
        {{ ucfirst($guia->pagador_tipo) }}
    @endif

    <div class="line"></div>
    <div class="center">
        {{ now()->format('d/m/Y H:i') }}
    </div>
    @if ($guia->documentosSustento && $guia->documentosSustento->count())
        <div class="line"></div>
        <strong>Sustento:</strong><br>
        @foreach ($guia->documentosSustento as $doc)
            {{ $doc->tipo_documento }}: {{ $doc->serie_numero }}<br>
            RUC: {{ $doc->ruc_emisor }}<br>
        @endforeach
    @endif


</body>

</html>
