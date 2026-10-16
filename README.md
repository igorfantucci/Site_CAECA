# 📘 Portal Web do CAECA (UNIFEI)

Portal do **Centro Acadêmico de Engenharia de Controle e Automação (CAECA)** da **Universidade Federal de Itajubá (UNIFEI)**.

---

## 🚀 Como Executar o Projeto Localmente

### Opção 1: Servidor Embutido do PHP (Recomendado para Testes Rápidos)

Abra o terminal na pasta raiz do projeto e execute:

```bash
php -S localhost:8000 -t public
```

Em seguida, acesse no navegador: **`http://localhost:8000`**

---

### Opção 2: XAMPP (Apache + MySQL ou SQLite)

1. Mova a pasta `CAECA_Site` para dentro de `C:\xampp\htdocs\`.
2. Inicie o **Apache** (e opcionalmente o **MySQL**) no XAMPP Control Panel.
3. Se desejar utilizar MySQL em vez de SQLite:
   - Crie o banco `caeca_db` no phpMyAdmin.
   - Importe o arquivo `database/schema.sql`.
   - No arquivo `src/config/database.php`, altere `define('DB_DRIVER', 'mysql');`.
4. Acesse no navegador: **`http://localhost/CAECA_Site/public`**

---

## 👥 Contas de Acesso Pré-configuradas para Testes

### 1. Diretoria do CAECA (Painel Administrativo)
- **URL:** `http://localhost:8000/admin/login.php`
- **E-mail:** `caeca@unifei.edu.br`
- **Senha:** `admin123`
- *Permite aprovar/rejeitar solicitações de cadastro de alunos e gerenciar o acervo de provas.*

### 2. Aluno Já Aprovado (Área do Aluno Ativa)
- **URL:** `http://localhost:8000/login.php`
- **Matrícula:** `2022001234`
- **Senha:** `aluno123`
- *Acesso completo para download de provas antigas e materiais didáticos.*

### 3. Aluno com Cadastro Pendente (Para Teste de Moderação)
- **Matrícula:** `2024009999`
- **Senha:** `pendente123`
- *Ao tentar logar, exibe aviso de "Aguardando aprovação da diretoria".*

---

## 🔒 Arquitetura e Segurança Implementada

- **Proteção do Acervo de Provas:** Os PDFs ficam localizados em `protected/provas/` (fora da pasta pública `public/`), impossibilitando o acesso direto via URL.
- **Download Seguro (`download.php`):** Verifica se o aluno está autenticado e aprovado no banco de dados, sanitiza o caminho do arquivo contra *Path Traversal* (`basename`) e envia cabeçalhos `nosniff` e `noindex`.
- **Bloqueio de Indexação:** Arquivo `public/robots.txt` bloqueando crawlers em `/area-aluno/` e `/admin/`.
- **Proteção contra CSRF:** Tokens únicos gerados em sessão e validados em todos os formulários (`cadastro.php`, `login.php`, `aprovar-alunos.php`, `gerenciar-provas.php`).
- **Timeout de Sessão:** Expiração automática de sessão após **15 minutos** de inatividade.
- **Prevenção de XSS e SQL Injection:** Saídas escapadas com `htmlspecialchars()` e consultas parametrizadas via `PDO Prepared Statements`.
- **Grafo Interativo de Disciplinas (`Vis.js`):** Nós por período (1º ao 10º semestre), arestas com cores normatizadas (Vermelho = total, Laranja = parcial, Amarelo = co-requisito, Verde = desbloqueia) e modais com ementas do PPC.
