@echo off
title ERP - Agente Local de Impressao Termica
color 0A

echo ========================================================
echo    ERP - AGENTE LOCAL DE IMPRESSAO TERMICA
echo ========================================================
echo.
echo  Porta local: 9187
echo  Status: Aguardando requisicoes do ERP na Nuvem...
echo.
echo  [Mantenha esta janela aberta enquanto o caixa estiver operando]
echo ========================================================
echo.

cd /d "%~dp0"

:: Tenta localizar o executável do PHP
set PHP_BIN=php
if exist "C:\xampp\php\php.exe" set PHP_BIN=C:\xampp\php\php.exe

%PHP_BIN% -S 127.0.0.1:9187 server.php
pause
