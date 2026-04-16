<?php
include '../config/conexao.php';

$acao = isset($_POST['acao']) ? $_POST['acao'] : (isset($_GET['acao']) ? $_GET['acao'] : '');

if ($acao == 'cadastrar') {
    $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
    $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);
    $imagem = mysqli_real_escape_string($conn, $_POST['imagem']);
    $tipo = mysqli_real_escape_string($conn, $_POST['tipo']);
    $url = mysqli_real_escape_string($conn, $_POST['url']);

    $sql = "INSERT INTO jogos (titulo, descricao, imagem, tipo, url) VALUES ('$titulo', '$descricao', '$imagem', '$tipo', '$url')";

    if (mysqli_query($conn, $sql)) {
        // Gera o backup atualizado antes de redirecionar
        include '../config/backup.php';
        header("Location: listar.php?msg=sucesso");
    }
    else {
        echo "Erro: " . mysqli_error($conn);
    }
}
else if ($acao == 'editar') {
    $id = $_POST['id'];
    $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
    $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);
    $imagem = mysqli_real_escape_string($conn, $_POST['imagem']);
    $tipo = mysqli_real_escape_string($conn, $_POST['tipo']);
    $url = mysqli_real_escape_string($conn, $_POST['url']);

    $sql = "UPDATE jogos SET titulo='$titulo', descricao='$descricao', imagem='$imagem', tipo='$tipo', url='$url' WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        // Gera o backup atualizado antes de redirecionar
        include '../config/backup.php';
        header("Location: listar.php?msg=editado");
    }
    else {
        echo "Erro: " . mysqli_error($conn);
    }
}
else if ($acao == 'deletar') {
    $id = $_GET['id'];
    $sql = "DELETE FROM jogos WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        // Gera o backup atualizado antes de redirecionar
        include '../config/backup.php';
        header("Location: listar.php?msg=deletado");
    }
    else {
        echo "Erro: " . mysqli_error($conn);
    }
}
else {
    header("Location: listar.php");
}
