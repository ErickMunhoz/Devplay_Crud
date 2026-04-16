/**
 * DevPlay - Plataforma de Jogos para Treinar Desenvolvimento
 * JavaScript principal com funcionalidades interativas
 * 
 * Este arquivo contém toda a lógica JavaScript da plataforma:
 * - Modal para jogos internos
 * - Tratamento de erros para links externos
 * - Sistema de notificações (toast)
 * - Navegação responsiva
 * - Acessibilidade e keyboard navigation
 * 
 * Criado por: Alunos do Curso de Desenvolvimento de Sistemas
 */

// ===== CONFIGURAÇÕES GLOBAIS =====
// Objeto que armazena todas as configurações da aplicação
const CONFIG = {
    // Tempo limite para verificar se um link externo está funcionando (5 segundos)
    EXTERNAL_LINK_TIMEOUT: 5000,
    
    // Tempo que uma notificação fica visível na tela (5 segundos)
    TOAST_DURATION: 5000,
    
    // Lista de jogos alternativos quando um link externo não funciona
    FALLBACK_GAMES: [
        { name: 'Quiz HTML', url: 'games/quiz-html/index.html', type: 'internal' },
        { name: 'Scratch', url: 'https://scratch.mit.edu', type: 'external' },
        { name: 'Code.org', url: 'https://code.org', type: 'external' }
    ]
};

// ===== CLASSE PRINCIPAL DA APLICAÇÃO =====
// Uma classe é como um molde para criar objetos com propriedades e métodos
class DevPlayApp {
    // Construtor é executado quando criamos uma nova instância da classe
    constructor() {
        // Propriedades da classe - armazenam referências aos elementos HTML
        this.modal = null;          // Janela modal para jogos internos
        this.toast = null;          // Elemento de notificação
        this.navMenu = null;        // Menu de navegação
        this.navToggle = null;      // Botão hambúrguer do menu
        this.gameFrame = null;      // Iframe onde os jogos são carregados
        this.loadingSpinner = null; // Indicador de carregamento
        
        // Sistema de notificações
        this.notificationButton = null;    // Botão de notificações
        this.notificationHistory = null;   // Container do histórico
        this.notificationBadge = null;     // Badge de contagem
        this.notificationHistoryBody = null; // Corpo do histórico
        this.notifications = [];           // Array de notificações
        
        // Inicia a aplicação
        this.init();
    }
    
    /**
     * Inicializa a aplicação
     * Verifica se o DOM já foi carregado antes de configurar tudo
     */
    init() {
        // Verifica se a página ainda está carregando
        if (document.readyState === 'loading') {
            // Se ainda está carregando, aguarda o evento DOMContentLoaded
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            // Se já carregou, executa a configuração imediatamente
            this.setup();
        }
    }
    
    /**
     * Configura todos os elementos e event listeners
     * Este método é chamado após o DOM estar pronto
     */
    setup() {
        // Executa todos os métodos de configuração em ordem
        this.cacheElements();        // Busca e armazena elementos HTML
        this.bindEvents();          // Adiciona event listeners
        this.setupAccessibility();  // Configura recursos de acessibilidade
        this.showWelcomeMessage();  // Mostra mensagem de boas-vindas
        
        // Log no console para confirmar que tudo foi inicializado
        console.log('DevPlay inicializado com sucesso! 🎮');
    }
    
    /**
     * Cacheia elementos DOM para melhor performance
     * Em vez de buscar elementos toda vez, armazenamos as referências
     */
    cacheElements() {
        // Busca elementos do modal
        this.modal = document.getElementById('gameModal');
        this.gameFrame = document.getElementById('gameFrame');
        // O ? é o operador opcional - só executa se this.modal existir
        this.loadingSpinner = this.modal?.querySelector('.loading-spinner');
        
        // Busca elemento de notificação
        this.toast = document.getElementById('toast');
        
        // Busca elementos de navegação
        this.navToggle = document.querySelector('.nav-toggle');
        this.navMenu = document.querySelector('.nav-menu');
        
        // Busca todos os botões de jogos (exceto os desabilitados)
        this.gameButtons = document.querySelectorAll('.game-button:not(.game-button--disabled)');
        
        // Busca elementos do sistema de notificações
        this.notificationButton = document.getElementById('notificationButton');
        this.notificationHistory = document.getElementById('notificationHistory');
        this.notificationBadge = document.getElementById('notificationBadge');
        this.notificationHistoryBody = document.getElementById('notificationHistoryBody');
    }
    
