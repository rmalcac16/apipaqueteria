<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>{{ $comprobante->tipo === '03' ? 'BOLETA DE VENTA' : 'FACTURA' }}
        {{ $comprobante->serie }}-{{ $comprobante->numero }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
            size: 58mm auto;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 9px;
            width: 54mm;
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
            font-size: 8px;
        }

        .text-xs {
            font-size: 7px;
        }

        .text-3xl {
            font-size: 14px;
        }

        .text-4xl {
            font-size: 18px;
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

        .product-description {
            padding: 3px;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
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

        .logo {
            text-align: center;
            margin-bottom: 5px;
        }

        .logo-img {
            max-width: 50px;
            max-height: 30px;
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

        .total-row {
            font-weight: bold;
            background-color: #f5f5f5;
        }

        .total-letras {
            font-size: 8px;
            font-style: italic;
            margin-top: 3px;
            padding: 2px;
            background-color: #f9f9f9;
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

        .info-adicional {
            font-size: 8px;
            color: #555;
            margin-top: 2px;
        }


        .cuotas-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        .cuotas-table th {
            background-color: #f0f0f0;
            padding: 2px;
            border: 1px solid #ddd;
        }

        .cuotas-table td {
            padding: 2px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .condicion-pago {
            font-weight: bold;
            margin-top: 4px;
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
            ESTADO SUNAT: {{ strtoupper($comprobante->estado_sunat) }}
            @if (!empty($comprobante->fecha_aceptacion))
                <br>FECHA ACEPTACIÓN: {{ $comprobante->fecha_aceptacion }}
            @endif
        </div>
    </div>

    <div class="section center">
        <p class="bold">{{ $comprobante->tipo === '03' ? 'BOLETA DE VENTA' : 'FACTURA' }} ELECTRÓNICA</p>
        <p class="text-4xl bold">{{ $comprobante->serie }}-{{ $comprobante->numero }}</p>
    </div>

    <div class="section">
        <table class="two-columns">
            <tr>
                <td>
                    <p class="text-sm bold">FECHA EMISIÓN:</p>
                    <p class="text-sm">{{ date('d/m/Y H:i:s', strtotime($comprobante->fecha_emision)) }}</p>
                </td>
                <td>
                    <p class="text-sm bold">MONEDA:</p>
                    <p class="text-sm">SOLES (PEN)</p>
                </td>
            </tr>
            @if ($comprobante->forma_pago === 'credito')
                <tr>
                    <td colspan="2">
                        <p class="condicion-pago">CONDICIÓN DE PAGO: CRÉDITO</p>
                    </td>
                </tr>
            @endif
        </table>
    </div>

    <div class="section">
        <p class="titulo">DATOS DEL CLIENTE</p>
        <div>
            <p class="text-sm bold">{{ $comprobante->cliente->nombreCompleto }}</p>
            <p class="text-sm">
                <span class="bold">
                    @if ($comprobante->cliente->tipoDocumento == '1')
                        DNI
                    @elseif($comprobante->cliente->tipoDocumento == '6')
                        RUC
                    @else
                        DOC({{ $comprobante->cliente->tipoDocumento }})
                    @endif
                </span>
                {{ $comprobante->cliente->numeroDocumento }}
            </p>
            @if (!empty($comprobante->cliente->direccion))
                <p class="text-sm"><span class="bold">DIRECCIÓN:</span> {{ $comprobante->cliente->direccion }}</p>
            @endif
        </div>
        @if ($comprobante->pago && $comprobante->pago->envio && $comprobante->pago->envio->user)
            <div>
                <p class="text-sm"><span class="bold">ATENDIDO POR:</span>
                    {{ $comprobante->pago->envio->user->name }}</p>
            </div>
        @endif
    </div>

    <div class="section">
        <p class="titulo">DETALLE DEL COMPROBANTE</p>
        <table class="product-table">
            <thead>
                <tr>
                    <th colspan="4">Descripción</th>
                </tr>
                <tr>
                    <th class="center">Cant</th>
                    <th class="center">Unidad</th>
                    <th class="right">P. Unit</th>
                    <th class="right">Importe</th>
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
                            <td colspan="4" class="product-description">
                                SERVICIO DE TRANSPORTE POR: {{ $item->descripcion }}
                                @if ($comprobante->pago->envio->guiaRemision)
                                    <div class="info-adicional">
                                        Guía: {{ $comprobante->pago->envio->guiaRemision->codigo }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="center">{{ $item->cantidad }}</td>
                            <td class="center">NIU</td>
                            <td class="right">S/ {{ number_format($montoPorItem / $item->cantidad, 2) }}</td>
                            <td class="right">S/ {{ number_format($montoPorItem, 2) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="product-description">
                            SERVICIO DE TRANSPORTE DE CARGA
                        </td>
                    </tr>
                    <tr>
                        <td class="center">1</td>
                        <td class="center">NIU</td>
                        <td class="right">S/ {{ number_format($comprobante->monto_total / 1.18, 2) }}</td>
                        <td class="right">S/ {{ number_format($comprobante->monto_total / 1.18, 2) }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="section">
        @php
            $total_gravada = $comprobante->monto_total / 1.18;
            $igv = $total_gravada * 0.18;
            $total = $total_gravada + $igv;
        @endphp

        <table style="width: 100%;">
            <tr class="total-row">
                <td class="left">
                    <p class="text-sm bold">OP. GRAVADA:</p>
                </td>
                <td class="right">
                    <p class="text-sm">S/ {{ number_format($total_gravada, 2) }}</p>
                </td>
            </tr>
            <tr class="total-row">
                <td class="left">
                    <p class="text-sm bold">IGV (18%):</p>
                </td>
                <td class="right">
                    <p class="text-sm">S/ {{ number_format($igv, 2) }}</p>
                </td>
            </tr>
            <tr class="total-row">
                <td class="left">
                    <p class="text-sm bold">TOTAL A PAGAR:</p>
                </td>
                <td class="right">
                    <p class="text-sm bold">S/ {{ number_format($total, 2) }}</p>
                </td>
            </tr>
        </table>
        <div class="total-letras">
            <p class="bold">SON: {{ $comprobante->monto_total_letras }}</p>
        </div>

        <!-- Sección de cuotas (solo para crédito) -->
        @if ($comprobante->forma_pago === 'credito' && $comprobante->cuotas && count($comprobante->cuotas) > 0)
            <div style="margin-top: 6px;">
                <p class="titulo">PLAN DE PAGOS</p>
                <table class="cuotas-table">
                    <thead>
                        <tr>
                            <th>N° Cuota</th>
                            <th>Monto</th>
                            <th>Fecha Venc.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($comprobante->cuotas as $cuota)
                            <tr>
                                <td>{{ $cuota->numero_cuota }}</td>
                                <td>S/ {{ number_format($cuota->monto, 2) }}</td>
                                <td>{{ date('d/m/Y', strtotime($cuota->fecha_vencimiento)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @if ($comprobante->estado_sunat === 'aceptado' && !empty($comprobante->hash_code))
        <div class="section">
            <div class="qr-container">
                <p class="text-sm bold">CÓDIGO DE VERIFICACIÓN</p>
                <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->size(100)->generate($comprobante->qr_text)) }}"
                    class="qr-code" alt="Código QR">
                <div class="hash">
                    {{ $comprobante->hash_code }}
                </div>
                <p class="text-xs">Verifique este documento en {{ getSetting('BUSINESS_URL') }}</p>
            </div>
        </div>
    @endif

    <div class="section">
        <table class="two-columns">
            <tr>
                <td>
                    <p class="text-sm bold">GENERADO POR:</p>
                    <p class="text-sm">{{ $comprobante->pago->envio->user->name ?? $comprobante->user->name }}</p>
                </td>
                <td class="right">
                    <p class="text-sm bold">FECHA IMPRESIÓN:</p>
                    <p class="text-sm">{{ date('d/m/Y H:i:s') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p class="representacion bold">REPRESENTACIÓN IMPRESA DE LA
            {{ $comprobante->tipo === '03' ? 'BOLETA DE VENTA' : 'FACTURA' }} ELECTRÓNICA</p>
        <p class="text-xs">{{ getSetting('BUSINESS_NAME') }}</p>
    </div>
</body>

</html>
