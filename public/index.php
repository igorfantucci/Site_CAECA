<?php
$pageTitle = "Início";
require_once __DIR__ . '/../src/views/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <h1 class="hero-title">Centro Acadêmico de Engenharia de Controle e Automação</h1>
        <p class="hero-subtitle">
            Representação estudantil, integração acadêmica e acervo de estudos para os alunos de Engenharia de Controle e Automação da Universidade Federal de Itajubá.
        </p>
        <div class="hero-actions">
            <?php if ($isAlunoAppr): ?>
                <a href="/area-aluno/provas.php" class="btn btn-secondary"><i class="fas fa-book-open"></i> Acessar Acervo de Provas</a>
            <?php else: ?>
                <a href="/login.php" class="btn btn-secondary"><i class="fas fa-lock"></i> Área do Aluno (Provas & Materiais)</a>
            <?php endif; ?>
            <a href="/disciplinas.php" class="btn btn-outline-hero"><i class="fas fa-project-diagram"></i> Grafo de Pré-requisitos</a>
            <a href="/professores.php" class="btn btn-outline-hero"><i class="fas fa-chalkboard-teacher"></i> Corpo Docente</a>
        </div>
    </div>
</section>

<!-- Carrossel de Notícias / Destaques do CAECA -->
<div class="container">
    <div class="news-carousel-container">
        <div class="news-card-wrapper">
            <div class="news-item">
                <span class="news-tag">Acadêmico</span>
                <h3 class="news-title">Edital de Monitoria 2024/2</h3>
                <p class="news-text">Inscrições abertas para vagas de monitoria em Circuitos, Cálculo e Controle Clássico. Confira os horários e salas.</p>
            </div>
            <div class="news-item gold">
                <span class="news-tag">Eventos</span>
                <h3 class="news-title">Semana da Automação (SECA)</h3>
                <p class="news-text">Ciclo de palestras, minicursos práticos de Indústria 4.0 e visitas técnicas a fábricas da região.</p>
            </div>
            <div class="news-item">
                <span class="news-tag">Estágios</span>
                <h3 class="news-title">Vagas Abertas em Empresas Parceiras</h3>
                <p class="news-text">Processos seletivos para estágio em Schlumberger, Ambev e Vale com vagas específicas para alunos de ECA.</p>
            </div>
        </div>
    </div>
</div>

<!-- Sobre o Curso e Formação -->
<section class="container" style="margin-bottom: 50px;">
    <div class="section-title-wrap">
        <h2 class="section-title">Engenharia de Controle e Automação na UNIFEI</h2>
        <p class="section-subtitle">Excelência em ensino, pesquisa e inovação tecnológica</p>
    </div>

    <div class="grid-sobre-caeca">
        <div class="card-sobre-texto">
            <h3 class="sobre-heading">Excelência e Inovação na Engenharia</h3>
            <p>
                O curso de <strong>Engenharia de Controle e Automação da UNIFEI</strong> (Campus Itajubá) possui carga horária de <strong>3.600 horas</strong> distribuídas ao longo de <strong>10 semestres</strong> letivos, preparando engenheiros qualificados para a vanguarda tecnológica.
            </p>
            <p>
                A formação une de forma sólida os pilares de <strong>eletrônica</strong>, <strong>sistemas de controle</strong>, <strong>programação computacional</strong>, <strong>redes industriais</strong> e <strong>robótica</strong>, capacitando profissionais para a <strong>Indústria 4.0</strong> e transformação digital.
            </p>
            <div style="margin-top: 20px;">
                <a href="/disciplinas.php" class="btn btn-outline"><i class="fas fa-layer-group"></i> Conhecer Estrutura Curricular</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-box">
                <i class="fas fa-calendar-alt stat-icon"></i>
                <div class="stat-number">10</div>
                <div class="stat-label">Semestres Letivos</div>
            </div>
            <div class="stat-box">
                <i class="fas fa-clock stat-icon"></i>
                <div class="stat-number">3.600h</div>
                <div class="stat-label">Carga Horária Total</div>
            </div>
            <div class="stat-box">
                <i class="fas fa-microchip stat-icon"></i>
                <div class="stat-number">10+</div>
                <div class="stat-label">Laboratórios Ativos</div>
            </div>
            <div class="stat-box">
                <i class="fas fa-graduation-cap stat-icon"></i>
                <div class="stat-number">100%</div>
                <div class="stat-label">Docentes Doutores</div>
            </div>
        </div>
    </div>
