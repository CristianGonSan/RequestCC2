<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Notificación de nueva solicitud recibida en el centro de costos.">
    <meta name="author" content="CODIAS">
    <meta name="robots" content="noindex,nofollow">
    <title>Notificación de Nueva Solicitud</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #0044cc;
            font-size: 26px;
            margin-bottom: 15px;
        }

        p {
            margin: 10px 0;
            line-height: 1.5;
        }

        .highlight {
            font-weight: bold;
            color: #0044cc;
        }

        .link {
            color: #0044cc;
            text-decoration: none;
            font-weight: bold;
        }

        .link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Cambio de Estado de Solicitud</h1>
        <p><strong>Fecha y Hora:</strong> {{ $request->updated_at->format('d-m-Y h:i:s a') }}</p>

        <p>El estado de la solicitud #{{ $request->id }} ha cambiado a
            <strong>{{ $request->getStatusText() }}</strong>
        </p>

        <p><strong>Tipo de Solicitud:</strong> {{ $request->is_transfer ? 'Transferencia' : 'Efectivo' }}<br>
            <strong>Monto:</strong> ${{ number_format($request->amount, 2) }}
        </p>
        <p><strong>Concepto:</strong> {{ $request->concept }}</p>
        <p>Para revisar los detalles de la solicitud, por favor haga clic en el siguiente enlace:</p>
        <p><a href="{{ route('requests.show', $request->id) }}" class="link">Ver Solicitud (Propia)</a></p>
        <p><a href="{{ route('management.requests.show', $request->id) }}" class="link">Ver Solicitud (Admin)</a></p>
        <p><a href="{{ route('accounting.requests.show', $request->id) }}" class="link">Ver Solicitud
                (Contabilidad)</a></p>
    </div>
</body>

</html>
