@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(to right, #6dd5ed, #c471ed);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .register-container {
        max-width: 420px;
        margin: 60px auto;
        padding: 40px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .register-container h2 {
        text-align: center;
        margin-bottom: 30px;
        font-weight: 700;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-control {
        border-radius: 30px;
        padding-left: 20px;
        height: 45px;
    }

    .form-control:focus {
        box-shadow: 0 0 0 0.1rem rgba(198, 99, 255, 0.25);
    }

    .btn-register {
        background: linear-gradient(to right, #36d1dc, #5b86e5);
        border: none;
        color: #fff;
        width: 100%;
        height: 45px;
        border-radius: 30px;
        font-weight: bold;
        letter-spacing: 1px;
        transition: 0.3s;
    }

    .btn-register:hover {
        opacity: 0.9;
    }

    .text-center a {
        color: #6c63ff;
        text-decoration: none;
    }

    .text-center a:hover {
        text-decoration: underline;
    }
</style>

<div class="register-container">
    <h2>Registre-se</h2>

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <input id="name" type="text"
                   class="form-control @error('name') is-invalid @enderror"
                   name="name" value="{{ old('name') }}"
                   placeholder="Full Name" required autocomplete="name" autofocus>
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <input id="email" type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   name="email" value="{{ old('email') }}"
                   placeholder="Email Address" required autocomplete="email">
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <input id="password" type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   name="password"
                   placeholder="Password" required autocomplete="new-password">
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <input id="password-confirm" type="password"
                   class="form-control"
                   name="password_confirmation"
                   placeholder="Confirm Password" required autocomplete="new-password">
        </div>

        <div class="form-group">
            <input type="file"
                   class="form-control @error('imagem') is-invalid @enderror"
                   name="imagem" required>
            @error('imagem')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-register">
            Register
        </button>

        <div class="text-center mt-3">
            Já possui uma conta?
            <a href="{{ route('login') }}">Faça Login aqui</a>
        </div>
    </form>
</div>
@endsection
