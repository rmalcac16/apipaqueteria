<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Envío #{{ $envio->numeroOrden }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
            size: 80mm auto;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            width: 76mm;
            margin: 0 auto;
            padding: 2mm;
            line-height: 1.3;
        }

        p {
            margin: 0 0 4px 0;
            padding: 0;
            word-break: break-word;
        }

        .nombreEmpresa {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
            text-align: center;
        }

        .header {
            border-bottom: 1px dashed #000;
            padding-bottom: 4px;
            margin-bottom: 6px;
        }

        section {
            margin-bottom: 6px;
            padding-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td,
        th {
            padding: 2px 0;
            text-align: left;
            vertical-align: top;
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

        .text-md {
            font-size: 11px;
        }

        .text-sm {
            font-size: 10px;
        }

        .text-xs {
            font-size: 9px;
        }

        .mt-1 {
            margin-top: 4px;
        }

        .mt-2 {
            margin-top: 6px;
        }

        .mb-1 {
            margin-bottom: 4px;
        }

        .mb-2 {
            margin-bottom: 6px;
        }

        .py-1 {
            padding-top: 3px;
            padding-bottom: 3px;
        }

        .bg-light {
            background-color: #f8f8f8;
        }

        .border-top {
            border-top: 1px dashed #000;
        }

        .border-bottom {
            border-bottom: 1px dashed #000;
        }

        .w-50 {
            width: 50%;
        }

        ul {
            padding-left: 14px;
            margin: 4px 0;
        }

        li {
            margin-bottom: 3px;
            font-size: 9px;
        }

        .qr-code {
            margin: 4px auto;
            width: 55px;
            height: 55px;
            border: 1px solid #ddd;
            padding: 2px;
        }

        .compact-section {
            margin-bottom: 5px;
            padding-bottom: 3px;
        }

        .no-margin {
            margin: 0;
        }

        .codigo-orden {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .domicilio-section {
            background-color: #f0f0f0;
            padding: 3px;
            margin: 4px 0;
            border-radius: 2px;
        }

        .domicilio-title {
            font-weight: bold;
            color: #d35400;
        }
    </style>
</head>

<body>

    <div class="header">
        <p class="nombreEmpresa">POPEYE CARGOS SAC</p>
        <p class="center text-sm">RUC: 12345678912</p>
        <p class="center text-sm no-margin">Calle Ignacio Cosio 1505</p>
        <p class="center text-sm no-margin">LA VICTORIA - LIMA</p>
        <p class="center text-sm">Tel: 98765432</p>
    </div>

    <section class="center bg-light py-1">
        <p class="uppercase bold no-margin">COMPROBANTE DE ENVÍO</p>
    </section>

    <section>
        <div class="codigo-orden">
            <span><strong>NRO. ORDEN:</strong> {{ $envio->numeroOrden }}</span>
            <span><strong>CODIGO:</strong> {{ $envio->codigo }}</span>
        </div>

        <table>
            <tr>
                <td class="w-50">
                    <p class="no-margin"><strong>Fecha Emisión:</strong></p>
                    <p class="no-margin text-sm">{{ $envio->fechaEmision }}</p>
                </td>
                <td class="w-50">
                    <p class="no-margin"><strong>Fecha Traslado:</strong></p>
                    <p class="no-margin text-sm">{{ $envio->fechaTraslado }}</p>
                </td>
            </tr>
        </table>
    </section>

    <!-- Sección de Recojo en Domicilio -->
    @if ($envio->recojoDomicilio == 1)
        <section class="domicilio-section">
            <p class="domicilio-title">RECOJO EN DOMICILIO</p>
            <p class="text-sm no-margin"><strong>Dirección:</strong> {{ $envio->recojo_direccion }}</p>
            @if ($envio->recojo_referencia)
                <p class="text-sm no-margin"><strong>Referencia:</strong> {{ $envio->recojo_referencia }}</p>
            @endif
            @if ($envio->recojo_telefono)
                <p class="text-sm no-margin"><strong>Teléfono:</strong> {{ $envio->recojo_telefono }}</p>
            @endif
        </section>
    @endif

    <!-- Sección de Entrega en Domicilio -->
    @if ($envio->entregaDomicilio == 1)
        <section class="domicilio-section">
            <p class="domicilio-title">ENTREGA EN DOMICILIO</p>
            <p class="text-sm no-margin"><strong>Dirección:</strong> {{ $envio->entrega_direccion }}</p>
            @if ($envio->entrega_referencia)
                <p class="text-sm no-margin"><strong>Referencia:</strong> {{ $envio->entrega_referencia }}</p>
            @endif
            @if ($envio->entrega_telefono)
                <p class="text-sm no-margin"><strong>Teléfono:</strong> {{ $envio->entrega_telefono }}</p>
            @endif
        </section>
    @endif

    <section>
        <p class="bold uppercase mb-1">Ruta</p>
        <table>
            <tr>
                <td class="w-50">
                    <p class="bold no-margin">Origen:</p>
                    <p class="text-sm uppercase no-margin">
                        {{ $envio->agenciaOrigen->direccion }}<br>
                        {{ $envio->agenciaOrigen->distrito }} / {{ $envio->agenciaOrigen->provincia }}
                    </p>
                </td>
                <td class="w-50">
                    <p class="bold no-margin">Destino:</p>
                    <p class="text-sm uppercase no-margin">
                        {{ $envio->agenciaDestino->direccion }}<br>
                        {{ $envio->agenciaDestino->distrito }} / {{ $envio->agenciaDestino->provincia }}
                    </p>
                </td>
            </tr>
        </table>
    </section>

    <section>
        <p class="bold uppercase mb-1">Remitente</p>
        <p class="text-sm uppercase no-margin"><strong>Nombre:</strong> {{ $envio->remitente->nombre_completo }}</p>
        <table>
            <tr>
                <td class="w-50">
                    <p class="text-sm no-margin"><strong>DNI/RUC:</strong> {{ $envio->remitente->documento }}</p>
                </td>
                <td class="w-50">
                    <p class="text-sm no-margin"><strong>Teléfono:</strong> {{ $envio->remitente->telefono }}</p>
                </td>
            </tr>
        </table>
    </section>

    <section>
        <p class="bold uppercase mb-1">Destinatario</p>
        <p class="text-sm uppercase no-margin"><strong>Nombre:</strong> {{ $envio->destinatario->nombre_completo }}</p>
        <table>
            <tr>
                <td class="w-50">
                    <p class="text-sm no-margin"><strong>DNI/RUC:</strong> {{ $envio->destinatario->documento }}</p>
                </td>
                <td class="w-50">
                    <p class="text-sm no-margin"><strong>Teléfono:</strong> {{ $envio->destinatario->telefono }}</p>
                </td>
            </tr>
        </table>
    </section>

    <section>
        <p class="bold no-margin">Observaciones:</p>
        <p class="text-sm no-margin">{{ $envio->observaciones ?? 'Sin observaciones' }}</p>
    </section>

    <section class="border-top border-bottom py-1">
        <table>
            <tr>
                <td>
                    <p class="bold no-margin">Total:</p>
                </td>
                <td class="right">
                    <p class="bold no-margin">S/ {{ number_format($envio->pago->monto, 2) }}</p>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p class="text-sm no-margin"><strong>Forma de pago:</strong>
                        {{ ucfirst($envio->pago->forma_pago) }}</p>
                </td>
            </tr>
        </table>
    </section>

    <div class="center mt-2">
        <div class="qr-code center">
            <!-- Código QR -->
            [QR]
        </div>
        <p class="text-sm no-margin">Escanee para verificar</p>
    </div>

    <section class="center mt-2">
        <p class="text-sm no-margin">Gracias por su preferencia</p>
        <p class="text-xs no-margin">Consulta: {{ parse_url(url('/'), PHP_URL_HOST) }}</p>
    </section>

    <section class="text-xs mt-2" style="border-top: 1px dashed #000; padding-top: 4px;">
        <p class="uppercase bold center no-margin">AVISO</p>
        <ul class="no-margin">
            <li>Clave de seguridad es requerida para recojo</li>
            <li>Presentar este comprobante para reclamos</li>
            <li>Válido por 48 horas</li>
        </ul>
    </section>

</body>

</html>
