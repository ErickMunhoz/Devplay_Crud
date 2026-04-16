<?php
session_start();

// Verificar se o usuário está logado como administrador
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header('Location: ../login.php');
    exit();
}

include '../config/conexao.php';

$sql = "SELECT * FROM jogos ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Jogos - DevPlay</title>
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

        .game-thumb {
            width: 80px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
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
                        <li><a href="usuarios_listar.php" class="nav-link">Gerenciar Usuários</a></li>
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
            <h2>Gerenciar Jogos</h2>
            <a href="cadastrar.php" class="btn btn-add">Cadastrar Novo Jogo</a>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>Título</th>
                        <th>Tipo</th>
                        <th>URL</th>
                        <th style="min-width: 180px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>
                                <?php if (preg_match('/\.mp4($|\?)/i', $row['imagem'])) { ?>
                                    <video src="<?php echo htmlspecialchars($row['imagem']); ?>" class="game-thumb" autoplay loop muted playsinline style="object-fit: cover;"></video>
                                <?php } else { ?>
                                    <img src="<?php echo htmlspecialchars($row['imagem']); ?>" class="game-thumb" alt="">
                                <?php } ?>
                            </td>
                            <td><?php echo $row['titulo']; ?></td>
                            <td><?php echo $row['tipo']; ?></td>
                            <td style="word-break: break-all; max-width: 200px;"><?php echo $row['url']; ?></td>
                            <td>
                                <div class="action-container">
                                    <a href="editar.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">Editar</a>
                                    <a href="processar.php?acao=deletar&id=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Tem certeza que deseja excluir este jogo da plataforma DevPlay?')">Excluir</a>
                                </div>
                            </td>
                        </tr>
                    <?php
endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>