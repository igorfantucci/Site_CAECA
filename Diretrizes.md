Com base na análise detalhada do PPC e em pesquisas complementares, elaborei o documento completo para você rodar na sua IA local. Ele contém todas as diretrizes, dados e especificações necessárias para a construção do site do Centro Acadêmico (CAECA).

---

# 📘 Documento Mestre: Site do CAECA (UNIFEI)

## 1. Visão Geral do Projeto

**Objetivo:** Criar um site para o Centro Acadêmico de Engenharia de Controle e Automação (CAECA) da UNIFEI, com visual limpo, navegação intuitiva e uma área restrita para alunos, protegendo o acervo de provas antigas de professores.

**Funcionalidades Principais:**

- Área pública com informações sobre o curso, disciplinas, professores, laboratórios, estágios e monitoria.
- Área restrita para alunos autenticados (cadastro com matrícula + aprovação manual do CAECA).
- Acervo de provas antigas protegido e não indexável.
- Grafo interativo de pré-requisitos das disciplinas.

**Público-Alvo:**

- Alunos do curso de ECA (usuários principais).
- Professores e coordenação (consulta de informações públicas).
- CAECA (administração do site e aprovação de cadastros).

---

## 2. Arquitetura Técnica (Protótipo Local)

### Stack Sugerida

- **Frontend:** HTML5, CSS3, JavaScript (opcional: Vue.js ou React).
- **Backend:** PHP 8+ (com XAMPP) ou Node.js + Express.
- **Banco de Dados:** MySQL (via XAMPP) ou SQLite.
- **Ambiente Local:** XAMPP ou Docker.
- **Controle de Versão:** Git.

### Estrutura de Diretórios (PHP/XAMPP)

```
caeca-site/
├── public/                      # Arquivos públicos
│   ├── index.php                # Página inicial
│   ├── login.php                # Login de alunos
│   ├── cadastro.php             # Cadastro de alunos
│   ├── disciplinas.php          # Lista e grafo de disciplinas
│   ├── professores.php          # Corpo docente
│   ├── laboratorios.php         # Laboratórios
│   ├── estagios.php             # Estágios e empresas
│   ├── monitores.php            # Monitores
│   ├── area-aluno/              # Área restrita (após login)
│   │   ├── provas.php
│   │   ├── materiais.php
│   │   └── ...
│   ├── admin/                   # Painel do CAECA
│   │   ├── dashboard.php
│   │   ├── aprovar-alunos.php
│   │   └── ...
│   ├── download.php             # Script protegido de download de provas
│   └── assets/                  # CSS, JS, imagens
├── src/
│   ├── config/database.php      # Conexão com banco
│   ├── models/                  # Classes de acesso ao banco
│   ├── controllers/             # Lógica das páginas
│   └── helpers/auth.php         # Funções de autenticação
├── protected/                   # Arquivos NÃO acessíveis via URL
│   └── provas/                  # PDFs das provas antigas
├── database/schema.sql          # Script de criação das tabelas
└── .git/                        # Repositório Git
```

---

## 3. Banco de Dados (Modelo Completo)

### Tabelas Principais

