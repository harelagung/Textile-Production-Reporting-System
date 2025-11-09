<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        @import "https://fonts.googleapis.com/css?family=Ubuntu:400,700italic";
        @import "https://fonts.googleapis.com/css?family=Cabin:400";

        * {
            box-sizing: border-box !important;
        }

        html,
        html body {
            margin: 0 !important;
            padding: 0 !important;
            height: 100dvh !important;
            overflow: hidden !important;
            /* Background dengan specificity lebih tinggi */
            background: url("{{ asset('images/bg.jpg') }}") no-repeat center center fixed !important;
            background-size: cover !important;
        }

        /* Container utama untuk centering */
        .main-container {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            min-height: 100vh !important;
            position: relative !important;
            z-index: 10 !important;
            padding: 20px !important;
        }

        /* =========================================
      Log in form
      ========================================= */
        #fade-box {
            animation: input-entry 3s ease-in;
            z-index: 4;
        }

        .tech-login {
            width: 100%;
            max-width: 500px;
            position: relative;
            z-index: 4;
        }

        .tech-login form {
            --final-form-height: 320px;
            animation: form-entry 3s ease-in-out;
            background: rgba(255, 255, 255, 0.95);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(240, 255, 255, 0.9));
            border: 3px solid #1426ef65;
            box-shadow: 0 0 20px rgba(0, 164, 162, 0.3), 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            display: block;
            min-height: 280px;
            padding: 25px;
            position: relative;
            z-index: 4;
            width: 100%;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .tech-login form:hover {
            border: 3px solid #1081ba;
            box-shadow: 0 0 30px rgba(0, 71, 225, 0.4), 0 8px 20px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        /* Ornamen biru muda untuk form */
        .tech-login form::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #65aedc6d, #ffffff);
            border-radius: 17px;
            z-index: -1;
            opacity: 0.7;
        }

        .tech-login form::after {
            content: '';
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            background: linear-gradient(135deg, rgba(135, 206, 235, 0.1), rgba(176, 224, 230, 0.1));
            border-radius: 10px;
            z-index: -1;
            pointer-events: none;
        }

        .tech-login input {
            background: rgba(255, 255, 255, 0.9);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(240, 248, 255, 0.8));
            border: 2px solid #87CEEB;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 164, 162, 0.1), inset 0 1px 3px rgba(135, 206, 235, 0.2);
            color: #333333;
            display: block;
            font-family: 'Cabin', helvetica, arial, sans-serif;
            font-size: clamp(14px, 2vw, 16px);
            height: 50px;
            margin: 15px auto;
            padding: 0 20px;
            text-shadow: none;
            width: 100%;
            max-width: 400px;
            transition: all 0.3s ease;
        }

        .tech-login input::placeholder {
            color: #888888;
            font-weight: 400;
        }

        .tech-login input:focus {
            animation: box-glow 1s ease-out infinite alternate;
            background: rgba(255, 255, 255, 1);
            border-color: #000ea4;
            box-shadow: 0 0 15px rgba(3, 0, 164, 0.3), inset 0 2px 5px rgba(137, 135, 235, 0.3), 0 5px 10px rgba(0, 0, 0, 0.1);
            color: #333333;
            outline: none;
        }

        .tech-login input:invalid {
            border: 2px solid #0661c43c;
            box-shadow: 0 0 8px rgba(28, 108, 246, 0.3), inset 0 1px 3px rgba(107, 169, 255, 0.1);
        }

        .tech-login button {
            animation: input-entry 3s ease-in;
            background: linear-gradient(135deg, #0e00a4, #0600b8);
            border: 2px solid #0e00a4;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 19, 164, 0.3), 0 2px 4px rgba(0, 0, 0, 0.1);
            color: #ffffff;
            cursor: pointer;
            display: block;
            font-family: Arial, Helvetica, sans-serif;
            font-size: clamp(14px, 2vw, 16px);
            font-weight: 600;
            height: 50px;
            line-height: 46px;
            margin: 20px auto;
            padding: 0;
            position: relative;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .tech-login button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .tech-login button:hover::before {
            left: 100%;
        }

        .tech-login button:hover,
        .tech-login button:focus {
            background: linear-gradient(135deg, #0c00b8, #1200d4);
            box-shadow: 0 6px 16px rgba(0, 5, 164, 0.4), 0 3px 6px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
            outline: none;
            transition: all 0.3s ease;
        }

        .tech-login button:active {
            background: linear-gradient(135deg, #00138b, #0000a4);
            box-shadow: 0 2px 6px rgba(0, 5, 164, 0.3), inset 0 2px 4px rgba(0, 0, 0, 0.1);
            transform: translateY(0);
        }

        /* Description text */
        .description {
            color: #555555;
            font-family: 'Cabin', helvetica, arial, sans-serif;
            font-size: clamp(13px, 2vw, 15px);
            line-height: 1.6;
            margin-bottom: 20px;
            text-align: center;
            animation: input-entry 3s ease-in;
        }

        /* Success message */
        .success-message {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid #22c55e;
            border-radius: 8px;
            color: #16a34a;
            font-family: 'Cabin', helvetica, arial, sans-serif;
            font-size: 14px;
            margin-bottom: 15px;
            padding: 10px 15px;
            animation: input-entry 3s ease-in;
        }

        /* Back to login link */
        .back-to-login {
            margin-top: 15px;
            text-align: center;
        }

        .back-link {
            color: #007bff;
            text-decoration: none;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            transition: color 0.3s ease;
            animation: input-entry 3s ease-in;
        }

        .back-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        /* Error message styling */
        .error-message {
            color: #dc2626;
            font-family: 'Cabin', helvetica, arial, sans-serif;
            font-size: 13px;
            margin-top: 5px;
            animation: input-entry 3s ease-in;
        }

        /* =========================================
      Spinner - Responsif
      ========================================= */
        #circle1 {
            animation: circle1 4s linear infinite, circle-entry 6s ease-in-out;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 50%;
            border: clamp(5px, 2vw, 10px) solid #0000a4;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.5), 0 0 0 clamp(3px, 1vw, 6px) #0011ff;
            height: clamp(250px, 50vw, 500px);
            width: clamp(250px, 50vw, 500px);
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            overflow: hidden;
            opacity: 0.3;
            z-index: -3;
        }

        #inner-cirlce1 {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 50%;
            border: clamp(18px, 4vw, 36px) solid #00fffc;
            height: calc(100% - clamp(10px, 2vw, 20px));
            width: calc(100% - clamp(10px, 2vw, 20px));
            margin: clamp(5px, 1vw, 10px);
            position: relative;
        }

        #inner-cirlce1:before {
            content: ' ';
            width: 50%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            position: absolute;
            top: 0;
            left: 0;
        }

        #inner-cirlce1:after {
            content: ' ';
            width: 100%;
            height: 50%;
            background: rgba(0, 0, 0, 0.8);
            position: absolute;
            top: 0;
            left: 0;
        }

        /* =========================================
      Hexagon Mesh - Responsif
      ========================================= */
        .hexagons {
            animation: logo-entry 4s ease-in;
            color: rgba(0, 0, 0, 0.1);
            font-size: clamp(24px, 5vw, 52px);
            letter-spacing: -0.2em;
            line-height: 0.7;
            position: fixed;
            text-shadow: 0 0 6px rgba(8, 0, 255, 0.3);
            bottom: clamp(-100px, -10vh, -50px);
            width: 100%;
            transform: perspective(600px) rotateX(60deg) scale(1.4);
            z-index: -3;
            overflow: hidden;
        }

        /* =========================================
      Media Queries untuk Responsivitas
      ========================================= */
        @media (max-width: 768px) {
            html {
                font-size: 8px;
            }

            .main-container {
                padding: 15px;
                justify-content: center;
                min-height: 100vh;
            }

            .tech-login form {
                min-height: 260px;
                padding: 20px;
                border-width: 3px;
                margin: 0 auto;
            }

            .tech-login input,
            .tech-login button {
                height: 50px;
                margin: 12px auto;
                font-size: 16px;
            }

            .description {
                font-size: 14px;
            }

            .hexagons {
                font-size: clamp(20px, 4vw, 32px);
                opacity: 0.3;
            }
        }

        @media (max-width: 480px) {
            .main-container {
                padding: 10px;
                justify-content: center;
            }

            .tech-login {
                width: 100%;
                max-width: 350px;
            }

            .tech-login form {
                --final-form-height: 280px;
                min-height: 250px;
                padding: 18px;
                border-width: 2px;
            }

            .tech-login input,
            .tech-login button {
                height: 48px;
                margin: 10px auto;
                font-size: 16px;
            }

            .description {
                font-size: 13px;
            }

            #circle1 {
                opacity: 0.2;
            }
        }

        @media (max-height: 600px) and (orientation: landscape) {
            .main-container {
                flex-direction: row;
                align-items: center;
                padding: 10px;
            }

            .tech-login {
                flex: 1;
                max-width: 400px;
            }

            #circle1 {
                height: clamp(200px, 40vw, 300px);
                width: clamp(200px, 40vw, 300px);
            }
        }

        /* =========================================
      Animation Keyframes
      ========================================= */
        @keyframes circle-entry {
            0% {
                opacity: 0;
                transform: translate(-50%, -50%) scale(0.8);
            }

            20% {
                opacity: 0;
            }

            100% {
                opacity: 0.3;
                transform: translate(-50%, -50%) scale(1);
            }
        }

        @keyframes input-entry {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }

            90% {
                opacity: 0;
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes form-entry {
            0% {
                height: 0;
                width: 0;
                opacity: 0;
                transform: scale(0.8);
            }

            20% {
                height: 0;
                border: 1px solid #004da4fe;
                width: 0;
                opacity: 0;
                transform: scale(0.9);
            }

            40% {
                width: 0;
                height: 280px;
                border: 3px solid #0057a4ee;
                opacity: 1;
                transform: scale(0.95);
            }

            100% {
                height: var(--final-form-height);
                width: 100%;
                transform: scale(1);
            }
        }

        @keyframes box-glow {
            0% {
                border-color: #87CEEB;
                box-shadow: 0 2px 8px rgba(0, 164, 162, 0.1), inset 0 1px 3px rgba(135, 206, 235, 0.2);
            }

            100% {
                border-color: #00a4a2;
                box-shadow: 0 0 15px rgba(0, 164, 162, 0.4), inset 0 2px 5px rgba(135, 206, 235, 0.4), 0 5px 10px rgba(0, 0, 0, 0.1);
            }
        }

        @keyframes circle1 {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        .swal2-large-text .swal2-title {
            font-size: 28px !important;
            font-weight: bold !important;
        }

        .swal2-large-text .swal2-html-container {
            font-size: 18px !important;
            line-height: 1.5 !important;
        }

        .swal2-large-text .swal2-confirm {
            font-size: 16px !important;
            padding: 12px 20px !important;
        }
    </style>
</head>

<body>
    <div class="main-container">
        <section class="tech-login">
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div id="fade-box">
                    <div class="description">
                        <b>Masukkan email Anda, lalu cek inbox untuk link reset password.</b>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="success-message">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Email Address -->
                    <input type="email" name="email" id="email" placeholder="Alamat Email"
                        value="{{ old('email') }}" required autofocus>

                    @error('email')
                        <div class="error-message">
                            {{ $message }}
                        </div>
                    @enderror

                    <button type="submit">Kirim Link Reset Password</button>

                    <!-- Back to Login -->
                    <div class="back-to-login">
                        <a href="{{ route('login') }}" class="back-link">Kembali ke Login</a>
                    </div>
                </div>
            </form>
        </section>
    </div>

    <!-- Background elements -->
    <div id="circle1">
        <div id="inner-cirlce1"></div>
    </div>

    <!-- SweetAlert2 for Success/Error Notification -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('status'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Email Terkirim',
                    text: '{{ session('status') }}',
                    confirmButtonColor: '#0e00a4',
                    confirmButtonText: 'OK',
                    customClass: {
                        popup: 'swal2-large-text'
                    },
                    width: '500px'
                });
            });
        </script>
    @endif

    @error('email')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Email Error',
                    text: '{{ $message }}',
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'OK',
                    customClass: {
                        popup: 'swal2-large-text'
                    },
                    width: '500px'
                });
            });
        </script>
    @enderror

</body>

</html>
