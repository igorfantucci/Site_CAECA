<?php
require_once __DIR__ . '/../../src/helpers/auth.php';
require_once __DIR__ . '/../../src/models/Prova.php';
require_once __DIR__ . '/../../src/models/Disciplina.php';

Auth::requireAluno();

$disciplinaId = isset($_GET['disciplina_id']) && $_GET['disciplina_id'] !== '' ? (int)$_GET['disciplina_id'] : null;
$ano = isset($_GET['ano']) && $_GET['ano'] !== '' ? (int)$_GET['ano'] : null;
$tipo = isset($_GET['tipo']) && $_GET['tipo'] !== '' ? sanitize_input($_GET['tipo']) : null;

$provas = Prova::getTodas($disciplinaId, $ano, $tipo);
$disciplinas = Disciplina::getTodas();

$pageTitle = "Acervo de Provas Antigas";
require_once __DIR__ . '/../../src/views/header.php';
?>

<div class="page-header">
    <div class="container">
        <h2>📚 Acervo Protegido de Provas e Exames</h2>
        <p>Consulte avaliações de semestres anteriores para orientar seus estudos e resolução de exercícios.</p>
    </div>
</div>

<div class="container">

    <div class="alert alert-warning" style="font-size: 0.88rem;">
        ⚠️ <strong>Aviso Importante:</strong> O acervo de provas do CAECA destina-se exclusivamente ao apoio didático dos alunos de ECA. É proibida a redistribuição externa ou indexação por motores de busca.
    </div>

    <!-- Filtros de Busca de Provas -->
    <form method="GET" action="/area-aluno/provas.php" class="filters-bar">
        <select name="disciplina_id" class="filter-select" style="min-width: 220px;">
            <option value="">Todas as Disciplinas</option>
            <?php foreach ($disciplinas as $d): ?>
                <option value="<?= $d['id'] ?>" <?= $disciplinaId === (int)$d['id'] ? 'selected' : '' ?>>
                    <?= e($d['codigo']) ?> - <?= e($d['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="tipo" class="filter-select">
            <option value="">Todos os Tipos</option>
            <option value="prova" <?= $tipo === 'prova' ? 'selected' : '' ?>>Prova</option>
            <option value="trabalho" <?= $tipo === 'trabalho' ? 'selected' : '' ?>>Trabalho</option>
            <option value="lista" <?= $tipo === 'lista' ? 'selected' : '' ?>>Lista de Exercícios</option>
        </select>

        <select name="ano" class="filter-select">
            <option value="">Todos os Anos</option>
            <?php for ($y = date('Y'); $y >= 2018; $y--): ?>
                <option value="<?= $y ?>" <?= $ano === $y ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
        </select>

        <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
        <a href="/area-aluno/provas.php" class="btn btn-outline btn-sm">Limpar Filtros</a>
        
        <input type="text" data-table-search="#tabela-acervo-provas" class="search-input" placeholder="🔍 Filtrar nesta tabela..." style="margin-left: auto;">
    </form>

    <!-- Tabela do Acervo -->
    <div class="table-container">
        <table id="tabela-acervo-provas" class="data-table">
            <thead>
                <tr>
                    <th style="width: 110px;">Código</th>
                    <th>Disciplina</th>
                    <th style="width: 100px;">Período</th>
                    <th style="width: 140px;">Ano/Semestre</th>
                    <th>Docente Responsável</th>
                    <th style="width: 100px;">Tipo</th>
                    <th style="width: 140px; text-align: center;">Download Seguro</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($provas)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                            Nenhuma prova encontrada com os filtros selecionados.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($provas as $p): ?>
                        <tr>
                            <td><span style="font-family: monospace; font-weight: 700; color: var(--primary);"><?= e($p['disciplina_codigo']) ?></span></td>
                            <td><strong><?= e($p['disciplina_nome']) ?></strong></td>
                            <td><?= e($p['periodo']) ?>º Semestre</td>
                            <td><?= e($p['ano']) ?> • <?= e($p['semestre'] ?: 'Anual') ?></td>
                            <td><?= e($p['professor'] ?: 'Não especificado') ?></td>
                            <td><span class="card-badge"><?= e(ucfirst($p['tipo'])) ?></span></td>
                            <td style="text-align: center;">
                                <a href="/download.php?id=<?= (int)$p['id'] ?>" target="_blank" class="btn btn-primary btn-sm">
                                    📥 Baixar PDF
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php require_once __DIR__ . '/../../src/views/footer.php'; ?>
