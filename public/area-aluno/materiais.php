<?php
require_once __DIR__ . '/../../src/helpers/auth.php';
Auth::requireAluno();

$pageTitle = "Materiais e Recursos Didáticos";
require_once __DIR__ . '/../../src/views/header.php';
?>

<div class="page-header">
    <div class="container">
        <h2>📖 Materiais Didáticos e Ferramentas</h2>
        <p>Apostilas, formulários de referência, guias de simulação e repositórios de software para o curso de ECA.</p>
    </div>
</div>

<div class="container">

    <div class="cards-grid">
        <div class="card">
            <span class="card-badge" style="background-color: #eff6ff; color: #1d4ed8;">Matemática & Controle</span>
            <h3 class="card-title">Tabela de Transformadas de Laplace & Fourier</h3>
            <p class="card-subtitle">Formulário de referência para Sinais e Sistemas e Controle Clássico</p>
            <div class="card-details">
                <p>Propriedades de linearidade, convolução, diferenciação, integração, teoremas do valor inicial e final.</p>
            </div>
            <div style="margin-top: 14px;">
                <span class="btn btn-outline btn-sm" style="cursor: default;">Disponível no SIGAA da Disciplina</span>
            </div>
        </div>

        <div class="card">
            <span class="card-badge" style="background-color: #fefce8; color: #854d0e;">Software & Simulação</span>
            <h3 class="card-title">Guia Rápido: MATLAB & Simulink para Controle</h3>
            <p class="card-subtitle">Comandos de tf, step, rlocus, bode, nyquist e lsim</p>
            <div class="card-details">
                <p>Scripts modelo para análise no domínio do tempo e frequência e sintonia de compensadores.</p>
            </div>
            <div style="margin-top: 14px;">
                <span class="btn btn-outline btn-sm" style="cursor: default;">Licença Estudantil UNIFEI</span>
            </div>
        </div>

        <div class="card">
            <span class="card-badge" style="background-color: #f0fdf4; color: #15803d;">Automação & CLPs</span>
            <h3 class="card-title">Apostila de Programação Ladder (IEC 61131-3)</h3>
            <p class="card-subtitle">Exercícios práticos de temporizadores, contadores e memórias de estado</p>
            <div class="card-details">
                <p>Metodologia de passo a passo (cadeia sequencial) para automatização de esteiras e prensas.</p>
            </div>
            <div style="margin-top: 14px;">
                <span class="btn btn-outline btn-sm" style="cursor: default;">Bancadas do LSA</span>
            </div>
        </div>

        <div class="card">
            <span class="card-badge" style="background-color: #fdf2f8; color: #9d174d;">Sistemas Embarcados</span>
            <h3 class="card-title">Pinout e Setup: ARM Cortex & ESP32</h3>
            <p class="card-subtitle">Configuração do STM32CubeIDE e VS Code PlatformIO</p>
            <div class="card-details">
                <p>Pinouts de microcontroladores utilizados nas disciplinas de Programação Embarcada e Microssistemas.</p>
            </div>
            <div style="margin-top: 14px;">
                <span class="btn btn-outline btn-sm" style="cursor: default;">Laboratórios LEC/LSE</span>
            </div>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../../src/views/footer.php'; ?>
