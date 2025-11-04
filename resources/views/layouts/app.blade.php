<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Datepicker CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">

    <!-- jQuery (necessário) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/locales/bootstrap-datepicker.pt-BR.min.js"></script>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Custom Scripts -->
    <script src="{{ asset('js/app-custom.js') }}" defer></script>

    <!-- CSS Customizado para Paginação -->
    <link href="{{ asset('css/pagination.css') }}" rel="stylesheet">

    <style>
        main {
            padding-top: 70px; /* ou um valor que cubra a altura da navbar */
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Nunito', sans-serif;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.1) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            width: 100%;
            z-index: 1050; /* ou mais alto, para ficar na frente */
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .content-wrapper {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            margin: 2rem auto;
            padding: 2rem;
            max-width: 1200px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .col-acoes {
            width: 1%;
            white-space: nowrap;
            text-align: center;
        }

        .btn-group > * > .btn,
        .btn-group > .btn {
            border-radius: 0 !important; /* remove o arredondamento de todos inicialmente */
        }

        /* Primeiro botão da esquerda */
        .btn-group > a:first-child,
        .btn-group > form:first-child button {
            border-top-left-radius: 25px !important;
            border-bottom-left-radius: 25px !important;
        }

        /* Último botão da direita */
        .btn-group > a:last-child,
        .btn-group > form:last-child button {
            border-top-right-radius: 25px !important;
            border-bottom-right-radius: 25px !important;
        }

        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-success {
            background: linear-gradient(45deg, #56ab2f, #a8e6cf);
            border: none;
            border-radius: 25px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(86, 171, 47, 0.4);
        }

        .btn-warning {
            background: linear-gradient(45deg, #f093fb, #f5576c);
            border: none;
            border-radius: 25px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(240, 147, 251, 0.4);
        }

        .btn-danger {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            border: none;
            border-radius: 25px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: 0.3s ease;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 107, 107, 0.4);
        }

        .table {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .table thead th {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            font-weight: 600;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.1);
            transform: scale(1.02);
        }

        .form-control {
            border-radius: 15px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .alert {
            border-radius: 15px;
            border: none;
            padding: 1rem 1.5rem;
        }

        .alert-success {
            background: linear-gradient(45deg, #56ab2f, #a8e6cf);
            color: white;
        }

        .alert-danger {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
        }

        .page-title {
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .dropdown-menu {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item {
            border-radius: 10px;
            margin: 0.25rem;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            transform: translateX(5px);
        }

        .user-avatar {
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .user-avatar:hover {
            transform: scale(1.1);
            border-color: rgba(255, 255, 255, 0.8);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .nav-link {
            border-radius: 10px;
            margin: 0 0.25rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/home') }}">
                    <i class="fas fa-book me-2"></i>{{ config('app.name', 'Sistema de Livros') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownCrud" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-database me-1"></i> Gerenciar
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownCrud">
                                    <li><a class="dropdown-item" href="{{ route('books.index') }}"><i class="fas fa-book me-2"></i> Livros</a></li>
                                    <li><a class="dropdown-item" href="{{ route('authors.index') }}"><i class="fas fa-user-edit me-2"></i> Autores</a></li>
                                    <li><a class="dropdown-item" href="{{ route('subjects.index') }}"><i class="fas fa-tags me-2"></i> Assuntos</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownReports" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-chart-bar me-1"></i> Relatórios
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownReports">
                                    <li><a class="dropdown-item" href="{{ route('reports.books-by-author') }}"><i class="fas fa-file-alt me-2"></i> Livros por Autor</a></li>

                                    <li><hr class="dropdown-divider"></li>

                                </ul>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-1"></i>Login</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}"><i class="fas fa-user-plus me-1"></i>Registrar</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img class="user-avatar rounded-circle me-2"
                                        src="{{ Auth::user()->arquivo ? asset('storage/' . Auth::user()->arquivo->caminho) : 'https://via.placeholder.com/32' }}"
                                        alt="avatar"
                                        width="32" height="32">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="content-wrapper fade-in-up">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
     <!-- Bootstrap JS -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

