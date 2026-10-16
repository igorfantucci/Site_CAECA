<?php
/**
 * Helper de Autenticação, Sessão e CSRF
 * CAECA - UNIFEI
 */

require_once __DIR__ . '/security.php';

class Auth {
    const SESSION_TIMEOUT = 900; // 15 minutos (em segundos)

    public static function initSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            // Configurações de cookie de sessão seguro
            ini_set('session.cookie_httponly', '1');
            ini_set('session.use_only_cookies', '1');
            session_start();
        }

        // Checagem de expiração de sessão (15 minutos)
        if (isset($_SESSION['LAST_ACTIVITY'])) {
            if (time() - $_SESSION['LAST_ACTIVITY'] > self::SESSION_TIMEOUT) {
                self::logout(true);
            }
        }
        $_SESSION['LAST_ACTIVITY'] = time();
    }

    public static function generateCsrfToken(): string {
        self::initSession();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function validateCsrfToken(?string $token): bool {
        self::initSession();
        if (empty($token) || empty($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function isAlunoLoggedIn(): bool {
        self::initSession();
        return !empty($_SESSION['aluno_id']) && !empty($_SESSION['aluno_matricula']);
    }

    public static function isAlunoApproved(): bool {
        self::initSession();
        return self::isAlunoLoggedIn() && ($_SESSION['aluno_status'] ?? '') === 'aprovado';
    }

    public static function isAdminLoggedIn(): bool {
        self::initSession();
        return !empty($_SESSION['admin_id']);
    }

    public static function requireAluno(string $redirectPath = '/login.php'): void {
        self::initSession();
        if (!self::isAlunoLoggedIn()) {
            $_SESSION['flash_error'] = 'Você precisa estar logado para acessar a Área do Aluno.';
            header("Location: {$redirectPath}");
            exit;
        }
        if ($_SESSION['aluno_status'] !== 'aprovado') {
            $_SESSION['flash_error'] = 'Seu cadastro está pendente de aprovação pela diretoria do CAECA.';
            header("Location: {$redirectPath}");
            exit;
        }
    }

    public static function requireAdmin(string $redirectPath = '/admin/login.php'): void {
        self::initSession();
        if (!self::isAdminLoggedIn()) {
            $_SESSION['flash_error'] = 'Acesso restrito à diretoria do CAECA. Faça login para continuar.';
            header("Location: {$redirectPath}");
            exit;
        }
    }

    public static function logout(bool $expired = false): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        if ($expired) {
            session_start();
            $_SESSION['flash_warning'] = 'Sua sessão expirou por inatividade (15 minutos). Por favor, faça login novamente.';
        }
    }
}
