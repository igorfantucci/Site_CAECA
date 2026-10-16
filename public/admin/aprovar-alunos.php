<?php
require_once __DIR__ . '/../../src/helpers/auth.php';
require_once __DIR__ . '/../../src/models/Aluno.php';

Auth::requireAdmin();

$adminId = (int)$_SESSION['admin_id'];

// Processamento de Ações (Aprovar / Rejeitar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alunoId = isset($_POST['aluno_id']) ? (int)$_POST['aluno_id'] : 0;
    $acao = $_POST['acao'] ?? '';
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!Auth::validateCsrfToken($csrfToken)) {
        $_SESSION['flash_error'] = 'Falha de validação de segurança (CSRF).';
    } elseif ($alunoId <= 0) {
        $_SESSION['flash_error'] = 'Aluno inválido selecionado.';
    } else {
        if ($acao === 'aprovar') {
            if (Aluno::aprovar($alunoId, $adminId)) {
                $_SESSION['flash_success'] = "Aluno aprovado com sucesso! Acesso ao acervo liberado.";
            } else {
                $_SESSION['flash_error'] = "Erro ao aprovar o aluno.";
            }
        } elseif ($acao === 'rejeitar') {
            if (Aluno::rejeitar($alunoId, $adminId)) {
                $_SESSION['flash_warning'] = "Solicitação de cadastro rejeitada.";
            } else {
                $_SESSION['flash_error'] = "Erro ao rejeitar a solicitação.";
            }
        }
    }
    header('Location: /admin/aprovar-alunos.php' . (isset($_GET['status']) ? '?status=' . urlencode($_GET['status']) : ''));
    exit;
}

$filtroStatus = $_GET['status'] ?? 'pendente';
$alunos = Aluno::getTodos($filtroStatus === 'todos' ? '' : $filtroStatus);
$stats = Aluno::getContagemStatus();

$pageTitle = "Aprovação de Alunos";
require_once __DIR__ . '/../../src/views/header.php';
$csrfToken = Auth::generateCsrfToken();
?>

<div class="page-header">
    <div class="container" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div>
            <h2>👥 Gerenciamento e Aprovação de Cadastros</h2>
            <p>Validação manual de matrículas de estudantes para concessão de acesso ao acervo.</p>
        </div>
        <div>
            <a href="/admin/dashboard.php" class="btn btn-outline btn-sm">&larr; Voltar ao Dashboard</a>
        </div>
    </div>
</div>

<div class="container">

    <!-- Abas de Filtro por Status -->
    <div class="filters-bar" style="justify-content: space-between;">
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="/admin/aprovar-alunos.php?status=pendente" class="btn <?= $filtroStatus === 'pendente' ? 'btn-primary' : 'btn-outline' ?> btn-sm">
                Pendentes (<?= $stats['pendentes'] ?>)
            </a>
            <a href="/admin/aprovar-alunos.php?status=aprovado" class="btn <?= $filtroStatus === 'aprovado' ? 'btn-primary' : 'btn-outline' ?> btn-sm">
                Aprovados (<?= $stats['aprovados'] ?>)
            </a>
            <a href="/admin/aprovar-alunos.php?status=rejeitado" class="btn <?= $filtroStatus === 'rejeitado' ? 'btn-primary' : 'btn-outline' ?> btn-sm">
                Rejeitados (<?= $stats['rejeitados'] ?>)
            </a>
            <a href="/admin/aprovar-alunos.php?status=todos" class="btn <?= $filtroStatus === 'todos' ? 'btn-primary' : 'btn-outline' ?> btn-sm">
                Todos (<?= $stats['total'] ?>)
            </a>
        </div>

        <div>
            <input type="text" data-table-search="#tabela-alunos" class="search-input" placeholder="🔍 Buscar aluno por nome ou matrícula..." style="min-width: 250px;">
        </div>
    </div>

    <!-- Tabela de Alunos -->
    <div class="table-container">
        <table id="tabela-alunos" class="data-table">
            <thead>
                <tr>
                    <th style="width: 140px;">Data Cadastro</th>
                    <th style="width: 130px;">Matrícula</th>
                    <th>Nome Completo</th>
                    <th>E-mail</th>
                    <th style="width: 110px; text-align: center;">Status</th>
                    <th style="width: 220px; text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($alunos)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 35px;">
                            Nenhum cadastro encontrado para o filtro "<strong><?= e(ucfirst($filtroStatus)) ?></strong>".
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($alunos as $aluno): ?>
                        <tr>
                            <td><?= date('d/m/Y H:i', strtotime($aluno['data_cadastro'])) ?></td>
                            <td><span style="font-family: monospace; font-weight: 700; color: var(--primary);"><?= e($aluno['matricula']) ?></span></td>
                            <td><strong><?= e($aluno['nome']) ?></strong></td>
                            <td><?= e($aluno['email']) ?></td>
                            <td style="text-align: center;">
                                <?php if ($aluno['status'] === 'aprovado'): ?>
                                    <span class="card-badge" style="background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0;">Aprovado</span>
                                <?php elseif ($aluno['status'] === 'pendente'): ?>
                                    <span class="card-badge" style="background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a;">Pendente</span>
                                <?php else: ?>
                                    <span class="card-badge" style="background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca;">Rejeitado</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <?php if ($aluno['status'] !== 'aprovado'): ?>
                                        <form method="POST" action="/admin/aprovar-alunos.php?status=<?= urlencode($filtroStatus) ?>" style="display: inline;">
                                            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                                            <input type="hidden" name="aluno_id" value="<?= (int)$aluno['id'] ?>">
                                            <input type="hidden" name="acao" value="aprovar">
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Deseja realmente aprovar o cadastro de <?= e(addslashes($aluno['nome'])) ?>?');">
                                                ✓ Aprovar
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($aluno['status'] !== 'rejeitado'): ?>
                                        <form method="POST" action="/admin/aprovar-alunos.php?status=<?= urlencode($filtroStatus) ?>" style="display: inline;">
                                            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                                            <input type="hidden" name="aluno_id" value="<?= (int)$aluno['id'] ?>">
                                            <input type="hidden" name="acao" value="rejeitar">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Deseja rejeitar o cadastro de <?= e(addslashes($aluno['nome'])) ?>?');">
                                                ✗ Rejeitar
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php require_once __DIR__ . '/../../src/views/footer.php'; ?>
