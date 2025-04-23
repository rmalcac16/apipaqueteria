<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Comprobante de Envío</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            margin: 30px;
        }

        .titulo {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            width: 120px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: left;
        }

        .seccion {
            margin-top: 25px;
        }

        .firma {
            margin-top: 50px;
        }
    </style>
</head>

<body>

    <div class="logo">
        <img src="{{ public_path('images/logo_popeyecargos.png') }}" alt="Logo PopeyeCargos">
    </div>

    <div class="titulo">Comprobante de Envío - {{ $envio->codigo }}</div>

    <table>
        <tr>
            <th>Fecha</th>
            <td>{{ $envio->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <th>Remitente</th>
            <td>{{ $envio->remitente->nombreCompleto ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Destinatario</th>
            <td>{{ $envio->destinatario_nombre }}</td>
        </tr>
        <tr>
            <th>Documento</th>
            <td>{{ $envio->destinatario_doc ?? '-' }}</td>
        </tr>
        <tr>
            <th>Dirección Origen</th>
            <td>{{ $envio->direccion_origen }}</td>
        </tr>
        <tr>
            <th>Dirección Destino</th>
            <td>{{ $envio->direccion_destino }}</td>
        </tr>
    </table>

    <table class="seccion">
        <tr>
            <th>Descripción</th>
            <td>{{ $envio->descripcion }}</td>
        </tr>
        <tr>
            <th>Peso (kg)</th>
            <td>{{ $envio->peso_kg }}</td>
        </tr>
        <tr>
            <th>Volumen (m³)</th>
            <td>{{ $envio->volumen_m3 }}</td>
        </tr>
        <tr>
            <th>¿Frágil?</th>
            <td>{{ $envio->es_fragil ? 'Sí' : 'No' }}</td>
        </tr>
        <tr>
            <th>Pago por</th>
            <td>{{ ucfirst($envio->metodo_pago) }}</td>
        </tr>
        <tr>
            <th>Estado</th>
            <td>{{ ucfirst($envio->estado) }}</td>
        </tr>
    </table>

    <div class="firma">
        <p>___________________________</p>
        <p>Firma del remitente</p>
    </div>

</body>

</html>
