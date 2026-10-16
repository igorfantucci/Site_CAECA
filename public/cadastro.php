<?php
require_once __DIR__ . '/../src/helpers/auth.php';
require_once __DIR__ . '/../src/models/Aluno.php';

Auth::initSession();

$erro = '';
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = sanitize_input($_POST['nome'] ?? '');
    $matricula = sanitize_input($_POST['matricula'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $senhaConfirm = $_POST['senha_confirm'] ?? '';
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!Auth::validateCsrfToken($csrfToken)) {
        $erro = 'Erro de validação de segurança (CSRF inválido). Recarregue a página e tente novamente.';
    } elseif (empty($nome) || empty($matricula) || empty($email) || empty($senha)) {
        $erro = 'Todos os campos são obrigatórios.';
    } elseif ($senha !== $senhaConfirm) {
        $erro = 'As senhas digitadas não coincidem.';
    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter no mínimo 6 caracteres.';
    } else {
        $resultado = Aluno::create($matricula, $nome, $email, $senha);
        if ($resultado['success']) {
            $sucesso = true;
        } else {
            $erro = $resultado['error'];
        }
    }
}

$pageTitle = "Cadastro de Aluno";
require_once __DIR__ . '/../src/views/header.php';
$csrfToken = Auth::generateCsrfToken();
?>

<div class="container">
    <div class="auth-wrapper" style="max-width: 500px;">
        <div class="auth-header">
            <h2>Cadastro de Aluno</h2>
            <p>Cadastre-se para solicitar acesso ao acervo de provas do CAECA</p>
        </div>

        <?php if ($sucesso): ?>
            <div class="alert alert-success">
                <h4 style="margin-bottom: 6px;">🎉 Solicitação Enviada com Sucesso!</h4>
                <p style="font-size: 0.9rem;">
                    Seu cadastro foi registrado com status <strong>Pendente</strong>. Para segurança do corpo docente e alunos, a diretoria do CAECA fará a validação manual da sua matrícula antes de liberar o acesso.
                </p>
                <div style="margin-top: 16px;">
                    <a href="/login.php" class="btn btn-primary btn-sm">Ir para Página de Login</a>
                </div>
            </div>
        <?php else: ?>

            <?php if (!empty($erro)): ?>
                <div class="alert alert-danger"><?= e($erro) ?></div>
            <?php endif; ?>

            <form method="POST" action="/cadastro.php">
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">

                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" name="nome" class="form-control" placeholder="Ex: João da Silva Santos" required value="<?= isset($nome) ? e($nome) : '' ?>">
                </div>

                <div class="form-group">
                    <label for="matricula">Matrícula UNIFEI</label>
                    <input type="text" id="matricula" name="matricula" class="form-control" placeholder="Ex: 2023005678" required value="<?= isset($matricula) ? e($matricula) : '' ?>">
                    <div class="form-text-help">Sua matrícula será conferida na lista oficial do curso de ECA.</div>
                </div>

                <div class="form-group">
                    <label for="email">E-mail Institucional ou Pessoal</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="exemplo@unifei.edu.br" required value="<?= isset($email) ? e($email) : '' ?>">
                </div>

                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" class="form-control" placeholder="Mínimo 6 caracteres" required>
                </div>

                <div class="form-group">
                    <label for="senha_confirm">Confirmar Senha</label>
                    <input type="password" id="senha_confirm" name="senha_confirm" class="form-control" placeholder="Repita a senha" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; margin-top: 10px;">
                    Solicitar Cadastro
                </button>
            </form>

            <div style="text-align: center; margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--border-color); font-size: 0.88rem;">
                Já possui uma conta? <a href="/login.php" style="font-weight: 600;">Fazer login</a>
            </div>

        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../src/views/footer.php'; ?>
