@echo off
SETLOCAL EnableDelayedExpansion
TITLE XAMPP + LocalTunnel for devplay_crud

echo Verificando servicos do XAMPP...

:: Verifica Apache
tasklist /FI "IMAGENAME eq httpd.exe" 2>NUL | find /I /N "httpd.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo Apache ja esta em execucao.
) else (
    echo Iniciando Apache...
    start "" "C:\xampp\apache_start.bat"
    set "WAIT_NEEDED=1"
)

:: Verifica MySQL
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo MySQL ja esta em execucao.
) else (
    echo Iniciando MySQL...
    start "" "C:\xampp\mysql_start.bat"
    set "WAIT_NEEDED=1"
)

:: Aguarda apenas se algum serviço foi iniciado agora
if defined WAIT_NEEDED (
    echo Aguardando servicos subirem...
    timeout /t 5 /nobreak > nul
)

echo Verificando conectividade em http://localhost...
powershell -Command "try { $resp = Invoke-WebRequest -Uri 'http://localhost' -Method Head -TimeoutSec 2 -ErrorAction Stop; exit 0 } catch { exit 1 }" >NUL 2>&1
if "%ERRORLEVEL%"=="0" (
    echo [OK] Site esta respondendo localmente.
) else (
    echo [AVISO] Apache rodando, mas http://localhost nao respondeu. Verifique se ha erros no XAMPP.
)

:: DEFINA SEU NOME PERSONALIZADO AQUI (Exemplo: devplay-crud)
SET "LT_SUBDOMAIN=devplay"

echo.
echo Iniciando LocalTunnel para https://%LT_SUBDOMAIN%.localtunnel.me...
echo [DICA] Na primeira visita, o LocalTunnel pode pedir seu IP publico para seguranca.
echo Seu IP atual pode ser visto em: https://checkip.amazonaws.com
:: Usa o caminho completo do lt.cmd instalado pelo npm
"%AppData%\npm\lt.cmd" --port 80 --subdomain %LT_SUBDOMAIN%
pause