```sql
-- Alunos cadastrados (pendentes, aprovados, rejeitados)
CREATE TABLE alunos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricula VARCHAR(20) UNIQUE NOT NULL,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    senha_hash VARCHAR(255) NOT NULL,
    status ENUM('pendente', 'aprovado', 'rejeitado') DEFAULT 'pendente',
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_aprovacao DATETIME NULL,
    aprovado_por INT NULL,
    FOREIGN KEY (aprovado_por) REFERENCES admins(id)
);

-- Administradores (CAECA)
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha_hash VARCHAR(255) NOT NULL,
    cargo VARCHAR(100) NULL
);

-- Disciplinas
CREATE TABLE disciplinas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    nome VARCHAR(200) NOT NULL,
    periodo INT NOT NULL,
    tipo ENUM('obrigatoria', 'optativa') DEFAULT 'obrigatoria',
    carga_horaria_teorica INT DEFAULT 0,
    carga_horaria_pratica INT DEFAULT 0,
    carga_horaria_total INT GENERATED ALWAYS AS (carga_horaria_teorica + carga_horaria_pratica) STORED,
    ementa TEXT,
    objetivos TEXT,
    bibliografia TEXT
);

-- Pré-requisitos (relacionamento)
CREATE TABLE prerequisitos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    disciplina_id INT NOT NULL,
    prerequisito_id INT NOT NULL,
    tipo ENUM('total', 'parcial', 'co-requisito') NOT NULL,
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id),
    FOREIGN KEY (prerequisito_id) REFERENCES disciplinas(id)
);

-- Provas antigas
CREATE TABLE provas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    disciplina_id INT NOT NULL,
    ano INT NOT NULL,
    semestre VARCHAR(10) NULL,
    professor VARCHAR(150) NULL,
    tipo ENUM('prova', 'trabalho', 'lista') DEFAULT 'prova',
    arquivo_path VARCHAR(255) NOT NULL,
    data_upload DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id)
);

-- Professores
CREATE TABLE professores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150),
    lattes VARCHAR(255),
    instituto VARCHAR(100),
    area_atuacao VARCHAR(200),
    titulacao VARCHAR(100),
    foto VARCHAR(255),
    eh_coordenador BOOLEAN DEFAULT FALSE,
    tipo_coordenacao VARCHAR(100) NULL, -- 'curso', 'estagio', 'tcc', 'monitoria'
    disciplinas_mininstradas TEXT
);

-- Laboratórios
CREATE TABLE laboratorios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sigla VARCHAR(20) UNIQUE NOT NULL,
    nome VARCHAR(200) NOT NULL,
    localizacao VARCHAR(200),
    responsavel_id INT,
    descricao TEXT,
    equipamentos TEXT,
    softwares TEXT,
    disciplinas_atendidas TEXT,
    FOREIGN KEY (responsavel_id) REFERENCES professores(id)
);

-- Monitores
CREATE TABLE monitores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    disciplina_id INT NOT NULL,
    horario VARCHAR(100),
    local VARCHAR(150),
    contato VARCHAR(150),
    semestre VARCHAR(20),
    FOREIGN KEY (aluno_id) REFERENCES alunos(id),
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id)
);

-- Empresas parceiras de estágio
CREATE TABLE empresas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(200) NOT NULL,
    site VARCHAR(255),
    area_atuacao VARCHAR(200),
    localizacao VARCHAR(200),
    editais_link VARCHAR(255),
    observacoes TEXT
);
```

---

## 4. Dados para Popular o Site

### 4.1 Disciplinas (Matriz Curricular 2022)

Com base no PPC, a matriz curricular do curso de ECA (campus Itajubá) é organizada em 10 semestres, totalizando 3600 horas.

**1º Semestre:**

- ECAE01 - Circuitos de Corrente Contínua
- ECAE11 - Laboratório de Circuitos de Corrente Contínua
- MAT00A - Cálculo A
- LET011 - Escrita Acadêmico-Científica
- ECAE00 - Introdução à Engenharia e ao Método Científico
- ECOP11A - Técnicas de Programação
- ECOM00 - Lógica para Engenharia
- ECOM10 - Laboratório de Lógica para Engenharia

**2º Semestre:**

- ECOP06 - Python, Orientação a Objetos, Estrutura de Dados
- MAT00B - Cálculo B
- ECAE02 - Circuitos de Corrente Alternada e Polifásicos
- ECAE12 - Laboratório de Circuitos de Corrente Alternada e Polifásicos
- LET012 - Comunicação Oral para fins Acadêmicos
- FIS210 - Física I
- FIS212 - Física Experimental I
- ELTD01A - Eletrônica Digital 1
- ELTD11A - Laboratório de Eletrônica Digital 1

**3º Semestre:**

- MAT00D - Equações Diferenciais A
- ECOP04 - Programação Embarcada
- ECOP14 - Laboratório de Programação Embarcada
- FIS320 - Física II B
- FIS322 - Física Experimental II B
- MAT00N - Cálculo Numérico
- ECAE03 - Eletrônica Analógica 1
- ECAE13 - Laboratório de Eletrônica Analógica 1
- ELTD12A - Eletrônica Digital 2

**4º Semestre:**

- ELT052A - Química e Ciência dos Materiais
- QUI212 - Química Geral Experimental
- MAT252 - Álgebra Linear e Aplicações
- ECAC00 - Introdução ao Controle de Processos
- ECAT00 - Instrumentação Fundamental para Controle e Automação
- ECAT10 - Laboratório de Instrumentação Fundamental para Controle e Automação
- ELTP01A - Máquinas e Acionamentos Eletrônicos
- ELTP11A - Laboratório de Máquinas e Acionamentos Eletrônicos
- ECAA00 - Introdução à Automação de Processos
- EMAG01 - Eletromagnetismo
- FIS412 - Física Experimental III

