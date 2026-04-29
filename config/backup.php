<?php
/**
 * Script Automático de Backup do Banco de Dados
 * 
 * Este arquivo é chamado automaticamente toda vez que um jogo é cadastrado, editado ou excluído.
 * Ele cria uma cópia fiel da tabela 'jogos' e salva em 'db/backup_data.sql'.
 * Assim, os alunos podem copiar a pasta do projeto para outro PC e restaurar seus próprios jogos.
 */

// Verifica se a conexão já existe (se foi chamado do processar.php), senão, tenta conectar
if (!isset($conn)) {
    include 'conexao.php';
}

// Caminho absoluto para a pasta db na raiz do projeto devplay_crud
$backupFile = __DIR__ . '/../db/backup_data.sql';

// 1. Inicia o texto do arquivo SQL que será gerado
$sqlDump = "-- BACKUP AUTOMÁTICO - DEVPLAY\n";
$sqlDump .= "-- Gerado em: " . date('Y-m-d H:i:s') . "\n\n";

$sqlDump .= "CREATE DATABASE IF NOT EXISTS devplay_db;\n";
$sqlDump .= "USE devplay_db;\n\n";

// DROP TABLE garante que ao restaurar, a tabela será recriada exatamente como no backup
$sqlDump .= "DROP TABLE IF EXISTS jogos;\n\n";

$sqlDump .= "CREATE TABLE IF NOT EXISTS jogos (\n";
$sqlDump .= "    id INT AUTO_INCREMENT PRIMARY KEY,\n";
$sqlDump .= "    titulo VARCHAR(100) NOT NULL,\n";
$sqlDump .= "    descricao TEXT NOT NULL,\n";
$sqlDump .= "    imagem TEXT NOT NULL,\n";
$sqlDump .= "    tipo ENUM('internal', 'external') NOT NULL DEFAULT 'internal',\n";
$sqlDump .= "    url VARCHAR(255) NOT NULL,\n";
$sqlDump .= "    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP\n";
$sqlDump .= ");\n\n";

// 2. Busca todos os jogos cadastrados atualmente no banco
$result = mysqli_query($conn, "SELECT * FROM jogos");

// Se houver algum jogo, preparamos o comando INSERT
if ($result && mysqli_num_rows($result) > 0) {
    $sqlDump .= "INSERT INTO jogos (id, titulo, descricao, imagem, tipo, url, created_at) VALUES \n";

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Precisamos escapar aspas simples para não quebrar o código SQL
        $id = $row['id'];
        $titulo = mysqli_real_escape_string($conn, $row['titulo']);
        $descricao = mysqli_real_escape_string($conn, $row['descricao']);
        $imagem = mysqli_real_escape_string($conn, $row['imagem']);
        $tipo = mysqli_real_escape_string($conn, $row['tipo']);
        $url = mysqli_real_escape_string($conn, $row['url']);
        $createdAt = mysqli_real_escape_string($conn, $row['created_at']);

        // Monta a linha de cada jogo no formato SQL: (1, 'Mario', 'Desc...', '...', '...', '...')
        $rows[] = "($id, '$titulo', '$descricao', '$imagem', '$tipo', '$url', '$createdAt')";
    }

    // Junta todas as linhas separadas por vírgula e adiciona Ponto e Vírgula no fim
    $sqlDump .= implode(",\n", $rows) . ";\n\n";
}

// ----------------------------------------------------
// BACKUP DA TABELA USUÁRIOS
// ----------------------------------------------------
$sqlDump .= "DROP TABLE IF EXISTS usuarios;\n\n";

$sqlDump .= "CREATE TABLE IF NOT EXISTS usuarios (\n";
$sqlDump .= "    id INT AUTO_INCREMENT PRIMARY KEY,\n";
$sqlDump .= "    nome VARCHAR(255) NOT NULL,\n";
$sqlDump .= "    email VARCHAR(255) NOT NULL UNIQUE,\n";
$sqlDump .= "    senha VARCHAR(255) NOT NULL,\n";
$sqlDump .= "    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP\n";
$sqlDump .= ");\n\n";

$resultUsuarios = mysqli_query($conn, "SELECT * FROM usuarios");

if ($resultUsuarios && mysqli_num_rows($resultUsuarios) > 0) {
    $sqlDump .= "INSERT INTO usuarios (id, nome, email, senha, data_cadastro) VALUES \n";

    $rowsUsuarios = [];
    while ($rowU = mysqli_fetch_assoc($resultUsuarios)) {
        $idU = $rowU['id'];
        $nomeU = mysqli_real_escape_string($conn, $rowU['nome']);
        $emailU = mysqli_real_escape_string($conn, $rowU['email']);
        $senhaU = mysqli_real_escape_string($conn, $rowU['senha']);
        $dataCadastroU = mysqli_real_escape_string($conn, $rowU['data_cadastro']);

        $rowsUsuarios[] = "($idU, '$nomeU', '$emailU', '$senhaU', '$dataCadastroU')";
    }

    $sqlDump .= implode(",\n", $rowsUsuarios) . ";\n\n";
}

// 3. Salva todo o texto gerado dentro do arquivo backup_data.sql
file_put_contents($backupFile, $sqlDump);
