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
    <h1>Cuenta Creada</h1>
    <p><strong>Fecha y Hora:</strong> {{ $user->created_at->format('d/m/Y h:i:s a') }}</p>

    <p>Su cuenta ha sido registrada y habilitada para usar el sistema de Centro de Costos.</p>

    <p><strong>Nombre de Usuario:</strong> {{ $user->name }}</p>
    <p><strong>Correo:</strong> {{ $user->email }}</p>
    <p><strong>Contraseña:</strong> {{ $password }}</p>

    <p>Puede intentar iniciar sesión con estas credenciales, en el siguiente enlace:</p>
    <p><a href="{{ route('login') }}" class="link">Iniciar Sesión</a></p>
</div>
</body>
</html>