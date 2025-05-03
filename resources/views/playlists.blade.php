<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tus Listas - Reproductor UPDS 4.0</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a1a, #2c2c2c);
            min-height: 100vh;
            color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            padding: 2rem;
        }
        .alert {
            background: #ff8c00;
            color: #fff;
            border: none;
            border-radius: 10px;
        }
        .list-group-item {
            background: #2a2a2a;
            color: #cccccc;
            border: none;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        .list-group-item:hover {
            background: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tus Listas de Reproducción</h1>
        @if ($listas->isEmpty())
            <div class="alert text-center">No tienes listas creadas.</div>
        @else
            <ul class="list-group">
                @foreach ($listas as $lista)
                    <li class="list-group-item">
                        {{ $lista->nombre }} ({{ $lista->canciones->count() }} canciones)
                    </li>
                @endforeach
            </ul>
        @endif
        <a href="{{ route('musica.index') }}" class="btn btn-primary mt-3">Volver al Reproductor</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>