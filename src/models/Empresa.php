<?php
/**
 * Model Empresa
 * CAECA - UNIFEI
 */

require_once __DIR__ . '/../config/database.php';

class Empresa {
    public static function getTodas(): array {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM empresas ORDER BY nome ASC");
        return $stmt->fetchAll();
    }
}
