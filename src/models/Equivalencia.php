<?php
/**
 * Model Equivalencia
 * CAECA - UNIFEI
 */

require_once __DIR__ . '/../config/database.php';

class Equivalencia {
    public static function getTodas(): array {
        $db = Database::getConnection();
        $stmt = $db->query("
            SELECT eq.*, d.codigo as codigo_atual, d.nome as nome_atual, d.periodo as periodo_atual, d.carga_horaria_total
            FROM equivalencias eq
            JOIN disciplinas d ON eq.disciplina_atual_id = d.id
            ORDER BY d.periodo ASC, d.codigo ASC
        ");
        return $stmt->fetchAll();
    }

    public static function findByDisciplinaId(int $disciplinaId): array {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT * FROM equivalencias 
            WHERE disciplina_atual_id = :disciplina_id
            ORDER BY codigo_antigo ASC
        ");
        $stmt->execute(['disciplina_id' => $disciplinaId]);
        return $stmt->fetchAll();
    }
}
