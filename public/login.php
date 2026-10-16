<?php
require_once __DIR__ . '/../src/helpers/auth.php';
require_once __DIR__ . '/../src/models/Aluno.php';

Auth::initSession();

// Se já estiver logado e aprovado, redireciona para a área do aluno
if (Auth::isAlunoApproved()) {
    header('Location: /area-aluno/index.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = sanitize_input($_POST['login'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!Auth::validateCsrfToken($csrfToken)) {
        $erro = 'Erro de validação de segurança (CSRF inválido). Recarregue a página e tente novamente.';
    } elseif (empty($login) || empty($senha)) {
        $erro = 'Por favor, preencha seu e-mail, nome ou matrícula e senha.';
    } else {
        $aluno = Aluno::findByLoginOrEmail($login);

        if (!$aluno || !password_verify($senha, $aluno['senha_hash'])) {
            $erro = 'Credenciais de acesso incorretas (usuário ou senha).';
        } else {
            if ($aluno['status'] === 'pendente') {
                $erro = 'Seu cadastro está pendente de aprovação manual pela diretoria do CAECA. Você receberá acesso assim que for validado.';
            } elseif ($aluno['status'] === 'rejeitado') {
                $erro = 'Seu cadastro não foi aprovado pela diretoria do CAECA. Entre em contato para regularização.';
            } else {
                // Aluno aprovado com sucesso
                $_SESSION['aluno_id'] = (int)$aluno['id'];
                $_SESSION['aluno_nome'] = $aluno['nome'];
                $_SESSION['aluno_matricula'] = $aluno['matricula'];
                $_SESSION['aluno_status'] = $aluno['status'];
                $_SESSION['LAST_ACTIVITY'] = time();

                $_SESSION['flash_success'] = "Bem-vindo(a), {$aluno['nome']}!";
                header('Location: /area-aluno/index.php');
                exit;
            }
        }
    }
}

$pageTitle = "Login do Aluno";
require_once __DIR__ . '/../src/views/header.php';
$csrfToken = Auth::generateCsrfToken();
?>

<div class="container">
    <div class="auth-wrapper">
        <div class="auth-header">
            <div class="logo-badge" style="display: inline-block; margin-bottom: 12px;">CAECA</div>
            <h2>Área Restrita do Aluno</h2>
            <p>Acesse o acervo protegido de provas antigas e materiais didáticos</p>
        </div>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?= e($erro) ?></div>
        <?php endif; ?>

        <form method="POST" action="/login.php">
            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">

            <div class="form-group">
                <label for="login">E-mail, Nome ou Matrícula</label>
                <input type="text" id="login" name="login" class="form-control" placeholder="Ex: teste123, 2022001234 ou seu e-mail" required autofocus value="<?= isset($login) ? e($login) : 'teste123' ?>">
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" class="form-control" placeholder="••••••••" required value="teste123">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; margin-top: 10px;">
                Entrar no Sistema
            </button>
        </form>

        <div style="margin-top: 20px; font-size: 0.82rem; background: #f8fafc; padding: 10px; border-radius: 6px; border: 1px solid var(--border-color); color: var(--text-muted);">
            <strong>🔑 Acesso Rápido de Teste:</strong><br>
            Login: <code>teste123</code> (ou e-mail / matrícula)<br>
            Senha: <code>teste123</code>
        </div>

        <div style="text-align: center; margin-top: 20px; padding-top: 14px; border-top: 1px solid var(--border-color); font-size: 0.88rem;">
            Ainda não possui cadastro? <a href="/cadastro.php" style="font-weight: 600;">Cadastre-se aqui</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../src/views/footer.php'; ?>
