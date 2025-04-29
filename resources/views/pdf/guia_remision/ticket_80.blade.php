<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Guía de Remisión #{{ $guia->codigo }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
            size: 80mm auto;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            width: 76mm;
            margin: 0 auto;
            padding: 2mm;
            line-height: 1.3;
            color: #000;
        }

        p,
        td,
        th,
        div {
            margin: 0;
            padding: 0;
            word-break: break-word;
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
            font-size: 9px;
        }

        .text-xs {
            font-size: 8px;
        }

        .text-3xl {
            font-size: 16px;
        }

        .text-4xl {
            font-size: 20px;
        }

        .section {
            margin-bottom: 6px;
            padding-bottom: 6px;
            border-bottom: 1px dashed #aaa;
        }

        .section:last-of-type {
            border-bottom: none;
        }

        .header {
            margin-bottom: 6px;
            padding-bottom: 6px;
            border-bottom: 1px solid #333;
        }

        .nombreEmpresa {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            margin: 4px 0;
        }

        td,
        th {
            padding: 3px 0;
            vertical-align: top;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
        }

        .product-table th {
            background-color: #f5f5f5;
            padding: 3px;
            border: 1px solid #ddd;
        }

        .product-table td {
            padding: 3px;
            border: 1px solid #ddd;
        }

        .titulo {
            font-weight: bold;
            font-size: 9px;
            margin-bottom: 3px;
            border-bottom: 1px dotted #aaa;
            padding-bottom: 2px;
        }

        .qr-container {
            margin: 5px auto;
            text-align: center;
        }

        .qr-code {
            width: 100px;
            height: 100px;
            margin: 0 auto;
            border: 1px solid #eee;
        }

        .footer {
            font-size: 8px;
            text-align: center;
            margin-top: 5px;
        }

        .two-columns {
            width: 100%;
        }

        .two-columns td {
            width: 50%;
            vertical-align: top;
        }

        .hash {
            font-family: monospace;
            font-size: 7px;
            word-break: break-all;
            margin-top: 3px;
        }

        .representacion {
            font-size: 8px;
            text-align: center;
            margin: 4px 0;
            font-style: italic;
        }

        .estado-sunat {
            font-weight: bold;
            margin: 4px 0;
            text-align: center;
            padding: 3px;
            border: 1px solid #000;
        }

        .tipo_pago {
            font-weight: bold;
            text-align: center;
            padding: 3px;
            border: 1px solid #000;
            width: 75%;
            margin: 4px auto;
        }

        .documentos-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        .documentos-table th {
            background-color: #f5f5f5;
            padding: 2px;
            border: 1px solid #ddd;
            font-size: 8px;
        }

        .documentos-table td {
            padding: 2px;
            border: 1px solid #ddd;
            font-size: 8px;
        }
    </style>
</head>

