Set WshShell = CreateObject("WScript.Shell")
Set FSO = CreateObject("Scripting.FileSystemObject")
currentDir = FSO.GetParentFolderName(WScript.ScriptFullName)

' Fecha qualquer instancia anterior na porta 9187 para evitar duplicidade
WshShell.Run "cmd /c for /f ""tokens=5"" %a in ('netstat -aon ^| find "":9187"" ^| find ""LISTENING""') do taskkill /f /pid %a", 0, True

' Inicia o agente de impressao em segundo plano (oculto)
WshShell.Run "cmd /c """ & currentDir & "\iniciar_impressor.bat""", 0, False

WScript.Sleep 600

' Exibe confirmacao visual ao usuario
MsgBox "Agente de Impressao Termica iniciado com sucesso em segundo plano!" & vbCrLf & vbCrLf & _
       "Porta local: 9187" & vbCrLf & _
       "Status: Ativo e pronto para receber impressoes da Hostinger.", 64, "ERP - Agente de Impressao"

