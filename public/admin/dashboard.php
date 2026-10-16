<?php
require_once __DIR__ . '/../../src/helpers/auth.php';
require_once __DIR__ . '/../../src/models/Aluno.php';
require_once __DIR__ . '/../../src/models/Disciplina.php';
require_once __DIR__ . '/../../src/models/Prova.php';

Auth::requireAdmin();

$stats = Aluno::getContagemStatus();
$pendentes = Aluno::getPendentes();
$totalProvas = count(Prova::getTodas());
$totalDisciplinas = count(Disciplina::getTodas());

$pageTitle = "Painel Administrativo CAECA";
require_once __DIR__ . '/../../src/views/header.php';
?>

<div class="page-header">
    <div class="container" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div>
            <h2>⚙️ Painel da Diretoria CAECA</h2>
            <p>Gerenciamento de solicitações de acesso de alunos e moderação do acervo.</p>
        </div>
        <div>
            <a href="/admin/aprovar-alunos.php" class="btn btn-primary">
                Aprovar Alunos (<?= $stats['pendentes'] ?>)
            </a>
            <a href="/admin/gerenciar-provas.php" class="btn btn-outline">
                Gerenciar Provas
            </a>
        </div>
    </div>
</div>

<div class="container">

    <!-- Alerta de Cadastros Pendentes -->
    <?php if ($stats['pendentes'] > 0): ?>
        <div class="alert alert-warning" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div>
                <strong>⚠️ Atenção:</strong> Existem <strong><?= $stats['pendentes'] ?></strong> aluno(s) aguardando validação de matrícula para acessar o acervo de provas.
            </div>
            <a href="/admin/aprovar-alunos.php" class="btn btn-secondary btn-sm">Revisar Cadastros Agora</a>
        </div>
    <?php endif; ?>

    <!-- Widgets de Métricas -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 35px;">
        <div style="background: #ffffff; padding: 20px; border-radius: var(--radius); border: 1px solid var(--border-color); box-shadow: var(--shadow);">
            <div style="font-size: 0.82rem; font-weight: 700; color: var(--warning); text-transform: uppercase;">Cadastros Pendentes</div>
            <div style="font-size: 2rem; font-weight: 800; color: #b45309; margin: 4px 0;"><?= $stats['pendentes'] ?></div>
            <div style="font-size: 0.8rem; color: var(--text-muted);">Requer aprovação manual</div>
        </div>

        <div style="background: #ffffff; padding: 20px; border-radius: var(--radius); border: 1px solid var(--border-color); box-shadow: var(--shadow);">
            <div style="font-size: 0.82rem; font-weight: 700; color: var(--success); text-transform: uppercase;">Alunos Aprovados</div>
            <div style="font-size: 2rem; font-weight: 800; color: #047857; margin: 4px 0;"><?= $stats['aprovados'] ?></div>
            <div style="font-size: 0.8rem; color: var(--text-muted);">Com acesso ativo ao acervo</div>
        </div>

        <div style="background: #ffffff; padding: 20px; border-radius: var(--radius); border: 1px solid var(--border-color); box-shadow: var(--shadow);">
            <div style="font-size: 0.82rem; font-weight: 700; color: var(--primary); text-transform: uppercase;">Provas no Acervo</div>
            <div style="font-size: 2rem; font-weight: 800; color: var(--primary); margin: 4px 0;"><?= $totalProvas ?></div>
            <div style="font-size: 0.8rem; color: var(--text-muted);">PDFs digitalizados</div>
        </div>

        <div style="background: #ffffff; padding: 20px; border-radius: var(--radius); border: 1px solid var(--border-color); box-shadow: var(--shadow);">
            <div style="font-size: 0.82rem; font-weight: 700; color: var(--accent); text-transform: uppercase;">Disciplinas do Curso</div>
            <div style="font-size: 2rem; font-weight: 800; color: #0284c7; margin: 4px 0;"><?= $totalDisciplinas ?></div>
            <div style="font-size: 0.8rem; color: var(--text-muted);">10 Semestres mapeados</div>
        </div>
    </div>

    <!-- Tabela de Solicitações Recentes -->
    <div style="background: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow); margin-bottom: 40px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 1.2rem; color: var(--primary);">Solicitações Pendentes Recentes</h3>
            <a href="/admin/aprovar-alunos.php" class="btn btn-outline btn-sm">Ver Todas</a>
        </div>

        <?php if (empty($pendentes)): ?>
            <div style="text-align: center; padding: 30px; color: var(--text-muted);">
                ✅ Não há cadastros pendentes de aprovação no momento.
            </div>
        <?php else: ?>
            <div class="table-container" style="box-shadow: none; margin-bottom: 0;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Matrícula</th>
                            <th>Nome Completo</th>
                            <th>E-mail</th>
                            <th style="text-align: center;">Ações Rápidas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($pendentes, 0, 5) as $p): ?>
                            <tr>
                                <td><?= date('d/m/Y H:i', strtotime($p['data_cadastro'])) ?></td>
                                <td><strong><?= e($p['matricula']) ?></strong></td>
                                <td><?= e($p['nome']) ?></td>
                                <td><?= e($p['email']) ?></td>
                                <td style="text-align: center;">
                                    <a href="/admin/aprovar-alunos.php" class="btn btn-primary btn-sm">Avaliar Solicitação</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php require_once __DIR__ . '/../../src/views/footer.php'; ?>
