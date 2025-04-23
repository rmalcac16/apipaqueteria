<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>GRT - 58mm</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            width: 100%;
            padding: 0;
            margin: 0;
        }

        .center {
            text-align: center;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 4px 0;
        }

        .section {
            margin-bottom: 6px;
        }
    </style>
</head>

<body>
    <div class="center">
        <strong>PopeyeCargos SAC</strong><br>
        RUC: 20123456789<br>
        <strong>GUÍA DE REMISIÓN</strong>
    </div>

    <div class="line"></div>

    <div class="section">
        <strong>Conductor:</strong><br>
        {{ $guia->conductor_nombre }}<br>
        {{ $guia->conductor_tipo_documento }}: {{ $guia->conductor_dni }}<br>
        Licencia: {{ $guia->conductor_licencia }}
    </div>

    <div class="section">
        <strong>Vehículo:</strong><br>
        Placa: {{ $guia->vehiculo_placa }}<br>
        TUC: {{ $guia->vehiculo_tuc }}
    </div>

    <div class="section">
        <strong>Traslado:</strong><br>
        Fecha: {{ \Carbon\Carbon::parse($guia->fecha_inicio_traslado)->format('d/m/Y H:i') }}<br>
        De: {{ $guia->punto_partida_direccion }}<br>
        A: {{ $guia->punto_llegada_direccion }}
    </div>

    <div class="section">
        <strong>Carga:</strong><br>
        {{ $guia->descripcion_carga }}<br>
        Peso: {{ $guia->peso_total }} {{ $guia->unidad_medida }}
    </div>

    <div class="section">
        <strong>Remitente:</strong><br>
        {{ $guia->remitente_nombre }}
    </div>

    <div class="section">
        <strong>Destinatario:</strong><br>
        {{ $guia->destinatario_nombre }}
    </div>

    <div class="section">
        <strong>Pagador:</strong><br>
        @if ($guia->pagador_tipo === 'tercero')
            {{ $guia->pagador_nombre_razon_social }}<br>
            {{ $guia->pagador_tipo_documento }}: {{ $guia->pagador_numero_documento }}
        @else
            {{ ucfirst($guia->pagador_tipo) }}
        @endif
    </div>

    <div class="line"></div>
    <div class="center">
        {{ now()->format('d/m/Y H:i') }}
    </div>
    @if ($guia->documentosSustento && $guia->documentosSustento->count())
        <div class="section">
            <strong>Sustento:</strong><br>
            @foreach ($guia->documentosSustento as $doc)
                {{ $doc->tipo_documento }}: {{ $doc->serie_numero }}<br>
                RUC: {{ $doc->ruc_emisor }}<br>
            @endforeach
        </div>
    @endif


</body>

</html>
