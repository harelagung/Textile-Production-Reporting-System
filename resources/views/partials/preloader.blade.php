<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flowing Water Preloader</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .preloader-container {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
        }

        .infinity-loader {
            width: 80px;
            height: 40px;
            position: relative;
        }

        .infinity-path {
            width: 100%;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .infinity-path::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80px;
            height: 40px;
            border: 3px solid transparent;
            border-radius: 40px 40px 0 0;
            border-top-color: #1e40af;
            border-left-color: #1e40af;
            border-right-color: #1e40af;
            animation: rotate-infinity 2s linear infinite;
        }

        .infinity-path::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(180deg);
            width: 80px;
            height: 40px;
            border: 3px solid transparent;
            border-radius: 40px 40px 0 0;
            border-top-color: #3b82f6;
            border-left-color: #3b82f6;
            border-right-color: #3b82f6;
            animation: rotate-infinity-reverse 2s linear infinite;
        }

        .water-drop {
            position: absolute;
            width: 8px;
            height: 8px;
            background: linear-gradient(45deg, #60a5fa, #93c5fd);
            border-radius: 50%;
            animation: flow-infinity 2s ease-in-out infinite;
            box-shadow: 0 0 10px rgba(96, 165, 250, 0.6);
        }

        .water-drop:nth-child(2) {
            animation-delay: -0.5s;
            background: linear-gradient(45deg, #3b82f6, #60a5fa);
        }

        .water-drop:nth-child(3) {
            animation-delay: -1s;
            background: linear-gradient(45deg, #1e40af, #3b82f6);
        }

        .water-drop:nth-child(4) {
            animation-delay: -1.5s;
            background: linear-gradient(45deg, #1e3a8a, #1e40af);
        }

        .loading-text {
            color: #e2e8f0;
            font-size: 1.2rem;
            font-weight: 500;
            text-align: center;
            opacity: 0;
            animation: fade-in-up 1s ease-out 0.5s forwards;
            letter-spacing: 0.05em;
        }

        .loading-dots {
            display: inline-block;
            animation: loading-dots 1.5s infinite;
        }

        .percentage {
            color: #60a5fa;
            font-size: 1.5rem;
            font-weight: 600;
            margin-top: 1rem;
            opacity: 0;
            animation: fade-in-up 1s ease-out 1s forwards;
        }

        .ripple {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 120px;
            height: 120px;
            border: 2px solid rgba(96, 165, 250, 0.3);
            border-radius: 50%;
            animation: ripple-effect 3s ease-out infinite;
        }

        .ripple:nth-child(2) {
            animation-delay: -1s;
            border-color: rgba(59, 130, 246, 0.2);
        }

        .ripple:nth-child(3) {
            animation-delay: -2s;
            border-color: rgba(30, 64, 175, 0.1);
        }

        @keyframes rotate-infinity {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        @keyframes rotate-infinity-reverse {
            0% {
                transform: translate(-50%, -50%) rotate(180deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(540deg);
            }
        }

        @keyframes flow-infinity {
            0% {
                transform: translate(0px, 0px) scale(1);
                opacity: 1;
            }

            25% {
                transform: translate(30px, -10px) scale(1.2);
                opacity: 0.8;
            }

            50% {
                transform: translate(0px, -20px) scale(1);
                opacity: 1;
            }

            75% {
                transform: translate(-30px, -10px) scale(0.8);
                opacity: 0.6;
            }

            100% {
                transform: translate(0px, 0px) scale(1);
                opacity: 1;
            }
        }

        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes loading-dots {

            0%,
            20% {
                opacity: 0;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        @keyframes ripple-effect {
            0% {
                width: 80px;
                height: 80px;
                opacity: 1;
            }

            100% {
                width: 200px;
                height: 200px;
                opacity: 0;
            }
        }

        /* Responsive */
        @media (max-width: 640px) {
            .infinity-loader {
                width: 60px;
                height: 30px;
            }

            .infinity-path::before,
            .infinity-path::after {
                width: 60px;
                height: 30px;
            }

            .loading-text {
                font-size: 1rem;
            }

            .percentage {
                font-size: 1.3rem;
            }
        }
    </style>
</head>

<body>
    <div class="preloader-container">
        <div class="ripple"></div>
        <div class="ripple"></div>
        <div class="ripple"></div>

        <div class="infinity-loader">
            <div class="infinity-path">
                <div class="water-drop"></div>
                <div class="water-drop"></div>
                <div class="water-drop"></div>
                <div class="water-drop"></div>
            </div>
        </div>

        <div class="loading-text">
            Memuat<span class="loading-dots">...</span>
        </div>

        <div class="percentage" id="percentage">0%</div>
    </div>

    <script>
        // Simulate loading progress
        let progress = 0;
        const percentageEl = document.getElementById('percentage');

        const loadingInterval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress >= 100) {
                progress = 100;
                clearInterval(loadingInterval);

                // Optional: Hide preloader after completion
                setTimeout(() => {
                    document.body.style.opacity = '0';
                    setTimeout(() => {
                        document.body.style.display = 'none';
                        // Here you can show your main content or redirect
                        console.log('Loading complete!');
                    }, 500);
                }, 800);
            }
            percentageEl.textContent = Math.floor(progress) + '%';
        }, 200);
    </script>
</body>

</html>
