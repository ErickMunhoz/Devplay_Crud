<?php
session_start();
include 'config/conexao.php';
?>
<!DOCTYPE html>
<!-- Declaração do tipo de documento HTML5 - sempre deve ser a primeira linha -->
<html lang="pt-BR">
<!-- Tag raiz do HTML com idioma português brasileiro para acessibilidade -->

<head>
    <!-- script para ativar recursos de acessibilidade vindas do site UserWay -->
    <!-- https://manage.userway.org/widget/my-sites/p/1 -->
    <script src="https://cdn.userway.org/widget.js" data-account="9Htbp7aBd8"></script>

    <!-- Seção HEAD contém metadados que não aparecem na página, mas são importantes para o navegador -->

    <!-- Define a codificação de caracteres para suportar acentos e caracteres especiais -->
    <meta charset="UTF-8">

    <!-- Configuração para responsividade - faz a página se adaptar a diferentes tamanhos de tela -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Descrição da página para motores de busca (SEO) -->
    <meta name="description" content="DevPlay - Plataforma de jogos educativos para treinar desenvolvimento de sistemas">

    <!-- Palavras-chave para motores de busca -->
    <meta name="keywords" content="jogos, desenvolvimento, programação, educação, sistemas">

    <!-- Autor da página -->
    <meta name="author" content="Alunos do Curso de Desenvolvimento de Sistemas">

    <!-- Favicon - ícone que aparece na aba do navegador (usando emoji como ícone) -->
    <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎮</text></svg>">

    <!-- Título que aparece na aba do navegador -->
    <title>DevPlay - Plataforma de Jogos para Treinar Programação</title>

    <!-- Link para o arquivo CSS externo que contém todos os estilos -->
    <link rel="stylesheet" href="css/style.css">

    <!-- JAVASCRIPT: Lógica do tema carregada bem cedo para evitar piscar de tela -->
    <script src="js/theme.js"></script>
</head>

