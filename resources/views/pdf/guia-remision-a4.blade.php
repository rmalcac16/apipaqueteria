<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Guía de Remisión - Transportista</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .section {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>PopeyeCargos SAC</h2>
        <p>RUC: 20123456789 | Calle Ficticia 123 - Cajamarca</p>
        <p><strong>GUÍA DE REMISIÓN - TRANSPORTISTA</strong></p>
    </div>

    <div class="section">
        <strong>Datos del Conductor:</strong><br>
        Nombre: {{ $guia->conductor_nombre }}<br>
        Tipo Documento: {{ $guia->conductor_tipo_documento }} - {{ $guia->conductor_dni }}<br>
        Licencia: {{ $guia->conductor_licencia }}
    </div>

    <div class="section">
        <strong>Datos del Vehículo:</strong><br>
        Placa: {{ $guia->vehiculo_placa }}<br>
        TUC: {{ $guia->vehiculo_tuc }}<br>
        Certificado: {{ $guia->vehiculo_certificado }}
    </div>

    <div class="section">
        <strong>Traslado:</strong><br>
        Fecha Inicio: {{ \Carbon\Carbon::parse($guia->fecha_inicio_traslado)->format('d/m/Y H:i') }}<br>
        Origen: [{{ $guia->punto_partida_ubigeo }}] {{ $guia->punto_partida_direccion }}<br>
        Destino: [{{ $guia->punto_llegada_ubigeo }}] {{ $guia->punto_llegada_direccion }}
    </div>

    <div class="section">
        <strong>Carga:</strong><br>
        Descripción: {{ $guia->descripcion_carga }}<br>
        Peso: {{ $guia->peso_total }} {{ $guia->unidad_medida }}
    </div>

    <div class="section">
        <strong>Remitente:</strong><br>
        {{ $guia->remitente_nombre }} - {{ $guia->remitente_documento }}
    </div>

    <div class="section">
        <strong>Destinatario:</strong><br>
        {{ $guia->destinatario_nombre }} - {{ $guia->destinatario_documento }}
    </div>

    <div class="section">
        <strong>Pagador del Servicio:</strong><br>
        Tipo: {{ ucfirst($guia->pagador_tipo) }}<br>
        @if ($guia->pagador_tipo === 'tercero')
            Documento: {{ $guia->pagador_tipo_documento }} - {{ $guia->pagador_numero_documento }}<br>
            Nombre/Razón Social: {{ $guia->pagador_nombre_razon_social }}
        @else
            El servicio es pagado por el {{ $guia->pagador_tipo }}
        @endif
    </div>

    <p style="text-align: right; font-size: 10px;">Generado el {{ now()->format('d/m/Y H:i') }}</p>

    @if ($guia->documentosSustento && $guia->documentosSustento->count())
        <div class="section">
            <strong>Documentos que sustentan el traslado:</strong><br>
            <ul>
                @foreach ($guia->documentosSustento as $doc)
                    <li>{{ $doc->tipo_documento }}: {{ $doc->serie_numero }} ({{ $doc->ruc_emisor }})</li>
                @endforeach
            </ul>
        </div>
    @endif


</body>

</html>
