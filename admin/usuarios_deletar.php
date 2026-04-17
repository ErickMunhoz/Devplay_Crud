<?php
session_start();

// Verificar se o usuário está logado como administrador
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header('Location: ../login.php');
    exit();
}

include '../config/conexao.php';

$id = $_GET['id'] ?? '';

if(empty($id)) {
    header('Location: usuarios_listar.php');
    exit();
}

// Buscar usuário para confirmar que existe
$sql = "SELECT * FROM usuarios WHERE id = $id";
$result = mysqli_query($conn, $sql);

if(!$result || mysqli_num_rows($result) == 0) {
    header('Location: usuarios_listar.php');
    exit();
}

// Deletar o usuário
$sql_delete = "DELETE FROM usuarios WHERE id = $id";

if(mysqli_query($conn, $sql_delete)) {
    include '../config/backup.php';
    header('Location: usuarios_listar.php?sucesso=deletado');
    exit();
} else {
    header('Location: usuarios_listar.php?erro=nao_deletado');
    exit();
}
?>
