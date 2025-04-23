<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Envío #{{ $envio->numeroOrden }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
            size: 58mm auto;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 8px;
            width: 54mm;
            margin: 0 auto;
            padding: 2mm;
            line-height: 1.2;
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
            font-size: 7px;
        }

        .text-xs {
            font-size: 6px;
        }

        .section {
            margin-bottom: 4px;
            padding-bottom: 4px;
            border-bottom: 1px solid #eaeaea;
        }

        .section:last-of-type {
            border-bottom: none;
        }

        .header {
            margin-bottom: 4px;
            padding-bottom: 4px;
            border-bottom: 1px solid #2c3e50;
        }

        .nombreEmpresa {
            font-size: 10px;
            font-weight: bold;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7px;
            margin: 2px 0;
        }

        td,
        th {
            padding: 1px 0;
            vertical-align: top;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #ddd;
            padding: 2px;
        }

        .domicilio-section {
            background: #f8f9fa;
            padding: 3px;
            border-radius: 2px;
            border-left: 2px solid #3498db;
            margin: 3px 0;
        }

        .titulo {
            font-weight: bold;
            font-size: 7px;
            margin-bottom: 2px;
            color: #2c3e50;
            border-bottom: 1px dotted #ccc;
            padding-bottom: 1px;
        }

        .qr-container {
            margin: 3px auto;
            text-align: center;
        }

        .qr-code {
            width: 40px;
            height: 40px;
            border: 1px solid #ddd;
            padding: 2px;
            background: white;
        }

        .badge {
            display: inline-block;
            padding: 1px 3px;
            background: #2c3e50;
            color: white;
            border-radius: 2px;
            font-size: 6px;
            font-weight: bold;
        }

        .footer {
            font-size: 6px;
            color: #7f8c8d;
            text-align: center;
            margin-top: 3px;
        }

        .logo {
            text-align: center;
            margin-bottom: 3px;
        }

        .logo-img {
            max-width: 30px;
            max-height: 20px;
        }

        .alert {
            background: #fff3cd;
            padding: 3px;
            border-radius: 2px;
            border-left: 2px solid #ffc107;
            font-size: 6px;
            margin: 3px 0;
        }

        .two-columns {
            width: 100%;
        }

        .two-columns td {
            width: 50%;
            vertical-align: top;
        }

        .atendido-por {
            font-size: 6px;
            text-align: right;
            margin-bottom: 2px;
            color: #555;
        }
    </style>
</head>

<body>

    <div class="header center">
        <div class="logo">
            <div class="logo-img">[LOGO]</div>
        </div>
        <p class="nombreEmpresa">POPEYE CARGOS SAC</p>
        <p class="text-sm">RUC: 12345678912</p>
        <p class="text-sm">Calle Ignacio Cosio 1505 - La Victoria</p>
    </div>

    <div class="atendido-por">
        Atendido por: {{ $envio->user->name }}
        @if ($envio->user->rol === 'agente' && $envio->user->agencia)
            ({{ $envio->user->agencia->nombre }})
        @endif
    </div>

    <div class="section center">
        <p class="uppercase bold" style="font-size: 9px;">COMPROBANTE DE ENVÍO</p>
        <div class="badge">N° {{ $envio->numeroOrden }}</div>
    </div>

    <table class="two-columns">
        <tr>
            <td>
                <p class="text-sm"><strong>Código:</strong></p>
                <p class="text-sm">{{ $envio->codigo }}</p>
            </td>
            <td>
                @if ($envio->guiaRemision)
                    <p class="text-sm"><strong>Guía:</strong></p>
                    <p class="text-sm">{{ $envio->guiaRemision->codigo }}</p>
                @endif
            </td>
        </tr>
        <tr>
            <td>
                <p class="text-sm"><strong>Fecha:</strong></p>
                <p class="text-sm">{{ date('d/m/Y H:i') }}</p>
            </td>
            <td>
                @if ($envio->guiaRemision)
                    <p class="text-sm"><strong>Traslado:</strong></p>
                    <p class="text-sm">{{ $envio->guiaRemision->fecha_inicio_traslado }}</p>
                @endif
            </td>
        </tr>
    </table>

    @if ($envio->recojoDomicilio)
        <div class="section">
            <p class="titulo">DATOS DE RECOJO</p>
            <div class="domicilio-section">
                <p class="text-sm bold">{{ $envio->recojo_direccion }}</p>
                @if ($envio->recojo_referencia)
                    <p class="text-sm"><strong>Ref:</strong> {{ $envio->recojo_referencia }}</p>
                @endif
                @if ($envio->recojo_telefono)
                    <p class="text-sm"><strong>Contacto:</strong> {{ $envio->recojo_telefono }}</p>
                @endif
            </div>
        </div>
    @endif

    @if ($envio->entregaDomicilio)
        <div class="section">
            <p class="titulo">DATOS DE ENTREGA</p>
            <div class="domicilio-section">
                <p class="text-sm bold">{{ $envio->entrega_direccion }}</p>
                @if ($envio->entrega_referencia)
                    <p class="text-sm"><strong>Ref:</strong> {{ $envio->entrega_referencia }}</p>
                @endif
                @if ($envio->entrega_telefono)
                    <p class="text-sm"><strong>Contacto:</strong> {{ $envio->entrega_telefono }}</p>
                @endif
            </div>
        </div>
    @endif

    <div class="section">
        <p class="titulo">DETALLE DEL ENVÍO</p>
        <table class="table-bordered">
            <thead>
                <tr>
                    <th width="15%">Cant</th>
                    <th width="15%">UM</th>
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
    </div>

    <table class="two-columns">
        <tr>
            <td>
                <p class="titulo">REMITENTE</p>
                <p class="text-sm bold">{{ $envio->remitente->nombreCompleto }}</p>
                <p class="text-sm">{{ $envio->remitente->numeroDocumento }}</p>
                <p class="text-sm">{{ $envio->remitente->telefono }}</p>
            </td>
            <td>
                <p class="titulo">DESTINATARIO</p>
                <p class="text-sm bold">{{ $envio->destinatario->nombreCompleto }}</p>
                <p class="text-sm">{{ $envio->destinatario->numeroDocumento }}</p>
                <p class="text-sm">{{ $envio->destinatario->telefono }}</p>
            </td>
        </tr>
    </table>

    <div class="section">
        <p class="titulo">RUTA</p>
        <table class="two-columns">
            <tr>
                <td>
                    <p class="text-sm"><strong>Origen:</strong></p>
                    <p class="text-sm">{{ $envio->agenciaOrigen->direccion }}</p>
                </td>
                <td>
                    <p class="text-sm"><strong>Destino:</strong></p>
                    <p class="text-sm">{{ $envio->agenciaDestino->direccion }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="qr-container">
            <div class="qr-code">[QR]</div>
            <p class="text-xs">Escanee para verificar</p>
        </div>
    </div>

    <div class="alert">
        <p class="bold center text-xs uppercase">Importante</p>
        <ul style="padding-left: 10px; margin: 2px 0;">
            <li>Presente este comprobante para reclamos</li>
            <li>Válido por 48 horas</li>
            <li>Datos ingresados por el remitente</li>
        </ul>
    </div>

    <div class="footer">
        <p>Gracias por su preferencia</p>
        <p>www.popeyecargo.com/consulta</p>
        <p class="text-xs">{{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>

</html>
