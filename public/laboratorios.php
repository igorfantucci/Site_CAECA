<?php
$pageTitle = "Laboratórios";
require_once __DIR__ . '/../src/models/Laboratorio.php';
require_once __DIR__ . '/../src/views/header.php';

$laboratorios = Laboratorio::getTodos();
?>

<div class="page-header">
    <div class="container">
        <h2>Laboratórios e Infraestrutura</h2>
        <p>Espaços de bancadas didáticas, pesquisa aplicada e prototipagem dedicados ao curso de Controle e Automação.</p>
    </div>
</div>

<div class="container">

    <!-- Laboratórios de Formação Específica (IESTI) -->
    <div class="section-title-wrap" style="text-align: left; margin-bottom: 20px;">
        <h3 class="section-title" style="font-size: 1.4rem;">Laboratórios de Formação Específica (IESTI)</h3>
        <p class="section-subtitle">Plantas piloto, células robotizadas, bancadas de CLPs e eletrônica aplicada</p>
    </div>

    <div class="filters-bar">
        <input type="text" id="lab-search" class="search-input" placeholder="🔍 Filtrar laboratório por nome, sigla, equipamentos ou softwares...">
    </div>

    <div class="cards-grid" id="lab-cards-container">
        <?php foreach ($laboratorios as $lab): ?>
            <div class="card lab-card" 
                 data-sigla="<?= e(strtolower($lab['sigla'])) ?>"
                 data-nome="<?= e(strtolower($lab['nome'])) ?>"
                 data-equip="<?= e(strtolower($lab['equipamentos'] ?? '')) ?>"
                 data-soft="<?= e(strtolower($lab['softwares'] ?? '')) ?>">
                
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                    <span class="card-badge badge-primary-lab">
                        <?= e($lab['sigla']) ?>
                    </span>
                    <span style="font-size: 0.82rem; color: var(--text-muted); font-weight: 600;">
                        <i class="fas fa-map-marker-alt" style="color: var(--secondary);"></i> <?= e($lab['localizacao']) ?>
                    </span>
                </div>

                <h3 class="card-title" style="font-size: 1.1rem; margin-bottom: 8px;"><?= e($lab['nome']) ?></h3>
                
                <p style="font-size: 0.88rem; color: var(--text-main); margin-bottom: 12px;">
                    <?= e($lab['descricao']) ?>
                </p>

                <div class="card-details" style="font-size: 0.84rem; background: #f8fafc; padding: 12px; border-radius: 6px; border: 1px solid var(--border-color);">
                    <?php if (!empty($lab['responsavel_nome'])): ?>
                        <p><strong>Responsável:</strong> <?= e($lab['responsavel_nome']) ?></p>
                    <?php endif; ?>

                    <?php if (!empty($lab['equipamentos'])): ?>
                        <p><strong>Equipamentos:</strong> <?= e($lab['equipamentos']) ?></p>
                    <?php endif; ?>

                    <?php if (!empty($lab['softwares'])): ?>
                        <p><strong>Softwares:</strong> <span style="font-family: monospace;"><?= e($lab['softwares']) ?></span></p>
                    <?php endif; ?>

                    <?php if (!empty($lab['disciplinas_atendidas'])): ?>
                        <p style="margin-top: 6px;">
                            <strong>Disciplinas:</strong> 
                            <span class="card-badge" style="background: #e0f2fe; color: #0369a1;"><?= e($lab['disciplinas_atendidas']) ?></span>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Laboratórios de Formação Básica -->
    <div class="section-title-wrap" style="text-align: left; margin: 40px 0 20px;">
        <h3 class="section-title" style="font-size: 1.4rem;">Laboratórios de Formação Básica (IFQ e IEM)</h3>
        <p class="section-subtitle">Laboratórios compartilhados para disciplinas de física, química e expressão gráfica</p>
    </div>

    <div class="cards-grid">
        <div class="card">
            <span class="card-badge" style="background: #334155; color: #fff;">LDF 1 a 6</span>
            <h3 class="card-title">Laboratórios Didáticos de Física</h3>
            <p class="card-subtitle">Instituto de Física e Química (IFQ)</p>
            <div class="card-details">
                <p>Atende às disciplinas de Física Experimental I (FIS212), Física Experimental II B (FIS322) e Física Experimental III (FIS412).</p>
            </div>
        </div>

        <div class="card">
            <span class="card-badge" style="background: #334155; color: #fff;">LQG</span>
            <h3 class="card-title">Laboratório de Química Geral</h3>
            <p class="card-subtitle">Instituto de Física e Química (IFQ)</p>
            <div class="card-details">
                <p>Atende à disciplina de Química Geral Experimental (QUI212) e ensaios de materiais.</p>
            </div>
        </div>

        <div class="card">
            <span class="card-badge" style="background: #334155; color: #fff;">LDDT / LEG</span>
            <h3 class="card-title">Laboratórios de Desenho e Expressões Gráficas</h3>
            <p class="card-subtitle">Instituto de Engenharia Mecânica (IEM)</p>
            <div class="card-details">
                <p>Pranchetas de precisão e estações CAD para modelagem geométrica e leitura de projetos mecânicos.</p>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('lab-search');
    const cards = document.querySelectorAll('.lab-card');

    searchInput.addEventListener('input', () => {
        const term = searchInput.value.toLowerCase().trim();
        cards.forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(term) ? '' : 'none';
        });
    });
});
</script>

<?php require_once __DIR__ . '/../src/views/footer.php'; ?>
