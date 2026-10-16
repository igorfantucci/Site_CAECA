<?php
/**
 * Model Monitor
 * CAECA - UNIFEI
 */

require_once __DIR__ . '/../config/database.php';

class Monitor {
    public static function getTodos(): array {
        $db = Database::getConnection();
        $stmt = $db->query("
            SELECT m.*, d.codigo as disciplina_codigo, d.nome as disciplina_nome, d.periodo
            FROM monitores m
            JOIN disciplinas d ON m.disciplina_id = d.id
            ORDER BY d.periodo ASC, d.codigo ASC
        ");
        return $stmt->fetchAll();
    }
}
