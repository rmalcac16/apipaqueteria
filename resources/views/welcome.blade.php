<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>API Paquetería</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #121212;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .content {
            text-align: center;
            z-index: 10;
            animation: zoomIn 1.5s ease-out forwards;
            opacity: 0;
        }

        h1 {
            font-size: 4rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(90deg, #7C3AED, #8B5CF6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 900
        }

        p {
            font-size: 1.5rem;
            color: #a1a1aa;
        }

        @keyframes zoomIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .floating {
            position: absolute;
            font-size: 1.5rem;
            opacity: 0.15;
            animation: float 8s linear infinite;
            pointer-events: none;
        }

        .floating:nth-child(1) {
            left: 10%;
            top: 20%;
            animation-delay: 0s;
        }

        .floating:nth-child(2) {
            left: 70%;
            top: 15%;
            animation-delay: 2s;
        }

        .floating:nth-child(3) {
            left: 40%;
            top: 60%;
            animation-delay: 4s;
        }

        .floating:nth-child(4) {
            left: 20%;
            top: 80%;
            animation-delay: 6s;
        }

        .floating:nth-child(5) {
            left: 85%;
            top: 40%;
            animation-delay: 1s;
        }

        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(10deg);
            }

            100% {
                transform: translateY(0) rotate(0deg);
            }
        }
    </style>
</head>

<body>
    <!-- Paquetes flotando -->
    <div class="floating">📦</div>
    <div class="floating">🚚</div>
    <div class="floating">📦</div>
    <div class="floating">📦</div>
    <div class="floating">🚚</div>

    <!-- Texto central -->
    <div class="content">
        <h1>API Paquetería</h1>
        <p>Desarrollado por Dark</p>
    </div>
</body>

</html>
