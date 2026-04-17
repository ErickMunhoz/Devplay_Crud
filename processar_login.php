<?php
session_start();

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo_login = isset($_POST['tipo_login']) ? $_POST['tipo_login'] : '';

    if ($tipo_login == 'usuario') {
        // ===== LOGIN DE USUÁRIO =====
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $senha = isset($_POST['senha']) ? trim($_POST['senha']) : '';

        // Validações básicas
        if (empty($email) || empty($senha)) {
            $_SESSION['erro_usuario'] = 'E-mail e senha são obrigatórios.';
            header('Location: login.php');
            exit();
        }

        // Validar formato de e-mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['erro_usuario'] = 'E-mail inválido.';
            header('Location: login.php');
            exit();
        }

        require_once 'config/conexao.php';
        
        $email_safe = mysqli_real_escape_string($conn, $email);
        $sql = "SELECT id, nome, email, senha FROM clientes WHERE email = '$email_safe'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            if (password_verify($senha, $user_data['senha'])) {
                // Login bem-sucedido
                $_SESSION['usuario_logado'] = true;
                $_SESSION['email_usuario'] = $user_data['email'];
                $_SESSION['nome_usuario'] = $user_data['nome'];
                $_SESSION['tipo_usuario'] = 'usuario';
    
                // Redirecionar para a página inicial ou dashboard do usuário
                header('Location: index.php');
                exit();
            } else {
                $_SESSION['erro_usuario'] = 'E-mail ou senha incorretos.';
                header('Location: login.php');
                exit();
            }
        } else {
            // Login falhou
            $_SESSION['erro_usuario'] = 'E-mail ou senha incorretos.';
            header('Location: login.php');
            exit();
        }

    } elseif ($tipo_login == 'adm') {
        // ===== LOGIN DE ADMINISTRADOR =====
        $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
        $senha = isset($_POST['senha']) ? trim($_POST['senha']) : '';
        $codigo = isset($_POST['codigo']) ? trim($_POST['codigo']) : '';

        // Validações básicas
        if (empty($usuario) || empty($senha) || empty($codigo)) {
            $_SESSION['erro_adm'] = 'Todos os campos são obrigatórios.';
            header('Location: login.php');
            exit();
        }

        // SIMULAÇÃO DE AUTENTICAÇÃO DE ADMINISTRADOR
        // Em um projeto real, você consultaria o banco de dados aqui
        $admins_validos = array(
            'admin' => array(
                'senha' => 'admin123',
                'codigo' => '1234'
            ),
            'gerente' => array(
                'senha' => 'gerente123',
                'codigo' => '5678'
            ),
            'devmaster' => array(
                'senha' => 'master2024',
                'codigo' => '9999'
            )
        );

        // Verificar credenciais
        if (isset($admins_validos[$usuario]) && 
            $admins_validos[$usuario]['senha'] === $senha && 
            $admins_validos[$usuario]['codigo'] === $codigo) {
            
            // Login bem-sucedido
            $_SESSION['admin_logado'] = true;
            $_SESSION['usuario_admin'] = $usuario;
            $_SESSION['tipo_usuario'] = 'admin';

            // Redirecionar para o painel administrativo
            header('Location: admin/listar.php');
            exit();
        } else {
            // Login falhou
            $_SESSION['erro_adm'] = 'Usuário, senha ou código de acesso incorretos.';
            header('Location: login.php');
            exit();
        }
    } else {
        // Tipo de login inválido
        $_SESSION['erro_geral'] = 'Tipo de login inválido.';
        header('Location: login.php');
        exit();
    }
} else {
    // Se não for POST, redirecionar para login
    header('Location: login.php');
    exit();
}
?>
