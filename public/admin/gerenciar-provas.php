<?php
require_once __DIR__ . '/../../src/helpers/auth.php';
require_once __DIR__ . '/../../src/models/Prova.php';
require_once __DIR__ . '/../../src/models/Disciplina.php';

Auth::requireAdmin();

$erro = '';
$sucesso = '';

// Processamento de Upload / Cadastro de Prova
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!Auth::validateCsrfToken($csrfToken)) {
        $erro = 'Falha de validação de segurança (CSRF).';
    } elseif ($acao === 'cadastrar') {
        $disciplinaId = (int)($_POST['disciplina_id'] ?? 0);
        $ano = (int)($_POST['ano'] ?? date('Y'));
        $semestre = sanitize_input($_POST['semestre'] ?? '1º Semestre');
        $professor = sanitize_input($_POST['professor'] ?? '');
        $tipo = sanitize_input($_POST['tipo'] ?? 'prova');

        if ($disciplinaId <= 0 || empty($ano)) {
            $erro = 'Selecione a disciplina e o ano da avaliação.';
        } elseif (!isset($_FILES['arquivo_pdf']) || $_FILES['arquivo_pdf']['error'] !== UPLOAD_ERR_OK) {
            $erro = 'Por favor, selecione um arquivo PDF válido para envio.';
        } else {
            $file = $_FILES['arquivo_pdf'];
            $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if ($fileExt !== 'pdf') {
                $erro = 'Apenas arquivos em formato .PDF são permitidos no acervo.';
            } else {
                $disciplina = Disciplina::findById($disciplinaId);
                $codigoClean = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $disciplina['codigo'] ?? 'disc'));
                $safeFileName = "prova_{$codigoClean}_{$ano}_" . time() . ".pdf";
                $destPath = dirname(__DIR__, 2) . '/protected/provas/' . $safeFileName;

                if (move_uploaded_file($file['tmp_name'], $destPath)) {
                    Prova::create($disciplinaId, $ano, $semestre, $professor, $tipo, $safeFileName);
                    $_SESSION['flash_success'] = 'Documento adicionado ao acervo com sucesso!';
                    header('Location: /admin/gerenciar-provas.php');
                    exit;
                } else {
                    $erro = 'Erro ao salvar o arquivo no diretório seguro.';
                }
            }
        }
    } elseif ($acao === 'excluir') {
        $provaId = (int)($_POST['prova_id'] ?? 0);
        $prova = Prova::findById($provaId);
        if ($prova) {
            $filePath = dirname(__DIR__, 2) . '/protected/provas/' . basename($prova['arquivo_path']);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            Prova::delete($provaId);
            $_SESSION['flash_success'] = 'Documento removido do acervo.';
            header('Location: /admin/gerenciar-provas.php');
            exit;
        }
    }
}

$disciplinas = Disciplina::getTodas();
$provas = Prova::getTodas();

$pageTitle = "Gerenciar Provas";
require_once __DIR__ . '/../../src/views/header.php';
$csrfToken = Auth::generateCsrfToken();
?>

<div class="page-header">
    <div class="container" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div>
            <h2>📚 Gerenciamento do Acervo de Provas</h2>
            <p>Upload e catalogação de avaliações para disponibilização segura aos alunos.</p>
        </div>
        <div>
            <a href="/admin/dashboard.php" class="btn btn-outline btn-sm">&larr; Voltar ao Dashboard</a>
        </div>
    </div>
</div>

<div class="container">

    <?php if (!empty($erro)): ?>
        <div class="alert alert-danger"><?= e($erro) ?></div>
    <?php endif; ?>

    <!-- Formulário de Upload de Nova Prova -->
    <div style="background: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow); margin-bottom: 35px;">
        <h3 style="font-size: 1.2rem; color: var(--primary); margin-bottom: 16px;">📤 Adicionar Nova Avaliação ao Acervo</h3>

        <form method="POST" action="/admin/gerenciar-provas.php" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
            <input type="hidden" name="acao" value="cadastrar">

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
                <div class="form-group">
                    <label for="disciplina_id">Disciplina</label>
                    <select name="disciplina_id" id="disciplina_id" class="form-control" required>
                        <option value="">Selecione uma disciplina...</option>
                        <?php foreach ($disciplinas as $d): ?>
                            <option value="<?= $d['id'] ?>"><?= e($d['codigo']) ?> - <?= e($d['nome']) ?> (<?= e($d['periodo']) ?>º Sem)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ano">Ano de Aplicação</label>
                    <input type="number" id="ano" name="ano" class="form-control" value="<?= date('Y') ?>" min="2000" max="<?= date('Y') + 1 ?>" required>
                </div>

                <div class="form-group">
                    <label for="semestre">Semestre</label>
                    <select name="semestre" id="semestre" class="form-control">
                        <option value="1º Semestre">1º Semestre</option>
                        <option value="2º Semestre">2º Semestre</option>
                        <option value="Exame Final">Exame Final</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="tipo">Tipo de Documento</label>
                    <select name="tipo" id="tipo" class="form-control">
                        <option value="prova">Prova / Exame</option>
                        <option value="trabalho">Trabalho / Projeto</option>
                        <option value="lista">Lista de Exercícios</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; align-items: end;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="professor">Professor(a) Responsável</label>
                    <input type="text" id="professor" name="professor" class="form-control" placeholder="Ex: Prof. Dr. Caio Fernandes de Paula">
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="arquivo_pdf">Arquivo PDF</label>
                    <input type="file" id="arquivo_pdf" name="arquivo_pdf" accept=".pdf,application/pdf" class="form-control" required>
                </div>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Salvar no Acervo Protegido</button>
            </div>
        </form>
    </div>

    <!-- Tabela de Provas Cadastradas -->
    <div class="section-title-wrap" style="text-align: left; margin-bottom: 16px;">
        <h3 class="section-title" style="font-size: 1.3rem;">Provas Cadastradas no Acervo (<?= count($provas) ?>)</h3>
    </div>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 100px;">Código</th>
                    <th>Disciplina</th>
                    <th style="width: 130px;">Ano / Semestre</th>
                    <th>Docente</th>
                    <th style="width: 100px;">Tipo</th>
                    <th style="width: 180px; text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($provas as $p): ?>
                    <tr>
                        <td><span style="font-family: monospace; font-weight: 700; color: var(--primary);"><?= e($p['disciplina_codigo']) ?></span></td>
                        <td><strong><?= e($p['disciplina_nome']) ?></strong></td>
                        <td><?= e($p['ano']) ?> (<?= e($p['semestre']) ?>)</td>
                        <td><?= e($p['professor'] ?: '-') ?></td>
                        <td><span class="card-badge"><?= e(ucfirst($p['tipo'])) ?></span></td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="/download.php?id=<?= (int)$p['id'] ?>" target="_blank" class="btn btn-outline btn-sm">
                                    Ver PDF
                                </a>
                                <form method="POST" action="/admin/gerenciar-provas.php" style="display: inline;">
                                    <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                                    <input type="hidden" name="acao" value="excluir">
                                    <input type="hidden" name="prova_id" value="<?= (int)$p['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Deseja realmente excluir este documento do acervo?');">
                                        Excluir
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<?php require_once __DIR__ . '/../../src/views/footer.php'; ?>