<body>
    <!-- Corpo da página onde fica todo o conteúdo visível -->

    <!-- CABEÇALHO FIXO DA PÁGINA -->
    <!-- role="banner" ajuda leitores de tela a identificar o cabeçalho principal -->
    <header class="header" role="banner">
        <!-- Container centraliza o conteúdo e limita a largura máxima -->
        <div class="container">
            <!-- Flexbox para organizar logo e navegação lado a lado -->
            <div class="header-content">

                <!-- SEÇÃO DO LOGO E NOME -->
                <div class="logo-section">
                    <!-- Logo usando emoji (funciona em todos os dispositivos) -->
                    <a href="index.php" class="logo-link" aria-label="Voltar à página inicial">
                        <div class="logo" aria-label="DevPlay Logo">🎮</div>
                    </a>
                    <!-- Título principal do site -->
                    <h1 class="site-title">DevPlay</h1>
                    <!-- Subtítulo explicativo -->
                    <span class="site-subtitle">Plataforma de Jogos para Treinar Programação</span>
                </div>

                <!-- MENU DE NAVEGAÇÃO -->
                <!-- role="navigation" e aria-label ajudam na acessibilidade -->
                <nav class="nav" role="navigation" aria-label="Navegação principal">
                    <!-- Botão hambúrguer para menu mobile -->
                    <!-- aria-expanded informa se o menu está aberto ou fechado -->
                    <button class="nav-toggle" aria-label="Abrir menu" aria-expanded="false">
                        <!-- Ícone hambúrguer feito com CSS -->
                        <span class="hamburger"></span>
                    </button>

                    <!-- Lista de links de navegação -->
                    <ul class="nav-menu">
                        <!-- Links para seções da página usando âncoras (#) -->
                        <li><a href="#jogos" class="nav-link">Jogos</a></li>
                        <li><a href="#sobre" class="nav-link">Sobre</a></li>
                        <li><a href="admin/listar.php" class="nav-link">Gerenciar</a></li>
                        <li>                       

                            <button class="notification-button" id="notificationButton" aria-label="Ver notificações">
                                <span class="notification-button-content">
                                    🔔<span class="notification-badge" id="notificationBadge">0</span>
                                </span>
                            </button>
                        </li>
                        <!-- BOTÃO DE ALTERNÂNCIA DO TEMA (Claro/Escuro) -->
                        <li>
                            <button class="theme-toggle" aria-label="Alternar para modo claro ou escuro">
                                <!-- Mostramos apenas a lua no modo claro (CSS esconde o outro) -->
                                <span class="icon-moon" aria-hidden="true">🌙</span>
                                <!-- Mostramos apenas o sol no modo escuro (CSS esconde o outro) -->
                                <span class="icon-sun" aria-hidden="true">☀️</span>
                            </button>
                        </li>
                        <?php if(isset($_SESSION['usuario_logado']) || isset($_SESSION['admin_logado'])): ?>
                            <li class="nav-user-greeting" style="display: flex; align-items: center; justify-content: center; padding: 0 10px;">
                                <span style="color: var(--primary); font-weight: bold;">
                                    👤 Olá, <?php echo isset($_SESSION['nome_usuario']) ? htmlspecialchars(explode(' ', trim($_SESSION['nome_usuario']))[0]) : (isset($_SESSION['usuario_admin']) ? htmlspecialchars($_SESSION['usuario_admin']) : 'Usuário'); ?>
                                </span>
                            </li>
                            <li>
                                <a href="logout.php" class="nav-link" style="color: #ef4444; font-weight: bold;">Sair</a>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="login.php" class="nav-link" style="color: #10b981; font-weight: bold;">Entrar</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>


    <!-- CONTEÚDO PRINCIPAL DA PÁGINA -->
    <!-- role="main" identifica o conteúdo principal para leitores de tela -->
    <main class="main" role="main">
        <div class="container">

            <!-- SEÇÃO DE INTRODUÇÃO/HERO -->
            <!-- aria-labelledby conecta a seção com seu título -->
            <section class="hero" aria-labelledby="hero-title">
                <!-- Título principal da seção -->
                <h2 id="hero-title" class="hero-title">Aprenda Programação Jogando</h2>
                <!-- Parágrafo explicativo -->
                <p class="hero-description">
                    Desenvolva suas habilidades de programação através de jogos interativos e desafios práticos.
                    Criado por alunos, para alunos.
                </p>
            </section>

            <!-- SEÇÃO DOS JOGOS -->
            <!-- id="jogos" permite navegação via âncora -->
            <section class="games-section" id="jogos" aria-labelledby="games-title">
                <!-- Título da seção -->
                <h2 id="games-title" class="section-title">Jogos Disponíveis</h2>

                <!-- GRID DE JOGOS -->
                <!-- role="list" informa que é uma lista de itens -->
                <div class="games-grid" role="list">
                    <?php
$sql = "SELECT * FROM jogos ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
?>
                            <article class="game-card" role="listitem">
                                <div class="game-image">
                                    <?php if (preg_match('/\.mp4($|\?)/i', $row['imagem'])) { ?>
                                        <video src="<?php echo htmlspecialchars($row['imagem']); ?>" autoplay loop muted playsinline style="width: 100%; height: 100%; object-fit: cover;"></video>
                                    <?php } else { ?>
                                        <img src="<?php echo htmlspecialchars($row['imagem']); ?>" alt="<?php echo htmlspecialchars($row['titulo']); ?>" loading="lazy">
                                    <?php } ?>
                                </div>
                                <div class="game-content">
                                    <h3 class="game-title"><?php echo $row['titulo']; ?></h3>
                                    <p class="game-description"><?php echo $row['descricao']; ?></p>
                                    <button class="game-button"
                                        data-type="<?php echo $row['tipo']; ?>"
                                        data-url="<?php echo $row['url']; ?>"
                                        aria-label="Jogar <?php echo $row['titulo']; ?>">
                                        Jogar Agora
                                    </button>
                                </div>
                            </article>
                    <?php
    }
}
?>

                    <!-- CARD PLACEHOLDER PARA NOVOS JOGOS -->
                    <article class="game-card game-card--placeholder" role="listitem">
                        <div class="game-image">
                            <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 200'><rect width='300' height='200' fill='%23e2e8f0' stroke='%2394a3b8' stroke-width='2' stroke-dasharray='10,5'/><text x='150' y='100' text-anchor='middle' fill='%2364748b' font-size='18' font-family='Arial'>+</text></svg>"
                                alt="Novo Jogo - Em breve"
                                loading="lazy">
                        </div>
                        <div class="game-content">
                            <h3 class="game-title">Novo Jogo</h3>
                            <p class="game-description">Em breve! Espaço reservado para novos jogos criados pelos alunos</p>
                            <button class="game-button game-button--disabled"
                                disabled
                                aria-label="Novo jogo em desenvolvimento">
                                Em Desenvolvimento
                            </button>
                        </div>
                    </article>
                </div>
            </section>

            <!-- SEÇÃO SOBRE O PROJETO -->
            <section class="about-section" id="sobre" aria-labelledby="about-title">
                <h2 id="about-title" class="section-title">Sobre o DevPlay</h2>
                <div class="about-content">
                    <!-- Parágrafos explicativos sobre o projeto -->
                    <p>
                        O DevPlay é uma plataforma educativa desenvolvida para auxiliar no aprendizado de programação
                        e desenvolvimento de sistemas. Através de jogos interativos e desafios práticos,
                        os estudantes podem aplicar conceitos teóricos de forma divertida e engajante.
                    </p>
                    <p>
                        Esta plataforma foi criada como projeto colaborativo pelos alunos do curso de
                        Desenvolvimento de Sistemas, demonstrando na prática os conhecimentos adquiridos
                        em HTML, CSS e JavaScript.
                    </p>
                </div>
            </section>
        </div>
    </main>

    <!-- MODAL PARA JOGOS INTERNOS -->
    <!-- Modal é uma janela que aparece sobre o conteúdo principal -->
    <!-- role="dialog" indica que é uma caixa de diálogo -->
    <!-- aria-hidden="true" esconde do leitor de tela quando fechado -->
    <div class="modal" id="gameModal" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <!-- Overlay escuro atrás do modal -->
        <div class="modal-overlay" aria-label="Fechar modal"></div>
        <!-- Conteúdo principal do modal -->
        <div class="modal-content">
            <!-- Cabeçalho do modal com título e botão fechar -->
            <div class="modal-header">
                <h3 id="modal-title" class="modal-title">Carregando jogo...</h3>
                <!-- Botão X para fechar -->
                <button class="modal-close" aria-label="Fechar jogo">
                    <!-- &times; é o símbolo × -->
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Corpo do modal onde o jogo será carregado -->
            <div class="modal-body">
                <!-- iframe carrega páginas externas dentro da nossa página -->
                <!-- allowfullscreen permite que o jogo use tela cheia se necessário -->
                <iframe id="gameFrame"
                    src=""
                    title="Jogo"
                    allowfullscreen>
                </iframe>
                <!-- Spinner de carregamento mostrado enquanto o jogo carrega -->
                <div class="loading-spinner" aria-label="Carregando...">
                    <!-- Círculo que gira (animação feita com CSS) -->
                    <div class="spinner"></div>
                    <p>Carregando jogo...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- TOAST PARA NOTIFICAÇÕES -->
    <!-- Toast são mensagens temporárias que aparecem no canto da tela -->
    <!-- role="alert" faz leitores de tela anunciarem a mensagem -->
    <!-- aria-live="polite" anuncia mudanças sem interromper o usuário -->
    <div class="toast" id="toast" role="alert" aria-live="polite">
        <div class="toast-content">
            <!-- Ícone da notificação (emoji) -->
            <span class="toast-icon"></span>
            <!-- Texto da mensagem -->
            <span class="toast-message"></span>
        </div>
        <!-- Botão para fechar a notificação -->
        <button class="toast-close" aria-label="Fechar notificação">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>


    <!-- Histórico de Notificações -->
    <div class="notification-history" id="notificationHistory" aria-hidden="true">
        <div class="notification-history-header">
            <h3>Notificações</h3>
            <button class="history-close" aria-label="Fechar histórico">&times;</button>
        </div>
        <div class="notification-history-body" id="notificationHistoryBody">
            <!-- Notificações serão inseridas aqui pelo JavaScript -->
            <p class="no-notifications">Nenhuma notificação ainda.</p>
        </div>
    </div>

    <!-- RODAPÉ DA PÁGINA -->
    <!-- role="contentinfo" identifica informações sobre a página -->
    <footer class="footer" role="contentinfo">
        <div class="container">
            <div class="footer-content">
                <!-- Texto principal do rodapé -->
                <p class="footer-text">
                    Feito por alunos do curso de Desenvolvimento de Sistemas
                </p>
                <!-- Copyright -->
                <p class="footer-year">
                    © 2025 DevPlay - Todos os direitos reservados
                </p>
            </div>
        </div>
    </footer>

    <!-- VLibras Widget - Acessibilidade em Libras -->
    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>

    <!-- JAVASCRIPT -->
    <!-- Carregado no final para garantir que o HTML já foi processado -->
    <script src="js/script.js"></script>
</body>

</html>
