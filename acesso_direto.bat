@echo off
SETLOCAL EnableDelayedExpansion
TITLE XAMPP + Cloudflare Tunnel (Acesso Direto)

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
    echo [AVISO] Apache rodando, mas http://localhost nao respondeu.
)

echo.
echo Iniciando Cloudflare Tunnel (Sem Landing Page/Sem Senha)...
echo [INFO] Aguarde o link aparecer abaixo (sera algo como .trycloudflare.com)
echo.

"C:\Users\Pichau\AppData\Local\Microsoft\WinGet\Packages\Cloudflare.cloudflared_Microsoft.Winget.Source_8wekyb3d8bbwe\cloudflared.exe" tunnel --url http://localhost:80
pause
