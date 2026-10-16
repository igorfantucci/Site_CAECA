<?php
$pageTitle = "Disciplinas, Grafo e Equivalências";
$loadGraphCss = true;
require_once __DIR__ . '/../src/models/Disciplina.php';
require_once __DIR__ . '/../src/models/Equivalencia.php';
require_once __DIR__ . '/../src/views/header.php';

$disciplinasObrigatorias = Disciplina::getTodas('obrigatoria');
$disciplinasOptativas = Disciplina::getTodas('optativa');
$grafoDados = Disciplina::getDadosGrafo();
$equivalencias = Equivalencia::getTodas();
?>

<div class="page-header">
    <div class="container">
        <h2>Matriz Curricular, Grafo e Equivalências</h2>
        <p>Explore a grade do curso de Engenharia de Controle e Automação (UNIFEI), consulte pré-requisitos, optativas e tabela de transição.</p>
    </div>
</div>

<div class="container">

    <!-- Abas de Navegação -->
    <div class="matrix-tabs">
        <button class="matrix-tab-btn active" onclick="switchTab('tab-grafo')">📊 Grafo Interativo</button>
        <button class="matrix-tab-btn" onclick="switchTab('tab-grade')">📑 Grade Completa (10 Semestres)</button>
        <button class="matrix-tab-btn" onclick="switchTab('tab-optativas')">🎯 Disciplinas Optativas (<?= count($disciplinasOptativas) ?>)</button>
        <button class="matrix-tab-btn" onclick="switchTab('tab-equivalencias')">🔄 Matérias Equivalentes (<?= count($equivalencias) ?>)</button>
    </div>

    <!-- ========================================================
         ABA 1: GRAFO INTERATIVO (Estilo LSA UNIFEI)
         ======================================================== -->
    <div id="tab-grafo" class="tab-pane active">
        
        <div class="graph-wrapper">
            <!-- Barra de Ferramentas do Grafo -->
            <div class="graph-toolbar">
                <div class="graph-toolbar-group">
                    <input type="text" id="graph-search" class="search-input" placeholder="🔍 Buscar disciplina por código ou nome..." style="min-width: 250px;">
                    
                    <select id="graph-periodo-filter" class="filter-select">
                        <option value="todos">Todos os Períodos</option>
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?>º Semestre</option>
                        <?php endfor; ?>
                    </select>

                    <select id="graph-tipo-filter" class="filter-select">
                        <option value="todos">Todos os Tipos</option>
                        <option value="obrigatoria">Apenas Obrigatórias</option>
                        <option value="optativa">Apenas Optativas</option>
                    </select>
                </div>
                
                <div class="graph-toolbar-group">
                    <label style="font-size: 0.86rem; font-weight: 600; display: flex; align-items: center; gap: 6px; cursor: pointer; color: var(--text-main);">
                        <input type="checkbox" id="graph-isolate-toggle" checked style="cursor: pointer;">
                        Ocultar não relacionadas ao clicar
                    </label>
                    <button id="graph-reset-btn" class="btn btn-outline btn-sm" title="Limpar seleção e resetar visão">🎯 Exibir Tudo</button>
                </div>
            </div>

            <!-- HUD de Seleção Dinâmica (Aparece ao clicar em uma matéria) -->
            <div id="graph-selection-hud" class="graph-hud">
                <div class="hud-header">
                    <div>
                        <div class="hud-title" id="hud-disciplina-nome">Código - Nome da Disciplina</div>
                        <div class="hud-subtitle" id="hud-disciplina-info">Período • CH • Tipo</div>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <button id="hud-detalhes-btn" class="btn btn-outline btn-sm">📖 Ver Ementa Completa</button>
                        <button id="hud-clear-btn" class="btn btn-primary btn-sm">✖ Limpar Seleção</button>
                    </div>
                </div>

                <div class="hud-grid">
                    <div>
                        <div class="hud-col-title" style="color: #b91c1c;">⬅️ Depende de (Pré-requisitos Necessários):</div>
                        <div id="hud-predecessores" class="hud-pills-wrap"></div>
                    </div>
                    <div>
                        <div class="hud-col-title" style="color: #15803d;">➡️ Libera (Disciplinas que Dependem Desta):</div>
                        <div id="hud-sucessores" class="hud-pills-wrap"></div>
                    </div>
                </div>
            </div>

            <!-- Canvas do Grafo Vis.js -->
            <div id="disciplina-graph" class="graph-container">
                <div id="graph-loading" class="graph-loading-overlay">
                    <div class="spinner"></div>
                    <span>Renderizando Grade Interativa...</span>
                </div>
            </div>

            <!-- Legenda -->
            <div class="graph-legend">
                <div class="legend-section">
                    <span class="legend-title">Arestas:</span>
                    <span class="legend-item"><span class="legend-line line-total"></span> Pré-requisito Total</span>
                    <span class="legend-item"><span class="legend-line line-parcial"></span> Pré-requisito Parcial</span>
                    <span class="legend-item"><span class="legend-line line-coreq"></span> Co-requisito</span>
                    <span class="legend-item"><span class="legend-line line-unlocked"></span> Desbloqueia</span>
                </div>
                <div class="legend-section">
                    <span class="legend-title">Como interagir:</span>
                    <span style="color: var(--text-muted);">Clique em uma matéria para isolar suas dependências. Duplo clique abre a ementa completa.</span>
                </div>
            </div>
        </div>

    </div>

    <!-- ========================================================
         ABA 2: GRADE CURRICULAR COMPLETA
         ======================================================== -->
    <div id="tab-grade" class="tab-pane">
        <div class="filters-bar">
            <input type="text" data-table-search="#tabela-grade-obrigatorias" class="search-input" placeholder="🔍 Filtrar disciplinas obrigatórias...">
        </div>

        <div class="table-container">
            <table id="tabela-grade-obrigatorias" class="data-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Período</th>
                        <th style="width: 110px;">Código</th>
                        <th>Nome da Disciplina</th>
                        <th style="width: 90px; text-align: center;">Teórica</th>
                        <th style="width: 90px; text-align: center;">Prática</th>
                        <th style="width: 100px; text-align: center;">CH Total</th>
                        <th style="width: 110px; text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($disciplinasObrigatorias as $d): ?>
                        <tr>
                            <td><strong><?= e($d['periodo']) ?>º</strong></td>
                            <td><span style="font-family: monospace; font-weight: 700; color: var(--primary);"><?= e($d['codigo']) ?></span></td>
                            <td><strong><?= e($d['nome']) ?></strong></td>
                            <td style="text-align: center;"><?= e($d['carga_horaria_teorica']) ?>h</td>
                            <td style="text-align: center;"><?= e($d['carga_horaria_pratica']) ?>h</td>
                            <td style="text-align: center;"><strong><?= e($d['carga_horaria_total']) ?>h</strong></td>
                            <td style="text-align: center;">
                                <button class="btn btn-outline btn-sm" onclick="exibirDetalhesDisciplina(<?= (int)$d['id'] ?>)">
                                    Detalhes
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ========================================================
         ABA 3: DISCIPLINAS OPTATIVAS DO PPC
         ======================================================== -->
    <div id="tab-optativas" class="tab-pane">
        <div class="alert alert-info" style="margin-bottom: 20px;">
            🎯 <strong>Regra de Optativas (PPC ECA):</strong> O discente deve integralizar no mínimo <strong>128 horas</strong> de disciplinas optativas (duas matérias de 64h ou equivalentes) distribuídas ao longo das trilhas de <strong>Controle</strong>, <strong>Automação</strong>, <strong>Sistemas Eletrônicos</strong> ou <strong>Computação</strong>.
        </div>

        <div class="filters-bar">
            <input type="text" data-table-search="#tabela-optativas" class="search-input" placeholder="🔍 Filtrar disciplinas optativas...">
        </div>

        <div class="table-container">
            <table id="tabela-optativas" class="data-table">
                <thead>
                    <tr>
                        <th style="width: 110px;">Código</th>
                        <th>Nome da Disciplina Optativa</th>
                        <th style="width: 90px; text-align: center;">Teórica</th>
                        <th style="width: 90px; text-align: center;">Prática</th>
                        <th style="width: 100px; text-align: center;">CH Total</th>
                        <th>Ementa Resumida</th>
                        <th style="width: 110px; text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($disciplinasOptativas as $opt): ?>
                        <tr>
                            <td><span style="font-family: monospace; font-weight: 700; color: var(--accent);"><?= e($opt['codigo']) ?></span></td>
                            <td><strong><?= e($opt['nome']) ?></strong></td>
                            <td style="text-align: center;"><?= e($opt['carga_horaria_teorica']) ?>h</td>
                            <td style="text-align: center;"><?= e($opt['carga_horaria_pratica']) ?>h</td>
                            <td style="text-align: center;"><strong><?= e($opt['carga_horaria_total']) ?>h</strong></td>
                            <td style="font-size: 0.85rem; color: var(--text-muted);"><?= e($opt['ementa']) ?></td>
                            <td style="text-align: center;">
                                <button class="btn btn-outline btn-sm" onclick="exibirDetalhesDisciplina(<?= (int)$opt['id'] ?>)">
                                    Detalhes
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ========================================================
         ABA 4: MATÉRIAS EQUIVALENTES (Transição Curricular PPC)
         ======================================================== -->
    <div id="tab-equivalencias" class="tab-pane">
        <div class="alert alert-warning" style="margin-bottom: 20px;">
            🔄 <strong>Tabela de Equivalências Curriculares:</strong> Utilizada para validação de matérias cursadas na matriz anterior (PPC 2012/2018) ou por transferência externa / reopção de curso.
        </div>

        <div class="filters-bar">
            <input type="text" data-table-search="#tabela-equivalencias" class="search-input" placeholder="🔍 Buscar equivalência por código antigo ou atual...">
        </div>

        <div class="table-container">
            <table id="tabela-equivalencias" class="data-table">
                <thead>
                    <tr>
                        <th style="width: 120px;">Código Antigo</th>
                        <th>Nome Antigo (PPC 2012/2018)</th>
                        <th style="width: 40px; text-align: center;">⇄</th>
                        <th style="width: 120px;">Código Atual</th>
                        <th>Disciplina Atual (PPC 2022/2024)</th>
                        <th style="width: 80px; text-align: center;">Período</th>
                        <th style="width: 140px;">Tipo Equivalência</th>
                        <th>Observações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($equivalencias as $eq): ?>
                        <tr>
                            <td><span style="font-family: monospace; font-weight: 700; color: #dc2626;"><?= e($eq['codigo_antigo']) ?></span></td>
                            <td><?= e($eq['nome_antigo']) ?></td>
                            <td style="text-align: center; color: var(--primary); font-weight: 700;">➔</td>
                            <td><span style="font-family: monospace; font-weight: 700; color: #16a34a;"><?= e($eq['codigo_atual']) ?></span></td>
                            <td><strong><?= e($eq['nome_atual']) ?></strong></td>
                            <td style="text-align: center;"><?= e($eq['periodo_atual']) ?>º</td>
                            <td><span class="card-badge" style="background-color: #dbeafe; color: #1e40af;"><?= e($eq['tipo_equivalencia']) ?></span></td>
                            <td style="font-size: 0.85rem; color: var(--text-muted);"><?= e($eq['observacoes']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal de Detalhes da Disciplina -->
<div id="modal-disciplina-detalhes" class="modal-backdrop">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3 id="modal-disc-titulo">Código - Nome da Disciplina</h3>
            <button class="modal-close-btn" data-close-modal>&times;</button>
        </div>
        <div class="modal-body">
            <div style="display: flex; gap: 16px; margin-bottom: 16px; flex-wrap: wrap;">
                <div><strong>Semestre:</strong> <span id="modal-disc-periodo">-</span></div>
                <div><strong>Carga Horária:</strong> <span id="modal-disc-ch">-</span></div>
            </div>

            <div style="margin-bottom: 16px;">
                <h4 style="font-size: 0.95rem; color: var(--primary); margin-bottom: 6px;">Ementa:</h4>
                <p id="modal-disc-ementa" style="font-size: 0.9rem; color: var(--text-main); background: #f8fafc; padding: 12px; border-radius: 6px; border: 1px solid var(--border-color);"></p>
            </div>

            <div style="margin-bottom: 16px;">
                <h4 style="font-size: 0.95rem; color: var(--primary); margin-bottom: 6px;">Objetivos do Curso:</h4>
                <p id="modal-disc-objetivos" style="font-size: 0.9rem; color: var(--text-muted);"></p>
            </div>

            <div style="margin-bottom: 16px;">
                <h4 style="font-size: 0.95rem; color: var(--primary); margin-bottom: 6px;">Pré-requisitos Necessários:</h4>
                <div id="modal-disc-prereqs" class="req-badge-group"></div>
            </div>

            <div>
                <h4 style="font-size: 0.95rem; color: var(--primary); margin-bottom: 6px;">Disciplinas Desbloqueadas por Esta:</h4>
                <div id="modal-disc-unlocks" class="req-badge-group"></div>
            </div>
        </div>
    </div>
</div>

<script src="/assets/js/graph.js"></script>
<script>
function switchTab(tabId) {
    document.querySelectorAll('.matrix-tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));

    const activePane = document.getElementById(tabId);
    if (activePane) activePane.classList.add('active');

    event.currentTarget.classList.add('active');

    if (tabId === 'tab-grafo' && window.network) {
        setTimeout(() => window.network.fit(), 150);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const nodes = <?= json_encode($grafoDados['nodes'], JSON_UNESCAPED_UNICODE) ?>;
    const edges = <?= json_encode($grafoDados['edges'], JSON_UNESCAPED_UNICODE) ?>;
    initDisciplinaGraph(nodes, edges);
});
</script>

<?php require_once __DIR__ . '/../src/views/footer.php'; ?>
