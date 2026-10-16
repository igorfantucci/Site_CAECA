<?php
/**
 * Model Prova (Acervo protegido)
 * CAECA - UNIFEI
 */

require_once __DIR__ . '/../config/database.php';

class Prova {
    public static function getTodas(?int $disciplinaId = null, ?int $ano = null, ?string $tipo = null): array {
        $db = Database::getConnection();
        
        $sql = "
            SELECT p.*, d.codigo as disciplina_codigo, d.nome as disciplina_nome, d.periodo
            FROM provas p
            JOIN disciplinas d ON p.disciplina_id = d.id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($disciplinaId)) {
            $sql .= " AND p.disciplina_id = :disciplina_id";
            $params['disciplina_id'] = $disciplinaId;
        }
        if (!empty($ano)) {
            $sql .= " AND p.ano = :ano";
            $params['ano'] = $ano;
        }
        if (!empty($tipo)) {
            $sql .= " AND p.tipo = :tipo";
            $params['tipo'] = $tipo;
        }

        $sql .= " ORDER BY p.ano DESC, d.codigo ASC, p.semestre DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT p.*, d.codigo as disciplina_codigo, d.nome as disciplina_nome 
            FROM provas p
            JOIN disciplinas d ON p.disciplina_id = d.id
            WHERE p.id = :id 
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(int $disciplinaId, int $ano, string $semestre, ?string $professor, string $tipo, string $arquivoPath): int {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO provas (disciplina_id, ano, semestre, professor, tipo, arquivo_path, data_upload)
            VALUES (:disciplina_id, :ano, :semestre, :professor, :tipo, :arquivo_path, CURRENT_TIMESTAMP)
        ");
        $stmt->execute([
            'disciplina_id' => $disciplinaId,
            'ano' => $ano,
            'semestre' => $semestre,
            'professor' => $professor,
            'tipo' => $tipo,
            'arquivo_path' => $arquivoPath
        ]);
        return (int)$db->lastInsertId();
    }

    public static function delete(int $id): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM provas WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
