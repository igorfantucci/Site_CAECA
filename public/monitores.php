<?php
$pageTitle = "Monitores e Apoio Pedagógico";
require_once __DIR__ . '/../src/models/Monitor.php';
require_once __DIR__ . '/../src/views/header.php';

$monitores = Monitor::getTodos();
?>

<div class="page-header">
    <div class="container">
        <h2>Monitoria Acadêmica</h2>
        <p>Atendimento presencial e online para auxílio nas disciplinas fundamentais e específicas do curso.</p>
    </div>
</div>

<div class="container">

    <!-- Aviso de Editais -->
    <div class="alert alert-info" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div>
            <strong>📢 Processo Seletivo de Monitores:</strong> Os editais de monitoria remunerada e voluntária são publicados semestralmente pela Pró-Reitoria de Graduação (PRG).
        </div>
        <a href="https://unifei.edu.br/prg/monitoria" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
            Portal PRG ↗
        </a>
    </div>

    <div class="filters-bar">
        <input type="text" data-table-search="#tabela-monitores" class="search-input" placeholder="🔍 Buscar por disciplina ou nome do monitor...">
    </div>

    <div class="table-container">
        <table id="tabela-monitores" class="data-table">
            <thead>
                <tr>
                    <th style="width: 120px;">Código</th>
                    <th>Disciplina</th>
                    <th style="width: 180px;">Monitor(a)</th>
                    <th style="width: 180px;">Horário de Atendimento</th>
                    <th style="width: 180px;">Local / Sala</th>
                    <th style="width: 140px; text-align: center;">Contato</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($monitores)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">
                            Nenhum monitor cadastrado no momento para o semestre corrente.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($monitores as $m): ?>
                        <tr>
                            <td><span style="font-family: monospace; font-weight: 700; color: var(--primary);"><?= e($m['disciplina_codigo']) ?></span></td>
                            <td><strong><?= e($m['disciplina_nome']) ?></strong> (<?= e($m['periodo']) ?>º Semestre)</td>
                            <td><?= e($m['nome_monitor']) ?></td>
                            <td>⏰ <?= e($m['horario']) ?></td>
                            <td>📍 <?= e($m['local']) ?></td>
                            <td style="text-align: center;">
                                <a href="mailto:<?= e($m['email_monitor']) ?>" class="btn btn-outline btn-sm">
                                    ✉️ E-mail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php require_once __DIR__ . '/../src/views/footer.php'; ?>
