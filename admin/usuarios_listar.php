<?php
session_start();

// Verificar se o usuário está logado como administrador
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header('Location: ../login.php');
    exit();
}

include '../config/conexao.php';

$sql = "SELECT * FROM usuarios ORDER BY data_cadastro DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários - DevPlay</title>
    <link rel="stylesheet" href="../css/style.css">
    
    <!-- JAVASCRIPT: Lógica do tema carregada bem cedo para evitar piscar de tela -->
    <script src="../js/theme.js"></script>
    <style>
        .admin-container {
            padding-top: 140px;
            padding-bottom: 50px;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            border: none;
        }

        .btn-add {
            background-color: var(--primary);
            color: white;
        }

        .btn-edit {
            background-color: var(--warning);
            color: white;
        }

        .btn-delete {
            background-color: var(--error);
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .action-container {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            background: var(--surface);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        th {
            background-color: var(--surface-hover);
            color: var(--text-primary);
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: var(--text-secondary);
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
                <!-- NAVEGAÇÃO E BOTÃO DE TEMA -->
                <nav class="nav">
                    <ul class="nav-menu active" style="position: static; display: flex; opacity: 1; visibility: visible; transform: none; box-shadow: none; border: none; background: none; align-items: center; margin: 0; padding: 0;">
                        <li><a href="../admin/listar.php" class="nav-link">Gerenciar Jogos</a></li>
                        <li><a href="../index.php" class="nav-link">Voltar ao Site</a></li>
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
        <div class="admin-header">
            <h2>Gerenciar Usuários</h2>
            <a href="usuarios_cadastrar.php" class="btn btn-add">Cadastrar Novo Usuário</a>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Data de Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($usuario = mysqli_fetch_assoc($result)) {
                            $data_cadastro = date('d/m/Y H:i', strtotime($usuario['data_cadastro']));
                            echo "
                            <tr>
                                <td>{$usuario['id']}</td>
                                <td>{$usuario['nome']}</td>
                                <td>{$usuario['email']}</td>
                                <td>{$data_cadastro}</td>
                                <td>
                                    <div class='action-container'>
                                        <a href='usuarios_editar.php?id={$usuario['id']}' class='btn btn-edit'>Editar</a>
                                        <a href='usuarios_deletar.php?id={$usuario['id']}' class='btn btn-delete' onclick='return confirm(\"Tem certeza que deseja deletar este usuário?\");'>Deletar</a>
                                    </div>
                                </td>
                            </tr>
                            ";
                        }
                    } else {
                        echo "
                        <tr>
                            <td colspan='5' class='no-data'>
                                Nenhum usuário cadastrado ainda.
                            </td>
                        </tr>
                        ";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <script src="../js/main.js"></script>
</body>

</html>
