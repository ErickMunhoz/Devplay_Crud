@echo off
setlocal
:: =====================================================================
:: GERENCIADOR GIT ETEC - MENU INTERATIVO
:: =====================================================================

:MENU
cls
echo =====================================================================
echo           GERENCIADOR DE PROJETO GIT - ETEC LAB
echo =====================================================================
echo  1. BAIXAR/ATUALIZAR (Trazer do GitHub para o PC)
echo  2. ENVIAR TUDO (Salvar do PC para o GitHub - MODO FORCADO)
echo  3. CONFIGURAR APENAS (Nome e Email)
echo  4. SAIR
echo =====================================================================
set /p opcao="Escolha uma opcao (1-4): "

:: Configurações Iniciais (Sempre executa para garantir)
git config --global user.name "ErickMunhoz"
git config --global user.email "erick@exemplo.com"
set REPO_URL=https://github.com/ErickMunhoz/Devplay_Crud.git
set PASTA_PROJETO=C:\xampp\htdocs\devplay_crud_usuarios

if "%opcao%"=="1" goto BAIXAR
if "%opcao%"=="2" goto ENVIAR
if "%opcao%"=="3" goto CONFIG
if "%opcao%"=="4" exit
goto MENU

:BAIXAR
echo.
echo [!] Iniciando Download/Atualizacao...
if not exist %PASTA_PROJETO% mkdir %PASTA_PROJETO%
cd /d %PASTA_PROJETO%

if not exist .git (
    echo [!] Pasta vazia. Clonando repositorio...
    cd ..
    git clone %REPO_URL% devplay_crud_usuarios
) else (
    echo [!] Repositorio ja existe. Puxando atualizacoes...
    git pull origin main --allow-unrelated-histories
)
echo.
echo Processo concluido!
pause
goto MENU

:ENVIAR
echo.
echo [!] Iniciando Envio (Upload)...
cd /d %PASTA_PROJETO%

if not exist .git (
    echo [!] Inicializando Git...
    git init
    git remote add origin %REPO_URL%
)

git add .
set /p msg="Digite uma mensagem para o commit (ou deixe vazio): "
if "%msg%"=="" set msg="Atualizacao automatica via script"

git commit -m "%msg%"
echo [!] Enviando arquivos (Modo Forcado)...
git push -f origin main

echo.
echo Processo concluido!
pause
goto MENU

:CONFIG
echo.
echo [!] Configurando identidade...
git config --global user.name "ErickMunhoz"
git config --global user.email "erick@exemplo.com"
echo Nome: ErickMunhoz
echo Email: erick@exemplo.com
pause
goto MENU
