<?php
$serverName = "localhost";
$userName = "root";
$password = "";
$dbName = "devplay_db";

// Conecta ao servidor MySQL
$conn = mysqli_connect($serverName, $userName, $password);

if (!$conn) {
    die("Falha na conexão com o servidor: " . mysqli_connect_error());
}

// Tenta selecionar o banco de dados. Se não existir, avisa o usuário.
if (!mysqli_select_db($conn, $dbName)) {
    // Evita loop infinito se já estivermos na página de restauração
    if (basename($_SERVER['PHP_SELF']) != 'setup_db.php') {
        // Descobre o nome da pasta do projeto (caso o aluno tenha renomeado de 'devplay_crud' para outra coisa)
        $pastaProjeto = basename(dirname(__DIR__));

        die("
        <div style='font-family: sans-serif; text-align: center; margin-top: 100px;'>
            <h2 style='color: #333;'>Banco de Dados Não Encontrado!</h2>
            <p style='color: #666;'>O DevPlay identificou que não há um banco de dados instalado neste computador.</p>
            <br>
            <a href='/{$pastaProjeto}/setup_db.php' style='display: inline-block; padding: 15px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 18px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);'>🔧 Configurar e Restaurar Banco Automaticamente</a>
            <p style='margin-top: 20px; color: #999; font-size: 14px;'>Você não precisará usar o phpMyAdmin, o sistema fará tudo sozinho!</p>
        </div>
        ");
    }
}
