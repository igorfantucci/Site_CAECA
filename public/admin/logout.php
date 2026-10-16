<?php
require_once __DIR__ . '/../../src/helpers/auth.php';
Auth::logout(false);
session_start();
$_SESSION['flash_success'] = 'Você encerrou sua sessão administrativa com sucesso.';
header('Location: /admin/login.php');
exit;
