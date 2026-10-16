-- ==========================================================
-- Banco de Dados: CAECA - UNIFEI
-- Sistema de Gestão do Centro Acadêmico de Eng. de Controle e Automação
-- ==========================================================

-- 1. Tabela de Administradores (CAECA)
CREATE TABLE IF NOT EXISTS admins (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha_hash VARCHAR(255) NOT NULL,
    cargo VARCHAR(100) NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 2. Tabela de Alunos cadastrados
CREATE TABLE IF NOT EXISTS alunos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    matricula VARCHAR(20) UNIQUE NOT NULL,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    senha_hash VARCHAR(255) NOT NULL,
    status VARCHAR(20) DEFAULT 'pendente' CHECK (status IN ('pendente', 'aprovado', 'rejeitado')),
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_aprovacao DATETIME NULL,
    aprovado_por INT NULL,
    FOREIGN KEY (aprovado_por) REFERENCES admins(id)
);

-- 3. Tabela de Professores
CREATE TABLE IF NOT EXISTS professores (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150),
    telefone VARCHAR(50),
    lattes VARCHAR(255),
    instituto VARCHAR(100),
    area_atuacao VARCHAR(200),
    titulacao VARCHAR(100),
    foto VARCHAR(255),
    eh_coordenador BOOLEAN DEFAULT 0,
    tipo_coordenacao VARCHAR(100) NULL, -- 'curso', 'estagio', 'tcc', 'direcao_iesti', 'vice_direcao_iesti'
    disciplinas_ministradas TEXT
);

-- 4. Tabela de Disciplinas
CREATE TABLE IF NOT EXISTS disciplinas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    nome VARCHAR(200) NOT NULL,
    periodo INT NOT NULL,
    tipo VARCHAR(20) DEFAULT 'obrigatoria' CHECK (tipo IN ('obrigatoria', 'optativa')),
    carga_horaria_teorica INT DEFAULT 0,
    carga_horaria_pratica INT DEFAULT 0,
    carga_horaria_total INT DEFAULT 0,
    ementa TEXT,
    objetivos TEXT,
    bibliografia TEXT
);

-- 5. Tabela de Pré-requisitos
CREATE TABLE IF NOT EXISTS prerequisitos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    disciplina_id INT NOT NULL,
    prerequisito_id INT NOT NULL,
    tipo VARCHAR(20) NOT NULL CHECK (tipo IN ('total', 'parcial', 'co-requisito')),
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id),
    FOREIGN KEY (prerequisito_id) REFERENCES disciplinas(id)
);

-- 6. Tabela de Provas Antigas
CREATE TABLE IF NOT EXISTS provas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    disciplina_id INT NOT NULL,
    ano INT NOT NULL,
    semestre VARCHAR(10) NULL,
    professor VARCHAR(150) NULL,
    tipo VARCHAR(20) DEFAULT 'prova' CHECK (tipo IN ('prova', 'trabalho', 'lista')),
    arquivo_path VARCHAR(255) NOT NULL,
    data_upload DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id)
);

-- 7. Tabela de Laboratórios
CREATE TABLE IF NOT EXISTS laboratorios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
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

-- 8. Tabela de Monitores
CREATE TABLE IF NOT EXISTS monitores (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome_monitor VARCHAR(150) NOT NULL,
    email_monitor VARCHAR(150) NOT NULL,
    disciplina_id INT NOT NULL,
    horario VARCHAR(100),
    local VARCHAR(150),
    semestre VARCHAR(20),
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id)
);

-- 9. Tabela de Empresas Parceiras de Estágio
CREATE TABLE IF NOT EXISTS empresas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome VARCHAR(200) NOT NULL,
    site VARCHAR(255),
    area_atuacao VARCHAR(200),
    localizacao VARCHAR(200),
    editais_link VARCHAR(255),
    observacoes TEXT
);

-- 10. Tabela de Equivalências Curriculares (Transição PPC)
CREATE TABLE IF NOT EXISTS equivalencias (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    disciplina_atual_id INT NOT NULL,
    codigo_antigo VARCHAR(20) NOT NULL,
    nome_antigo VARCHAR(200) NOT NULL,
    matriz_origem VARCHAR(50) DEFAULT 'PPC 2012/2018',
    tipo_equivalencia VARCHAR(50) DEFAULT 'Total (1:1)',
    observacoes TEXT,
    FOREIGN KEY (disciplina_atual_id) REFERENCES disciplinas(id)
);
