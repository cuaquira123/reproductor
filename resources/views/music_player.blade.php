<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎶 Reproductor upds 5.0</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a1a, #2c2c2c);
            min-height: 100vh;
            color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .offcanvas {
            background: #000000;
            color: #ffffff;
            width: 20rem !important;
        }

        .offcanvas-header h2 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #ff4b4b;
            margin-bottom: 0;
        }

        .offcanvas-body ul li {
            margin-bottom: 1rem;
        }

        .offcanvas-body ul li a {
            color: #cccccc;
            font-size: 1.125rem;
            text-decoration: none;
        }

        .offcanvas-body ul li a:hover {
            color: #ffffff;
        }

        .main-content {
            padding: 2rem;
        }

        .header {
            background: linear-gradient(90deg, #ff4b4b, #ff8c00);
            padding: 1rem;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 1.5rem;
            margin: 0;
        }

        .player-container {
            max-width: 300px;
        }

        .card {
            background: #2a2a2a;
            border: none;
            border-radius: 15px;
            transition: transform 0.3s, box-shadow 0.3s;
            padding: 0.75rem;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(255, 75, 75, 0.2);
        }

        .card-title {
            color: #ff4b4b;
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 0.25rem;
        }

        .card-text {
            color: #cccccc;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        audio {
            width: 100%;
            border-radius: 10px;
            background: #333;
            margin-bottom: 0.5rem;
        }

        .alert {
            background: #ff8c00;
            color: #fff;
            border: none;
            border-radius: 10px;
            animation: fadeIn 0.5s;
        }

        .alert-warning a {
            color: #007bff;
            font-size: 0.85rem;
        }

        .form-container {
            background: #2a2a2a;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }

        .form-container h5 {
            color: #ff4b4b;
            margin-bottom: 1rem;
        }

        .form-container input,
        .form-container select {
            background: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .form-container button {
            background: linear-gradient(90deg, #ff4b4b, #ff8c00);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            color: #fff;
            font-weight: bold;
        }

        .highlighted {
            background: #ff8c00 !important;
            border: 2px solid #ff4b4b;
        }

        .btn-danger {
            font-size: 0.85rem;
            padding: 0.25rem 0.5rem;
        }

        .form-select-sm {
            font-size: 0.85rem;
            padding: 0.25rem 0.5rem;
            background: #333;
            color: #fff;
            border: none;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .carousel-inner img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 1rem;
            }

            .header {
                padding: 1rem;
                flex-direction: column;
                gap: 1rem;
            }

            .header h1 {
                font-size: 1.2rem;
            }

            .player-container {
                max-width: 100%;
            }
        }
 
    </style>
</head>

<body>


<!-- Área sensible al hover para abrir el panel -->
<div id="hoverTrigger" style="position: fixed; top: 0; left: 0; width: 20px; height: 100vh; z-index: 1001;"></div>

<!-- Panel desplegable -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
    
    <div class="offcanvas-header">
        <h2 id="sidebarMenuLabel">🎶 Reproductor UPDS 5.0</h2>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <nav>
            <ul>
                <li><a href="{{ route('musica.index') }}">Inicio</a></li>
                
                @auth
                    <li><a href="{{ route('playlists.index') }}">Tus Listas</a></li>
                    <li><a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar
                            Sesión</a></li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @else
                    <li><a href="{{ route('login') }}">Iniciar Sesión</a></li>
                    <li><a href="{{ route('register') }}">Registrarse</a></li>
                @endauth
            </ul>
        </nav>

        <!-- Formulario para crear carpeta (solo usuarios autenticados) -->
        @auth
            <div class="form-container">
                <h5>Crear Nueva Lista de Reproducción</h5>
                <form action="{{ route('playlists.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nombre_lista" class="form-label">Nombre de la Lista</label>
                        <input type="text" name="nombre" id="nombre_lista" class="form-control"
                            placeholder="Ej: Mi Lista Favorita" required>
                    </div>
                    <button type="submit">Crear Lista</button>
                </form>
            </div>
        @endauth

        <!-- Formulario para subir música desde el ordenador (solo usuarios autenticados) -->
        @auth
            <div class="form-container">
                <h5>Subir Nueva Canción</h5>
                <form action="{{ route('musica.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Nombre de la Canción</label>
                        <input type="text" name="titulo" id="titulo" class="form-control"
                            placeholder="Ej: Mi Canción Favorita" required>
                    </div>
                    <div class="mb-3">
                        <label for="artista" class="form-label">Artista</label>
                        <input type="text" name="artista" id="artista" class="form-control"
                            placeholder="Ej: Nombre del Artista" required>
                    </div>
                    <div class="mb-3">
                        <label for="archivo" class="form-label">Archivo MP3</label>
                        <input type="file" name="archivo" id="archivo" class="form-control"
                            accept="audio/mpeg" required>
                    </div>
                    <div class="mb-3">
                        <label for="lista_id" class="form-label">Seleccionar Lista</label>
                        <select name="lista_id" id="lista_id" class="form-control">
                            <option value="">Ninguna</option>
                            @foreach (auth()->user()->listas as $lista)
                                <option value="{{ $lista->id }}">{{ $lista->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit">Subir Canción</button>
                </form>
            </div>
        @endauth

        <!-- Formulario para añadir música externa -->
        @auth
            <div class="form-container">
                <h5>Añadir Canción Externa</h5>
                <form action="{{ route('musica.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Nombre de la canción</label>
                        <input type="text" name="titulo" id="titulo" class="form-control"
                            placeholder="Ej: Mi Canción Favorita" required>
                    </div>
                    <div class="mb-3">
                        <label for="url_externa" class="form-label">URL de la música</label>
                        <input type="url" name="url_externa" id="url_externa" class="form-control"
                            placeholder="Ej: https://example.com/cancion.mp3" required>
                    </div>
                    <button type="submit">Añadir Canción</button>
                </form>
            </div>
        @endauth
    </div>
</div>

<!-- Script para abrir el panel al pasar el cursor -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const hoverTrigger = document.getElementById('hoverTrigger');
        const sidebarMenu = new bootstrap.Offcanvas(document.getElementById('sidebarMenu'));

        hoverTrigger.addEventListener('mouseenter', function () {
            sidebarMenu.show();
        });
    });
</script>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Botón pequeño en la esquina superior izquierda -->
<button class="btn btn-primary m-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" style="position: fixed; top: 10px; left: 10px; z-index: 1000; padding: 5px 10px; font-size: 0.9rem;">
    ☰
</button>
        <div class="header text-center">
            <h1>🎶 Reproductor UPDS 5.0</h1>
            @auth
                <p>Bienvenido, {{ auth()->user()->name }}</p>
            @else
                <p>Modo Invitado: Inicia sesión para crear tus propias listas de reproducción.</p>
            @endauth
            <div class="player-container">
                <audio controls>
                    <source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3" type="audio/mpeg">
                    Tu navegador no soporta el reproductor de audio.
                </audio>
            </div>
        </div>

        <div id="carouselMusical" class="carousel slide mt-3" data-bs-ride="carousel" data-bs-interval="8000"
            style="max-width: 100%; margin: 0 auto;">
            <div class="carousel-inner rounded-4 shadow-lg">
                <div class="carousel-item active">
                    <img src="https://imgs.search.brave.com/tVzJgtzKoiwz2ytPx710pE2wkCazYXSR2m1Hwv4-rFg/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWcu/ZnJlZXBpay5jb20v/Zm90by1ncmF0aXMv/cGVyc29uYWplLWFu/aW1lLXRvY2FuZG8t/Z3VpdGFycmFfMjMt/MjE1MTEwMzQ0My5q/cGc_c2VtdD1haXNf/aHlicmlkJnc9NzQw"
                        class="d-block w-100 img-fluid" alt="Canción 1">
                </div>
                <div class="carousel-item">
                    <img src="https://imgs.search.brave.com/7y99v5OVEG82HDegXLjlAqPLbb_8saR__27Db5-19f4/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWcu/ZnJlZXBpay5jb20v/Zm90by1ncmF0aXMv/ZGotcGVyc29uYWpl/cy1hbmltZS10b2Nh/bmRvLW11c2ljYV8y/My0yMTUxMTAzNDgw/LmpwZz9zZW10PWFp/c19oeWJyaWQmdz03/NDA"
                        class="d-block w-100 img-fluid" alt="Canción 2">
                </div>
                <div class="carousel-item">
                    <img src="https://imgs.search.brave.com/vhvFqqIW_duDmZ2VkCBmiU6NiejlfosznEXoYqDElOY/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWcu/ZnJlZXBpay5jb20v/Zm90by1ncmF0aXMv/cGVyc29uYWplLWFu/aW1lLXRvY2FuZG8t/dmlvbGluXzIzLTIx/NTExMDMzNzEuanBn/P3NlbXQ9YWlzX2h5/YnJpZCZ3PTc0MA"
                        class="d-block w-100 img-fluid" alt="Canción 3">
                </div>
                <div class="carousel-item">
                    <img src="https://imgs.search.brave.com/AwhvoYQqubPfKJAEjRbLT1X50bB6ua_PmZxnnX_VS8M/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWcu/ZnJlZXBpay5jb20v/Zm90by1ncmF0aXMv/cGVyc29uYWplLWRp/YnVqb3MtYW5pbWFk/b3MtM2QtaGFjaWVu/ZG8tZGotZmllc3Rh/XzIzLTIxNTE2ODg1/MjMuanBnP3NlbXQ9/YWlzX2h5YnJpZCZ3/PTc0MA"
                        class="d-block w-100 img-fluid" alt="Canción 4">
                </div>
                <div class="carousel-item">
                    <img src="https://imgs.search.brave.com/VXD0CeJTpkS_bTXhH7odjsbvA2Viar_xHkJUpiZl0yY/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWcu/ZnJlZXBpay5jb20v/Zm90by1ncmF0aXMv/aWx1c3RyYWNpb24t/YXJ0ZS1kaWdpdGFs/LXJldHJvLXBlcnNv/bmEtdXNhbmRvLXRl/Y25vbG9naWEtcmFk/aW9fMjMtMjE1MTM1/NjA1Ny5qcGc_c2Vt/dD1haXNfaHlicmlk/Jnc9NzQw"
                        class="d-block w-100 img-fluid" alt="Canción 5">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselMusical" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselMusical" data-bs-slide="next">
                <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>

        <div class="container mt-3">
            <!-- Formulario para buscar música -->
            <div class="form-container mb-3">
                <h5>Buscar Canción</h5>
                <form action="{{ route('musica.search') }}" method="GET">
                    <div class="mb-3">
                        <label for="search" class="form-label">Buscar canción por nombre</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Ej: Canción 1" required>
                    </div>
                    <button type="submit">Buscar</button>
                </form>
            </div>

            <!-- Listado de canciones -->
            <p>Total de canciones: {{ count($canciones) }}</p>
            @if (empty($canciones) || count($canciones) === 0)
                <div class="alert text-center">
                    <span>🎵 No hay canciones disponibles por el momento.</span>
                </div>
            @else
                <div class="row">
                    @foreach ($canciones as $cancion)
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <div class="card shadow {{ request()->query('search') && stripos($cancion->titulo, request()->query('search')) !== false ? 'highlighted' : '' }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ htmlspecialchars($cancion->titulo) }}</h5>
                                    <p class="card-text">🎤 Artista: {{ htmlspecialchars($cancion->artista) }}</p>
                                    @if ($cancion->user)
                                        <p class="card-text">📁 Subido Por: {{ htmlspecialchars($cancion->user->name) }}</p>
                                    @endif

                                    @php
                                        $src = $cancion->url_externa ?? asset('storage/' . $cancion->archivo);
                                    @endphp

                                    <audio controls class="w-100">
                                        <source src="{{ $src }}" type="audio/mpeg">
                                        Tu navegador no soporta el reproductor de audio.
                                    </audio>

                                    <!-- Botón para eliminar (solo si el usuario es el propietario) -->
                                    @auth
                                        @if ($cancion->user_id === auth()->id())
                                            <form action="{{ route('musica.destroy', $cancion->id) }}" method="POST"
                                                onsubmit="return confirm('¿Estás seguro de eliminar esta canción?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger mt-2">Eliminar</button>
                                            </form>
                                        @endif
                                    @endauth

                                    <!-- Formulario para añadir a listas personales -->
                                    @auth
                                        <div class="mt-2">
                                            @if (auth()->user()->listas->count() > 0)
                                                <form action="{{ route('playlists.addCancion', ['lista' => 'SELECTED_LIST']) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="cancion_id" value="{{ $cancion->id }}">
                                                    <select name="lista_id"
                                                        onchange="this.form.action=this.form.action.replace('SELECTED_LIST', this.value); this.form.submit()"
                                                        class="form-select form-select-sm">
                                                        <option value="">Agregar a lista...</option>
                                                        @foreach (auth()->user()->listas as $lista)
                                                            <option value="{{ $lista->id }}">{{ htmlspecialchars($lista->nombre) }}</option>
                                                        @endforeach
                                                    </select>
                                                </form>
                                            @else
                                                <div class="alert alert-warning p-1 mt-1">
                                                    <a href="#create-playlist" class="text-decoration-none"
                                                        onclick="document.getElementById('nombre_lista').focus()">
                                                        Crea tu primera lista
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Mensajes de éxito o error -->
            @if (session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger text-center">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>