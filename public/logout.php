<?php
require_once __DIR__ . '/../src/helpers/auth.php';
Auth::logout(false);
session_start();
$_SESSION['flash_success'] = 'Você saiu da sua conta com sucesso.';
header('Location: /login.php');
exit;
