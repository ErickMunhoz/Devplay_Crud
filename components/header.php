<?php
// =========================================================================
// COMPONENTE DE CABEÇALHO (HEADER) UNIFICADO
// =========================================================================
// Este arquivo contém o cabeçalho de todas as páginas do nosso projeto.
// Em vez de copiar e colar o mesmo código HTML em toda página, nós "incluímos"
// este arquivo. Assim, se mudarmos o logo ou um botão, muda em todo o site!

// Pega o caminho do script atual (ex: /devplay_crud/admin/listar.php)
$script_path = $_SERVER['SCRIPT_NAME'];

// Verifica se a palavra '/admin/' está no caminho. Se sim, significa que estamos na área administrativa
$is_admin_area = strpos($script_path, '/admin/') !== false;

// Se estivermos no admin, precisamos voltar uma pasta ('../') para achar as imagens e links da raiz
// Se não estivermos (estamos na index), a base é a mesma pasta ('')
$base_url = $is_admin_area ? '../' : '';

// Pega apenas o nome final do arquivo atual (ex: 'listar.php', 'index.php') para saber em que página estamos
$current_page = basename($script_path);

// Define um nome padrão caso não ache nenhum na sessão
$nome_exibicao = 'Usuário';

// Se existir a variável de sessão 'nome_usuario' (significa que um usuário comum está logado)
if (isset($_SESSION['nome_usuario'])) {
    // Quebra o nome completo em pedaços usando o espaço (ex: "João Silva" vira ["João", "Silva"])
    $partes_nome = explode(' ', trim($_SESSION['nome_usuario']));
    // Pega só o primeiro nome e usa htmlspecialchars para segurança (evitar ataques XSS)
    $nome_exibicao = htmlspecialchars($partes_nome[0]);
} 
// Se não, verifica se é um administrador logado
elseif (isset($_SESSION['usuario_admin'])) {
    // Usa o nome do admin diretamente, aplicando a proteção
    $nome_exibicao = htmlspecialchars($_SESSION['usuario_admin']);
}
?>

