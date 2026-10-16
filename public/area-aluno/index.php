<?php
require_once __DIR__ . '/../../src/helpers/auth.php';
require_once __DIR__ . '/../../src/models/Prova.php';
require_once __DIR__ . '/../../src/models/Disciplina.php';

Auth::requireAluno();

$alunoNome = $_SESSION['aluno_nome'] ?? 'Aluno';
$alunoMatricula = $_SESSION['aluno_matricula'] ?? '';
$provas = Prova::getTodas();
$disciplinas = Disciplina::getTodas();

$pageTitle = "Área do Aluno";
require_once __DIR__ . '/../../src/views/header.php';
?>

<div class="page-header">
    <div class="container">
        <h2>🎓 Área do Aluno</h2>
        <p>Bem-vindo(a), <strong><?= e($alunoNome) ?></strong> (Matrícula: <?= e($alunoMatricula) ?>)</p>
    </div>
</div>

<div class="container">

    <!-- Cards de Resumo -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; margin-bottom: 35px;">
        <div style="background: #ffffff; padding: 24px; border-radius: var(--radius); border: 1px solid var(--border-color); box-shadow: var(--shadow);">
            <div style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Acervo de Provas</div>
            <div style="font-size: 2.2rem; font-weight: 800; color: var(--primary); margin: 6px 0;"><?= count($provas) ?></div>
            <div style="font-size: 0.85rem; color: var(--text-muted);">Provas e exames digitalizados disponíveis</div>
            <div style="margin-top: 14px;">
                <a href="/area-aluno/provas.php" class="btn btn-primary btn-sm">Explorar Provas &rarr;</a>
            </div>
        </div>

        <div style="background: #ffffff; padding: 24px; border-radius: var(--radius); border: 1px solid var(--border-color); box-shadow: var(--shadow);">
            <div style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Grade Curricular</div>
            <div style="font-size: 2.2rem; font-weight: 800; color: var(--secondary); margin: 6px 0;"><?= count($disciplinas) ?></div>
            <div style="font-size: 0.85rem; color: var(--text-muted);">Disciplinas mapeadas com pré-requisitos</div>
            <div style="margin-top: 14px;">
                <a href="/disciplinas.php" class="btn btn-outline btn-sm">Ver Grafo &rarr;</a>
            </div>
        </div>

        <div style="background: #ffffff; padding: 24px; border-radius: var(--radius); border: 1px solid var(--border-color); box-shadow: var(--shadow);">
            <div style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Materiais de Apoio</div>
            <div style="font-size: 2.2rem; font-weight: 800; color: var(--accent); margin: 6px 0;">📚</div>
            <div style="font-size: 0.85rem; color: var(--text-muted);">Listas de exercícios e apostilas</div>
            <div style="margin-top: 14px;">
                <a href="/area-aluno/materiais.php" class="btn btn-outline btn-sm">Ver Materiais &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Seção de Provas Recentes -->
    <div style="background: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow); margin-bottom: 40px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
            <h3 style="font-size: 1.25rem; color: var(--primary);">📄 Provas Adicionadas Recentemente</h3>
            <a href="/area-aluno/provas.php" class="btn btn-outline btn-sm">Ver Todo o Acervo</a>
        </div>

        <div class="table-container" style="box-shadow: none; border-radius: 6px; margin-bottom: 0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Disciplina</th>
                        <th>Ano/Semestre</th>
                        <th>Professor</th>
                        <th>Tipo</th>
                        <th style="text-align: center;">Download</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($provas, 0, 5) as $p): ?>
                        <tr>
                            <td><span style="font-family: monospace; font-weight: 700; color: var(--primary);"><?= e($p['disciplina_codigo']) ?></span></td>
                            <td><?= e($p['disciplina_nome']) ?></td>
                            <td><?= e($p['ano']) ?> (<?= e($p['semestre']) ?>)</td>
                            <td><?= e($p['professor'] ?: 'Não informado') ?></td>
                            <td><span class="card-badge"><?= e(ucfirst($p['tipo'])) ?></span></td>
                            <td style="text-align: center;">
                                <a href="/download.php?id=<?= (int)$p['id'] ?>" class="btn btn-primary btn-sm" target="_blank">
                                    📥 Baixar PDF
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../../src/views/footer.php'; ?>
