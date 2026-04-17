/**
 * Sistema de Tema (Claro/Escuro) - DevPlay
 * 
 * Este arquivo controla a mudança de cores do site entre claro e escuro.
 * Ele salva a preferência do usuário no navegador (localStorage) para que
 * o tema escolhido continue o mesmo quando a pessoa mudar de página.
 */

// Esta função é executada assim que a página carrega
// Ela verifica qual tema devemos aplicar inicialmentee
function applyInitialTheme() {
    // 1. Primeiro, tentamos ler a escolha salva anteriormente no navegador
    // O 'localStorage' é como uma pequena memória do navegador
    const savedTheme = localStorage.getItem('devplay_theme');

    // 2. Aplicamos o tema encontrado ou o padrão (claro)
    if (savedTheme === 'dark') {
        // Se estava salvo como escuro, aplicamos a marcação "data-theme='dark'" no <html>
        // Isso faz com que o CSS mude as cores automaticamente
        document.documentElement.setAttribute('data-theme', 'dark');
    } else {
        // Se estava como claro ou não tinha nada salvo, definimos como claro
        document.documentElement.setAttribute('data-theme', 'light');
    }
}

// Executamos a aplicação do tema imediatamente!
// (Isso evita que a tela pisque branco antes de ficar escura)
applyInitialTheme();

// Esta função configura o botão que troca o tema
function setupThemeToggle() {
    // 1. Encontra todos os botões de trocar o tema na página
    // (Pode haver mais de um, por exemplo, na barra de navegação principal e na área de admin)
    const toggleButtons = document.querySelectorAll('.theme-toggle');

    // Se não encontrou nenhum botão, para a função por aqui (evita erros)
    if (!toggleButtons) return;

    // 2. Para cada botão encontrado, adicionamos uma ação de clique
    toggleButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Pegamos qual é o tema atual ativo no HTML
            const currentTheme = document.documentElement.getAttribute('data-theme');

            // Decidimos qual será o NOVO tema (se está claro, vira escuro e vice-versa)
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            // 3. Aplicamos o novo tema no HTML (que ativa o CSS novo)
            document.documentElement.setAttribute('data-theme', newTheme);

            // 4. Salvamos a nova escolha no navegador (localStorage)
            localStorage.setItem('devplay_theme', newTheme);
        });
    });
}

// Esperamos que todos os elementos HTML da página sejam carregados
// antes de tentar encontrar e configurar o botão de tema.
document.addEventListener('DOMContentLoaded', setupThemeToggle);

// Sincroniza o tema caso seja alterado em outra aba ou no iframe
window.addEventListener('storage', (event) => {
    // Se a propriedade 'devplay_theme' do localStorage mudar
    if (event.key === 'devplay_theme') {
        const newTheme = event.newValue || 'light';
        // Atualiza a página atual/iframe também
        document.documentElement.setAttribute('data-theme', newTheme);
    }
});