    /**
     * Vincula todos os event listeners
     * Event listeners "escutam" eventos como cliques, teclas pressionadas, etc.
     */
    bindEvents() {
        // Adiciona event listener para cada botão de jogo
        this.gameButtons.forEach(button => {
            // addEventListener adiciona uma função que será executada quando o evento ocorrer
            button.addEventListener('click', (e) => this.handleGameClick(e));
        });
        
        // Configura eventos do modal (se existir)
        if (this.modal) {
            // Botão de fechar modal
            const closeBtn = this.modal.querySelector('.modal-close');
            closeBtn?.addEventListener('click', () => this.closeModal());
            
            // Clique no overlay (fundo escuro) também fecha o modal
            const overlay = this.modal.querySelector('.modal-overlay');
            overlay?.addEventListener('click', () => this.closeModal());
            
            // Tecla Escape fecha o modal
            document.addEventListener('keydown', (e) => {
                // Verifica se a tecla pressionada foi Escape E se o modal está aberto
                if (e.key === 'Escape' && this.modal.classList.contains('active')) {
                    this.closeModal();
                }
            });
        }
        
        // Configura navegação mobile
        if (this.navToggle && this.navMenu) {
            // Clique no botão hambúrguer
            this.navToggle.addEventListener('click', () => this.toggleNavigation());
            
            // Fecha menu ao clicar fora dele
            document.addEventListener('click', (e) => {
                // Verifica se o clique foi fora do botão e do menu
                if (!this.navToggle.contains(e.target) && !this.navMenu.contains(e.target)) {
                    this.closeNavigation();
                }
            });
        }
        
        // Configura botão de fechar toast
        if (this.toast) {
            const closeBtn = this.toast.querySelector('.toast-close');
            closeBtn?.addEventListener('click', () => this.hideToast());
        }
        
        // Configura sistema de notificações
        if (this.notificationButton) {
            this.notificationButton.addEventListener('click', () => this.toggleNotificationHistory());
        }
        
        if (this.notificationHistory) {
            const closeBtn = this.notificationHistory.querySelector('.history-close');
            closeBtn?.addEventListener('click', () => this.hideNotificationHistory());
            
            // Fecha histórico ao clicar fora dele
            document.addEventListener('click', (e) => {
                if (!this.notificationHistory.contains(e.target) && 
                    !this.notificationButton.contains(e.target) &&
                    this.notificationHistory.classList.contains('show')) {
                    this.hideNotificationHistory();
                }
            });
        }
        
        // Scroll suave para links âncora (links que começam com #)
        document.querySelectorAll('a[href^="#"]').forEach(link => {
            link.addEventListener('click', (e) => this.handleSmoothScroll(e));
        });
        
        // Evento de redimensionamento da janela
        window.addEventListener('resize', () => this.handleResize());
        
        // Listener para mensagens dos jogos internos
        window.addEventListener('message', (e) => this.handleGameMessage(e));
    }
    
    /**
     * Configura recursos de acessibilidade
     * Torna a aplicação utilizável por pessoas com deficiências
     */
    setupAccessibility() {
        // Configura focus trap no modal (impede que o foco saia do modal)
        if (this.modal) {
            this.setupModalFocusTrap();
        }
        
        // Atualiza labels ARIA dinamicamente
        this.updateAriaLabels();
        
        // Configura navegação por teclado nos cards
        this.setupCardKeyboardNavigation();
    }
    
