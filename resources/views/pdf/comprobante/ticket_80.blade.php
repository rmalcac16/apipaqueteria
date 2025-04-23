<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>{{ $comprobante->tipo_documento }} {{ $comprobante->serie }}-{{ $comprobante->numero }}</title>
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

        .total-row {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        .total-letras {
            font-size: 7px;
            font-style: italic;
            margin-top: 2px;
        }

        .representacion {
            font-size: 7px;
            text-align: center;
            margin: 3px 0;
            font-style: italic;
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
        <p class="text-sm">{{ $comprobante->tipo_documento }} ELECTRÓNICA</p>
        <p class="text-sm">{{ $comprobante->serie }}-{{ $comprobante->numero }}</p>

        @if (!empty($comprobante->hash))
            <div class="qr-container">
                <div class="qr-code">[QR: {{ $comprobante->hash }}]</div>
                <p class="text-xs">Código Hash de verificación:</p>
                <div class="hash">{{ $comprobante->hash }}</div>
                @if (!empty($comprobante->cdr_path))
                    <p class="text-xs" style="margin-top: 3px;">Documento electrónico válido</p>
                @endif
            </div>
        @endif

        <p class="representacion">REPRESENTACIÓN IMPRESA DE LA {{ $comprobante->tipo_documento }} ELECTRÓNICA</p>
        <p class="text-xs">Generada en {{ date('d/m/Y H:i:s') }}</p>
    </div>

    @if (empty($comprobante->hash) && empty($comprobante->cdr_path) && $comprobante->estado_sunat === 'pendiente')
        <div class="alert">
            <p class="bold center text-xs uppercase">DOCUMENTO PROVISIONAL</p>
            <p class="text-xs center">Este documento no tiene validez fiscal hasta que sea procesado por SUNAT</p>
        </div>
    @endif

    <div class="section">
        <table class="two-columns">
            <tr>
                <td>
                    <p class="text-sm"><strong>Fecha Emisión:</strong></p>
                    <p class="text-sm">{{ $comprobante->fecha_emision }}</p>
                </td>
                <td>
                    <p class="text-sm"><strong>Moneda:</strong></p>
                    <p class="text-sm">SOL (PEN)</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="text-sm"><strong>Forma de Pago:</strong></p>
                    <p class="text-sm">CONTADO</p>
                </td>
                <td>
                    <p class="text-sm"><strong>Condición:</strong></p>
                    <p class="text-sm">EFECTIVO</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <p class="titulo">DATOS DEL CLIENTE</p>
        <div class="domicilio-section">
            <p class="text-sm bold">{{ $comprobante->cliente->nombreCompleto }}</p>
            <p class="text-sm">
                <strong>
                    @if ($comprobante->cliente->tipoDocumento == '1')
                        DNI
                    @elseif($comprobante->cliente->tipoDocumento == '6')
                        RUC
                    @else
                        DOC({{ $comprobante->cliente->tipoDocumento }})
                    @endif
                </strong>
                {{ $comprobante->cliente->numeroDocumento }}
            </p>
            <p class="text-sm"><strong>Dirección:</strong> {{ $comprobante->cliente->direccion ?? 'N/A' }}</p>
            @if ($comprobante->pago && $comprobante->pago->envio && $comprobante->pago->envio->user)
                <p class="text-sm"><strong>Atendido por:</strong> {{ $comprobante->pago->envio->user->name }}</p>
            @endif
        </div>
    </div>

    <div class="section">
        <p class="titulo">DETALLE DEL COMPROBANTE</p>
        <table class="table-bordered">
            <thead>
                <tr>
                    <th width="10%">Cant.</th>
                    <th width="40%">Descripción</th>
                    <th width="20%">P. Unit</th>
                    <th width="20%">Total</th>
                </tr>
            </thead>
            <tbody>
                @if ($comprobante->pago && $comprobante->pago->envio)
                    @php
                        $totalItems = count($comprobante->pago->envio->items);
                        $montoPorItem = $comprobante->monto_total / $totalItems;
                    @endphp
                    @foreach ($comprobante->pago->envio->items as $item)
                        <tr>
                            <td class="center">{{ $item->cantidad }}</td>
                            <td>
                                SERVICIO DE TRANSPORTE {{ $item->descripcion }}
                                @if ($comprobante->pago->envio->guiaRemision)
                                    <br><span class="text-xs">Guía:
                                        {{ $comprobante->pago->envio->guiaRemision->codigo }}</span>
                                @endif
                            </td>
                            <td class="right">{{ number_format($montoPorItem / $item->cantidad, 2) }}</td>
                            <td class="right">{{ number_format($montoPorItem, 2) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="center">1</td>
                        <td>SERVICIO DE TRANSPORTE DE CARGA</td>
                        <td class="right">{{ number_format($comprobante->monto_total / 1.18, 2) }}</td>
                        <td class="right">{{ number_format($comprobante->monto_total / 1.18, 2) }}</td>
                    </tr>
                @endif
                <tr>
                    <td colspan="3" class="right"><strong>TOTAL</strong></td>
                    <td class="right"><strong>{{ number_format($comprobante->monto_total, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        @php
            // Cálculo de valores gravados e IGV
            $total_gravada = $comprobante->monto_total / 1.18;

            $igv = $total_gravada * 0.18;
            $total = $total_gravada + $igv;

            $formatter = new \Luecano\NumeroALetras\NumeroALetras();
            $totalEnLetras = $formatter->toMoney($total, 2, 'SOLES', 'CENTIMOS');
        @endphp

        <table style="width: 100%;">
            <tr>
                <td>
                    <p class="text-sm"><strong>OP. GRAVADA:</strong></p>
                </td>
                <td class="right">
                    <p class="text-sm">{{ number_format($total_gravada, 2) }}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="text-sm"><strong>IGV (18%):</strong></p>
                </td>
                <td class="right">
                    <p class="text-sm">{{ number_format($igv, 2) }}</p>
                </td>
            </tr>
            <tr class="total-row">
                <td>
                    <p class="text-sm"><strong>TOTAL:</strong></p>
                </td>
                <td class="right">
                    <p class="text-sm">{{ number_format($total, 2) }}</p>
                </td>
            </tr>
        </table>
        <p class="total-letras">SON: {{ $totalEnLetras }}</p>
    </div>

    <div class="section">
        <table class="two-columns">
            <tr>
                <td>
                    <p class="text-sm"><strong>Generado por:</strong></p>
                    <p class="text-sm">{{ $comprobante->pago->envio->user->name ?? $comprobante->user->name }}</p>
                    <p class="text-xs">
                        {{ $comprobante->pago->envio->user->agencia->nombre ?? ($comprobante->user->agencia->nombre ?? 'Administración') }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>{{ env('BUSSINESS_NAME') ?? 'NOMBRE DE LA EMPRESA' }}</p>
        <p class="text-xs">Sistema de facturación electrónica</p>
    </div>
</body>

</html>