**5º Semestre:**

- ECAA01A - Automação de Processos
- EME402 - Dinâmica dos Sólidos 1
- MAT013 - Probabilidade e Estatística
- ECAA04A - Automação Pneumática e Hidráulica
- ECAA14A - Laboratório de Automação Pneumática e Hidráulica
- ELTD03A - Microcontroladores e Microprocessadores
- ELTD13A - Laboratório de Microcontroladores e Microprocessadores
- ECAC01B - Sinais e Sistemas

**6º Semestre:**

- TELC04A - Redes Industriais
- TELC14A - Laboratório de Redes Industriais
- ECAA02A - Automação e Supervisão de Processos
- ECAA08 - Automática
- IEPG08 - Gestão de Projetos
- ECAC07 - Modelagem de Sistemas Dinâmicos
- ECAC02A - Controle Clássico

**7º Semestre:**

- ECAA05A - Fundamentos da Indústria 4.0
- ELTE02 - Engenharia de Usabilidade
- ECAA06A - Robótica
- ECAA16 - Laboratório de Robótica
- ECA410 - Gestão de Operações
- ECAC03A - Controle Moderno
- ECAA03A - Sistemas a Eventos Discretos

**8º Semestre:**

- ECAA010 - Projeto Integrador de Automação
- ECAA09 - Inteligência Artificial Aplicada à Automação
- EME320 - Processos de Transformação
- IEPG10 - Engenharia Econômica
- ECAC08 - Prática de Controle Industrial
- IEPG20 - Introdução à Economia
- ECAA07A - Banco de Dados para Automação
- ECAA011 - Robótica Avançada (Optativa)

**9º Semestre:**

- IRN001 - Ciências do Ambiente
- TCC1 - Trabalho de Conclusão de Curso 1
- OPTATIVA 1
- OPTATIVA 2

**10º Semestre:**

- TCC2 - Trabalho de Conclusão de Curso 2
- ESTÁGIO

### 4.2 Corpo Docente

**Coordenação do Curso (Itajubá):**

- **Coordenador:** Prof. Dr. Caio Fernandes de Paula
  - E-mail: `eca.itajuba@unifei.edu.br`
  - Telefone: +55 35 3629-1217

**Coordenador de Estágios:**

- E-mail: `estagio.eca.itajuba@unifei.edu.br`

**Coordenador de TCC:**

- Prof. Dr. Geraldo Fabiano de Souza Moraes

**Professores do IESTI (Instituto de Engenharia de Sistemas e Tecnologias da Informação):**

| Nome                                | Titulação | Área de Atuação                       |
| ----------------------------------- | --------- | ------------------------------------- |
| Caio Fernandes de Paula             | Doutor    | Sistemas de Controle                  |
| Carlos Henrique Valério de Moraes   | Doutor    | Programação e Inteligência Artificial |
| Denis de Carvalho Braga             | Doutor    | Sistemas Dinâmicos                    |
| Giscard Francimeire Cintra Veloso   | Doutor    | Processamento Digital de Sinais       |
| Fernando Henrique Duarte Guaracy    | Doutor    | Sistemas de Controle                  |
| Jeremias Barbosa Machado            | Doutor    | Sistemas de Controle e IA             |
| Luis Henrique de Carvalho Ferreira  | Doutor    | Controle e Instrumentação             |
| Luiz Edival de Souza                | Doutor    | Sistemas de Automação                 |
| Robson Bauwelz Gonzatti             | Doutor    | Eletrônica de Potência                |
| Rondineli Rodrigues Pereira         | Doutor    | Eletrônica de Potência                |
| Leonardo Breseghello Zoccal         | Doutor    | Eletrônica Analógica                  |
| Ana Paula Siqueira Silva de Almeida | Doutor    | Usabilidade                           |
| Thatyana de Faria Piola Seraphim    | Doutor    | Computação                            |
| Odilon de Oliveira Dutra            | Doutor    | Microssistemas                        |
| Maurílio Pereira Coutinho           | Doutor    | Sistemas Embarcados                   |
| Edvard Martins de Oliveira          | Doutor    | Redes                                 |
| Bruno Tardiole Kuehne               | Doutor    | Computação                            |
| Gabriel Antonio Fanelli de Souza    | Doutor    | Computação                            |

