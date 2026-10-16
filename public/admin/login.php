<?php
require_once __DIR__ . '/../../src/helpers/auth.php';
require_once __DIR__ . '/../../src/models/Admin.php';

Auth::initSession();

if (Auth::isAdminLoggedIn()) {
    header('Location: /admin/dashboard.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = sanitize_input($_POST['login'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!Auth::validateCsrfToken($csrfToken)) {
        $erro = 'Erro de segurança (CSRF inválido). Recarregue e tente novamente.';
    } elseif (empty($login) || empty($senha)) {
        $erro = 'Por favor, preencha o login e senha de administrador.';
    } else {
        $admin = Admin::findByLoginOrEmail($login);
        if (!$admin || !password_verify($senha, $admin['senha_hash'])) {
            $erro = 'Credenciais administrativas incorretas.';
        } else {
            $_SESSION['admin_id'] = (int)$admin['id'];
            $_SESSION['admin_nome'] = $admin['nome'];
            $_SESSION['admin_cargo'] = $admin['cargo'];
            $_SESSION['LAST_ACTIVITY'] = time();

            $_SESSION['flash_success'] = "Painel administrativo autenticado. Bem-vindo(a), {$admin['nome']}!";
            header('Location: /admin/dashboard.php');
            exit;
        }
    }
}

$pageTitle = "Login Administrativo CAECA";
require_once __DIR__ . '/../../src/views/header.php';
$csrfToken = Auth::generateCsrfToken();
?>

<div class="container">
    <div class="auth-wrapper" style="border-top: 4px solid var(--primary);">
        <div class="auth-header">
            <div class="logo-badge" style="display: inline-block; margin-bottom: 12px; background: var(--primary);">⚙️ CAECA</div>
            <h2>Painel da Diretoria</h2>
            <p>Acesso restrito para gestão de cadastros e moderação de conteúdos</p>
        </div>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?= e($erro) ?></div>
        <?php endif; ?>

        <form method="POST" action="/admin/login.php">
            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">

            <div class="form-group">
                <label for="login">E-mail ou Nome de Administrador</label>
                <input type="text" id="login" name="login" class="form-control" placeholder="Ex: teste123 ou caeca@unifei.edu.br" required autofocus value="<?= isset($login) ? e($login) : 'teste123' ?>">
            </div>

            <div class="form-group">
                <label for="senha">Senha de Acesso</label>
                <input type="password" id="senha" name="senha" class="form-control" placeholder="••••••••" required value="teste123">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; margin-top: 10px;">
                Entrar no Painel Admin
            </button>
        </form>

        <div style="margin-top: 20px; font-size: 0.82rem; background: #f8fafc; padding: 10px; border-radius: 6px; border: 1px solid var(--border-color); color: var(--text-muted);">
            <strong>🔑 Acesso Rápido de Teste (Admin):</strong><br>
            Login: <code>teste123</code> (ou <code>caeca@unifei.edu.br</code>)<br>
            Senha: <code>teste123</code>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../src/views/footer.php'; ?>
