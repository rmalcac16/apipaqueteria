<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Envío #{{ $envio->numeroOrden }}</title>
    <style>
        @page {
            margin: 0;
            size: 80mm auto;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            width: 76mm;
            margin: 0 auto;
            padding: 5mm 3mm;
            line-height: 1.5;
            color: #000;
            background-color: #fff;
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

        .section {
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 1px dashed #444;
        }

        .header {
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #000;
        }

        .titulo {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 2px;
            color: #111;
        }

        .text-xs {
            font-size: 10px;
        }

        .text-sm {
            font-size: 11px;
        }

        .text-md {
            font-size: 13px;
        }

        .text-lg {
            font-size: 15px;
        }

        .text-xl {
            font-size: 17px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .product-table th {
            background-color: #e0e0e0;
            border: 1px solid #333;
            padding: 4px;
            font-size: 11px;
        }

        .product-table td {
            border: 1px solid #333;
            padding: 4px;
            font-size: 11px;
        }

        .two-columns td {
            width: 50%;
            vertical-align: top;
            padding-right: 5px;
        }

        .qr-container {
            margin-top: 6px;
            text-align: center;
        }

        .qr-code {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            border: 1px solid #333;
        }

        .etiqueta {
            background-color: #ffeeba;
            border: 1px solid #ffc107;
            padding: 2px 4px;
            font-size: 9px;
            border-radius: 3px;
            display: inline-block;
            margin-top: 4px;
            color: #000;
        }
    </style>
</head>

<body>

    <!-- ENCABEZADO -->
    <div class="header center">
        <p class="text-lg bold">{{ getSetting('BUSINESS_NAME') }}</p>
        <p class="text-sm">RUC: {{ getSetting('BUSINESS_RUC') }}</p>
        <p class="text-sm">{{ getSetting('BUSINESS_ADDRESS') }}</p>
        <p class="text-sm">TEL: {{ getSetting('BUSINESS_PHONE') }}</p>
    </div>

    <!-- TÍTULO -->
    <div class="section center">
        <p class="text-xl bold">Orden #{{ $envio->numeroOrden }}</p>

        @if ($envio->guiaRemision)
            <p class="text-md bold">Guía de Remisión:
                {{ $envio->guiaRemision->serie }}-{{ str_pad($envio->guiaRemision->numero, 8, '0', STR_PAD_LEFT) }}</p>
        @endif

        <p class="text-sm">Código: {{ $envio->codigo }}</p>
        <p class="text-sm">Fecha: {{ date('d/m/Y H:i') }}</p>
    </div>

    <!-- DATOS DE PARTES -->
    <div class="section">
        <table class="two-columns">
            <tr>
                <td>
                    <p class="titulo">Remitente</p>
                    <p class="bold">{{ $envio->remitente->nombreCompleto }}</p>
                    <p class="text-sm">DNI/RUC: {{ $envio->remitente->numeroDocumento }}</p>
                    <p class="text-sm">Tel: {{ $envio->remitente->telefono }}</p>
                </td>
                <td>
                    <p class="titulo">Destinatario</p>
                    <p class="bold">{{ $envio->destinatario->nombreCompleto }}</p>
                    <p class="text-sm">DNI/RUC: {{ $envio->destinatario->numeroDocumento }}</p>
                    <p class="text-sm">Tel: {{ $envio->destinatario->telefono }}</p>
                </td>
            </tr>
        </table>
    </div>

    <!-- ORIGEN Y DESTINO -->
    <div class="section">
        <table class="two-columns">
            <tr>
                <td>
                    <p class="titulo">Origen</p>
                    <p class="bold">
                        @if ($envio->recojoDomicilio)
                            {{ $envio->recojo_direccion }}
                        @else
                            {{ $envio->agenciaOrigen->direccion }}
                        @endif
                    </p>
                    <p class="text-sm">
                        @if ($envio->recojoDomicilio)
                            {{ $envio->recojo_distrito }} / {{ $envio->recojo_provincia }} /
                            {{ $envio->recojo_departamento }}
                        @else
                            {{ $envio->agenciaOrigen->distrito }} / {{ $envio->agenciaOrigen->provincia }} /
                            {{ $envio->agenciaOrigen->departamento }}
                        @endif
                    </p>
                </td>
                <td>
                    <p class="titulo">Destino</p>
                    <p class="bold">
                        @if ($envio->entregaDomicilio)
                            {{ $envio->entrega_direccion }}
                        @else
                            {{ $envio->agenciaDestino->direccion }}
                        @endif
                    </p>
                    <p class="text-sm">
                        @if ($envio->entregaDomicilio)
                            {{ $envio->entrega_distrito }} / {{ $envio->entrega_provincia }} /
                            {{ $envio->entrega_departamento }}
                        @else
                            {{ $envio->agenciaDestino->distrito }} / {{ $envio->agenciaDestino->provincia }} /
                            {{ $envio->agenciaDestino->departamento }}
                        @endif
                    </p>
                </td>
            </tr>
        </table>
    </div>

    {{-- ENTREGA --}}

    <div class="section">
        <p class="text-sm">
            <strong class="bold uppercase">Entrega:</strong>
            @if ($envio->entregaDomicilio)
                {{ $envio->entrega_direccion }} / {{ $envio->entrega_distrito }} / {{ $envio->entrega_provincia }} /
                {{ $envio->entrega_departamento }}
            @else
                ENTREGAR EN AGENCIA
            @endif
        </p>
    </div>

    <!-- DETALLE DEL ENVÍO -->
    <div class="section">
        <p class="titulo">Detalle del Envío</p>
        <table class="product-table">
            <thead>
                <tr>
                    <th>Cant</th>
                    <th>UM</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($envio->items as $item)
                    <tr>
                        <td class="center">{{ $item->cantidad }}</td>
                        <td class="center">{{ $item->unidad_medida }}</td>
                        <td>{{ $item->descripcion }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p class="text-sm right" style="margin-top: 4px;">Peso: {{ number_format($envio->pesoTotal, 2) }}
            {{ $envio->unidadMedida }}</p>
    </div>

    <!-- INFORMACIÓN FINAL -->
    <div class="section center">
        <p class="bold uppercase text-md">Total a Pagar</p>
        <p class="bold" style="font-size: 18px;">S/ {{ number_format($envio->costoEnvio, 2) }}</p>

        <div style="margin-top: 6px;">
            <p class="bold text-md">Forma de pago:</p>
            <p class="bold uppercase text-lg">{{ strtoupper($envio->formaPago) }}</p>
        </div>

        @if ($envio->valorDeclarado)
            <p class="text-sm bold">Valor declarado: S/ {{ number_format($envio->valorDeclarado, 2) }}</p>
        @endif

        @if ($envio->esFragil || $envio->esPeligroso)
            <div style="margin-top: 5px;">
                @if ($envio->esFragil)
                    <span class="etiqueta">FRÁGIL</span>
                @endif
                @if ($envio->esPeligroso)
                    <span class="etiqueta" style="background-color: #f8d7da; border-color: #f5c2c7;">PELIGROSO</span>
                @endif
            </div>
        @endif
    </div>

    <!-- OBSERVACIONES -->
    <div class="section">
        <p class="titulo">Observaciones</p>
        <p class="text-sm">• Carga recibida sin verificación de contenido.</p>
        <p class="text-sm">• Remitente asume daños por embalaje/manipulación.</p>
        <p class="text-sm">• Garantía: {{ $envio->contratoGarantia ? 'CONTRATADA' : 'NO CONTRATADA' }}</p>
    </div>


    <!-- ATENDIDO POR -->
    <div class="section">
        <table class="two-columns">
            <tr>
                <td>
                    <p class="text-sm bold">Atendido por:</p>
                    <p class="text-sm">{{ $envio->user->name }}</p>
                </td>
                <td class="right">
                    <p class="text-sm bold">Impresión:</p>
                    <p class="text-sm">{{ date('d/m/Y H:i:s') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <!-- FOOTER -->
    <div class="center text-xs" style="margin-top: 10px;">
        <p>Gracias por confiar en nosotros</p>
        <p>Consulta tu envío: {{ getSetting('BUSINESS_URL') }}</p>
    </div>

</body>

</html>
