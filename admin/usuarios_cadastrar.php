<?php
session_start();

// Verificar se o usuário está logado como administrador
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header('Location: ../login.php');
    exit();
}

include '../config/conexao.php';
/** @var mysqli $conn */

$erro = '';
$sucesso = '';

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
                include '../config/backup.php';
                $sucesso = 'Usuário cadastrado com sucesso!';
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
    <title>Cadastrar Usuário - DevPlay Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/theme.js"></script>
    <style>
        .admin-container {
            padding-top: 140px;
            padding-bottom: 50px;
        }

        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background: var(--surface);
            padding: 30px;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
        }

        .form-header {
            margin-bottom: 30px;
        }

        .form-header h2 {
            font-size: var(--font-size-2xl);
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-primary);
            font-weight: 600;
            font-size: var(--font-size-sm);
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: var(--font-size-base);
            background: var(--surface);
            color: var(--text-primary);
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-submit:hover {
            opacity: 0.9;
        }

        .btn-voltar {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: var(--surface-hover);
            color: var(--text-primary);
            text-decoration: none;
            border-radius: 6px;
            transition: 0.3s;
        }

        .btn-voltar:hover {
            background: var(--border);
        }

        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: var(--font-size-sm);
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
    </style>
</head>
<body>
    <?php include '../components/header.php'; ?>

    <main class="admin-container container">
        <div class="form-container">
            <div class="form-header">
                <h2>Cadastrar Novo Usuário</h2>
            </div>

            <?php if($erro): ?>
                <div class="alert alert-erro">
                    <?php echo $erro; ?>
                </div>
            <?php endif; ?>

            <?php if($sucesso): ?>
                <div class="alert alert-sucesso">
                    <?php echo $sucesso; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" name="nome" placeholder="Nome do usuário" value="<?php echo isset($nome) ? htmlspecialchars($nome) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="email@exemplo.com" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="Mínimo 6 caracteres" required>
                </div>

                <div class="form-group">
                    <label for="confirmar_senha">Confirmar Senha</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Confirme a senha" required>
                </div>

                <button type="submit" class="btn-submit">Cadastrar Usuário</button>
            </form>

            <a href="usuarios_listar.php" class="btn-voltar">← Voltar</a>
        </div>
    </main>

    <script src="../js/main.js"></script>
</body>
</html>
