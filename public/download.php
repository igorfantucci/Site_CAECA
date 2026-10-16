<?php
/**
 * Script de Download Protegido de Provas Antigas
 * CAECA - UNIFEI
 */

require_once __DIR__ . '/../src/helpers/auth.php';
require_once __DIR__ . '/../src/models/Prova.php';

// Exige autenticação e aprovação de aluno (ou admin)
if (!Auth::isAlunoApproved() && !Auth::isAdminLoggedIn()) {
    http_response_code(403);
    die('Acesso proibido. Faça login com uma conta de aluno aprovada.');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    http_response_code(400);
    die('ID de documento inválido.');
}

$prova = Prova::findById($id);
if (!$prova) {
    http_response_code(404);
    die('Documento não encontrado no acervo.');
}

// Prevenção rigorosa de Path Traversal
$fileName = basename($prova['arquivo_path']);
$filePath = dirname(__DIR__) . '/protected/provas/' . $fileName;

if (!file_exists($filePath) || !is_readable($filePath)) {
    http_response_code(404);
    die('Arquivo físico não encontrado no servidor seguro.');
}

// Envio seguro de cabeçalhos HTTP
header('Content-Description: File Transfer');
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $fileName . '"');
header('Expires: 0');
header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($filePath));
header('X-Content-Type-Options: nosniff');
header('X-Robots-Tag: noindex, nofollow, noarchive');

// Limpeza de buffer e envio do arquivo
if (ob_get_level()) {
    ob_end_clean();
}
readfile($filePath);
exit;
