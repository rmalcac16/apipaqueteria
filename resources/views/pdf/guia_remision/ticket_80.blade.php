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
            font-size: 9px;
            width: 76mm;
            margin: 0 auto;
            padding: 2mm;
            line-height: 1.25;
            color: #333;
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

        .bold {
            font-weight: bold;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .text-sm {
            font-size: 8px;
        }

        .text-xs {
            font-size: 7px;
        }

        .section {
            margin-bottom: 5px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eaeaea;
        }

        .section:last-of-type {
            border-bottom: none;
        }

        .header {
            margin-bottom: 5px;
            padding-bottom: 5px;
            border-bottom: 2px solid #2c3e50;
        }

        .nombreEmpresa {
            font-size: 12px;
            font-weight: bold;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            margin: 3px 0;
        }

        td,
        th {
            padding: 2px 0;
            vertical-align: top;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #ddd;
            padding: 2px;
        }

        .domicilio-section {
            background: #f8f9fa;
            padding: 4px;
            border-radius: 2px;
            border-left: 2px solid #3498db;
            margin: 3px 0;
        }

        .titulo {
            font-weight: bold;
            font-size: 8px;
            margin-bottom: 2px;
            color: #2c3e50;
            border-bottom: 1px dotted #ccc;
            padding-bottom: 1px;
        }

        .qr-container {
            margin: 4px auto;
            text-align: center;
        }

        .qr-code {
            width: 45px;
            height: 45px;
            border: 1px solid #ddd;
            padding: 2px;
            background: white;
        }

        .badge {
            display: inline-block;
            padding: 1px 4px;
            background: #2c3e50;
            color: white;
            border-radius: 2px;
            font-size: 7px;
            font-weight: bold;
        }

        .footer {
            font-size: 7px;
            color: #7f8c8d;
            text-align: center;
            margin-top: 4px;
        }

        .logo {
            text-align: center;
            margin-bottom: 4px;
        }

        .logo-img {
            max-width: 40px;
            max-height: 25px;
        }

        .alert {
            background: #fff3cd;
            padding: 3px;
            border-radius: 2px;
            border-left: 2px solid #ffc107;
            font-size: 7px;
            margin: 3px 0;
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
            font-size: 6px;
            word-break: break-all;
            background: #f5f5f5;
            padding: 2px;
            border-radius: 2px;
        }
    </style>
</head>

<body>

    <div class="header center">
        <div class="logo">
            <div class="logo-img">[LOGO]</div>
        </div>
        <p class="nombreEmpresa">{{ env('BUSSINESS_NAME') ?? 'NOMBRE DE LA EMPRESA' }}</p>
        <p class="text-sm">RUC: {{ env('BUSSINESS_RUC') ?? '20000000001' }}</p>
        <p class="text-sm">{{ env('BUSSINESS_ADDRESS') ?? 'DIRECCION DE LA EMPRESA' }}</p>
        <p class="text-sm">Tel: {{ env('BUSSINESS_PHONE') ?? '123456789' }}</p>
    </div>

    <div class="section center">
        <p class="uppercase bold" style="font-size: 10px;">GUÍA DE REMISIÓN ELECTRÓNICA TRANSPORTISTA</p>
        <div class="badge">N° {{ $guia->codigo }}</div>
        <p class="text-xs">Fecha: {{ $guia->fecha_emision }}</p>
    </div>

    <div class="section">
        <p class="titulo">DATOS DEL TRANSPORTE</p>
        <table class="two-columns">
            <tr>
                <td>
                    <p class="text-sm"><strong>Vehículo Principal:</strong></p>
                    <p class="text-sm">{{ $guia->viaje->vehiculoPrincipal->placa ?? 'N/A' }}</p>
                </td>
                <td>
                    <p class="text-sm"><strong>Vehículo Secundario:</strong></p>
                    <p class="text-sm">{{ $guia->viaje->vehiculoSecundario->placa ?? 'N/A' }}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="text-sm"><strong>Conductor Principal:</strong></p>
                    <p class="text-sm">{{ $guia->viaje->conductorPrincipal->nombreCompleto ?? 'N/A' }}</p>
                    <p class="text-sm">{{ $guia->viaje->conductorPrincipal->numeroLicencia ?? '' }}</p>
                </td>
                <td>
                    <p class="text-sm"><strong>Conductor Secundario:</strong></p>
                    <p class="text-sm">{{ $guia->viaje->conductorSecundario->nombreCompleto ?? 'N/A' }}</p>
                    <p class="text-sm">{{ $guia->viaje->conductorSecundario->numeroLicencia ?? '' }}</p>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p class="text-sm"><strong>Fecha Traslado:</strong> {{ $guia->fecha_inicio_traslado }}</p>
                    <p class="text-sm"><strong>Motivo:</strong> {{ $guia->motivo_traslado }}</p>
                </td>
            </tr>
        </table>
    </div>

    <!-- Nueva sección de Documentos de Sustento -->
    <div class="section">
        <p class="titulo">DOCUMENTOS DE SUSTENTO</p>
        <table class="table-bordered">
            <thead>
                <tr>
                    <th>Serie-Número</th>
                    <th>RUC Emisor</th>
                </tr>
            </thead>
            <tbody class="center">
                @if ($guia->documentosSustento && count($guia->documentosSustento) > 0)
                    @foreach ($guia->documentosSustento as $documento)
                        <tr>
                            <td>{{ $documento->serie_numero }}</td>
                            <td>{{ $documento->ruc_emisor }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2" class="center">No hay documentos de sustento registrados</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="section">
        <p class="titulo">PESO TOTAL</p>
        <div class="domicilio-section" style="text-align: center;">
            <p class="text-sm bold">PESO BRUTO TOTAL: {{ number_format($guia->envio->pesoTotal, 2) }} KG</p>
        </div>
    </div>

    <div class="section">
        <p class="titulo">RUTA DE TRANSPORTE</p>
        <table class="two-columns">
            <tr>
                <td>
                    <p class="text-sm"><strong>Punto de Partida:</strong></p>
                    @if ($guia->envio->recojoDomicilio)
                        <p class="text-sm">{{ $guia->envio->recojo_direccion }}</p>
                        @if ($guia->envio->recojo_referencia)
                            <p class="text-xs">Ref: {{ $guia->envio->recojo_referencia }}</p>
                        @endif
                    @else
                        <p class="text-sm">{{ $guia->envio->agenciaOrigen->direccion }}</p>
                    @endif
                    <p class="text-xs">{{ $guia->envio->agenciaOrigen->distrito }},
                        {{ $guia->envio->agenciaOrigen->provincia }}, {{ $guia->envio->agenciaOrigen->departamento }}
                    </p>
                </td>
                <td>
                    <p class="text-sm"><strong>Punto de Llegada:</strong></p>
                    @if ($guia->envio->entregaDomicilio)
                        <p class="text-sm">{{ $guia->envio->entrega_direccion }}</p>
                        @if ($guia->envio->entrega_referencia)
                            <p class="text-xs">Ref: {{ $guia->envio->entrega_referencia }}</p>
                        @endif
                    @else
                        <p class="text-sm">{{ $guia->envio->agenciaDestino->direccion }}</p>

                    @endif
                    <p class="text-xs">{{ $guia->envio->agenciaDestino->distrito }},
                        {{ $guia->envio->agenciaDestino->provincia }},
                        {{ $guia->envio->agenciaDestino->departamento }}</p>

                    </p>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <p class="titulo">DATOS DEL REMITENTE</p>
        <div class="domicilio-section">
            <p class="text-sm bold">{{ $guia->envio->remitente->nombreCompleto }}</p>
            <p class="text-sm"><strong>Doc:</strong> {{ $guia->envio->remitente->numeroDocumento }}</p>
            <p class="text-sm"><strong>Tel:</strong> {{ $guia->envio->remitente->telefono }}</p>
            @if ($guia->envio->recojoDomicilio)
                <p class="text-sm"><strong>Dirección:</strong> {{ $guia->envio->recojo_direccion }}</p>
            @endif
        </div>
    </div>

    <div class="section">
        <p class="titulo">DATOS DEL DESTINATARIO</p>
        <div class="domicilio-section">
            <p class="text-sm bold">{{ $guia->envio->destinatario->nombreCompleto }}</p>
            <p class="text-sm"><strong>Doc:</strong> {{ $guia->envio->destinatario->numeroDocumento }}</p>
            <p class="text-sm"><strong>Tel:</strong> {{ $guia->envio->destinatario->telefono }}</p>
            @if ($guia->envio->entregaDomicilio)
                <p class="text-sm"><strong>Dirección:</strong> {{ $guia->envio->entrega_direccion }}</p>
            @endif
        </div>
    </div>

    <div class="section">
        <p class="titulo">DETALLE DE LA MERCANCÍA</p>
        <table class="table-bordered">
            <thead>
                <tr>
                    <th width="10%">Cant.</th>
                    <th width="15%">UM</th>
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
        <p class="text-xs right" style="margin-top: 2px;">Total bultos: {{ $guia->envio->items->sum('cantidad') }}</p>
    </div>

    <div class="section">
        <p class="titulo">OBSERVACIONES</p>
        <p class="text-sm">{{ $guia->observaciones ?? 'Ninguna' }}</p>
    </div>

    @if (empty($guia->hash) && empty($guia->cdr_path) && $guia->estado_sunat === 'pendiente')
        <div class="alert alert-warning"
            style="background: #fff3cd; padding: 5px; border-left: 3px solid #ffc107; margin: 5px 0; border-radius: 2px;">
            <p class="bold center text-xs uppercase">DOCUMENTO PROVISIONAL</p>
            <p class="text-xs center">Este documento no tiene validez fiscal hasta que sea procesado por SUNAT</p>
            <p class="text-xs center">Se emitirá una versión final con los datos completos</p>
        </div>
    @endif

    @if (!empty($guia->hash))
        <div class="section center">
            <div class="qr-container">
                <div class="qr-code">[QR: {{ $guia->hash }}]</div>
                <p class="text-xs">Código Hash de verificación:</p>
                <div class="hash">{{ $guia->hash }}</div>
                @if (!empty($guia->cdr_path))
                    <p class="text-xs" style="margin-top: 3px;">Documento electrónico válido</p>
                @endif
            </div>
        </div>
    @endif

    <div class="alert">
        <p class="bold center text-xs uppercase">DECLARACIÓN JURADA</p>
        <p class="text-xs">El conductor declara bajo juramento que los datos consignados son veraces y que la mercancía
            transportada corresponde a lo descrito en esta guía.</p>
    </div>


    <div class="section">
        <table class="two-columns">
            <tr>
                <td>
                    <p class="text-sm"><strong>Generado por:</strong></p>
                    <p class="text-sm">{{ $guia->envio->user->name }}</p>
                    <p class="text-xs">{{ $guia->envio->user->agencia->nombre ?? 'Administración' }}</p>
                </td>
                <td style="text-align: center;">
                    <p class="text-sm"><strong>Firma del Transportista</strong></p>
                    <div style="height: 25px; border-bottom: 1px dashed #333; margin: 5px 0;"></div>
                    <p class="text-xs">
                        {{ $guia->viaje->conductorPrincipal->nombreCompleto ?? 'Nombre del conductor' }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Documento válido por 48 horas desde su emisión</p>
        <p class="text-xs">Sistema de gestión Popeye Cargos - {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>

</html>
