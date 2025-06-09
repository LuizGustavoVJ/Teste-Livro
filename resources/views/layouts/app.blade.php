<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Custom Scripts -->
    <script src="{{ asset('js/app-custom.js') }}" defer></script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{-- <i class="fas fa-book"></i> {{ config('app.name', 'Sistema de Livros') }} --}}
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
                                    <i class="fas fa-database"></i> Gerenciar
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownCrud">
                                    <li><a class="dropdown-item" href="{{ route('books.index') }}"><i class="fas fa-book"></i> Livros</a></li>
                                    <li><a class="dropdown-item" href="{{ route('authors.index') }}"><i class="fas fa-user-edit"></i> Autores</a></li>
                                    <li><a class="dropdown-item" href="{{ route('subjects.index') }}"><i class="fas fa-tags"></i> Assuntos</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownReports" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-chart-bar"></i> Relatórios
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownReports">
                                    <li><a class="dropdown-item" href="{{ route('reports.books-by-author') }}"><i class="fas fa-file-alt"></i> Livros por Autor</a></li>
                                    <li><a class="dropdown-item" href="{{ route('reports.books-by-author.from-view') }}"><i class="fas fa-table"></i> Livros por Autor (View)</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('email.form') }}"><i class="fas fa-envelope"></i> Enviar por E-mail</a></li>
                                </ul>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i><span>Login</span></a>
                            @endif
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"><i class="fas fa-user-plus"></i><span>Registrar</span></a>
                            @endif
                        @else
                            <div class="dropdown ms-3">
                                <a class="dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img class="user-avatar rounded-circle"
                                        src="{{ Auth::user()->arquivo ? asset('storage/' . Auth::user()->arquivo->caminho) : 'https://via.placeholder.com/24' }}"
                                        alt="avatar"
                                        width="24" height="24">
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
     <!-- Bootstrap JS -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

