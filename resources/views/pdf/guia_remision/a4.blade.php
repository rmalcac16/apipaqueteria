<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Guía de Remisión #{{ $guia->codigo }}</title>
    <style>
        @page {
            margin: 12mm 10mm;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 9pt;
            width: 190mm;
            margin: 0 auto;
            color: #000;
            line-height: 1.35;
        }

        p,
        td,
        th,
        div {
            margin: 0;
            padding: 0;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .left {
            text-align: left;
        }

        .bold {
            font-weight: bold;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .text-sm {
            font-size: 8.5pt;
        }

        .text-xs {
            font-size: 7.5pt;
        }

        .header {
            margin-bottom: 8pt;
            padding-bottom: 8pt;
            border-bottom: 1px solid #333;
            overflow: hidden;
        }

        .logo-container {
            width: 45mm;
            float: left;
            margin-right: 10pt;
        }

        .logo-empresa {
            max-width: 35mm;
            max-height: 20mm;
        }

        .datos-empresa {
            float: left;
            width: 80mm;
        }

        .nombreEmpresa {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 3pt;
        }

        .ruc-empresa {
            font-size: 10pt;
            margin-bottom: 3pt;
        }

        .numeracion {
            border: 1px solid #000;
            padding: 6pt;
            width: 55mm;
            float: right;
            text-align: center;
        }

        .titulo-documento {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 3pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 5pt 0;
            page-break-inside: avoid;
        }

        td,
        th {
            padding: 3.5pt;
            vertical-align: top;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        /* Títulos generales */
        .titulo-seccion {
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 5pt;
            border-bottom: 1px solid #aaa;
            padding-bottom: 2.5pt;
            clear: both;
        }

        /* Títulos dentro de columnas (sin clear) */
        .titulo-columna {
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 5pt;
            border-bottom: 1px solid #aaa;
            padding-bottom: 2.5pt;
        }

        .qr-container {
            margin: 8pt auto;
            text-align: center;
        }

        .qr-code {
            width: 70pt;
            height: 70pt;
            margin: 0 auto;
            border: 1px solid #eee;
        }

        .footer {
            font-size: 7.5pt;
            text-align: center;
            margin-top: 10pt;
            border-top: 1px solid #333;
            padding-top: 6pt;
            clear: both;
        }

        .column-left {
            width: 48%;
            float: left;
            overflow: hidden;
        }

        .column-right {
            width: 48%;
            float: right;
            overflow: hidden;
        }

        .clearfix {
            clear: both;
        }

        .hash {
            font-family: monospace;
            font-size: 6.5pt;
            word-break: break-all;
            margin-top: 4pt;
        }

        .estado-sunat {
            font-weight: bold;
            margin: 6pt 0;
            text-align: center;
            padding: 4pt;
            border: 1px solid #000;
            background-color: #f9f9f9;
            font-size: 8pt;
        }

        .total-bultos,
        .peso-total {
            text-align: right;
            font-weight: bold;
            margin-top: 4pt;
        }

        .section {
            margin-bottom: 6pt;
            padding-bottom: 6pt;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="logo-container">
            <img src="{{ getSetting('BUSINESS_LOGO') }}" class="logo-empresa" alt="Logo">
        </div>

        <div class="datos-empresa">
            <p class="nombreEmpresa">{{ getSetting('BUSINESS_NAME') }}</p>
            <p class="ruc-empresa">RUC: {{ getSetting('BUSINESS_RUC') }}</p>
            <p>{{ getSetting('BUSINESS_ADDRESS') }}</p>
            <p class="text-sm">TELÉFONO: {{ getSetting('BUSINESS_PHONE') }}</p>
        </div>

        <div class="numeracion">
            <p class="titulo-documento">GUÍA DE REMISIÓN ELECTRÓNICA TRANSPORTISTA</p>
            <p class="bold">{{ $guia->codigo }}</p>
            <p class="text-sm">Fecha emisión: {{ $guia->fecha_emision }}</p>

            @if (!empty($guia->hash_code))
                <div class="estado-sunat">
                    ESTADO SUNAT: {{ strtoupper($guia->estado_sunat) }}
                </div>
            @else
                <div class="estado-sunat">
                    DOCUMENTO PROVISIONAL
                </div>
            @endif
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="section">
        <div class="column-left">
            <p class="titulo-columna">DATOS DEL TRASLADO</p>
            <p><strong>Fecha de traslado:</strong> {{ date('d/m/Y', strtotime($guia->fecha_inicio_traslado)) }}</p>
            <p><strong>Motivo:</strong> {{ $guia->motivo_traslado }}</p>
        </div>
        <div class="column-right">
            <p class="titulo-columna">DATOS DEL TRANSPORTE</p>
            <p><strong>Vehículo:</strong> {{ $guia->viaje->vehiculoPrincipal->placa ?? 'N/A' }}</p>
            <p><strong>Conductor:</strong> {{ $guia->viaje->conductorPrincipal->nombreCompleto ?? 'N/A' }}</p>
            <p><strong>Licencia:</strong> {{ $guia->viaje->conductorPrincipal->numeroLicencia ?? '' }}</p>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="section">
        <p class="titulo-seccion">DOCUMENTOS DE SUSTENTO</p>
        @forelse ($guia->documentosSustento as $documento)
            <p><strong>{{ $documento->tipo_documento }}:</strong> {{ $documento->serie_numero }} -
                {{ $documento->ruc_emisor }}</p>
        @empty
            <p>No hay documentos de sustento</p>
        @endforelse
    </div>

    <div class="section">
        <p class="titulo-seccion">RUTA</p>
        <div class="column-left">
            <p><strong>Origen:</strong></p>
            <p>
                @if ($guia->envio->recojoDomicilio)
                    {{ $guia->envio->recojo_direccion }}
                @else
                    {{ $guia->envio->agenciaOrigen->direccion }}
                @endif
            </p>
            <p>{{ $guia->envio->agenciaOrigen->distrito }}, {{ $guia->envio->agenciaOrigen->provincia }},
                {{ $guia->envio->agenciaOrigen->departamento }}</p>
        </div>
        <div class="column-right">
            <p><strong>Destino:</strong></p>
            <p>
                @if ($guia->envio->entregaDomicilio)
                    {{ $guia->envio->entrega_direccion }}
                @else
                    {{ $guia->envio->agenciaDestino->direccion }}
                @endif
            </p>
            <p>{{ $guia->envio->agenciaDestino->distrito }}, {{ $guia->envio->agenciaDestino->provincia }},
                {{ $guia->envio->agenciaDestino->departamento }}</p>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="section">
        <p class="titulo-seccion">PARTES</p>
        <div class="column-left">
            <p><strong>REMITENTE:</strong></p>
            <p>{{ $guia->envio->remitente->nombreCompleto }}</p>
            <p>{{ $guia->envio->remitente->numeroDocumento }}</p>
        </div>
        <div class="column-right">
            <p><strong>DESTINATARIO:</strong></p>
            <p>{{ $guia->envio->destinatario->nombreCompleto }}</p>
            <p>{{ $guia->envio->destinatario->numeroDocumento }}</p>
        </div>
        <div class="clearfix"></div>
        <p class="text-sm">El pagador del flete es: {{ $guia->envio->pagador->nombreCompleto }}
            <span class="bold">
                (@if ($guia->envio->pagador_id === $guia->envio->remitente_id)
                    REMITENTE
                @elseif ($guia->envio->pagador_id === $guia->envio->destinatario_id)
                    DESTINATARIO
                @else
                    OTRO
                @endif)
            </span>
        </p>
    </div>

    <div class="section">
        <p class="titulo-seccion">DATOS DE LOS BIENES A TRANSPORTAR</p>
        <table>
            <thead>
                <tr>
                    <th width="10%">Cantidad</th>
                    <th width="15%">Unidad</th>
                    <th width="55%">Descripción</th>
                    <th width="20%">Peso (kg)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($guia->envio->items as $item)
                    <tr>
                        <td class="center">{{ $item->cantidad }}</td>
                        <td class="center">{{ $item->unidad_medida }}</td>
                        <td>{{ $item->descripcion }}</td>
                        <td class="right">{{ number_format($item->peso, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p class="total-bultos">Total bultos: {{ $guia->envio->items->sum('cantidad') }}</p>
        <p class="peso-total">Peso total: {{ number_format($guia->envio->pesoTotal, 2) }} KG</p>
    </div>

    @if (!empty($guia->hash_code))
        <div class="section">
            <div class="qr-container">
                <p class="bold">CÓDIGO DE VERIFICACIÓN</p>
                <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->size(90)->generate($guia->qr_text)) }}"
                    class="qr-code" alt="Código QR">
                <div class="hash">{{ $guia->hash_code }}</div>
            </div>
        </div>
    @endif

    <div class="section">
        <div class="column-left">
            <p><strong>Generado por:</strong> {{ $guia->envio->user->name }}</p>
        </div>
        <div class="column-right" style="text-align: right;">
            <p><strong>Fecha impresión:</strong> {{ date('d/m/Y H:i') }}</p>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="footer">
        <p class="bold">REPRESENTACIÓN IMPRESA DE LA GUÍA DE REMISIÓN ELECTRÓNICA TRANSPORTISTA</p>
        <p>{{ getSetting('BUSINESS_NAME') }} - RUC: {{ getSetting('BUSINESS_RUC') }}</p>
    </div>
</body>

</html>
