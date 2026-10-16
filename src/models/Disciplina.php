<?php
/**
 * Model Disciplina
 * CAECA - UNIFEI
 */

require_once __DIR__ . '/../config/database.php';

class Disciplina {
    public static function getTodas(?string $tipo = null): array {
        $db = Database::getConnection();
        if ($tipo) {
            $stmt = $db->prepare("SELECT * FROM disciplinas WHERE tipo = :tipo ORDER BY periodo ASC, codigo ASC");
            $stmt->execute(['tipo' => $tipo]);
            return $stmt->fetchAll();
        }
        $stmt = $db->query("SELECT * FROM disciplinas ORDER BY periodo ASC, tipo ASC, codigo ASC");
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM disciplinas WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findByCodigo(string $codigo): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM disciplinas WHERE codigo = :codigo LIMIT 1");
        $stmt->execute(['codigo' => $codigo]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function getPrerequisitos(int $disciplinaId): array {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT p.tipo, d.id, d.codigo, d.nome, d.periodo 
            FROM prerequisitos p
            JOIN disciplinas d ON p.prerequisito_id = d.id
            WHERE p.disciplina_id = :id
            ORDER BY d.periodo ASC, d.codigo ASC
        ");
        $stmt->execute(['id' => $disciplinaId]);
        return $stmt->fetchAll();
    }

    public static function getDesbloqueia(int $disciplinaId): array {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT p.tipo, d.id, d.codigo, d.nome, d.periodo 
            FROM prerequisitos p
            JOIN disciplinas d ON p.disciplina_id = d.id
            WHERE p.prerequisito_id = :id
            ORDER BY d.periodo ASC, d.codigo ASC
        ");
        $stmt->execute(['id' => $disciplinaId]);
        return $stmt->fetchAll();
    }

    /**
     * Retorna estrutura completa formatada para o grafo interativo Vis.js
     */
    public static function getDadosGrafo(): array {
        $db = Database::getConnection();
        
        $disciplinas = self::getTodas();
        $stmtEdges = $db->query("
            SELECT 
                p.disciplina_id as `to`,
                p.prerequisito_id as `from`,
                p.tipo,
                d_to.codigo as to_codigo,
                d_from.codigo as from_codigo
            FROM prerequisitos p
            JOIN disciplinas d_to ON p.disciplina_id = d_to.id
            JOIN disciplinas d_from ON p.prerequisito_id = d_from.id
        ");
        $edgesRaw = $stmtEdges->fetchAll();

        // Mapeamento de cores por período para os nós do grafo
        $periodColors = [
            1 => ['background' => '#2563eb', 'border' => '#1d4ed8'],
            2 => ['background' => '#0891b2', 'border' => '#0e7490'],
            3 => ['background' => '#059669', 'border' => '#047857'],
            4 => ['background' => '#65a30d', 'border' => '#4d7c0f'],
            5 => ['background' => '#ca8a04', 'border' => '#a16207'],
            6 => ['background' => '#ea580c', 'border' => '#c2410c'],
            7 => ['background' => '#dc2626', 'border' => '#b91c1c'],
            8 => ['background' => '#db2777', 'border' => '#be185d'],
            9 => ['background' => '#7c3aed', 'border' => '#6d28d9'],
            10 => ['background' => '#4f46e5', 'border' => '#4338ca'],
        ];

        $nodes = [];
        foreach ($disciplinas as $d) {
            $periodo = (int)$d['periodo'];
            $isOptativa = ($d['tipo'] === 'optativa');
            
            if ($isOptativa) {
                $color = ['background' => '#64748b', 'border' => '#475569'];
            } else {
                $color = $periodColors[$periodo] ?? ['background' => '#64748b', 'border' => '#334155'];
            }

            $label = $d['codigo'] . "\n" . (mb_strlen($d['nome']) > 22 ? mb_substr($d['nome'], 0, 20) . '...' : $d['nome']);
            if ($isOptativa) {
                $label .= " (Opt)";
            }

            $nodes[] = [
                'id' => (int)$d['id'],
                'label' => $label,
                'title' => "<b>{$d['codigo']} - {$d['nome']}</b><br>Período: {$d['periodo']}º | CH: {$d['carga_horaria_total']}h | Tipo: " . ucfirst($d['tipo']),
                'level' => $periodo,
                'group' => (string)$periodo,
                'periodo' => $periodo,
                'tipo' => $d['tipo'],
                'codigo' => $d['codigo'],
                'nome' => $d['nome'],
                'carga_horaria_total' => $d['carga_horaria_total'],
                'carga_horaria_teorica' => $d['carga_horaria_teorica'],
                'carga_horaria_pratica' => $d['carga_horaria_pratica'],
                'ementa' => $d['ementa'],
                'objetivos' => $d['objetivos'],
                'originalColor' => [
                    'background' => $color['background'],
                    'border' => $color['border']
                ],
                'color' => [
                    'background' => $color['background'],
                    'border' => $color['border'],
                    'highlight' => ['background' => '#facc15', 'border' => '#ca8a04']
                ],
                'font' => ['color' => '#ffffff', 'size' => 12, 'face' => 'Inter, sans-serif', 'multi' => true]
            ];
        }

        $edges = [];
        foreach ($edgesRaw as $edge) {
            $edgeConfig = [
                'id' => 'e_' . $edge['from'] . '_' . $edge['to'],
                'from' => (int)$edge['from'],
                'to' => (int)$edge['to'],
                'arrows' => 'to',
                'tipo' => $edge['tipo']
            ];

            switch ($edge['tipo']) {
                case 'total':
                    $edgeConfig['color'] = ['color' => '#ef4444', 'highlight' => '#b91c1c', 'hover' => '#dc2626'];
                    $edgeConfig['originalColor'] = '#ef4444';
                    $edgeConfig['title'] = "Pré-requisito Total: {$edge['from_codigo']} ➔ {$edge['to_codigo']}";
                    $edgeConfig['width'] = 2.2;
                    break;
                case 'parcial':
                    $edgeConfig['color'] = ['color' => '#f97316', 'highlight' => '#c2410c', 'hover' => '#ea580c'];
                    $edgeConfig['originalColor'] = '#f97316';
                    $edgeConfig['title'] = "Pré-requisito Parcial: {$edge['from_codigo']} ➔ {$edge['to_codigo']}";
                    $edgeConfig['width'] = 1.8;
                    $edgeConfig['dashes'] = [6, 4];
                    break;
                case 'co-requisito':
                    $edgeConfig['color'] = ['color' => '#eab308', 'highlight' => '#a16207', 'hover' => '#ca8a04'];
                    $edgeConfig['originalColor'] = '#eab308';
                    $edgeConfig['title'] = "Co-requisito: {$edge['from_codigo']} & {$edge['to_codigo']}";
                    $edgeConfig['width'] = 1.8;
                    $edgeConfig['dashes'] = [3, 3];
                    break;
            }

            $edges[] = $edgeConfig;
        }

        return ['nodes' => $nodes, 'edges' => $edges];
    }
}