*Observação: Para a lista completa de todos os professores que lecionam para o curso, incluindo o ciclo básico (IFQ, IEM, IEPG, etc.), consultar o Anexo A do PPC.*

### 4.3 Laboratórios

**Laboratórios de Formação Específica (IESTI):**

| Sigla              | Nome                                                           | Localização | Responsável                                | Disciplinas Atendidas         |
| ------------------ | -------------------------------------------------------------- | ----------- | ------------------------------------------ | ----------------------------- |
| **LEPA**           | Laboratório de Eletrônica de Potência Aplicada                 | Bloco K     | Prof. Rondineli Rodrigues Pereira          | ELTP01A, ELTP11A              |
| **LSAHP**          | Laboratório de Sistemas de Automação Hidráulicos e Pneumáticos | Bloco I     | —                                          | ECAA04A, ECAA14A              |
| **LSA**            | Laboratório de Sistemas de Automação                           | Bloco I     | —                                          | ECAA00, ECAA01A, ECAA02A      |
| **LRO**            | Laboratório de Robótica                                        | Bloco I     | —                                          | ECAA06A, ECAA16               |
| **LCPIC**          | Laboratório de Controle de Processos Industriais Contínuos     | Bloco I     | Prof. Jeremias Barbosa Machado             | ECAC00, ECAC02A, ECAC08       |
| **LUFH**           | Laboratório de Usabilidade e Fatores Humanos                   | Bloco I     | Profa. Ana Paula Siqueira Silva de Almeida | ELTE02                        |
| **LEC I, II, III** | Laboratórios de Engenharia de Computação                       | Bloco I     | Profa. Thatyana de Faria Piola Seraphim    | ECOP11A, ECOP06, ECOP04, etc. |
| **LMS I, II**      | Laboratórios de Microssistemas                                 | Bloco I     | Prof. Odilon de Oliveira Dutra             | ELTD03A, ELTD13A              |
| **LSC**            | Laboratório de Sistemas de Comunicação                         | Bloco I     | Prof. Kazuo Nakashima                      | TELC04A                       |
| **LSE**            | Laboratório de Sistemas Embarcados                             | Bloco I     | Prof. Maurílio Pereira Coutinho            | ECOP04, ECOP14                |

**Laboratórios de Formação Básica (IFQ, IEM, etc.):**

- **LDF1 a LDF6** (Laboratórios Didáticos de Física) - IFQ
- **LQG** (Laboratório de Química Geral) - IFQ
- **LDDT** (Laboratório Didático de Desenho Técnico) - IEM
- **LEG** (Laboratório Expressões Gráficas) - IEM

### 4.4 Empresas Parceiras para Estágio

Com base em convênios históricos da UNIFEI:

| Empresa                  | Área de Atuação      | Localização            |
| ------------------------ | -------------------- | ---------------------- |
| Schlumberger             | Petróleo e Gás       | Nacional/Internacional |
| Johnson & Johnson        | Saúde e Farmacêutica | Nacional               |
| Ambev                    | Bebidas              | Nacional               |
| General Motors do Brasil | Automotiva           | Nacional               |
| CSN                      | Siderurgia           | Nacional               |
| Furnas                   | Energia Elétrica     | Nacional               |
| Danone                   | Alimentos            | Nacional               |
| FUPAI                    | Pesquisa e Inovação  | Itajubá/MG             |
| Vale                     | Mineração            | Itabira/MG             |

*Nota: A lista completa de empresas conveniadas pode ser obtida junto à Coordenação de Estágios.*

### 4.5 Monitores

*Observação: A lista de monitores é atualizada semestralmente por meio de editais. Para o protótipo, podem ser utilizados dados fictícios ou uma lista de exemplo.*

**Exemplo de estrutura para exibição:**

| Disciplina                        | Monitor        | Contato                      | Horário         | Local     |
| --------------------------------- | -------------- | ---------------------------- | --------------- | --------- |
| ECAE01 - Circuitos CC             | João Silva     | joao.silva@unifei.edu.br     | Ter/Qui 14h-16h | LEA I     |
| MAT00A - Cálculo A                | Maria Santos   | maria.santos@unifei.edu.br   | Seg/Qua 10h-12h | Sala 2101 |
| ECOP11A - Técnicas de Programação | Pedro Oliveira | pedro.oliveira@unifei.edu.br | Sex 08h-12h     | LEC I     |

