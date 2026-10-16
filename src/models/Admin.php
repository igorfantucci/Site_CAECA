<?php
/**
 * Model Admin
 * CAECA - UNIFEI
 */

require_once __DIR__ . '/../config/database.php';

class Admin {
    public static function findByLoginOrEmail(string $login): ?array {
        $db = Database::getConnection();
        $loginClean = trim($login);
        $stmt = $db->prepare("
            SELECT * FROM admins 
            WHERE LOWER(email) = LOWER(:login) 
               OR LOWER(nome) = LOWER(:login) 
            LIMIT 1
        ");
        $stmt->execute(['login' => $loginClean]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findByEmail(string $email): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM admins WHERE LOWER(email) = LOWER(:email) LIMIT 1");
        $stmt->execute(['email' => trim($email)]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findById(int $id): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM admins WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}
