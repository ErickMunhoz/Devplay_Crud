<!DOCTYPE <?php
session_start();

// Verificar se o usuário está logado como administrador
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header('Location: ../login.php');
    exit();
}
?>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Jogo - DevPlay</title>
    <link rel="stylesheet" href="../css/style.css">

    <!-- JAVASCRIPT: Lógica do tema carregada bem cedo para evitar piscar de tela -->
    <script src="../js/theme.js"></script>
    <style>
        .admin-container {
            padding-top: 140px;
            padding-bottom: 50px;
        }

        .form-card {
            background: var(--surface);
            padding: 30px;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: inherit;
            background: var(--background);
            color: var(--text-primary);
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            border: none;
            width: 100%;
        }

        .btn-save {
            background-color: var(--primary);
            color: white;
        }

        .btn-cancel {
            background-color: var(--text-muted);
            color: white;
            text-align: center;
            display: inline-block;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <?php include '../components/header.php'; ?>

    <main class="admin-container container">
        <div class="form-card">
            <h2>Cadastrar Novo Jogo</h2>
            <form action="processar.php" method="POST">
                <input type="hidden" name="acao" value="cadastrar">

                <div class="form-group">
                    <label for="titulo">Título do Jogo</label>
                    <input type="text" id="titulo" name="titulo" required placeholder="Ex: Quiz HTML">
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição Curta</label>
                    <textarea id="descricao" name="descricao" required placeholder="Ex: Teste seus conhecimentos sobre HTML básico" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="imagem">URL da Imagem (ou data:image/svg+xml,...)</label>
                    <input type="text" id="imagem" name="imagem" required placeholder="Ex: data:image/svg+xml,...">
                </div>

                <div class="form-group">
                    <label for="tipo" style="display: flex; align-items: center; gap: 8px;">
                        Tipo de Jogo
                        <span class="tooltip-icon" tabindex="0" title="Sites muito famosos ou externos (ex: codedex.io) bloqueiam que suas páginas sejam abertas dentro de um 'modal' (iframe) de outros sites. Tem um script inteligente nas páginas de Cadastrar e Editar, onde usando o arquivo check_iframe.php, se você digitar um link que comece com http:// ou https:// na URL do jogo, o sistema manda um 'espião' rápido lá no servidor do jogo (em menos de 1 segundo). Se o servidor do jogo confirmar que aceita ser aberto dentro de modal (sem dar o erro de recusa), legal, ele não muda o 'Tipo de Jogo'. MAS SE ELE DETECTAR O ERRO (que o site bloqueia), O 'Tipo de Jogo' vira 'Externo' sozinho. Assim, em vez do jogo tentar abrir dentro do nosso modal onde daria o erro de conexão recusada, ele vai abrir certinho em uma nova aba do navegador.">?</span>
                    </label>
                    <select id="tipo" name="tipo" required>
                        <option value="internal">Interno (Abre em Modal)</option>
                        <option value="external">Externo (Link direto)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="url">URL do Jogo</label>
                    <input type="text" id="url" name="url" required placeholder="Ex: games/quiz-html/index.html ou https://...">
                </div>

                <button type="submit" class="btn btn-save">Salvar Jogo</button>
                <a href="listar.php" class="btn btn-cancel">Cancelar</a>
            </form>
        </div>
    </main>
    <script>
        // Sistema Inteligente de Detecção de Bloqueio de IFrame
        const urlInput = document.getElementById('url');
        const tipoSelect = document.getElementById('tipo');
        const tooltipIcon = document.querySelector('.tooltip-icon');
        const originalTooltipText = tooltipIcon.title;
        let debounceTimer;

        urlInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const val = this.value.trim();
            
            // Só testa se for uma URL web completa
            if (val.startsWith('http://') || val.startsWith('https://')) {
                // Adiciona um pequeno atraso para não fazer requisição a cada tecla digitada
                debounceTimer = setTimeout(() => {
                    fetch('check_iframe.php?url=' + encodeURIComponent(val))
                        .then(res => res.json())
                        .then(data => {
                            // Se a API detectar que o site proíbe iframes (Daria erro de conexão)
                            if (data.can_frame === false) {
                                tipoSelect.value = 'external';
                                
                                // Feedback visual forte
                                tipoSelect.style.borderColor = 'var(--error)';
                                tipoSelect.style.borderWidth = '2px';
                                
                                // Altera o tooltip para explicar o que acabou de acontecer
                                tooltipIcon.style.backgroundColor = 'var(--error)';
                                tooltipIcon.style.transform = 'scale(1.2)';
                                tooltipIcon.textContent = '!';
                                tooltipIcon.title = "Atenção: Testamos o link e ele bloqueia iframes. Mudamos para externo automaticamente.";
                                
                                // Volta o select e o icone ao normal após uns segundos
                                setTimeout(() => {
                                    tipoSelect.style.borderColor = '';
                                    tipoSelect.style.borderWidth = '';
                                    tooltipIcon.style.transform = '';
                                }, 3000);
                            } else {
                                // Se for um site que permite iframe, reseta o aviso caso exista
                                tooltipIcon.style.backgroundColor = 'var(--primary)';
                                tooltipIcon.textContent = '?';
                                tooltipIcon.title = originalTooltipText;
                            }
                        })
                        .catch(err => console.log('Erro na verificação do iframe:', err));
                }, 800);
            }
        });
    </script>
</body>

</html>