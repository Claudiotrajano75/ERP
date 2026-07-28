---
name: git-push
description: Skill para atualizar o projeto no GitHub automaticamente. Contém os dados de configuração do repositório Git do usuário.
---

# 🚀 Git Push — ERP

> **Objetivo**: Quando o usuário disser "atualizar no git", "enviar para o git", "dar git push", "fazer commit e push" ou qualquer variação disso, siga estes passos automaticamente.

---

## 📋 Configuração do Repositório

| Informação | Valor |
|------------|-------|
| **Usuário GitHub** | `Claudiotrajano75` |
| **Repositório** | `ERP` |
| **Remote URL** | `https://github.com/Claudiotrajano75/ERP.git` |
| **Branch** | `main` |
| **Nome do Autor** | `Claudio Trajano` |
| **Email do Autor** | `trajano75@hotmail.com` |

---

## 🔧 Passos para Atualizar

Quando o usuário pedir para atualizar o Git, execute **todos** os passos abaixo:

### 1. Verificar o estado atual

```bash
cd /c/xampp/htdocs/ERP && git status
```

### 2. Adicionar todas as alterações

```bash
cd /c/xampp/htdocs/ERP && git add --all
```

### 3. Fazer commit (perguntar ao usuário qual mensagem, ou usar padrão)

Se o usuário não especificar uma mensagem, pergunte: *"Qual mensagem do commit?"*

Exemplo:
```bash
cd /c/xampp/htdocs/ERP && git commit -m "Mensagem descritiva das alterações"
```

### 4. Enviar para o GitHub

```bash
cd /c/xampp/htdocs/ERP && git push -u origin main
```

### 5. Confirmar sucesso

Verifique se o push foi concluído e informe o usuário.

---

## ⚠️ Observações

- O Git já está configurado neste diretório com o remote `origin` apontando para o repositório correto.
- Não é necessário `git init` nem configurar remote novamente.
- O projeto tem um `.gitignore` que exclui `vendor/`, `node_modules/`, `.env`, `storage/` (exceto `public`), etc.