    /**
     * Configura focus trap no modal
     * Focus trap mantém o foco dentro do modal quando ele está aberto
     */
    setupModalFocusTrap() {
        // Busca todos os elementos que podem receber foco dentro do modal
        const focusableElements = this.modal.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        // Se não há elementos focáveis, não faz nada
        if (focusableElements.length === 0) return;
        
        // Primeiro e último elemento focável
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];
        
        // Adiciona listener para a tecla Tab
        this.modal.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                if (e.shiftKey) {
                    // Shift + Tab (navegação reversa)
                    if (document.activeElement === firstElement) {
                        e.preventDefault(); // Impede comportamento padrão
                        lastElement.focus(); // Vai para o último elemento
                    }
                } else {
                    // Tab normal
                    if (document.activeElement === lastElement) {
                        e.preventDefault(); // Impede comportamento padrão
                        firstElement.focus(); // Vai para o primeiro elemento
                    }
                }
            }
        });
    }
    
    /**
     * Atualiza ARIA labels dinamicamente
     * ARIA labels ajudam leitores de tela a entender o conteúdo
     */
    updateAriaLabels() {
        // Conta quantos jogos estão disponíveis
        const gameCards = document.querySelectorAll('.game-card:not(.game-card--placeholder)');
        const gamesSection = document.querySelector('.games-section');
        
        // Atualiza o label da seção de jogos
        if (gamesSection) {
            gamesSection.setAttribute('aria-label', `${gameCards.length} jogos disponíveis`);
        }
    }
    
    /**
     * Configura navegação por teclado nos cards
     * Permite usar setas para navegar entre os jogos
     */
    setupCardKeyboardNavigation() {
        // Para cada botão de jogo
        this.gameButtons.forEach((button, index) => {
            button.addEventListener('keydown', (e) => {
                // Seta direita ou para baixo - próximo jogo
                if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                    e.preventDefault(); // Impede scroll da página
                    // Se é o último, vai para o primeiro (navegação circular)
                    const nextButton = this.gameButtons[index + 1] || this.gameButtons[0];
                    nextButton.focus();
                } 
                // Seta esquerda ou para cima - jogo anterior
                else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                    e.preventDefault(); // Impede scroll da página
                    // Se é o primeiro, vai para o último (navegação circular)
                    const prevButton = this.gameButtons[index - 1] || this.gameButtons[this.gameButtons.length - 1];
                    prevButton.focus();
                }
            });
        });
    }
    
    /**
     * Manipula cliques nos botões de jogos
     * Esta função é chamada sempre que alguém clica em um botão de jogo
     */
    async handleGameClick(event) {
        // Impede o comportamento padrão do botão
        event.preventDefault();
        
        // Pega o botão que foi clicado
        const button = event.currentTarget;
        // Pega os dados do botão (tipo e URL do jogo)
        const gameType = button.dataset.type;   // 'internal' ou 'external'
        const gameUrl = button.dataset.url;     // URL do jogo
        // Pega o título do jogo
        const gameTitle = button.closest('.game-card').querySelector('.game-title').textContent;
        
        // Feedback visual - faz o botão "afundar" quando clicado
        button.style.transform = 'scale(0.95)';
        setTimeout(() => {
            button.style.transform = ''; // Volta ao normal após 150ms
        }, 150);
        
        // Decide como abrir o jogo baseado no tipo
        if (gameType === 'internal') {
            // Jogo interno - abre no modal
            this.openInternalGame(gameUrl, gameTitle);
        } else if (gameType === 'external') {
            // Jogo externo - abre em nova aba (com verificação)
            await this.openExternalGame(gameUrl, gameTitle);
        }
    }
    
    /**
     * Abre jogo interno no modal
     * Jogos internos são carregados em um iframe dentro de um modal
     */
    openInternalGame(url, title) {
        // Verifica se os elementos necessários existem
        if (!this.modal || !this.gameFrame) {
            this.showToast('Erro ao abrir o jogo. Tente novamente.', 'error');
            return;
        }
        
        // Atualiza o título do modal
        const modalTitle = this.modal.querySelector('.modal-title');
        if (modalTitle) {
            modalTitle.textContent = title;
        }
        
        // Mostra indicador de carregamento
        this.showLoading();
        
        // Configura o iframe para carregar o jogo
        this.gameFrame.src = url;    // Define a URL do jogo
        this.gameFrame.title = title; // Define o título para acessibilidade
        
        // Função executada quando o iframe termina de carregar
        const handleLoad = () => {
            this.hideLoading(); // Esconde o loading
            this.gameFrame.removeEventListener('load', handleLoad); // Remove o listener
        };
        
        // Função executada se houver erro no carregamento
        const handleError = () => {
            this.hideLoading(); // Esconde o loading
            this.showToast(`Erro ao carregar o jogo "${title}". Verifique se o arquivo existe.`, 'error');
            this.closeModal(); // Fecha o modal
            this.gameFrame.removeEventListener('error', handleError); // Remove o listener
        };
        
        // Adiciona os listeners de evento
        this.gameFrame.addEventListener('load', handleLoad);
        this.gameFrame.addEventListener('error', handleError);
        
        // Abre o modal
        this.openModal();
        
        // Timeout de segurança - se demorar muito para carregar, esconde o loading
        setTimeout(() => {
            if (this.loadingSpinner && !this.loadingSpinner.style.display === 'none') {
                this.hideLoading();
            }
        }, 10000); // 10 segundos
    }
    
    /**
     * Abre jogo externo com verificação de disponibilidade
     * Antes de abrir, verifica se o site está funcionando
     */
    async openExternalGame(url, title) {
        try {
            // Mostra notificação de que está verificando
            this.showToast(`Verificando disponibilidade de "${title}"...`, 'info');
            
            const newWindow = window.open(url, '_blank', 'noopener,noreferrer');
            
            if (newWindow) {
                this.showToast(`"${title}" aberto em nova aba!`, 'success');
            } else {
                this.showToast('Popup bloqueado. Clique no botão novamente e permita popups.', 'warning');
            }
        } catch (error) {
            // Erro inesperado
            console.error('Erro ao abrir jogo externo:', error);
            this.handleExternalGameError(title);
        }
    }
    
    /**
     * Verifica se um link externo está acessível
     * Retorna uma Promise que resolve com true/false
     */
    async checkLinkAccessibility(url) {
        return new Promise((resolve) => {
            // Timeout - se demorar muito, considera como inacessível
            const timeout = setTimeout(() => {
                resolve(false);
            }, CONFIG.EXTERNAL_LINK_TIMEOUT);
            
            // Tenta fazer uma requisição HEAD para verificar se o site responde
            fetch(url, { 
                method: 'HEAD',      // Só pega os headers, não o conteúdo
                mode: 'no-cors',     // Evita problemas de CORS
                cache: 'no-cache'    // Não usa cache
            })
            .then(() => {
                // Sucesso - site está acessível
                clearTimeout(timeout);
                resolve(true);
            })
            .catch(() => {
                // Erro - site não está acessível
                clearTimeout(timeout);
                resolve(false);
            });
        });
    }
    
    /**
     * Manipula erros de jogos externos
     * Mostra mensagem de erro e sugere jogos alternativos
     */
    handleExternalGameError(gameTitle) {
        // Filtra jogos alternativos (remove o que deu erro)
        const fallbackGames = CONFIG.FALLBACK_GAMES
            .filter(game => game.name !== gameTitle)  // Remove o jogo que falhou
            .slice(0, 2);                            // Pega apenas os 2 primeiros
        
        // Monta a mensagem de erro
        let message = `Não foi possível acessar "${gameTitle}". `;
        
        if (fallbackGames.length > 0) {
            // Se há jogos alternativos, sugere eles
            const suggestions = fallbackGames.map(game => game.name).join(' ou ');
            message += `Que tal tentar ${suggestions}?`;
        } else {
            // Se não há alternativas, pede para tentar mais tarde
            message += 'Tente novamente mais tarde.';
        }
        
        // Mostra a notificação de erro
        this.showToast(message, 'error');
        
        // Destaca os jogos alternativos na interface
        this.highlightAlternativeGames(fallbackGames);
    }
    
    /**
     * Destaca jogos alternativos na interface
     * Adiciona uma classe CSS que faz os jogos "brilharem"
     */
    highlightAlternativeGames(games) {
        // Remove destaques anteriores
        document.querySelectorAll('.game-card--highlighted').forEach(card => {
            card.classList.remove('game-card--highlighted');
        });
        
        // Adiciona destaque aos jogos sugeridos
        games.forEach(game => {
            // Procura o card do jogo pelo título
            const gameCard = Array.from(document.querySelectorAll('.game-card')).find(card => {
                const title = card.querySelector('.game-title')?.textContent;
                return title === game.name;
            });
            
            if (gameCard) {
                // Adiciona classe de destaque
                gameCard.classList.add('game-card--highlighted');
                
                // Remove destaque após 3 segundos
                setTimeout(() => {
                    gameCard.classList.remove('game-card--highlighted');
                }, 3000);
            }
        });
    }
    
    /**
     * Abre o modal
     * Torna o modal visível e configura acessibilidade
     */
    openModal() {
        if (!this.modal) return;
        
        // Adiciona classe que torna o modal visível
        this.modal.classList.add('active');
        // Informa leitores de tela que o modal está aberto
        this.modal.setAttribute('aria-hidden', 'false');
        
        // Foca no botão de fechar para navegação por teclado
        const closeBtn = this.modal.querySelector('.modal-close');
        if (closeBtn) {
            closeBtn.focus();
        }
        
        // Previne scroll da página de fundo
        document.body.style.overflow = 'hidden';
    }
    
    /**
     * Fecha o modal
     * Esconde o modal e restaura o estado anterior
     */
    closeModal() {
        if (!this.modal) return;
        
        // Remove classe que torna o modal visível
        this.modal.classList.remove('active');
        // Informa leitores de tela que o modal está fechado
        this.modal.setAttribute('aria-hidden', 'true');
        
        // Limpa o iframe (para de carregar o jogo)
        if (this.gameFrame) {
            this.gameFrame.src = '';
        }
        
        // Restaura scroll da página
        document.body.style.overflow = '';
        
        // Retorna foco para o botão que abriu o modal
        const activeGameButton = document.querySelector('.game-button:focus');
        if (activeGameButton) {
            activeGameButton.focus();
        }
    }
    
    /**
     * Mostra indicador de carregamento no modal
     */
    showLoading() {
        if (this.loadingSpinner) {
            this.loadingSpinner.style.display = 'block'; // Mostra spinner
        }
        if (this.gameFrame) {
            this.gameFrame.style.display = 'none'; // Esconde iframe
        }
    }
    
    /**
     * Esconde indicador de carregamento no modal
     */
    hideLoading() {
        if (this.loadingSpinner) {
            this.loadingSpinner.style.display = 'none'; // Esconde spinner
        }
        if (this.gameFrame) {
            this.gameFrame.style.display = 'block'; // Mostra iframe
        }
    }
    
    /**
     * Alterna navegação mobile (abre/fecha)
     */
    toggleNavigation() {
        if (!this.navMenu || !this.navToggle) return;
        
        // Verifica se o menu está aberto
        const isOpen = this.navMenu.classList.contains('active');
        
        if (isOpen) {
            this.closeNavigation(); // Se está aberto, fecha
        } else {
            this.openNavigation();  // Se está fechado, abre
        }
    }
    
    /**
     * Abre navegação mobile
     */
    openNavigation() {
        this.navMenu.classList.add('active');              // Adiciona classe de ativo
        this.navToggle.setAttribute('aria-expanded', 'true'); // Atualiza ARIA
        
        // Foca no primeiro link para navegação por teclado
        const firstLink = this.navMenu.querySelector('.nav-link');
        if (firstLink) {
            firstLink.focus();
        }
    }
    
    /**
     * Fecha navegação mobile
     */
    closeNavigation() {
        this.navMenu.classList.remove('active');               // Remove classe de ativo
        this.navToggle.setAttribute('aria-expanded', 'false');  // Atualiza ARIA
    }
    
    /**
     * Manipula scroll suave para âncoras
     * Quando clica em um link #jogos, faz scroll suave até a seção
     */
    handleSmoothScroll(event) {
        // Pega o atributo href do link
        const href = event.currentTarget.getAttribute('href');
        
        // Verifica se é um link âncora (começa com #)
        if (href.startsWith('#')) {
            event.preventDefault(); // Impede comportamento padrão
            
            // Pega o ID da seção (remove o #)
            const targetId = href.substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                // Calcula posição considerando o header fixo
                const headerHeight = document.querySelector('.header')?.offsetHeight || 0;
                const targetPosition = targetElement.offsetTop - headerHeight - 20;
                
                // Faz scroll suave até a posição
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
                
                // Fecha navegação mobile se estiver aberta
                this.closeNavigation();
            }
        }
    }
    
    /**
     * Manipula redimensionamento da janela
     * Executado quando a janela é redimensionada
     */
    handleResize() {
        // Fecha navegação mobile em telas maiores
        if (window.innerWidth >= 768) {
            this.closeNavigation();
        }
    }
    
    /**
     * Manipula mensagens dos jogos internos
     * Recebe mensagens enviadas pelos jogos via postMessage
     */
    handleGameMessage(event) {
        // Verifica se a mensagem é para fechar o jogo
        if (event.data === 'closeGame') {
            this.closeModal();
            this.showToast('Jogo fechado com sucesso!', 'success');
        }
    }
    
    /**
     * Mostra toast de notificação
     * Toast são mensagens temporárias que aparecem no canto da tela
     */
    showToast(message, type = 'info', duration = CONFIG.TOAST_DURATION) {
        if (!this.toast) return;
        
        // Limpa classes anteriores
        this.toast.className = 'toast';
        
        // Adiciona classe do tipo (error, success, warning, info)
        if (type) {
            this.toast.classList.add(type);
        }
        
        // Define ícone baseado no tipo
        const icon = this.getToastIcon(type);
        const iconElement = this.toast.querySelector('.toast-icon');
        const messageElement = this.toast.querySelector('.toast-message');
        
        // Atualiza conteúdo do toast
        if (iconElement) iconElement.textContent = icon;
        if (messageElement) messageElement.textContent = message;
        
        // Mostra o toast
        this.toast.classList.add('show');
        
        // Adiciona notificação ao histórico
        this.addNotificationToHistory(message, type);
        
        // Auto-hide após duração especificada
        if (duration > 0) {
            setTimeout(() => {
                this.hideToast();
            }, duration);
        }
    }
    
    /**
     * Esconde toast
     */
    hideToast() {
        if (this.toast) {
            this.toast.classList.remove('show');
        }
    }
    
    /**
     * Retorna ícone para o tipo de toast
     * Cada tipo de notificação tem um emoji diferente
     */
    getToastIcon(type) {
        const icons = {
            success: '✅',  // Sucesso
            error: '❌',    // Erro
            warning: '⚠️',  // Aviso
            info: 'ℹ️'      // Informação
        };
        
        // Retorna o ícone do tipo ou ícone padrão (info)
        return icons[type] || icons.info;
    }
    
    /**
     * Mostra mensagem de boas-vindas
     * Executado apenas na primeira visita
     */
    showWelcomeMessage() {
        // Verifica se é a primeira visita usando localStorage
        const hasVisited = localStorage.getItem('devplay_visited');
        
        if (!hasVisited) {
            // Primeira visita - mostra mensagem após 1 segundo
            setTimeout(() => {
                this.showToast('Bem-vindo ao DevPlay! Explore nossos jogos educativos. 🎮', 'success', 6000);
                // Marca que o usuário já visitou
                localStorage.setItem('devplay_visited', 'true');
            }, 1000);
        }
    }
    
    /**
     * Adiciona notificação ao histórico
     */
    addNotificationToHistory(message, type) {
        const notification = {
            id: Date.now(),
            message,
            type,
            timestamp: new Date()
        };
        
        // Adiciona ao início do array
        this.notifications.unshift(notification);
        
        // Mantém apenas as últimas 50 notificações
        if (this.notifications.length > 50) {
            this.notifications = this.notifications.slice(0, 50);
        }
        
        // Atualiza o badge
        this.updateNotificationBadge();
        
        // Atualiza o histórico visual
        this.renderNotificationHistory();
    }
    
    /**
     * Atualiza o badge de contagem de notificações
     */
    updateNotificationBadge() {
        if (!this.notificationBadge) return;
        
        const count = this.notifications.length;
        this.notificationBadge.textContent = count;
        
        if (count > 0) {
            this.notificationBadge.classList.remove('hidden');
        } else {
            this.notificationBadge.classList.add('hidden');
        }
    }
    
    /**
     * Renderiza o histórico de notificações
     */
    renderNotificationHistory() {
        if (!this.notificationHistoryBody) return;
        
        if (this.notifications.length === 0) {
            this.notificationHistoryBody.innerHTML = '<p class="no-notifications">Nenhuma notificação ainda.</p>';
            return;
        }
        
        const notificationsHTML = this.notifications.map(notification => {
            const timeAgo = this.getTimeAgo(notification.timestamp);
            const icon = this.getToastIcon(notification.type);
            
            return `
                <div class="notification-item ${notification.type}">
                    <div class="notification-item-icon">${icon}</div>
                    <div class="notification-item-content">
                        <div class="notification-item-message">${notification.message}</div>
                        <div class="notification-item-time">${timeAgo}</div>
                    </div>
                </div>
            `;
        }).join('');
        
        this.notificationHistoryBody.innerHTML = notificationsHTML;
    }
    
    /**
     * Alterna visibilidade do histórico de notificações
     */
    toggleNotificationHistory() {
        if (!this.notificationHistory) return;
        
        const isVisible = this.notificationHistory.classList.contains('show');
        
        if (isVisible) {
            this.hideNotificationHistory();
        } else {
            this.showNotificationHistory();
        }
    }
    
    /**
     * Mostra o histórico de notificações
     */
    showNotificationHistory() {
        if (!this.notificationHistory) return;
        
        this.notificationHistory.classList.add('show');
        this.notificationHistory.setAttribute('aria-hidden', 'false');
        
        // Renderiza o histórico atualizado
        this.renderNotificationHistory();
    }
    
    /**
     * Esconde o histórico de notificações
     */
    hideNotificationHistory() {
        if (!this.notificationHistory) return;
        
        this.notificationHistory.classList.remove('show');
        this.notificationHistory.setAttribute('aria-hidden', 'true');
    }
    
    /**
     * Calcula tempo relativo (ex: "há 2 minutos")
     */
    getTimeAgo(timestamp) {
        const now = new Date();
        const diff = now - timestamp;
        const seconds = Math.floor(diff / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);
        
        if (days > 0) {
            return `há ${days} dia${days > 1 ? 's' : ''}`;
        } else if (hours > 0) {
            return `há ${hours} hora${hours > 1 ? 's' : ''}`;
        } else if (minutes > 0) {
            return `há ${minutes} minuto${minutes > 1 ? 's' : ''}`;
        } else {
            return 'agora mesmo';
        }
    }
}

