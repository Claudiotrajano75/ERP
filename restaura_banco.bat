@echo off
setlocal

echo ========================================================
echo     SISTEMA DE RESTAURACAO DO BANCO DE DADOS (MySQL)
echo ========================================================
echo.

set MYSQL_PATH=C:\xampp\mysql\bin\mysql.exe
set DB_HOST=127.0.0.1
set DB_USER=root
set DB_NAME=u544033777_erp
set SCRIPT_CORRECAO=%~dp0scripts\corrigir_autoincrement.php

if not exist "%MYSQL_PATH%" (
    echo [ERRO] Utilitario mysql nao foi encontrado em: %MYSQL_PATH%
    echo Verifique a instalacao do XAMPP.
    pause
    exit /b 1
)

set SQL_FILE=%~1

if "%SQL_FILE%"=="" (
    if exist "C:\u544033777_erp.sql" (
        set SUGESTAO=C:\u544033777_erp.sql
    ) else (
        for /f "tokens=*" %%a in ('powershell -NoProfile -Command "Get-ChildItem -Path '%~dp0backups\*.sql' -ErrorAction SilentlyContinue | Sort-Object LastWriteTime -Descending | Select-Object -First 1 -ExpandProperty FullName"') do set SUGESTAO=%%a
    )
)

if "%SQL_FILE%"=="" (
    if not "%SUGESTAO%"=="" (
        echo Arquivo sugerido para restauracao:
        echo   [%SUGESTAO%]
        echo.
        set /p INPUT_PATH=Pressione [ENTER] para usar o arquivo sugerido ou digite o caminho de outro: 
    ) else (
        set /p INPUT_PATH=Arraste ou digite o caminho completo do arquivo .sql para restaurar: 
    )
)

if "%SQL_FILE%"=="" (
    if "%INPUT_PATH%"=="" (
        set SQL_FILE=%SUGESTAO%
    ) else (
        set SQL_FILE=%INPUT_PATH%
    )
)

:: Remove aspas se houver
set SQL_FILE=%SQL_FILE:"=%

if not exist "%SQL_FILE%" (
    echo.
    echo [ERRO] O arquivo especificado nao foi encontrado:
    echo "%SQL_FILE%"
    echo.
    pause
    exit /b 1
)

echo.
echo ========================================================
echo [ATENCAO] O banco de dados "%DB_NAME%" local sera restaurado.
echo Arquivo de origem: "%SQL_FILE%"
echo ========================================================
set /p CONFIRMA=Deseja prosseguir com a restauracao? (S/N): 
if /i not "%CONFIRMA%"=="S" (
    echo Restauracao cancelada pelo usuario.
    pause
    exit /b 0
)

echo.
echo [1/3] Garantindo criacao do banco "%DB_NAME%" se nao existir...
"%MYSQL_PATH%" --host=%DB_HOST% --user=%DB_USER% -e "CREATE DATABASE IF NOT EXISTS %DB_NAME% CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

echo [2/3] Importando dados a partir do arquivo SQL...
echo       Aguarde, este processo pode levar alguns segundos...
"%MYSQL_PATH%" --host=%DB_HOST% --user=%DB_USER% --default-character-set=utf8mb4 --max_allowed_packet=512M %DB_NAME% < "%SQL_FILE%"

if %ERRORLEVEL% neq 0 (
    echo.
    echo [ERRO] Falha ao importar o arquivo SQL no MySQL.
    pause
    exit /b 1
)

echo.
echo [3/3] Verificando e corrigindo AUTO_INCREMENT em todas as tabelas...
if exist "%SCRIPT_CORRECAO%" (
    php "%SCRIPT_CORRECAO%"
)

echo.
echo ========================================================
echo [SUCESSO] Restauracao concluida com total integridade!
echo O banco "%DB_NAME%" esta 100%% atualizado e pronto para uso.
echo ========================================================
echo.
pause
