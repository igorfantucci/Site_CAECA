<?php
/**
 * Model Laboratório
 * CAECA - UNIFEI
 */

require_once __DIR__ . '/../config/database.php';

class Laboratorio {
    public static function getTodos(): array {
        $db = Database::getConnection();
        $stmt = $db->query("
            SELECT l.*, p.nome as responsavel_nome, p.email as responsavel_email 
            FROM laboratorios l
            LEFT JOIN professores p ON l.responsavel_id = p.id
            ORDER BY l.sigla ASC
        ");
        return $stmt->fetchAll();
    }
}