</section>

<!-- Acessos Rápidos -->
<section class="container">
    <div class="section-title-wrap">
        <h2 class="section-title">Recursos e Navegação</h2>
        <p class="section-subtitle">Acesso rápido aos principais módulos do portal</p>
    </div>

    <div class="quick-grid">
        <a href="/disciplinas.php" class="quick-card">
            <div class="quick-icon"><i class="fas fa-network-wired"></i></div>
            <h3>Matriz & Pré-requisitos</h3>
            <p>Consulte a grade completa do curso com grafo interativo e visualização de dependências entre matérias.</p>
            <span class="btn-card-action">Explorar Grade <i class="fas fa-arrow-right"></i></span>
        </a>

        <a href="/professores.php" class="quick-card">
            <div class="quick-icon"><i class="fas fa-chalkboard-teacher"></i></div>
            <h3>Corpo Docente</h3>
            <p>Conheça os professores do IESTI, suas áreas de pesquisa, currículos Lattes e canais de contato direto.</p>
            <span class="btn-card-action">Ver Professores <i class="fas fa-arrow-right"></i></span>
        </a>

        <a href="/laboratorios.php" class="quick-card">
            <div class="quick-icon"><i class="fas fa-microchip"></i></div>
            <h3>Laboratórios do IESTI</h3>
            <p>Conheça a infraestrutura de bancadas didáticas de CLPs, robôs industriais, plantas de controle e computação.</p>
            <span class="btn-card-action">Ver Laboratórios <i class="fas fa-arrow-right"></i></span>
        </a>

        <a href="/estagios.php" class="quick-card">
            <div class="quick-icon"><i class="fas fa-briefcase"></i></div>
            <h3>Estágios e Empresas</h3>
            <p>Empresas conveniadas, canais de processos seletivos e passo a passo para o estágio supervisionado.</p>
            <span class="btn-card-action">Guia de Estágio <i class="fas fa-arrow-right"></i></span>
        </a>

        <a href="/monitores.php" class="quick-card">
            <div class="quick-icon"><i class="fas fa-user-graduate"></i></div>
            <h3>Monitoria Acadêmica</h3>
            <p>Horários e locais de atendimento com monitores para tirar dúvidas de cálculo, programação e circuitos.</p>
            <span class="btn-card-action">Ver Horários <i class="fas fa-arrow-right"></i></span>
        </a>

        <a href="/login.php" class="quick-card featured-gold">
            <div class="quick-icon"><i class="fas fa-lock"></i></div>
            <h3>Acervo Restrito de Provas</h3>
            <p>Área restrita e segura exclusiva para alunos aprovados consultarem provas e exames de semestres anteriores.</p>
            <span class="btn-card-action">Acessar Acervo <i class="fas fa-arrow-right"></i></span>
        </a>
    </div>
</section>

<!-- Contato da Coordenação e CAECA -->
<section class="container" style="margin-bottom: 60px;">
    <div class="coord-contact-banner">
        <div>
            <h3 style="color: var(--primary); margin-bottom: 6px; font-size: 1.3rem; font-weight: 700;">Coordenação do Curso de ECA (Itajubá)</h3>
            <p style="color: var(--text-main); font-size: 0.95rem; margin-bottom: 6px;"><strong>Coordenador:</strong> Prof. Dr. Caio Fernandes de Paula</p>
            <p style="color: var(--text-muted); font-size: 0.9rem;">
                <i class="fas fa-envelope" style="color: var(--secondary); margin-right: 4px;"></i> <a href="mailto:eca.itajuba@unifei.edu.br">eca.itajuba@unifei.edu.br</a> 
                &nbsp;|&nbsp; 
                <i class="fas fa-phone" style="color: var(--secondary); margin-right: 4px;"></i> +55 (35) 3629-1217
            </p>
        </div>
        <div>
            <a href="mailto:eca.itajuba@unifei.edu.br" class="btn btn-secondary"><i class="fas fa-paper-plane"></i> Falar com a Coordenação</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../src/views/footer.php'; ?>
