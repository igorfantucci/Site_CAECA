<?php
/**
 * Model Aluno
 * CAECA - UNIFEI
 */

require_once __DIR__ . '/../config/database.php';

class Aluno {
    public static function findByLoginOrEmail(string $login): ?array {
        $db = Database::getConnection();
        $loginClean = trim($login);
        $stmt = $db->prepare("
            SELECT * FROM alunos 
            WHERE LOWER(email) = LOWER(:login) 
               OR LOWER(nome) = LOWER(:login) 
               OR matricula = :login 
            LIMIT 1
        ");
        $stmt->execute(['login' => $loginClean]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findByMatricula(string $matricula): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM alunos WHERE matricula = :matricula LIMIT 1");
        $stmt->execute(['matricula' => $matricula]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findById(int $id): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM alunos WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(string $matricula, string $nome, string $email, string $senha): array {
        $db = Database::getConnection();

        // Checar duplicidade de matrícula
        if (self::findByMatricula($matricula)) {
            return ['success' => false, 'error' => 'Esta matrícula já está cadastrada no sistema.'];
        }

        // Checar duplicidade de e-mail
        $stmt = $db->prepare("SELECT id FROM alunos WHERE LOWER(email) = LOWER(:email) LIMIT 1");
        $stmt->execute(['email' => trim($email)]);
        if ($stmt->fetch()) {
            return ['success' => false, 'error' => 'Este e-mail já está cadastrado.'];
        }

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $db->prepare("
            INSERT INTO alunos (matricula, nome, email, senha_hash, status, data_cadastro)
            VALUES (:matricula, :nome, :email, :senha_hash, 'pendente', CURRENT_TIMESTAMP)
        ");
        $stmt->execute([
            'matricula' => $matricula,
            'nome' => $nome,
            'email' => $email,
            'senha_hash' => $senhaHash
        ]);

        return ['success' => true, 'id' => $db->lastInsertId()];
    }

    public static function getPendentes(): array {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM alunos WHERE status = 'pendente' ORDER BY data_cadastro DESC");
        return $stmt->fetchAll();
    }

    public static function getTodos(string $filtroStatus = ''): array {
        $db = Database::getConnection();
        if (!empty($filtroStatus)) {
            $stmt = $db->prepare("SELECT * FROM alunos WHERE status = :status ORDER BY data_cadastro DESC");
            $stmt->execute(['status' => $filtroStatus]);
            return $stmt->fetchAll();
        }
        $stmt = $db->query("SELECT * FROM alunos ORDER BY data_cadastro DESC");
        return $stmt->fetchAll();
    }

    public static function aprovar(int $alunoId, int $adminId): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            UPDATE alunos 
            SET status = 'aprovado', data_aprovacao = CURRENT_TIMESTAMP, aprovado_por = :admin_id 
            WHERE id = :id
        ");
        return $stmt->execute(['admin_id' => $adminId, 'id' => $alunoId]);
    }

    public static function rejeitar(int $alunoId, int $adminId): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            UPDATE alunos 
            SET status = 'rejeitado', data_aprovacao = CURRENT_TIMESTAMP, aprovado_por = :admin_id 
            WHERE id = :id
        ");
        return $stmt->execute(['admin_id' => $adminId, 'id' => $alunoId]);
    }

    public static function getContagemStatus(): array {
        $db = Database::getConnection();
        $stmt = $db->query("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pendente' THEN 1 ELSE 0 END) as pendentes,
                SUM(CASE WHEN status = 'aprovado' THEN 1 ELSE 0 END) as aprovados,
                SUM(CASE WHEN status = 'rejeitado' THEN 1 ELSE 0 END) as rejeitados
            FROM alunos
        ");
        return $stmt->fetch() ?: ['total' => 0, 'pendentes' => 0, 'aprovados' => 0, 'rejeitados' => 0];
    }
}
