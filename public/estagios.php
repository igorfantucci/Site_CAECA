<?php
$pageTitle = "Estágios e Oportunidades";
require_once __DIR__ . '/../src/models/Empresa.php';
require_once __DIR__ . '/../src/views/header.php';

$empresas = Empresa::getTodas();
?>

<div class="page-header">
    <div class="container">
        <h2>Estágios e Empresas Parceiras</h2>
        <p>Orientações para o estágio supervisionado, empresas conveniadas e oportunidades no mercado de trabalho.</p>
    </div>
</div>

<div class="container">

    <!-- Passo a Passo do Estágio -->
    <div style="background: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius); padding: 30px; box-shadow: var(--shadow); margin-bottom: 40px;">
        <h3 style="color: var(--primary); font-size: 1.3rem; margin-bottom: 16px;">📋 Guia do Estágio Supervisionado</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px;">
            <div style="border-left: 3px solid var(--accent); padding-left: 14px;">
                <h4 style="font-size: 1rem; color: var(--primary); margin-bottom: 4px;">1. Elegibilidade</h4>
                <p style="font-size: 0.88rem; color: var(--text-muted);">Ter concluído no mínimo 60% da carga horária do curso e estar regularmente matriculado no SIGAA.</p>
            </div>
            
            <div style="border-left: 3px solid var(--accent); padding-left: 14px;">
                <h4 style="font-size: 1rem; color: var(--primary); margin-bottom: 4px;">2. Plano de Atividades</h4>
                <p style="font-size: 0.88rem; color: var(--text-muted);">Definir o plano de trabalho em conjunto com a empresa e submeter à aprovação do Coordenador de Estágios.</p>
            </div>

            <div style="border-left: 3px solid var(--accent); padding-left: 14px;">
                <h4 style="font-size: 1rem; color: var(--primary); margin-bottom: 4px;">3. Termo de Compromisso</h4>
                <p style="font-size: 0.88rem; color: var(--text-muted);">Assinatura digital via SIGAA/SEI pelo aluno, supervisor na empresa e coordenação da UNIFEI.</p>
            </div>

            <div style="border-left: 3px solid var(--accent); padding-left: 14px;">
                <h4 style="font-size: 1rem; color: var(--primary); margin-bottom: 4px;">4. Relatório Final</h4>
                <p style="font-size: 0.88rem; color: var(--text-muted);">Apresentação do relatório técnico circunstanciado e validação das 160h mínimas para integralização.</p>
            </div>
        </div>

        <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border-color); font-size: 0.9rem; color: var(--text-main);">
            <strong>Coordenadoria de Estágios do Curso:</strong> 
            <a href="mailto:estagio.eca.itajuba@unifei.edu.br">estagio.eca.itajuba@unifei.edu.br</a>
        </div>
    </div>

    <!-- Tabela de Empresas Conveniadas -->
    <div class="section-title-wrap" style="text-align: left; margin-bottom: 20px;">
        <h3 class="section-title" style="font-size: 1.4rem;">Empresas Parceiras com Histórico de Convênio</h3>
        <p class="section-subtitle">Indústrias e centros de tecnologia com processos seletivos frequentes para ECA</p>
    </div>

    <div class="filters-bar">
        <input type="text" data-table-search="#tabela-empresas" class="search-input" placeholder="🔍 Buscar empresa por nome, setor ou localização...">
    </div>

    <div class="table-container">
        <table id="tabela-empresas" class="data-table">
            <thead>
                <tr>
                    <th style="width: 220px;">Empresa</th>
                    <th style="width: 200px;">Área de Atuação</th>
                    <th style="width: 180px;">Localização</th>
                    <th>Observações / Foco em Automação</th>
                    <th style="width: 140px; text-align: center;">Carreiras / Editais</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($empresas as $emp): ?>
                    <tr>
                        <td>
                            <strong><?= e($emp['nome']) ?></strong>
                            <?php if (!empty($emp['site'])): ?>
                                <br><a href="<?= e($emp['site']) ?>" target="_blank" rel="noopener" style="font-size: 0.8rem; color: var(--text-muted);">Visitar Website ↗</a>
                            <?php endif; ?>
                        </td>
                        <td><?= e($emp['area_atuacao']) ?></td>
                        <td>📍 <?= e($emp['localizacao']) ?></td>
                        <td style="font-size: 0.86rem; color: var(--text-main);"><?= e($emp['observacoes']) ?></td>
                        <td style="text-align: center;">
                            <?php if (!empty($emp['editais_link'])): ?>
                                <a href="<?= e($emp['editais_link']) ?>" target="_blank" rel="noopener" class="btn btn-primary btn-sm">
                                    Vagas ↗
                                </a>
                            <?php else: ?>
                                <span style="color: var(--text-muted); font-size: 0.8rem;">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<?php require_once __DIR__ . '/../src/views/footer.php'; ?>
