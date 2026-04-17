<?php 
session_start();
$page_title = 'Login';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./imagem/logo.png">
    <title><?php echo $page_title; ?> - DevPlay</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 20px;
        }

        .login-box {
            background-color: #1e293b;
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.3);
            width: 100%;
            max-width: 500px;
            padding: 0;
            overflow: hidden;
            border: 1px solid #334155;
        }

        .login-header {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            padding: 40px 30px;
            text-align: center;
            border-bottom: 2px solid #6366f1;
            text-decoration: none;
        }

        .login-header h1 {
            color: #ffffff;
            font-size: 2rem;
            margin: 0;
            font-weight: bold;
        }

        .login-header p {
            color: #e0e7ff;
            margin: 8px 0 0 0;
            font-size: 0.95rem;
        }

        .login-header .emoji {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .login-tabs {
            display: flex;
            border-bottom: 2px solid #334155;
            background-color: #0f172a;
        }

        .login-tab {
            flex: 1;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            background-color: #0f172a;
            color: #94a3b8;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }

        .login-tab:hover {
            background-color: #1e293b;
            color: #818cf8;
        }

        .login-tab.active {
            background-color: #1e293b;
            color: #818cf8;
            border-bottom-color: #818cf8;
        }

        .login-content {
            padding: 40px;
            display: none;
        }

        .login-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #f1f5f9;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #334155;
            border-radius: 8px;
            font-size: 1rem;
            background-color: #0f172a;
            color: #f1f5f9;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-group input::placeholder {
            color: #64748b;
        }

        .form-group input:focus {
            outline: none;
            border-color: #818cf8;
            background-color: #1e293b;
            box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.1);
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            cursor: pointer;
            accent-color: #6366f1;
        }

        .remember-me label {
            color: #64748b;
            margin: 0;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 15px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgb(99 102 241 / 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .login-footer {
            text-align: center;
            color: #64748b;
            font-size: 0.9rem;
            padding: 0 40px 20px;
        }

        .login-footer a {
            color: #6366f1;
            text-decoration: none;
            transition: color 0.3s ease;
            font-weight: 600;
        }

        .login-footer a:hover {
            color: #8b5cf6;
            text-decoration: underline;
        }

        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .alert.show {
            display: block;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 2px solid #fecaca;
        }

        .alert-success {
            background-color: #dcfce7;
            color: #166534;
            border: 2px solid #bbf7d0;
        }

        .adm-info {
            background-color: #0f172a;
            border: 2px solid #334155;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            color: #f1f5f9;
            font-size: 0.9rem;
        }

        .adm-info strong {
            color: #6366f1;
        }

        @media (max-width: 600px) {
            .login-box {
                max-width: 100%;
            }

            .login-header {
                padding: 30px 20px;
            }

            .login-header a {
                text-decoration: none;
                color: inherit; 
            }

            .login-header h1 {
                font-size: 1.5rem;
                text-decoration: none;
                color: inherit; 
            }

            .login-header .emoji {
                font-size: 2rem;
            }

            .login-content {
                padding: 25px;
            }

            .login-footer {
                padding: 0 25px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <!-- Header -->
            <div class="login-header" style="padding: 0; background: none; border-bottom: none;">
                <a href="index.php" style="display: block; line-height: 0;">
                    <video src="teste.mp4" autoplay loop muted playsinline style="width: 100%; height: auto; display: block; object-fit: cover;"></video>
                </a>
            </div>

            <!-- Tabs -->
            <?php if(isset($_SESSION['sucesso_geral'])): ?>
                <div style="background-color: #dcfce7; color: #166534; padding: 15px; text-align: center; font-weight: bold; border-bottom: 2px solid #bbf7d0;">
                    ✅ <?php echo htmlspecialchars($_SESSION['sucesso_geral']); unset($_SESSION['sucesso_geral']); ?>
                </div>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['usuario_logado']) || isset($_SESSION['admin_logado'])): ?>
                <?php 
                $nome = isset($_SESSION['nome_usuario']) ? htmlspecialchars(explode(' ', trim($_SESSION['nome_usuario']))[0]) : (isset($_SESSION['usuario_admin']) ? htmlspecialchars($_SESSION['usuario_admin']) : 'Usuário');
                ?>
                <div style="padding: 40px; text-align: center;">
                    <h2 style="color: #6366f1; margin-bottom: 15px;">Você já está conectado!</h2>
                    <p style="font-size: 1.1rem; color: #f1f5f9; margin-bottom: 30px;">Olá, <strong><?php echo $nome; ?></strong>. A sua sessão já está ativa.</p>
                    <a href="index.php" class="btn-login" style="display: inline-block; text-decoration: none; width: auto; padding: 12px 30px; box-sizing: border-box;">🏠 Ir para a Home</a>
                    <div style="margin-top: 20px;">
                        <a href="logout.php" style="color: #ef4444; font-weight: bold; text-decoration: none; display: inline-block;">Quer sair da conta?</a>
                    </div>
                </div>
            <?php else: ?>
            <div class="login-tabs">
                <button class="login-tab active" onclick="switchTab('usuario')">
                    👤 Usuário
                </button>
                <button class="login-tab" onclick="switchTab('adm')">
                    🔐 Administrador
                </button>
            </div>

            <!-- Conteúdo de Login - Usuário -->
            <div id="usuario" class="login-content active">
                <h2 style="color: #6366f1; text-align: center; margin-top: 0;">Login de Usuário</h2>
                
                <div class="alert alert-error" id="userError"></div>
                <div class="alert alert-success" id="userSuccess"></div>

                <form method="POST" action="processar_login.php" onsubmit="return validateUserLogin()">

                    <div class="form-group">
                        <label for="user_email">E-mail</label>
                        <input type="email" id="user_email" name="email" placeholder="seu@email.com" required>
                    </div>

                    <div class="form-group">
                        <label for="user_password">Senha</label>
                        <input type="password" id="user_password" name="senha" placeholder="Digite sua senha" required>
                    </div>

                    <div class="remember-me">
                        <input type="checkbox" id="user_remember" name="remember">
                        <label for="user_remember">Lembrar-me</label>
                    </div>

                    <button type="submit" name="tipo_login" value="usuario" class="btn-login">Entrar como Usuário</button>
                </form>

                <div class="login-footer">
                    Não tem uma conta? <a href="cadastro_usuario.php">Criar uma agora</a> | <a href="index.php">Voltar ao início</a>
                </div>
            </div>

            <!-- Conteúdo de Login - Administrador -->
            <div id="adm" class="login-content">
                <h2 style="color: #6366f1; text-align: center; margin-top: 0;">Login Administrativo</h2>

                <div class="adm-info">
                    <strong>🔐 Acesso Restrito</strong><br>
                    Esta área é exclusiva para administradores. Você terá acesso ao painel de controle para gerenciar jogos e conteúdos.
                </div>

                <div class="alert alert-error" id="admError"></div>
                <div class="alert alert-success" id="admSuccess"></div>

                <form method="POST" action="processar_login.php" onsubmit="return validateAdmLogin()">

                    <div class="form-group">
                        <label for="adm_usuario">Usuário</label>
                        <input type="text" id="adm_usuario" name="usuario" placeholder="Nome de usuário" required>
                    </div>

                    <div class="form-group">
                        <label for="adm_password">Senha</label>
                        <input type="password" id="adm_password" name="senha" placeholder="Digite sua senha" required>
                    </div>

                    <div class="form-group">
                        <label for="adm_codigo">Código de Acesso</label>
                        <input type="password" id="adm_codigo" name="codigo" placeholder="Código de segurança" required>
                    </div>

                    <div class="remember-me">
                        <input type="checkbox" id="adm_remember" name="remember">
                        <label for="adm_remember">Lembrar-me</label>
                    </div>

                    <button type="submit" name="tipo_login" value="adm" class="btn-login">Entrar como Administrador</button>
                </form>

                <div class="login-footer">
                    <a href="index.php">Voltar ao início</a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Função para alternar entre as abas
        function switchTab(tab) {
            // Ocultar todos os conteúdos
            document.getElementById('usuario').classList.remove('active');
            document.getElementById('adm').classList.remove('active');

            // Remover classe active de todos os botões
            document.querySelectorAll('.login-tab').forEach(btn => {
                btn.classList.remove('active');
            });

            // Mostrar o conteúdo selecionado
            document.getElementById(tab).classList.add('active');

            // Adicionar classe active ao botão clicado
            event.target.classList.add('active');
        }

        // Validação do formulário de usuário
        function validateUserLogin() {
            const email = document.getElementById('user_email').value;
            const senha = document.getElementById('user_password').value;

            if (!email || !senha) {
                showError('userError', 'Por favor, preencha todos os campos.');
                return false;
            }

            if (!email.includes('@')) {
                showError('userError', 'Por favor, insira um e-mail válido.');
                return false;
            }

            return true;
        }

        // Validação do formulário de administrador
        function validateAdmLogin() {
            const usuario = document.getElementById('adm_usuario').value;
            const senha = document.getElementById('adm_password').value;
            const codigo = document.getElementById('adm_codigo').value;

            if (!usuario || !senha || !codigo) {
                showError('admError', 'Por favor, preencha todos os campos.');
                return false;
            }

            if (senha.length < 6) {
                showError('admError', 'A senha deve ter no mínimo 6 caracteres.');
                return false;
            }

            return true;
        }

        // Função para exibir mensagens de erro
        function showError(elementId, message) {
            const element = document.getElementById(elementId);
            element.textContent = message;
            element.classList.add('show');

            // Remover mensagem após 5 segundos
            setTimeout(() => {
                element.classList.remove('show');
            }, 5000);
        }
    </script>

    <?php if(isset($_SESSION['erro_usuario'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showError('userError', '<?php echo addslashes($_SESSION['erro_usuario']); unset($_SESSION['erro_usuario']); ?>');
            switchTab('usuario');
        });
    </script>
    <?php endif; ?>

    <?php if(isset($_SESSION['erro_adm'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showError('admError', '<?php echo addslashes($_SESSION['erro_adm']); unset($_SESSION['erro_adm']); ?>');
            switchTab('adm');
        });
    </script>
    <?php endif; ?>
</body>
</html>
