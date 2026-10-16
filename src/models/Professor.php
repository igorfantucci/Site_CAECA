<?php
/**
 * Model Professor
 * CAECA - UNIFEI
 */

require_once __DIR__ . '/../config/database.php';

class Professor {
    public static function getTodos(): array {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM professores ORDER BY eh_coordenador DESC, nome ASC");
        return $stmt->fetchAll();
    }

    public static function getCoordenadores(): array {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM professores WHERE eh_coordenador = 1 ORDER BY nome ASC");
        return $stmt->fetchAll();
    }

    public static function getInstitutos(): array {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT DISTINCT instituto FROM professores WHERE instituto IS NOT NULL AND instituto != '' ORDER BY instituto ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
