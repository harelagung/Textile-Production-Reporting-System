<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

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
      Logo
      ========================================= */
        #logo {
            animation: logo-entry 4s ease-in;
            width: min(300px, 80vw);
            margin-bottom: 30px;
            position: relative;
            z-index: 40;
        }

        #logo img {
            width: 100%;
            height: auto;
            max-width: 170px;
        }

        /* =========================================
      Verification form
      ========================================= */
        #fade-box {
            animation: input-entry .6s ease-out both;
            /* z-index: 4; */
        }

        /* Animasi kotak */
        .verification-container {
            animation: form-entry 0.8s ease forwards;
            overflow: hidden;
            /* supaya isi gak tumpuk pas expand */
        }

        /* Animasi isi teks */
        .verification-content>* {
            opacity: 0;
            animation: fadeInUp 0.8s ease forwards;
        }

        /* Terapkan ke elemen isi */
        .verification-title,
        .verification-message,
        .success-message,
        .button-container {
            opacity: 0;
            /* default invisible */
            animation: fadeInUp 0.8s ease forwards;
        }

        .tech-verification {
            width: 100%;
            max-width: 600px;
            position: relative;
            z-index: 4;
        }

        .tech-verification .verification-container {
            --final-form-height: 350px;
            animation: form-entry 3s ease-in-out;
            background: rgba(255, 255, 255, 0.95);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(240, 255, 255, 0.9));
            border: 3px solid #1426ef65;
            box-shadow: 0 0 20px rgba(0, 164, 162, 0.3), 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            display: block;
            min-height: 300px;
            padding: 30px;
            position: relative;
            z-index: 4;
            width: 100%;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .tech-verification .verification-container:hover {
            border: 3px solid #1081ba;
            box-shadow: 0 0 30px rgba(0, 71, 225, 0.4), 0 8px 20px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        /* Ornamen biru muda untuk container */
        .tech-verification .verification-container::before {
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

        .tech-verification .verification-container::after {
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

        /* Email Icon */
        .email-icon {
            text-align: center;
            margin-bottom: 25px;
        }

        .email-icon ion-icon {
            font-size: 60px;
            color: #0e00a4;
            opacity: 0.8;
            animation: pulse 2s infinite;
        }

        /* Typography */
        .verification-title {
            animation-delay: 0.8s;
            font-family: 'Ubuntu', helvetica, arial, sans-serif;
            font-size: clamp(20px, 3vw, 24px);
            font-weight: 700;
            color: #333333;
            text-align: center;
            margin-bottom: 20px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .verification-message {
            animation-delay: 1.2s;
            font-family: 'Cabin', helvetica, arial, sans-serif;
            font-size: clamp(14px, 2vw, 16px);
            color: #666666;
            text-align: center;
            line-height: 1.6;
            margin-bottom: 25px;
            background: rgba(240, 248, 255, 0.6);
            padding: 15px;
            border-radius: 10px;
            border-left: 4px solid #87CEEB;
        }

        .success-message {
            animation-delay: 1.6s;
            font-family: 'Cabin', helvetica, arial, sans-serif;
            font-size: clamp(14px, 2vw, 16px);
            color: #155724;
            background: rgba(212, 237, 218, 0.9);
            border: 2px solid #c3e6cb;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 25px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 164, 162, 0.1);
        }

        /* Buttons */
        .tech-verification button {
            animation: input-entry 3s ease-in;
            background: linear-gradient(135deg, #0e00a4, #0600b8);
            border: 2px solid #0e00a4;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 19, 164, 0.3), 0 2px 4px rgba(0, 0, 0, 0.1);
            color: #ffffff;
            cursor: pointer;
            display: inline-block;
            font-family: Arial, Helvetica, sans-serif;
            font-size: clamp(14px, 2vw, 16px);
            font-weight: 600;
            height: 50px;
            line-height: 46px;
            margin: 10px;
            padding: 0 25px;
            position: relative;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
            overflow: hidden;
            min-width: 180px;
        }

        .tech-verification button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .tech-verification button:hover::before {
            left: 100%;
        }

        .tech-verification button:hover,
        .tech-verification button:focus {
            background: linear-gradient(135deg, #0c00b8, #1200d4);
            box-shadow: 0 6px 16px rgba(0, 5, 164, 0.4), 0 3px 6px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
            outline: none;
            transition: all 0.3s ease;
        }

        .tech-verification button:active {
            background: linear-gradient(135deg, #00138b, #0000a4);
            box-shadow: 0 2px 6px rgba(0, 5, 164, 0.3), inset 0 2px 4px rgba(0, 0, 0, 0.1);
            transform: translateY(0);
        }

        /* Logout button - different style */
        .logout-btn {
            background: linear-gradient(135deg, #6c757d, #5a6268) !important;
            border: 2px solid #6c757d !important;
        }

        .logout-btn:hover,
        .logout-btn:focus {
            background: linear-gradient(135deg, #5a6268, #495057) !important;
            border: 2px solid #5a6268 !important;
            box-shadow: 0 6px 16px rgba(108, 117, 125, 0.4), 0 3px 6px rgba(0, 0, 0, 0.15);
        }

        .logout-btn:active {
            background: linear-gradient(135deg, #495057, #343a40) !important;
        }

        /* Button container */
        .button-container {
            animation-delay: .5s;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin-top: 25px;
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

            #logo {
                margin-bottom: 25px;
                width: min(250px, 70vw);
            }

            .tech-verification .verification-container {
                min-height: 280px;
                padding: 25px;
                border-width: 3px;
                margin: 0 auto;
            }

            .button-container {
                flex-direction: column;
                gap: 10px;
            }

            .tech-verification button {
                width: 100%;
                max-width: 280px;
                margin: 5px 0;
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

            #logo {
                margin-bottom: 20px;
                width: min(200px, 60vw);
            }

            .tech-verification {
                width: 100%;
                max-width: 380px;
            }

            .tech-verification .verification-container {
                --final-form-height: 320px;
                min-height: 300px;
                padding: 20px;
                border-width: 2px;
            }

            .tech-verification button {
                height: 48px;
                line-height: 44px;
                font-size: 16px;
                min-width: 160px;
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

            #logo {
                margin-bottom: 0;
                margin-right: 20px;
                flex-shrink: 0;
            }

            .tech-verification {
                flex: 1;
                max-width: 500px;
            }

            #circle1 {
                height: clamp(200px, 40vw, 300px);
                width: clamp(200px, 40vw, 300px);
            }
        }

        /* =========================================
      Animation Keyframes
      ========================================= */

        /* Animasi teks */
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(15px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes logo-entry {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }

            80% {
                opacity: 0;
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

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
                opacity: 0;
                transform: scale(0.8);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }

            50% {
                transform: scale(1.1);
                opacity: 1;
            }

            100% {
                transform: scale(1);
                opacity: 0.8;
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
        {{-- <div id="logo">
            <center>
                <img src="{{ asset('images/logo ihara.png') }}" alt="Ihara Logo">
            </center>
        </div> --}}

        <section class="tech-verification">
            <div id="fade-box">
                <div class="verification-container">
                    <div class="verification-content">
                        <!-- Email Icon -->
                        <div class="email-icon">
                            <ion-icon name="mail-outline"></ion-icon>
                        </div>

                        <!-- Title -->
                        <h2 class="verification-title">Verifikasi Email Anda</h2>

                        <!-- Main Message -->
                        <div class="verification-message">
                            {{ __('Selamat Datang! Silakan verifikasi email Anda lewat tautan yang kami kirim. Belum menerima email? Klik Kirim Ulang Email Verifikasi.') }}
                        </div>

                        <!-- Success Message (jika ada) -->
                        @if (session('status') == 'verification-link-sent')
                            <div class="success-message">
                                {{ __('Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.') }}
                            </div>
                        @endif

                        <!-- Button Container -->
                        <div class="button-container">
                            <!-- Resend Verification Button -->
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit">
                                    <ion-icon name="refresh-outline"
                                        style="margin-right: 8px; font-size: 16px;"></ion-icon>
                                    {{ __('Kirim Ulang Email Verifikasi') }}
                                </button>
                            </form>

                            <!-- Logout Button -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="logout-btn">
                                    <ion-icon name="log-out-outline"
                                        style="margin-right: 8px; font-size: 16px;"></ion-icon>
                                    {{ __('Keluar') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- SweetAlert2 for Success Notification -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('status') == 'verification-link-sent')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Email Terkirim',
                    text: 'Tautan verifikasi baru telah dikirim ke email Anda.',
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
</body>

</html>
