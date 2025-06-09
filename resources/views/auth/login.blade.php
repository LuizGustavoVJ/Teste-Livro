<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #6dd5ed, #c471ed);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: #fff;
            padding: 40px 30px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group i {
            position: absolute;
            top: 12px;
            left: 10px;
            color: #999;
        }

        .input-group input {
            width: 100%;
            padding: 10px 10px 10px 35px;
            border: none;
            border-bottom: 2px solid #eee;
            outline: none;
            font-size: 14px;
            color: #333;
            background: transparent;
        }

        .input-group input::placeholder {
            color: #ccc;
        }

        .forgot-pass {
            text-align: right;
            font-size: 12px;
            margin-top: -10px;
            margin-bottom: 20px;
        }

        .btn-login {
            background: linear-gradient(to right, #00dbde, #fc00ff);
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(252, 0, 255, 0.4);
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            opacity: 0.9;
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            font-size: 13px;
            color: #888;
        }

        .social-login {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .social-login i {
            font-size: 20px;
            color: white;
            padding: 10px;
            border-radius: 50%;
            background: #3b5998;
            cursor: pointer;
            transition: 0.3s;
        }

        .social-login .twitter { background: #1da1f2; }
        .social-login .google  { background: #db4437; }

        .signup-text {
            text-align: center;
            font-size: 13px;
        }

        .signup-text a {
            color: #555;
            font-weight: 600;
            text-decoration: none;
        }

        .btn-signup {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background: linear-gradient(to right, #36d1dc, #5b86e5);
            color: white;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-signup:hover {
            opacity: 0.85;
        }

    </style>
</head>
<body>

    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="email" placeholder="Type your e-mail" required autofocus>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Type your password" required>
            </div>
            <div class="forgot-pass">
                <a href="#">Forgot password?</a>
            </div>
            <button type="submit" class="btn-login">LOGIN</button>
        </form>

        <div class="divider">Or Sign Up Using</div>

        <div class="social-login">
            <i class="fab fa-facebook-f"></i>
            <i class="fab fa-twitter twitter"></i>
            <i class="fab fa-google google"></i>
        </div>

        <div class="signup-text">
            Ainda não tem uma conta? <br>
            <a href="{{ route('register') }}" class="btn-signup">Registre-se</a>
        </div>
    </div>

</body>
</html>
