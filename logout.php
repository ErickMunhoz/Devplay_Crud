<?php
session_start();

// Destruir todas as variáveis de sessão
$_SESSION = array();

// Se um cookie de sessão existe, remover
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir a sessão
session_destroy();

// Inicia nova sessão limpa apenas para passar a mensagem
session_start();
$_SESSION['sucesso_geral'] = 'Sessão encerrada com sucesso.';

// Redirecionar para a página de login para o usuário ver a notificação
header('Location: login.php');
exit();
?>
