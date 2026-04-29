<?php
$conn = mysqli_connect('localhost', 'root', '', 'devplay_db');
if (!$conn) die('Failed to connect');

$sql = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if(mysqli_query($conn, $sql)) echo "Success creating usuarios table\n";
else echo mysqli_error($conn) . "\n";

// Insert a default admin user for testing
$senha = password_hash('123456', PASSWORD_DEFAULT);
$sql = "INSERT IGNORE INTO usuarios (nome, email, senha) VALUES ('Administrador', 'admin@admin.com', '$senha')";
mysqli_query($conn, $sql);
?>