---

## 5. Diretrizes para a IA Local (Prompt de Geração)

Ao rodar este documento em sua IA local, utilize as seguintes diretrizes para garantir que o resultado seja consistente com o que foi planejado:

### 5.1 Instruções Gerais

```
Você é um desenvolvedor web full-stack especializado em PHP, MySQL, HTML, CSS e JavaScript. Sua tarefa é criar um site completo para o Centro Acadêmico de Engenharia de Controle e Automação (CAECA) da UNIFEI, com base no documento de especificação fornecido.

Siga rigorosamente a estrutura de diretórios, o modelo de banco de dados e as funcionalidades descritas. Priorize:
1. Código limpo, comentado e seguro (contra SQL Injection, XSS, CSRF).
2. Design responsivo e minimalista.
3. Área restrita protegida por autenticação e aprovação manual.
4. Acervo de provas antigas inacessível a usuários não logados e não indexável.
5. Grafo interativo de pré-requisitos usando Vis.js ou D3.js.

Para cada página, crie o arquivo PHP correspondente, o CSS e o JavaScript necessário.
```

### 5.2 Especificações de Cada Página

**Página Inicial (`index.php`):**

- Carrossel de notícias (pode ser estático para o protótipo).
- Texto resumido sobre o curso.
- Botões de acesso rápido (Área do Aluno, Disciplinas, Professores).
- Links úteis (SIGAA, e-mail da coordenação).

**Login (`login.php`):**

- Formulário com matrícula e senha.
- Validação contra a tabela `alunos` (status `aprovado`).
- Redirecionamento para `area-aluno/` após login.
- Sessão com tempo de expiração de 15 minutos.

**Cadastro (`cadastro.php`):**

- Formulário com nome, matrícula, e-mail e senha.
- Insere na tabela `alunos` com status `pendente`.
- Exibe mensagem de "aguardando aprovação".

**Painel Admin (`admin/dashboard.php`):**

- Lista de alunos pendentes.
- Botões "Aprovar" e "Rejeitar".
- Acesso restrito a usuários da tabela `admins`.

**Disciplinas (`disciplinas.php`):**

- Tabela com todas as disciplinas (código, nome, período, CH).
- Botão "Ver Grafo" que carrega o grafo interativo.
- Ao clicar em um nó, exibir detalhes da disciplina em um modal.

**Grafo de Disciplinas:**

- Usar Vis.js com nós coloridos por período.
- Arestas coloridas: Vermelho (pré-requisito total), Laranja (parcial), Amarelo (co-requisito), Verde (desbloqueia).

**Professores (`professores.php`):**

- Cards com foto, nome, titulação, área, e-mail e Lattes.
- Filtros por instituto e área de atuação.
- Destaque para coordenadores.

**Laboratórios (`laboratorios.php`):**

- Cards com sigla, nome, localização, responsável, equipamentos e disciplinas atendidas.
- Fotos dos ambientes (podem ser placeholders).

**Estágios (`estagios.php`):**

- Tabela com empresas parceiras, área de atuação e localização.
- Link para editais (quando disponíveis).
- Guia passo a passo do estágio supervisionado.

**Monitores (`monitores.php`):**

- Tabela com disciplina, monitor, contato, horário e local.
- Link para editais de monitoria.

**Download Protegido (`download.php`):**

- Script que verifica se o aluno está logado e aprovado antes de servir o PDF.
- Os PDFs ficam em `protected/provas/`, fora da pasta pública.

### 5.3 Requisitos de Segurança

- **Senhas:** Usar `password_hash()` e `password_verify()`.
- **Sessões:** Usar `session_start()` e verificar `$_SESSION['aluno_id']` e `$_SESSION['status']`.
- **SQL Injection:** Usar prepared statements (PDO ou MySQLi).
- **XSS:** Escapar saídas com `htmlspecialchars()`.
- **CSRF:** Gerar e validar tokens em formulários.
- **Bloqueio de Indexação:** Criar `robots.txt` com `Disallow: /area-aluno/` e `Disallow: /admin/`.

### 5.4 Dados para Popular o Banco

Inclua no script `schema.sql` os dados iniciais das disciplinas (conforme seção 4.1), professores (seção 4.2), laboratórios (seção 4.3) e empresas (seção 4.4). Para monitores e provas, deixe as tabelas vazias (serão populadas pelo CAECA).