// ===== UTILITÁRIOS GLOBAIS =====

/**
 * Debounce function para otimizar performance
 * Debounce impede que uma função seja executada muitas vezes seguidas
 * Útil para eventos como resize, scroll, etc.
 */
function debounce(func, wait) {
    let timeout; // Armazena o ID do timeout
    
    // Retorna uma nova função que será executada no lugar da original
    return function executedFunction(...args) {
        // Função que será executada após o delay
        const later = () => {
            clearTimeout(timeout);  // Limpa o timeout anterior
            func(...args);          // Executa a função original
        };
        
        clearTimeout(timeout);      // Cancela execução anterior
        timeout = setTimeout(later, wait); // Agenda nova execução
    };
}

/**
 * Throttle function para otimizar performance
 * Throttle limita quantas vezes uma função pode ser executada
 * Útil para eventos que disparam muito frequentemente
 */
function throttle(func, limit) {
    let inThrottle; // Flag para controlar se está no período de throttle
    
    return function() {
        const args = arguments;    // Argumentos da função
        const context = this;      // Contexto (this) da função
        
        if (!inThrottle) {
            func.apply(context, args);  // Executa a função
            inThrottle = true;          // Ativa o throttle
            
            // Desativa o throttle após o limite de tempo
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// ===== CSS DINÂMICO PARA DESTACAR JOGOS =====
// Cria um elemento <style> e adiciona CSS dinamicamente
const style = document.createElement('style');
style.textContent = `
    /* Classe adicionada dinamicamente para destacar jogos alternativos */
    .game-card--highlighted {
        animation: highlight-pulse 2s ease-in-out;              /* Animação de pulso */
        border-color: var(--accent) !important;                /* Borda colorida */
        box-shadow: 0 0 20px rgba(6, 182, 212, 0.3) !important; /* Sombra colorida */
    }
    
    /* Animação de pulso */
    @keyframes highlight-pulse {
        0%, 100% { transform: scale(1); }    /* Tamanho normal */
        50% { transform: scale(1.02); }      /* Ligeiramente maior */
    }
`;
// Adiciona o CSS ao <head> da página
document.head.appendChild(style);

// ===== INICIALIZAÇÃO =====
// Cria uma instância global da aplicação
// Esta linha efetivamente "liga" toda a aplicação
window.devPlayApp = new DevPlayApp();

// ===== SERVICE WORKER (OPCIONAL) =====
// Service Worker permite funcionalidade offline
// Comentado por enquanto, mas pode ser implementado no futuro
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        // Descomente as linhas abaixo para ativar o service worker
        // navigator.serviceWorker.register('/sw.js')
        //     .then(registration => console.log('SW registrado:', registration))
        //     .catch(error => console.log('SW falhou:', error));
    });
}

