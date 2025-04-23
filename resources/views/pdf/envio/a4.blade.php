<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Comprobante de Envío</title>
    <style>
        @page {
            margin: 20mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
        }

        .header-table,
        .info-table,
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
            padding: 4px;
        }

        .logo-box {
            border: 1px dashed #999;
            height: 80px;
            width: 150px;
            text-align: center;
            font-size: 10px;
        }

        .company-title {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .comprobante-box {
            border: 2px solid #000;
            text-align: center;
            padding: 5px;
            font-size: 13px;
        }

        .section {
            margin-top: 12px;
            border: 1px solid #000;
            padding: 6px;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 4px;
            font-size: 13px;
        }

        .info-table td {
            border: 1px solid #000;
            padding: 5px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        .firma {
            height: 80px;
            border: 1px solid #000;
            padding: 5px;
            margin-top: 10px;
        }

        .footer {
            font-size: 10px;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Cabecera -->
        <table class="header-table">
            <tr>
                <td width="65%">
                    <div class="company-title">Popeye Cargos SAC</div>
                    <p>RUC: 12345678912</p>
                    <p>Dirección: Calle Ignacio Cosio 1505 - LA VICTORIA - LIMA</p>
                    <p>Teléfono: 98765432</p>
                </td>
                <td align="center">
                    <div class="logo-box">LOGO AQUÍ</div>
                </td>
            </tr>
        </table>

        <div class="comprobante-box">
            <strong>COMPROBANTE DE ENVÍO</strong><br>
            N° {{ $envio->numeroOrden }}
        </div>

        <!-- Info envío -->
        <div class="section">
            <table class="info-table">
                <tr>
                    <td><strong>Fecha Emisión:</strong> {{ $envio->fechaEmision }}</td>
                    <td><strong>Fecha Traslado:</strong> {{ $envio->fechaTraslado }}</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Código:</strong> {{ $envio->codigo }}</td>
                </tr>
            </table>
        </div>

        <!-- Remitente / Destinatario -->
        <div class="section">
            <table class="info-table">
                <tr>
                    <td><strong>Remitente:</strong><br>
                        {{ $envio->remitente->nombre_completo }}<br>
                        DNI/RUC: {{ $envio->remitente->documento }}<br>
                        Tel: {{ $envio->remitente->telefono }}
                    </td>
                    <td><strong>Destinatario:</strong><br>
                        {{ $envio->destinatario->nombre_completo }}<br>
                        DNI/RUC: {{ $envio->destinatario->documento }}<br>
                        Tel: {{ $envio->destinatario->telefono }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- Ruta -->
        <div class="section">
            <div class="section-title">Ruta</div>
            <table class="info-table">
                <tr>
                    <td><strong>Origen:</strong><br>{{ $envio->agenciaOrigen->direccion }}<br>{{ $envio->agenciaOrigen->distrito }}
                        / {{ $envio->agenciaOrigen->provincia }}</td>
                    <td><strong>Destino:</strong><br>{{ $envio->agenciaDestino->direccion }}<br>{{ $envio->agenciaDestino->distrito }}
                        / {{ $envio->agenciaDestino->provincia }}</td>
                </tr>
            </table>
        </div>

        <!-- Detalle -->
        <div class="section">
            <div class="section-title">Detalle del Envío</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Descripción</th>
                        <th>Peso (kg)</th>
                        <th>Volumen (m³)</th>
                        <th>Fragil</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>{{ $envio->descripcion }}</td>
                        <td>{{ $envio->peso }}</td>
                        <td>{{ $envio->volumen }}</td>
                        <td>{{ $envio->fragil ? 'Sí' : 'No' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Recojo / Entrega -->
        @if ($envio->recojoDomicilio)
            <div class="section">
                <div class="section-title">Recojo en Domicilio</div>
                <p>Dirección: {{ $envio->recojo_direccion }}</p>
                @if ($envio->recojo_referencia)
                    <p>Referencia: {{ $envio->recojo_referencia }}</p>
                @endif
                @if ($envio->recojo_telefono)
                    <p>Teléfono: {{ $envio->recojo_telefono }}</p>
                @endif
            </div>
        @endif

        @if ($envio->entregaDomicilio)
            <div class="section">
                <div class="section-title">Entrega en Domicilio</div>
                <p>Dirección: {{ $envio->entrega_direccion }}</p>
                @if ($envio->entrega_referencia)
                    <p>Referencia: {{ $envio->entrega_referencia }}</p>
                @endif
                @if ($envio->entrega_telefono)
                    <p>Teléfono: {{ $envio->entrega_telefono }}</p>
                @endif
            </div>
        @endif

        <!-- Firma -->
        <div class="section">
            <div class="section-title">Conformidad del Cliente</div>
            <div class="firma">
                Firma: ___________________________<br><br>
                Nombre: ___________________________<br>
                DNI: ______________________________
            </div>
        </div>

        <div class="footer">
            La mercadería viaja por cuenta y riesgo del remitente. No se aceptan devoluciones.
        </div>
    </div>
</body>

</html>