<body>

    <div class="header center">
        <p class="nombreEmpresa">{{ getSetting('BUSINESS_NAME') }}</p>
        <p class="text-sm">RUC: {{ getSetting('BUSINESS_RUC') }}</p>
        <p class="text-sm">{{ getSetting('BUSINESS_ADDRESS') }}</p>
        <p class="text-sm">TELÉFONO: {{ getSetting('BUSINESS_PHONE') }}</p>

        <div class="estado-sunat">
            @if (!empty($guia->hash_code))
                ESTADO SUNAT: {{ strtoupper($guia->estado_sunat) }}
            @else
                DOCUMENTO PROVISIONAL
            @endif
        </div>
    </div>

    <div class="section center">
        <p class="bold uppercase">GUÍA DE REMISIÓN ELECTRÓNICA TRANSPORTISTA</p>
        <p class="text-4xl bold">{{ $guia->codigo }}</p>
        <p>Fecha emisión: {{ $guia->fecha_emision }}</p>
    </div>


    <div class="section">
        <table class="two-columns">
            <tr>
                <td>
                    <p class="text-sm bold">FECHA TRASLADO:</p>
                    <p class="text-sm">{{ $guia->fecha_inicio_traslado }}</p>
                </td>
                <td>
                    <p class="text-sm bold">MOTIVO:</p>
                    <p class="text-sm">{{ $guia->motivo_traslado }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <p class="titulo">DATOS DEL TRANSPORTE</p>
        <table>
            <tr>
                <td class="text-sm"><strong>Vehículo:</strong></td>
                <td class="text-sm">{{ $guia->viaje->vehiculoPrincipal->placa ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="text-sm"><strong>Conductor:</strong></td>
                <td class="text-sm">{{ $guia->viaje->conductorPrincipal->nombreCompleto ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="text-sm"><strong>Licencia:</strong></td>
                <td class="text-sm">{{ $guia->viaje->conductorPrincipal->numeroLicencia ?? '' }}</td>
            </tr>
        </table>
    </div>

    <!-- Sección de Documentos de Sustento -->
    <div class="section">
        <p class="titulo">DOCUMENTOS DE SUSTENTO</p>
        @if ($guia->documentosSustento && count($guia->documentosSustento) > 0)
            <table class="documentos-table">
                <thead>
                    <tr>
                        <th>Serie-Número</th>
                        <th>RUC Emisor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($guia->documentosSustento as $documento)
                        <tr>
                            <td class="center">{{ $documento->serie_numero }}</td>
                            <td class="center">{{ $documento->ruc_emisor }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-sm">No hay documentos de sustento</p>
        @endif
    </div>

    <div class="section">
        <p class="titulo">RUTA</p>
        <table>
            <tr>
                <td class="text-sm"><strong>Origen:</strong></td>
                <td class="text-sm">
                    <p>
                        @if ($guia->envio->recojoDomicilio)
                            {{ $guia->envio->recojo_direccion }}
                        @else
                            {{ $guia->envio->agenciaOrigen->direccion }}
                        @endif
                    </p>
                    <p>{{ $guia->envio->agenciaOrigen->distrito }},
                        {{ $guia->envio->agenciaOrigen->provincia }}, {{ $guia->envio->agenciaOrigen->departamento }}
                    </p>
                </td>
            </tr>
            <tr>
                <td class="text-sm"><strong>Destino:</strong></td>
                <td class="text-sm">
                    @if ($guia->envio->entregaDomicilio)
                        {{ $guia->envio->entrega_direccion }}
                    @else
                        {{ $guia->envio->agenciaDestino->direccion }}
                    @endif
                    <p>{{ $guia->envio->agenciaDestino->distrito }},
                        {{ $guia->envio->agenciaDestino->provincia }},
                        {{ $guia->envio->agenciaDestino->departamento }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <p class="titulo">PARTES</p>
        <table class="two-columns">
            <tr>
                <td>
                    <p class="text-sm bold">REMITENTE:</p>
                    <p class="text-sm">{{ $guia->envio->remitente->nombreCompleto }}</p>
                    <p class="text-sm">{{ $guia->envio->remitente->numeroDocumento }}</p>
                </td>
                <td>
                    <p class="text-sm bold">DESTINATARIO:</p>
                    <p class="text-sm">{{ $guia->envio->destinatario->nombreCompleto }}</p>
                    <p class="text-sm">{{ $guia->envio->destinatario->numeroDocumento }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <p class="titulo">DATOS DE LOS BIENES A TRANSPORTAR</p>
        <table class="product-table">
            <thead>
                <tr>
                    <th width="15%">Cant</th>
                    <th width="20%">UM</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($guia->envio->items as $item)
                    <tr>
                        <td class="center">{{ $item->cantidad }}</td>
                        <td class="center">{{ $item->unidad_medida }}</td>
                        <td>{{ $item->descripcion }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p class="text-sm right">Total bultos: {{ $guia->envio->items->sum('cantidad') }}</p>
        <p class="text-sm bold">Peso total: {{ number_format($guia->envio->pesoTotal, 2) }} KG</p>
    </div>

    @if (!empty($guia->hash_code))
        <div class="section">
            <div class="qr-container">
                <p class="text-sm bold">CÓDIGO DE VERIFICACIÓN</p>
                <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->size(100)->generate($guia->qr_text)) }}"
                    class="qr-code" alt="Código QR">
                <div class="hash">
                    {{ $guia->hash_code }}
                </div>
            </div>
        </div>
    @endif

    <div class="section">
        <table class="two-columns">
            <tr>
                <td>
                    <p class="text-sm bold">GENERADO POR:</p>
                    <p class="text-sm">{{ $guia->envio->user->name }}</p>
                </td>
                <td class="right">
                    <p class="text-sm bold">FECHA IMPRESIÓN:</p>
                    <p class="text-sm">{{ date('d/m/Y H:i:s') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p class="representacion bold">REPRESENTACIÓN IMPRESA DE LA GUÍA DE REMISIÓN ELECTRÓNICA TRANSPORTISTA</p>
        <p class="text-xs">{{ getSetting('BUSINESS_NAME') }}</p>
    </div>
</body>

</html>