// ===== ANALYTICS SIMPLES (OPCIONAL) =====
// Sistema básico de analytics sem dependências externas
const Analytics = {
    /**
     * Registra um evento para análise posterior
     * @param {string} event - Nome do evento
     * @param {object} data - Dados adicionais do evento
     */
    track(event, data = {}) {
        // Pega eventos existentes do localStorage
        const events = JSON.parse(localStorage.getItem('devplay_analytics') || '[]');
        
        // Adiciona novo evento
        events.push({
            event,                              // Nome do evento
            data,                              // Dados do evento
            timestamp: new Date().toISOString(), // Data/hora
            userAgent: navigator.userAgent,     // Informações do navegador
            url: window.location.href          // URL atual
        });
        
        // Mantém apenas os últimos 100 eventos (para não ocupar muito espaço)
        if (events.length > 100) {
            events.splice(0, events.length - 100);
        }
        
        // Salva de volta no localStorage
        localStorage.setItem('devplay_analytics', JSON.stringify(events));
    }
};

// Tracking automático de cliques em jogos
document.addEventListener('click', (e) => {
    // Se clicou em um botão de jogo, registra o evento
    if (e.target.classList.contains('game-button')) {
        const gameTitle = e.target.closest('.game-card')?.querySelector('.game-title')?.textContent;
        Analytics.track('game_click', { game: gameTitle });
    }
});

// ===== EXPORTAÇÕES PARA TESTES =====
// Expõe funções para testes (apenas em desenvolvimento local)
if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
    window.DevPlayTest = {
        app: window.devPlayApp,  // Instância da aplicação
        Analytics,               // Sistema de analytics
        CONFIG                   // Configurações
    };
}

