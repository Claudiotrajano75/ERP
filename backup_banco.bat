@echo off
setlocal

echo ========================================================
echo       SISTEMA DE BACKUP DO BANCO DE DADOS (MySQL)
echo ========================================================
echo.

set MYSQLDUMP_PATH=C:\xampp\mysql\bin\mysqldump.exe
set DB_HOST=127.0.0.1
set DB_USER=root
set DB_NAME=u544033777_erp
set BACKUP_DIR=%~dp0backups

if not exist "%MYSQLDUMP_PATH%" (
    echo [ERRO] Utilitario mysqldump nao foi encontrado em: %MYSQLDUMP_PATH%
    echo Verifique a instalacao do XAMPP.
    pause
    exit /b 1
)

if not exist "%BACKUP_DIR%" (
    mkdir "%BACKUP_DIR%"
)

for /f "tokens=*" %%a in ('powershell -NoProfile -Command "Get-Date -Format 'yyyy-MM-dd_HH-mm-ss'"') do set TIMESTAMP=%%a

set OUTPUT_FILE=%BACKUP_DIR%\backup_%DB_NAME%_%TIMESTAMP%.sql

echo [1/2] Iniciando exportacao do banco de dados "%DB_NAME%"...
echo       Destino: %OUTPUT_FILE%
echo       Aguarde alguns instantes...
echo.

"%MYSQLDUMP_PATH%" --host=%DB_HOST% --user=%DB_USER% --default-character-set=utf8mb4 --single-transaction --quick --routines --triggers --max_allowed_packet=512M %DB_NAME% > "%OUTPUT_FILE%"

if %ERRORLEVEL% equ 0 (
    echo [2/2] Backup realizado com SUCESSO!
    echo.
    echo       Arquivo gerado na pasta backups!
    echo       Localizacao: %OUTPUT_FILE%
    echo.
    echo ========================================================
    echo Backup concluido perfeitamente sem perda de indices!
    echo ========================================================
) else (
    echo [ERRO] Ocorreu uma falha durante o processo de exportacao.
    if exist "%OUTPUT_FILE%" del "%OUTPUT_FILE%"
)

echo.
pause
