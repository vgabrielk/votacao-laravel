<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Bem-vindo à Choicefy!</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Reset e estilos base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            margin: 0;
            padding: 20px 0;
            line-height: 1.6;
            color: #2d3748;
            min-height: 100vh;
        }

        .email-container {
            width: 100%;
            max-width: 640px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            position: relative;
        }

        .email-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-10px) rotate(180deg);
            }
        }

        .header-content {
            position: relative;
            z-index: 2;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header p {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 300;
        }

        .content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 24px;
        }

        .greeting .name {
            color: #667eea;
            font-weight: 700;
        }

        .message {
            font-size: 16px;
            color: #4a5568;
            line-height: 1.7;
            margin-bottom: 32px;
        }

        .message strong {
            color: #2d3748;
            font-weight: 600;
        }

        .cta-section {
            text-align: center;
            margin: 32px 0;
        }

        .cta-button {
            display: inline-block;
            background: #667eea;
            color: #ffffff !important;
            padding: 16px 32px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px -8px rgba(102, 126, 234, 0.4);
            position: relative;
            overflow: hidden;
        }

        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .cta-button:hover::before {
            left: 100%;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px -8px rgba(102, 126, 234, 0.5);
        }

        .footer {
            background: #f7fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .footer p {
            font-size: 13px;
            color: #718096;
            margin-bottom: 8px;
        }

        .footer .copyright {
            font-weight: 500;
            color: #4a5568;
        }

        @media (max-width: 640px) {
            body {
                padding: 10px 0;
            }

            .email-container {
                margin: 0 10px;
                border-radius: 12px;
            }

            .header {
                padding: 30px 20px;
            }

            .header h1 {
                font-size: 24px;
            }

            .content {
                padding: 30px 20px;
            }

            .cta-button {
                padding: 14px 28px;
                font-size: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <h1>Bem-vindo à Choicefy!</h1>
                <p>Estamos felizes em ter você conosco.</p>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Olá, <span class="name">{{ $user->name }}</span>! 👋
            </div>

            <div class="message">
                <p>Seu cadastro na <strong>Choicefy</strong> foi concluído com sucesso.</p>
                <p>Agora você pode criar enquetes, participar de votações e se conectar com outros usuários.</p>
            </div>

            <div class="message">
                <p><small>💡 Dica: personalize seu perfil, adicione amigos e explore todas as funcionalidades do
                        Choicefy!</small></p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Se você não esperava este e-mail, pode ignorá-lo com segurança.</p>
            <p class="copyright">
                &copy; {{ date('Y') }} <strong>Choicefy</strong>. Todos os direitos reservados.
            </p>
        </div>
    </div>
</body>

</html>
