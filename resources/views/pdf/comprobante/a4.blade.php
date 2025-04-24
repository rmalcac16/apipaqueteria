<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>{{ $comprobante->tipo === '03' ? 'BOLETA DE VENTA' : 'FACTURA' }}
        {{ $comprobante->serie }}-{{ $comprobante->numero }}</title>
    <style>
        @page {
            margin: 15mm 15mm;
            size: A4;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }

        .container {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
        }

        .header,
        .client-info,
        .items,
        .totals,
        .footer {
            margin-bottom: 15px;
        }

        .header-table,
        .client-table,
        .items-table,
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .header-table td {
            vertical-align: top;
            padding: 5px;
        }

        .logo {
            width: 100px;
        }

        .company-details {
            font-size: 10px;
            padding-left: 15px;
            padding-right: 15px;
        }

        .company-details strong {
            font-size: 12px;
            display: block;
            margin-bottom: 3px;
        }

        .invoice-box {
            border: 1px solid #000;
            padding: 8px 5px;
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            background-color: #f8f8f8;
        }

        .invoice-title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin: 10px 0;
            text-transform: uppercase;
            padding: 5px;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
        }

        .client-table td {
            padding: 4px 8px;
            vertical-align: top;
        }

        .client-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .items-table {
            margin-top: 10px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            word-wrap: break-word;
        }

        .items-table th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
            font-size: 11px;
        }

        .items-table td {
            text-align: left;
            font-size: 11px;
        }

        .items-table td.num {
            text-align: right;
        }

        .items-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .totals-table {
            margin-left: auto;
            width: 50%;
            min-width: 200px;
        }

        .totals-table td {
            padding: 6px 10px;
        }

        .totals-table td:first-child {
            font-weight: bold;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .totals-table td:last-child {
            text-align: right;
            border-bottom: 1px solid #eee;
            font-family: 'Courier New', monospace;
        }

        .totals-table tr:last-child td {
            border-bottom: none;
            font-weight: bold;
            background-color: #f0f0f0;
        }

        .totals-table tr.total-row td {
            font-size: 13px;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
        }

        .footer {
            font-size: 9px;
            text-align: center;
            border-top: 1px dashed #aaa;
            padding-top: 8px;
            color: #666;
            margin-top: 20px;
        }

        .qr-container {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            border: 1px dashed #ccc;
            border-radius: 5px;
            background-color: #fafafa;
        }

        .qr-code {
            margin: 0 auto;
            padding: 5px;
            background: white;
            display: inline-block;
        }

        .hash {
            font-family: monospace;
            font-size: 8px;
            word-break: break-all;
            margin-top: 5px;
            color: #555;
        }

        .text-muted {
            color: #777;
        }

        .text-small {
            font-size: 9px;
        }

        .text-bold {
            font-weight: bold;
        }

        .border-top {
            border-top: 1px solid #eee;
        }

        .mt-10 {
            margin-top: 10px;
        }

        .mb-10 {
            margin-bottom: 10px;
        }

        .servicio-detalle {
            font-size: 10px;
            color: #555;
            margin-top: 3px;
        }

        .guia-remision {
            display: inline-block;
            background-color: #f0f0f0;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 9px;
            margin-top: 3px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- ENCABEZADO MEJORADO -->
        <div class="header">
            <table class="header-table">
                <tr>
                    <td style="width: 25%;">
                        <img src="{{ public_path('logo.png') }}" class="logo" alt="Logo">
                    </td>
                    <td style="width: 50%;" class="company-details">
                        <strong>{{ getSetting('BUSINESS_NAME') }}</strong>
                        <div class="text-small">
                            {{ getSetting('BUSINESS_ADDRESS') }}<br>
                            Tel: {{ getSetting('BUSINESS_PHONE') }} |
                            Email: {{ getSetting('BUSINESS_EMAIL') }}<br>
                            RUC: {{ getSetting('BUSINESS_RUC') }}
                        </div>
                    </td>
                    <td style="width: 25%;">
                        <div class="invoice-box">
                            {{ $comprobante->tipo === '03' ? 'BOLETA DE VENTA' : 'FACTURA' }}<br>
                            {{ $comprobante->serie }}-{{ str_pad($comprobante->numero, 8, '0', STR_PAD_LEFT) }}
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="invoice-title">
            {{ $comprobante->tipo === '03' ? 'BOLETA DE VENTA ELECTRÓNICA' : 'FACTURA ELECTRÓNICA' }}
        </div>

        <!-- DATOS DEL CLIENTE MEJORADO -->
        <div class="client-info">
            <table class="client-table">
                <tr>
                    <td style="width: 60%;"><strong>Cliente:</strong>
                        {{ strtoupper($comprobante->cliente->nombreCompleto) }}</td>
                    <td><strong>Fecha Emisión:</strong>
                        {{ date('d/m/Y H:i:s', strtotime($comprobante->fecha_emision)) }}</td>
                </tr>
                <tr>
                    <td>
                        <strong>Documento:</strong>
                        @if ($comprobante->cliente->tipoDocumento == '1')
                            DNI
                        @elseif($comprobante->cliente->tipoDocumento == '6')
                            RUC
                        @else
                            DOC({{ $comprobante->cliente->tipoDocumento }})
                        @endif
                        {{ $comprobante->cliente->numeroDocumento }}
                    </td>
                    <td><strong>Dirección:</strong> {{ $comprobante->cliente->direccion ?? 'SIN DIRECCIÓN' }}</td>
                </tr>
                @if ($comprobante->pago && $comprobante->pago->envio && $comprobante->pago->envio->user)
                    <tr>
                        <td colspan="2"><strong>Atendido por:</strong> {{ $comprobante->pago->envio->user->name }}
                        </td>
                    </tr>
                @endif
            </table>
        </div>

        <!-- DETALLE MEJORADO -->
        <div class="items">
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 8%;">Cant.</th>
                        <th style="width: 52%;">Descripción</th>
                        <th style="width: 15%;">P. Unitario</th>
                        <th style="width: 15%;">Importe</th>
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
                                <td class="num">{{ number_format($item->cantidad, 0) }}</td>
                                <td>
                                    <div class="text-bold">SERVICIO DE TRANSPORTE</div>
                                    <div class="servicio-detalle">{{ $item->descripcion }}</div>
                                    @if ($comprobante->pago->envio->guiaRemision)
                                        <div class="guia-remision">
                                            Guía Remisión: {{ $comprobante->pago->envio->guiaRemision->codigo }}
                                        </div>
                                    @endif
                                </td>
                                <td class="num">S/ {{ number_format($montoPorItem / $item->cantidad, 2) }}</td>
                                <td class="num">S/ {{ number_format($montoPorItem, 2) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="num">1</td>
                            <td>
                                <div class="text-bold">SERVICIO DE TRANSPORTE DE CARGA</div>
                                <div class="servicio-detalle">Transporte de mercancías varias</div>
                            </td>
                            <td class="num">S/ {{ number_format($comprobante->monto_total / 1.18, 2) }}</td>
                            <td class="num">S/ {{ number_format($comprobante->monto_total / 1.18, 2) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- TOTALES MEJORADO -->
        @php
            $total_gravada = $comprobante->monto_total / 1.18;
            $igv = $total_gravada * 0.18;
        @endphp
        <div class="totals">
            <table class="totals-table">
                <tr>
                    <td>OP. GRAVADA</td>
                    <td>S/ {{ number_format($total_gravada, 2) }}</td>
                </tr>
                <tr>
                    <td>IGV (18%)</td>
                    <td>S/ {{ number_format($igv, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>TOTAL A PAGAR</td>
                    <td>S/ {{ number_format($comprobante->monto_total, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="font-style: italic; padding-top: 10px;">
                        <strong>SON:</strong> {{ strtoupper($comprobante->monto_total_letras) }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- QR Y CÓDIGO HASH MEJORADO -->
        @if ($comprobante->estado_sunat === 'aceptado' && !empty($comprobante->hash_code))
            <div class="qr-container">
                <div class="text-bold mb-10">CÓDIGO DE VERIFICACIÓN</div>
                <div class="qr-code">
                    <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->size(120)->generate($comprobante->qr_text)) }}"
                        alt="Código QR" width="120">
                </div>
                <div class="hash mt-10">
                    {{ chunk_split($comprobante->hash_code, 50, '<br>') }}
                </div>
                <div class="text-small mt-10">
                    Verifique este documento en <strong>{{ getSetting('BUSINESS_URL') }}</strong>
                </div>
            </div>
        @endif

        <!-- FOOTER MEJORADO -->
        <div class="footer">
            <div class="text-bold">Representación impresa de la
                {{ $comprobante->tipo === '03' ? 'Boleta de Venta' : 'Factura' }} Electrónica</div>
            <div class="text-small mt-10">
                {{ env('BUSINESS_NAME', 'Nombre de la Empresa') }} - Sistema de Facturación Electrónica<br>
                Fecha de impresión: {{ date('d/m/Y H:i:s') }}
            </div>
        </div>
    </div>
</body>

</html>
