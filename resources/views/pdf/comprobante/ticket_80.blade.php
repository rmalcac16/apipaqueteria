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
            size: 80mm auto;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            width: 76mm;
            margin: 0 auto;
            padding: 2mm;
            line-height: 1.4;
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
            font-size: 10px;
        }

        .text-xs {
            font-size: 9px;
        }

        .text-3xl {
            font-size: 18px;
        }

        .text-4xl {
            font-size: 24px;
        }

        .section {
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 1px dashed #aaa;
        }

        .section:last-of-type {
            border-bottom: none;
        }

        .header {
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 1px solid #333;
        }

        .nombreEmpresa {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin: 4px 0;
        }

        td,
        th {
            padding: 4px;
            vertical-align: top;
        }

        .product-table,
        .cuotas-table {
            width: 100%;
            border-collapse: collapse;
        }

        .product-table th,
        .cuotas-table th {
            background-color: #f5f5f5;
            padding: 4px;
            border: 1px solid #ddd;
        }

        .product-table td,
        .cuotas-table td {
            padding: 4px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .product-description {
            padding: 4px;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
        }

        .titulo {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 3px;
            border-bottom: 1px dotted #aaa;
            padding-bottom: 2px;
        }

        .qr-container {
            margin: 8px auto;
            text-align: center;
        }

        .qr-code {
            width: 100px;
            height: 100px;
            margin: 0 auto;
            border: 1px solid #eee;
        }

        .footer {
            font-size: 9px;
            text-align: center;
            margin-top: 8px;
        }

        .hash {
            font-family: monospace;
            font-size: 8px;
            word-break: break-all;
            margin-top: 4px;
        }

        .total-row {
            font-weight: bold;
            background-color: #f5f5f5;
            font-size: 11px;
        }

        .total-letras {
            font-size: 10px;
            font-style: italic;
            margin-top: 4px;
            padding: 3px;
            background-color: #f9f9f9;
        }

        .estado-sunat {
            font-weight: bold;
            font-size: 12px;
            margin: 6px 0;
            text-align: center;
            padding: 5px;
            border: 1px solid #000;
        }

        .representacion {
            font-size: 10px;
            text-align: center;
            margin: 6px 0;
            font-style: italic;
        }

        .info-adicional {
            font-size: 9px;
            color: #555;
            margin-top: 2px;
        }

        .condicion-pago {
            font-weight: bold;
            margin-top: 5px;
            font-size: 11px;
        }
    </style>
</head>

<body>

    <!-- ENCABEZADO -->
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

    <!-- TÍTULO -->
    <div class="section center">
        <p class="bold">{{ $comprobante->tipo === '03' ? 'BOLETA DE VENTA' : 'FACTURA' }} ELECTRÓNICA</p>
        <p class="text-4xl bold">{{ $comprobante->serie }}-{{ $comprobante->numero }}</p>
    </div>

    <!-- INFORMACIÓN DEL COMPROBANTE -->
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

    <!-- CLIENTE -->
    <div class="section">
        <p class="titulo">DATOS DEL CLIENTE</p>
        <p class="bold">{{ $comprobante->cliente->nombreCompleto }}</p>
        <p class="text-sm">
            <span class="bold">
                @if ($comprobante->cliente->tipoDocumento == '1')
                    DNI
                @elseif($comprobante->cliente->tipoDocumento == '6')
                    RUC
                @else
                    DOC({{ $comprobante->cliente->tipoDocumento }})
                @endif
            </span> {{ $comprobante->cliente->numeroDocumento }}
        </p>
        @if (!empty($comprobante->cliente->direccion))
            <p class="text-sm"><span class="bold">DIRECCIÓN:</span> {{ $comprobante->cliente->direccion }}</p>
        @endif
        @if ($comprobante->pago && $comprobante->pago->envio && $comprobante->pago->envio->user)
            <p class="text-sm"><span class="bold">ATENDIDO POR:</span> {{ $comprobante->pago->envio->user->name }}
            </p>
        @endif
    </div>

    <!-- DETALLE -->
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
                @endif
            </tbody>
        </table>
    </div>

    <!-- TOTALES -->
    <div class="section">
        @php
            $total_gravada = $comprobante->monto_total / 1.18;
            $igv = $total_gravada * 0.18;
            $total = $total_gravada + $igv;
        @endphp
        <table>
            <tr class="total-row">
                <td class="left">OP. GRAVADA:</td>
                <td class="right">S/ {{ number_format($total_gravada, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td class="left">IGV (18%):</td>
                <td class="right">S/ {{ number_format($igv, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td class="left">TOTAL A PAGAR:</td>
                <td class="right">S/ {{ number_format($total, 2) }}</td>
            </tr>
        </table>
        <div class="total-letras">
            <p class="bold">SON: {{ $comprobante->monto_total_letras }}</p>
        </div>
    </div>

    <!-- PLAN DE PAGOS -->
    @if ($comprobante->forma_pago === 'credito' && $comprobante->cuotas && count($comprobante->cuotas) > 0)
        <div class="section">
            <p class="titulo" style="font-size: 11px;">PLAN DE PAGOS</p>
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

    <!-- QR Y HASH -->
    @if ($comprobante->estado_sunat === 'aceptado' && !empty($comprobante->hash_code))
        <div class="section">
            <div class="qr-container">
                <p class="text-sm bold">CÓDIGO DE VERIFICACIÓN</p>
                <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->size(100)->generate($comprobante->qr_text)) }}"
                    class="qr-code" alt="Código QR">
                <div class="hash">{{ $comprobante->hash_code }}</div>
                <p class="text-xs">Verifique este documento en {{ getSetting('BUSINESS_URL') }}</p>
            </div>
        </div>
    @endif

    <!-- FOOTER -->
    <div class="footer">
        <p class="representacion bold">REPRESENTACIÓN IMPRESA DE LA
            {{ $comprobante->tipo === '03' ? 'BOLETA DE VENTA' : 'FACTURA' }} ELECTRÓNICA</p>
        <p class="text-xs">{{ getSetting('BUSINESS_NAME') }}</p>
    </div>

</body>

</html>
