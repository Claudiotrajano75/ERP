@echo off
echo Parando o Agente Local de Impressao...
for /f "tokens=5" %%a in ('netstat -aon ^| find ":9187" ^| find "LISTENING"') do taskkill /f /pid %%a >nul 2>&1
echo Agente parado com sucesso!
timeout /t 2 >nul
