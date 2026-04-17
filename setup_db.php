<?php
/**
 * Instalador Visual do DevPlay
 * 
 * Verifica se existe um backup salvo e oferece ao usuário a opção
 * de continuar de onde parou ou instalar o banco de dados do zero.
 */

$serverName = "localhost";
$userName = "root";
$password = "";

$initFile = 'db/init.sql';
$backupFile = 'db/backup_data.sql';

// Verifica se o arquivo de backup existe
$hasBackup = file_exists($backupFile);

$msg = "";
$status = "";

// Processa a requisição quando o usuário clica em algum botão
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $sqlFileToRun = ($action === 'restore' && $hasBackup) ? $backupFile : $initFile;

    // Conecta ao MySQL (ainda sem selecionar banco de dados, pois vamos criá-lo)
    $conn = mysqli_connect($serverName, $userName, $password);

    if (!$conn) {
        $msg = "Falha na conexão com o servidor MySQL: " . mysqli_connect_error();
        $status = "error";
    }
    else {
        // Lê as instruções SQL do arquivo escolhido
        $sql = file_get_contents($sqlFileToRun);

        // Executa todas as instruções SQL (multi query)
        if (mysqli_multi_query($conn, $sql)) {
            do {
                if ($result = mysqli_store_result($conn)) {
                    mysqli_free_result($result);
                }
            } while (mysqli_more_results($conn) && mysqli_next_result($conn));

            $msg = "Banco de dados configurado com sucesso!";
            $status = "success";
        }
        else {
            $msg = "Erro ao executar o script SQL: " . mysqli_error($conn);
            $status = "error";
        }
        mysqli_close($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuração do Banco - DevPlay</title>
    <!-- Usa o CSS principal, mas define estilos específicos caso o CSS falhe -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            min-height: 100vh; 
            background: #f8fafc; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }
        .setup-card { 
            background: white; 
            padding: 40px; 
            border-radius: 12px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
            max-width: 600px; 
            width: 90%; 
            text-align: center; 
        }
        .btn-large { 
            display: inline-block; 
            width: 100%; 
            padding: 15px; 
            margin: 10px 0; 
            border-radius: 8px; 
            font-weight: bold; 
            border: none; 
            cursor: pointer; 
            font-size: 16px; 
            transition: 0.2s; 
        }
        .btn-restore { background: #4caf50; color: white; }
        .btn-restore:hover { background: #45a049; }
        
        .btn-init { background: #f44336; color: white; }
        .btn-init:hover { background: #d32f2f; }
        
        .btn-home { background: #667eea; color: white; text-decoration: none; margin-top: 20px; box-sizing: border-box;}
        .btn-home:hover { background: #5a6cd6; }
        
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; }
        .alert-success { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
        .alert-error { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
    </style>
</head>
<body>
    <div class="setup-card">
        <h1>🔧 Instalação do DevPlay</h1>
        <p style="color: #666; margin-bottom: 30px;">O sistema precisa criar o banco de dados para funcionar no seu computador.</p>
        
        <?php if ($msg): ?>
            <!-- Tela de Sucesso ou Erro após clicar no botão -->
            <div class="alert alert-<?php echo $status; ?>">
                <?php echo $msg; ?>
            </div>
            <?php if ($status === 'success'): ?>
                <a href="index.php" class="btn-large btn-home">Ir para a Plataforma</a>
            <?php
    endif; ?>
            
            <?php if ($status === 'error'): ?>
                <button onclick="window.history.back()" class="btn-large" style="background: #ccc;">Voltar e Tentar Novamente</button>
            <?php
    endif; ?>

        <?php
else: ?>
            <!-- Tela Inicial com os Botões -->
            <form method="POST">
                <?php if ($hasBackup): ?>
                    <!-- Se existir backup, destaca a opção de restaurar -->
                    <div style="background: #f0f4ff; border: 1px solid #c3d2ff; padding: 20px; border-radius: 8px; margin-bottom: 20px; text-align: left;">
                        <h3 style="color: #3f51b5; margin-bottom: 10px;">🌟 Backup Encontrado!</h3>
                        <p style="font-size: 14px; color: #555;">Encontramos o arquivo <code>db/backup_data.sql</code> com os seus jogos salvos anteriormente.</p>
                        <button type="submit" name="action" value="restore" class="btn-large btn-restore">
                            Continuar de Onde Parei (Restaurar)
                        </button>
                    </div>
                <?php
    else: ?>
                    <p style="margin-bottom: 20px; color: #888;">Nenhum arquivo de backup encontrado. Será feita uma instalação limpa padrão.</p>
                <?php
    endif; ?>

                <div style="border-top: 1px solid #eee; padding-top: 20px;">
                    <button type="submit" name="action" value="init" class="btn-large btn-init" <?php echo $hasBackup ? 'onclick="return confirm(\'Tem certeza? Você vai ignorar o seu backup e começar de novo!\')"' : ''; ?>>
                        <?php echo $hasBackup ? 'Ignorar Backup e Começar do Zero' : 'Instalar Banco de Dados Padrão'; ?>
                    </button>
                </div>
            </form>
            
        <?php
endif; ?>
    </div>
</body>
</html>
