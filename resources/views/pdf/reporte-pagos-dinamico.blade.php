<!DOCTYPE html>
<html>

<head>
    <title>Reporte de Pagos</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
    </style>
</head>

<body>
    <h2>Reporte de Pagos</h2>
    <p><strong>Agencia:</strong> {{ $filtros['agencia'] }}</p>
    <p><strong>Usuario:</strong> {{ $filtros['usuario'] }}</p>
    <p><strong>Método de Pago:</strong> {{ $filtros['metodo_pago'] }}</p>
    <p><strong>Desde:</strong> {{ $filtros['fecha_inicio'] ?? '---' }} <strong>Hasta:</strong>
        {{ $filtros['fecha_fin'] ?? '---' }}</p>
    <p><strong>Total pagado:</strong> S/. {{ number_format($total, 2) }}</p>
    <p><strong>Fecha de generación:</strong> {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Envío</th>
                <th>Agencia</th>
                <th>Usuario</th>
                <th>Método</th>
                <th>Monto</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pagos as $pago)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $pago->envio->codigo ?? '-' }}</td>
                    <td>{{ $pago->agencia->nombre ?? '-' }}</td>
                    <td>{{ $pago->usuario->nombreCompleto ?? '-' }}</td>
                    <td>{{ $pago->metodo_pago }}</td>
                    <td>S/. {{ number_format($pago->monto, 2) }}</td>
                    <td>{{ $pago->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
