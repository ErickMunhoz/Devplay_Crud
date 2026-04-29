<?php
session_start();
include 'config/conexao.php';

// Se o usuário já está logado, redireciona para a página inicial
if(isset($_SESSION['usuario_logado'])) {
    header('Location: index.php');
    exit();
}

$erro = '';
$sucesso = '';

// Processar o formulário de cadastro
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Validações
    if(empty($nome) || empty($email) || empty($senha) || empty($confirmar_senha)) {
        $erro = 'Todos os campos são obrigatórios!';
    } elseif(strlen($nome) < 3) {
        $erro = 'O nome deve ter pelo menos 3 caracteres!';
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Por favor, insira um email válido!';
    } elseif(strlen($senha) < 6) {
        $erro = 'A senha deve ter pelo menos 6 caracteres!';
    } elseif($senha !== $confirmar_senha) {
        $erro = 'As senhas não conferem!';
    } else {
        // Verificar se o email já existe
        $sql_check = "SELECT id FROM usuarios WHERE email = '$email'";
        $result_check = mysqli_query($conn, $sql_check);
        
        if(mysqli_num_rows($result_check) > 0) {
            $erro = 'Este email já está cadastrado!';
        } else {
            // Criptografar a senha
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            
            // Inserir novo usuário
            $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha_hash')";
            
            if(mysqli_query($conn, $sql)) {
                include 'config/backup.php';
                $sucesso = 'Cadastro realizado com sucesso! Você pode fazer login agora.';
                // Limpar o formulário
                $nome = '';
                $email = '';
                $senha = '';
                $confirmar_senha = '';
            } else {
                $erro = 'Erro ao cadastrar usuário: ' . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário - DevPlay</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/theme.js"></script>
    <style>
        .cadastro-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--spacing-4);
            background: var(--background);
        }

        .cadastro-form {
            width: 100%;
            max-width: 450px;
            background: var(--surface);
            padding: var(--spacing-8);
            border-radius: 12px;
            box-shadow: var(--shadow-md);
        }

        .cadastro-header {
            text-align: center;
            margin-bottom: var(--spacing-8);
        }

        .cadastro-header h1 {
            font-size: var(--font-size-2xl);
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: var(--spacing-2);
        }

        .cadastro-header p {
            color: var(--text-secondary);
            font-size: var(--font-size-sm);
        }

        .form-group {
            margin-bottom: var(--spacing-4);
        }

        .form-group label {
            display: block;
            margin-bottom: var(--spacing-2);
            color: var(--text-primary);
            font-weight: 600;
            font-size: var(--font-size-sm);
        }

        .form-group input {
            width: 100%;
            padding: var(--spacing-3);
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: var(--font-size-base);
            background: var(--surface);
            color: var(--text-primary);
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-group input::placeholder {
            color: var(--text-muted);
        }

        .btn-cadastro {
            width: 100%;
            padding: var(--spacing-3);
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            border-radius: 6px;
            font-size: var(--font-size-base);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: var(--spacing-4);
        }

        .btn-cadastro:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px -2px rgba(99, 102, 241, 0.3);
        }

        .btn-cadastro:active {
            transform: translateY(0);
        }

        .alert {
            padding: var(--spacing-4);
            border-radius: 6px;
            margin-bottom: var(--spacing-4);
            font-size: var(--font-size-sm);
            animation: slideDown 0.3s ease;
        }

        .alert-erro {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--error);
            border-left: 4px solid var(--error);
        }

        .alert-sucesso {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border-left: 4px solid var(--success);
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-link {
            text-align: center;
            margin-top: var(--spacing-6);
            color: var(--text-secondary);
            font-size: var(--font-size-sm);
        }

        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: var(--primary-dark);
        }

        @media (max-width: 600px) {
            .cadastro-form {
                padding: var(--spacing-6);
            }

            .cadastro-header h1 {
                font-size: var(--font-size-xl);
            }
        }
    </style>
</head>
<body>
    <div class="cadastro-container">
        <div class="cadastro-form">
            <div class="cadastro-header">
                <h1>🎮 DevPlay</h1>
                <p>Crie sua conta para começar a jogar</p>
            </div>

            <?php if($erro): ?>
                <div class="alert alert-erro">
                    <?php echo $erro; ?>
                </div>
            <?php endif; ?>

            <?php if($sucesso): ?>
                <div class="alert alert-sucesso">
                    <?php echo $sucesso; ?>
                    <br><br>
                    <a href="login.php" style="color: var(--success); text-decoration: underline;">Clique aqui para fazer login</a>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" name="nome" placeholder="Seu nome completo" value="<?php echo isset($nome) ? htmlspecialchars($nome) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="seu.email@exemplo.com" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="Mínimo 6 caracteres" required>
                </div>

                <div class="form-group">
                    <label for="confirmar_senha">Confirmar Senha</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Confirme sua senha" required>
                </div>

                <button type="submit" class="btn-cadastro">Criar Conta</button>
            </form>

            <div class="login-link">
                Já tem uma conta? <a href="login.php">Faça login aqui</a>
            </div>
        </div>
    </div>

    <script src="js/main.js"></script>
</body>
</html>
