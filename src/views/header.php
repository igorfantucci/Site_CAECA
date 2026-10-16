<?php
/**
 * Header Global
 * CAECA - UNIFEI
 */
require_once __DIR__ . '/../helpers/auth.php';
Auth::initSession();

$currentPage = basename($_SERVER['SCRIPT_NAME']);
$isAlunoLogged = Auth::isAlunoLoggedIn();
$isAlunoAppr = Auth::isAlunoApproved();
$isAdminLogged = Auth::isAdminLoggedIn();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' | CAECA UNIFEI' : 'CAECA - Centro Acadêmico de Eng. de Controle e Automação (UNIFEI)' ?></title>
    
    <!-- Fontes: Montserrat (Habitat style) & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons (Habitat style) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Principal -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <?php if (!empty($loadGraphCss)): ?>
        <link rel="stylesheet" href="/assets/css/graph.css">
        <!-- Vis.js Network CDN -->
        <script type="text/javascript" src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
    <?php endif; ?>
</head>
<body>

<header class="main-header">
    <div class="container header-inner">
        <a href="/index.php" class="logo-area">
            <div class="logo-badge">CAECA</div>
            <div class="logo-text">
                <h1>Engenharia de Controle e Automação</h1>
                <span>UNIFEI • Campus Itajubá</span>
            </div>
        </a>

        <button class="mobile-toggle" aria-label="Abrir Menu">
            <i class="fas fa-bars"></i>
        </button>

        <ul class="nav-links">
            <li><a href="/index.php" class="<?= $currentPage === 'index.php' ? 'active' : '' ?>">Início</a></li>
            <li><a href="/disciplinas.php" class="<?= $currentPage === 'disciplinas.php' ? 'active' : '' ?>">Disciplinas & Grafo</a></li>
            <li><a href="/professores.php" class="<?= $currentPage === 'professores.php' ? 'active' : '' ?>">Professores</a></li>
            <li><a href="/laboratorios.php" class="<?= $currentPage === 'laboratorios.php' ? 'active' : '' ?>">Laboratórios</a></li>
            <li><a href="/estagios.php" class="<?= $currentPage === 'estagios.php' ? 'active' : '' ?>">Estágios</a></li>
            <li><a href="/monitores.php" class="<?= $currentPage === 'monitores.php' ? 'active' : '' ?>">Monitores</a></li>
            
            <?php if ($isAlunoAppr): ?>
                <li><a href="/area-aluno/index.php" class="<?= strpos($_SERVER['REQUEST_URI'], 'area-aluno') !== false ? 'active' : '' ?>">🎓 Área do Aluno</a></li>
            <?php endif; ?>

            <?php if ($isAdminLogged): ?>
                <li><a href="/admin/dashboard.php" class="<?= strpos($_SERVER['REQUEST_URI'], 'admin') !== false ? 'active' : '' ?>">⚙️ Painel CAECA</a></li>
            <?php endif; ?>
        </ul>

        <div class="nav-auth-buttons">
            <?php if ($isAlunoLogged || $isAdminLogged): ?>
                <a href="<?= $isAlunoLogged ? '/logout.php' : '/admin/logout.php' ?>" class="btn btn-outline btn-sm">Sair</a>
            <?php else: ?>
                <a href="/login.php" class="btn btn-outline btn-sm">Entrar</a>
                <a href="/cadastro.php" class="btn btn-primary btn-sm">Cadastrar</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<main class="main-content">
    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="container" style="margin-top: 20px;">
            <div class="alert alert-danger"><?= e($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?></div>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="container" style="margin-top: 20px;">
            <div class="alert alert-success"><?= e($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?></div>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['flash_warning'])): ?>
        <div class="container" style="margin-top: 20px;">
            <div class="alert alert-warning"><?= e($_SESSION['flash_warning']); unset($_SESSION['flash_warning']); ?></div>
        </div>
    <?php endif; ?>