<!-- CABEÇALHO FIXO DA PÁGINA -->
<!-- Se NÃO estiver no admin, adiciona a tag role="banner" para acessibilidade (leitores de tela) -->
<header class="header" <?php echo !$is_admin_area ? 'role="banner"' : ''; ?>>
    <div class="container">
        <div class="header-content">
            
            <!-- SEÇÃO DO LOGO E NOME DO SITE -->
            <div class="logo-section">
                <!-- O link do logo sempre aponta para a index.php, usando a variável $base_url para acertar o caminho -->
                <a href="<?php echo $base_url; ?>index.php" class="logo-link" <?php echo !$is_admin_area ? 'aria-label="Voltar à página inicial"' : ''; ?>>
                    <!-- Nossa imagem do logo. O $base_url garante que ele ache a pasta 'imagem' não importa em qual subpasta estejamos -->
                    <div class="logo" aria-label="DevPlay Logo"><img src="<?php echo $base_url; ?>imagem/DEV-PLAY-LOGO-Final-correto.svg" alt="logo" width="40px" height="40px"></div>
                </a>
                
                <!-- Título principal: Se for admin, escreve 'DevPlay Admin', se for raiz escreve 'DevPlay' -->
                <h1 class="site-title"><?php echo $is_admin_area ? 'DevPlay Admin' : 'DevPlay'; ?></h1>
                
                <!-- Subtítulo só aparece se NÃO estivermos na área de admin -->
                <?php if (!$is_admin_area): ?>
                    <span class="site-subtitle">Plataforma de Jogos para Treinar Programação</span>
                <?php endif; ?>
            </div>

            <!-- MENU DE NAVEGAÇÃO -->
            <!-- Role="navigation" avisa o navegador que aqui ficam os links principais -->
            <nav class="nav" <?php echo !$is_admin_area ? 'role="navigation" aria-label="Navegação principal"' : ''; ?>>
                
                <!-- Botão de "hambúrguer" (menu mobile) só aparece fora do admin -->
                <?php if (!$is_admin_area): ?>
                    <button class="nav-toggle" aria-label="Abrir menu" aria-expanded="false">
                        <span class="hamburger"></span>
                    </button>
                <?php endif; ?>

                <!-- Lista de links (ul). No admin, aplicamos um estilo para o menu ficar visível direto e não ter versão mobile complexa -->
                <ul class="nav-menu <?php echo $is_admin_area ? 'active' : ''; ?>" <?php echo $is_admin_area ? 'style="position: static; display: flex; opacity: 1; visibility: visible; transform: none; box-shadow: none; border: none; background: none; align-items: center; margin: 0; padding: 0;"' : ''; ?>>
                    
                    <?php if (!$is_admin_area): ?>
                        <!-- LINKS DA PÁGINA INICIAL (RAIZ) -->
                        <li><a href="#jogos" class="nav-link">Jogos</a></li>
                        <li><a href="#sobre" class="nav-link">Sobre</a></li>
                        <li><a href="admin/listar.php" class="nav-link">Gerenciar</a></li>
                        <li>
                            <!-- Botão de notificações (o javascript que controla) -->
                            <button class="notification-button" id="notificationButton" aria-label="Ver notificações">
                                <span class="notification-button-content">
                                    🔔<span class="notification-badge" id="notificationBadge">0</span>
                                </span>
                            </button>
                        </li>
                    <?php else: ?>
                        <!-- LINKS DA ÁREA DE ADMINISTRAÇÃO -->
                        <?php if ($current_page == 'listar.php' || $current_page == 'cadastrar.php' || $current_page == 'editar.php'): ?>
                            <!-- Se estiver gerenciando JOGOS, mostra link para ir gerenciar USUÁRIOS -->
                            <li><a href="usuarios_listar.php" class="nav-link">Gerenciar Usuários</a></li>
                        <?php else: ?>
                            <!-- Se estiver gerenciando USUÁRIOS, mostra link para ir gerenciar JOGOS -->
                            <li><a href="listar.php" class="nav-link">Gerenciar Jogos</a></li>
                        <?php endif; ?>
                        <!-- Link para sair do admin e voltar pro site -->
                        <li><a href="../index.php" class="nav-link">Voltar ao Site</a></li>
                    <?php endif; ?>

                    <!-- BOTÃO DO TEMA (Modo Claro / Modo Escuro) - Sempre visível -->
                    <li>
                        <button class="theme-toggle" aria-label="Alternar para modo claro ou escuro">
                            <span class="icon-moon" aria-hidden="true">🌙</span>
                            <span class="icon-sun" aria-hidden="true">☀️</span>
                        </button>
                    </li>

                    <!-- LÓGICA DE USUÁRIO LOGADO -->
                    <?php if(isset($_SESSION['usuario_logado']) || isset($_SESSION['admin_logado'])): ?>
                        <!-- Se alguém estiver logado, mostramos a saudação -->
                        <li class="nav-user-greeting" style="display: flex; align-items: center; justify-content: center; padding: 0 10px;">
                            <span style="color: var(--primary); font-weight: bold;">
                                👤 Olá, <?php echo $nome_exibicao; ?>
                            </span>
                        </li>
                        <!-- Botão de Sair (logout). O $base_url ajuda a achar o arquivo logout.php na raiz -->
                        <li>
                            <a href="<?php echo $base_url; ?>logout.php" class="nav-link" style="color: #ef4444; font-weight: bold;">Sair</a>
                        </li>
                    <?php else: ?>
                        <!-- Se ninguém estiver logado, mostramos a opção de Entrar (login) -->
                        <li>
                            <a href="<?php echo $base_url; ?>login.php" class="nav-link" style="color: #10b981; font-weight: bold;">Entrar</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</header>
