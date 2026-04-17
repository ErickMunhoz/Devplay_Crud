<?php
session_start();

// Verificar se o usuário está logado como administrador
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header('Location: ../login.php');
    exit();
}

include '../config/conexao.php';

$erro = '';
$sucesso = '';
$id = $_GET['id'] ?? '';

if(empty($id)) {
    header('Location: usuarios_listar.php');
    exit();
}

// Buscar usuário
<<<<<<< HEAD
$sql = "SELECT * FROM usuarios WHERE id = $id";
=======
$sql = "SELECT * FROM clientes WHERE id = $id";
>>>>>>> b8b74a4c73e4d7076b9416ec179cf809cc78a0fb
$result = mysqli_query($conn, $sql);

if(!$result || mysqli_num_rows($result) == 0) {
    header('Location: usuarios_listar.php');
    exit();
}

$usuario = mysqli_fetch_assoc($result);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    // Validações
    if(empty($nome) || empty($email)) {
        $erro = 'Nome e email são obrigatórios!';
    } elseif(strlen($nome) < 3) {
        $erro = 'O nome deve ter pelo menos 3 caracteres!';
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Por favor, insira um email válido!';
    } else {
        // Verificar se o email já existe (para outro usuário)
<<<<<<< HEAD
        $sql_check = "SELECT id FROM usuarios WHERE email = '$email' AND id != $id";
=======
        $sql_check = "SELECT id FROM clientes WHERE email = '$email' AND id != $id";
>>>>>>> b8b74a4c73e4d7076b9416ec179cf809cc78a0fb
        $result_check = mysqli_query($conn, $sql_check);
        
        if(mysqli_num_rows($result_check) > 0) {
            $erro = 'Este email já está cadastrado por outro usuário!';
        } else {
            // Se a senha foi preenchida, atualizar com nova senha
            if(!empty($senha)) {
                if(strlen($senha) < 6) {
                    $erro = 'A senha deve ter pelo menos 6 caracteres!';
                } else {
                    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
<<<<<<< HEAD
                    $sql_update = "UPDATE usuarios SET nome = '$nome', email = '$email', senha = '$senha_hash' WHERE id = $id";
                }
            } else {
                // Atualizar sem alterar a senha
                $sql_update = "UPDATE usuarios SET nome = '$nome', email = '$email' WHERE id = $id";
=======
                    $sql_update = "UPDATE clientes SET nome = '$nome', email = '$email', senha = '$senha_hash' WHERE id = $id";
                }
            } else {
                // Atualizar sem alterar a senha
                $sql_update = "UPDATE clientes SET nome = '$nome', email = '$email' WHERE id = $id";
>>>>>>> b8b74a4c73e4d7076b9416ec179cf809cc78a0fb
            }

            if(empty($erro) && mysqli_query($conn, $sql_update)) {
                include '../config/backup.php';
                $sucesso = 'Usuário atualizado com sucesso!';
                // Recarregar os dados
<<<<<<< HEAD
                $sql = "SELECT * FROM usuarios WHERE id = $id";
=======
                $sql = "SELECT * FROM clientes WHERE id = $id";
>>>>>>> b8b74a4c73e4d7076b9416ec179cf809cc78a0fb
                $result = mysqli_query($conn, $sql);
                $usuario = mysqli_fetch_assoc($result);
            } else {
                $erro = 'Erro ao atualizar usuário: ' . mysqli_error($conn);
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
    <title>Editar Usuário - DevPlay Admin</title>
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

        .form-group small {
            display: block;
            margin-top: 5px;
            color: var(--text-secondary);
            font-size: var(--font-size-xs);
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
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo-section">
                    <a href="../index.php" class="logo-link">
                        <div class="logo">🎮</div>
                    </a>
                    <h1 class="site-title">DevPlay Admin</h1>
                </div>
                <nav class="nav">
                    <ul class="nav-menu active" style="position: static; display: flex; opacity: 1; visibility: visible; transform: none; box-shadow: none; border: none; background: none; align-items: center; margin: 0; padding: 0;">
                        <li><a href="usuarios_listar.php" class="nav-link">Voltar</a></li>
                        <li>
                            <button class="theme-toggle" aria-label="Alternar para modo claro ou escuro">
                                <span class="icon-moon" aria-hidden="true">🌙</span>
                                <span class="icon-sun" aria-hidden="true">☀️</span>
                            </button>
                        </li>
                        <li class="nav-user-greeting" style="display: flex; align-items: center; justify-content: center; padding: 0 10px;">
                            <span style="color: var(--primary); font-weight: bold;">
                                👤 Olá, <?php echo isset($_SESSION['nome_usuario']) ? htmlspecialchars(explode(' ', trim($_SESSION['nome_usuario']))[0]) : (isset($_SESSION['usuario_admin']) ? htmlspecialchars($_SESSION['usuario_admin']) : 'Usuário'); ?>
                            </span>
                        </li>
                        <li>
                            <a href="../logout.php" class="nav-link" style="color: #ef4444; font-weight: bold;">Sair</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="admin-container container">
        <div class="form-container">
            <div class="form-header">
                <h2>Editar Usuário</h2>
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
                    <input type="text" id="nome" name="nome" placeholder="Nome do usuário" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="email@exemplo.com" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="senha">Nova Senha (deixe em branco para manter a atual)</label>
                    <input type="password" id="senha" name="senha" placeholder="Deixe em branco para não alterar">
                    <small>Se preenchido, deve ter no mínimo 6 caracteres</small>
                </div>

                <button type="submit" class="btn-submit">Atualizar Usuário</button>
            </form>

            <a href="usuarios_listar.php" class="btn-voltar">← Voltar</a>
        </div>
    </main>

    <script src="../js/main.js"></script>
</body>
</html>
