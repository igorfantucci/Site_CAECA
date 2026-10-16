<?php
$pageTitle = "Corpo Docente e Coordenações";
require_once __DIR__ . '/../src/models/Professor.php';
require_once __DIR__ . '/../src/views/header.php';

$professores = Professor::getTodos();
?>

<div class="page-header">
    <div class="container">
        <h2>Corpo Docente e Coordenações</h2>
        <p>Relação de docentes, coordenações do curso de ECA e direção do IESTI (UNIFEI) organizada por áreas temáticas.</p>
    </div>
</div>

<div class="container">

    <!-- Seção de Coordenações e Liderança -->
    <div class="section-title-wrap" style="text-align: left; margin-bottom: 20px;">
        <h3 class="section-title" style="font-size: 1.35rem;">⭐ Coordenações do Curso e Direção do IESTI</h3>
        <p class="section-subtitle">Contatos diretos dos responsáveis pela gestão acadêmica e administrativa</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 40px;">
        
        <!-- Coordenação do Curso -->
        <div class="card" style="border-left: 4px solid var(--primary); background: #ffffff;">
            <span class="card-badge badge-coord"><i class="fas fa-user-tie"></i> Coordenador do Curso (ECA)</span>
            <h3 class="card-title" style="margin-top: 6px;">Prof. Dr. Caio Fernandes de Paula</h3>
            <div class="card-details">
                <p><strong><i class="fas fa-envelope" style="color: var(--secondary); width: 16px;"></i> E-mail:</strong> <a href="mailto:eca.itajuba@unifei.edu.br">eca.itajuba@unifei.edu.br</a></p>
                <p><strong><i class="fas fa-phone" style="color: var(--secondary); width: 16px;"></i> Telefone:</strong> (35) 3629-1217</p>
                <p><strong>Atribuições:</strong> Gestão pedagógica, matrícula, equivalências e atendimento discente.</p>
            </div>
            <div style="margin-top: 14px;">
                <a href="http://lattes.cnpq.br/4009761502425488" target="_blank" rel="noopener" class="btn btn-outline btn-sm"><i class="fas fa-external-link-alt"></i> Currículo Lattes</a>
            </div>
        </div>

        <!-- Coordenação de Estágios -->
        <div class="card" style="border-left: 4px solid var(--accent); background: #ffffff;">
            <span class="card-badge badge-coord"><i class="fas fa-briefcase"></i> Coordenador de Estágios (ECA)</span>
            <h3 class="card-title" style="margin-top: 6px;">Prof. Dr. Fernando Henrique Duarte Guaracy</h3>
            <div class="card-details">
                <p><strong><i class="fas fa-envelope" style="color: var(--secondary); width: 16px;"></i> E-mail:</strong> <a href="mailto:estagio.eca.itajuba@unifei.edu.br">estagio.eca.itajuba@unifei.edu.br</a></p>
                <p><strong><i class="fas fa-phone" style="color: var(--secondary); width: 16px;"></i> Telefone:</strong> (35) 3629-1854</p>
                <p><strong>Atribuições:</strong> Validação de termos de compromisso, planos e relatórios de estágio.</p>
            </div>
            <div style="margin-top: 14px;">
                <a href="http://lattes.cnpq.br/2486493863462694" target="_blank" rel="noopener" class="btn btn-outline btn-sm"><i class="fas fa-external-link-alt"></i> Currículo Lattes</a>
            </div>
        </div>

        <!-- Coordenação de TCC -->
        <div class="card" style="border-left: 4px solid var(--secondary); background: #ffffff;">
            <span class="card-badge badge-coord"><i class="fas fa-book-reader"></i> Coordenador de TCC (ECA)</span>
            <h3 class="card-title" style="margin-top: 6px;">Prof. Dr. Geraldo Fabiano de Souza Moraes</h3>
            <div class="card-details">
                <p><strong><i class="fas fa-envelope" style="color: var(--secondary); width: 16px;"></i> E-mail:</strong> <a href="mailto:gmoraes@unifei.edu.br">gmoraes@unifei.edu.br</a></p>
                <p><strong><i class="fas fa-phone" style="color: var(--secondary); width: 16px;"></i> Telefone:</strong> (35) 3629-1180</p>
                <p><strong>Atribuições:</strong> Acompanhamento de propostas, bancas e defesas de TCC1 e TCC2.</p>
            </div>
            <div style="margin-top: 14px;">
                <a href="http://lattes.cnpq.br/6355653496055536" target="_blank" rel="noopener" class="btn btn-outline btn-sm"><i class="fas fa-external-link-alt"></i> Currículo Lattes</a>
            </div>
        </div>

        <!-- Direção do IESTI -->
        <div class="card" style="border-left: 4px solid #475569; background: #ffffff;">
            <span class="card-badge" style="background: #e2e8f0; color: #1e293b;"><i class="fas fa-university"></i> Direção do Instituto (IESTI)</span>
            <h3 class="card-title" style="margin-top: 6px;">Prof. Dr. Luís Henrique de Carvalho Ferreira</h3>
            <div class="card-details">
                <p><strong>Diretor:</strong> Prof. Dr. Luís Henrique de Carvalho Ferreira (Ramal: 1925)</p>
                <p><strong>Vice-Diretor:</strong> Prof. Dr. Jeremias Barbosa Machado (Ramal: 1667)</p>
                <p><strong><i class="fas fa-envelope" style="color: var(--secondary); width: 16px;"></i> E-mail:</strong> <a href="mailto:direcao.iesti@unifei.edu.br">direcao.iesti@unifei.edu.br</a></p>
            </div>
            <div style="margin-top: 14px;">
                <a href="https://iesti.unifei.edu.br" target="_blank" rel="noopener" class="btn btn-outline btn-sm"><i class="fas fa-external-link-alt"></i> Portal IESTI</a>
            </div>
        </div>

    </div>

    <!-- Barra de Filtros e Busca -->
    <div class="section-title-wrap" style="text-align: left; margin-bottom: 16px;">
        <h3 class="section-title" style="font-size: 1.35rem;">Docentes do IESTI (<?= count($professores) ?> Professores)</h3>
    </div>

    <div class="filters-bar">
        <input type="text" id="prof-search" class="search-input" placeholder="🔍 Buscar docente por nome, área ou telefone...">
        
        <select id="prof-area-filter" class="filter-select" style="min-width: 180px;">
            <option value="">Todas as Áreas</option>
            <option value="Controle">Sistemas de Controle</option>
            <option value="Automação">Automação e Robótica</option>
            <option value="Eletrônica">Eletrônica e Potência</option>
            <option value="Computação">Computação e Embarcados</option>
            <option value="Telecomunicações">Telecomunicações e Sinais</option>
            <option value="Outros">Outras Áreas</option>
        </select>

        <select id="prof-cargo-filter" class="filter-select">
            <option value="">Todos os Cargos</option>
            <option value="coord">Apenas Coordenações e Direção</option>
        </select>
    </div>

    <!-- Grid de Cards de Professores -->
    <div class="cards-grid" id="prof-cards-container">
        <?php foreach ($professores as $prof): ?>
            <?php
            $grandeArea = $prof['grande_area'] ?? 'Outros';
            $badgeColor = '#3b82f6';
            if ($grandeArea === 'Controle') $badgeColor = '#ef4444';
            elseif ($grandeArea === 'Automação') $badgeColor = '#10b981';
            elseif ($grandeArea === 'Eletrônica') $badgeColor = '#f59e0b';
            elseif ($grandeArea === 'Computação') $badgeColor = '#8b5cf6';
            elseif ($grandeArea === 'Telecomunicações') $badgeColor = '#06b6d4';
            ?>
            <div class="card prof-card" 
                 data-nome="<?= e(strtolower($prof['nome'])) ?>" 
                 data-area="<?= e(strtolower($prof['area_atuacao'])) ?>"
                 data-grande-area="<?= e($grandeArea) ?>"
                 data-telefone="<?= e(strtolower($prof['telefone'] ?? '')) ?>"
                 data-coord="<?= $prof['eh_coordenador'] ? '1' : '0' ?>">
                
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px; flex-wrap: wrap;">
                    <?php if ($prof['eh_coordenador']): ?>
                        <span class="card-badge badge-coord">
                            ⭐ <?= $prof['tipo_coordenacao'] === 'curso' ? 'Coordenador do Curso' : ($prof['tipo_coordenacao'] === 'estagio' ? 'Coordenador de Estágios' : ($prof['tipo_coordenacao'] === 'tcc' ? 'Coordenador de TCC' : ($prof['tipo_coordenacao'] === 'direcao_iesti' ? 'Diretor IESTI' : 'Vice-Diretor IESTI'))) ?>
                        </span>
                    <?php endif; ?>
                    <span class="card-badge" style="background-color: <?= $badgeColor ?>18; color: <?= $badgeColor ?>; border: 1px solid <?= $badgeColor ?>40;">
                        <?= e($grandeArea) ?>
                    </span>
                </div>

                <h3 class="card-title" style="margin-top: 8px;"><?= e($prof['nome']) ?></h3>
                <div class="card-subtitle"><?= e($prof['titulacao']) ?> • IESTI UNIFEI</div>

                <div class="card-details">
                    <p><strong>Linha de Atuação:</strong> <?= e($prof['area_atuacao']) ?></p>
                    <?php if (!empty($prof['telefone'])): ?>
                        <p><strong>Telefone / Ramal:</strong> <?= e($prof['telefone']) ?></p>
                    <?php endif; ?>
                    <p style="margin-top: 8px;">
                        <strong>E-mail:</strong> <a href="mailto:<?= e($prof['email']) ?>"><?= e($prof['email']) ?></a>
                    </p>
                </div>

                <div style="margin-top: 16px; border-top: 1px solid var(--border-color); padding-top: 12px; display: flex; justify-content: space-between; align-items: center;">
                    <?php if (!empty($prof['lattes'])): ?>
                        <a href="<?= e($prof['lattes']) ?>" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                            <i class="fas fa-file-alt"></i> Currículo Lattes
                        </a>
                    <?php else: ?>
                        <span></span>
                    <?php endif; ?>
                    <a href="mailto:<?= e($prof['email']) ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-envelope"></i> Contato
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('prof-search');
    const areaFilter = document.getElementById('prof-area-filter');
    const cargoFilter = document.getElementById('prof-cargo-filter');
    const cards = document.querySelectorAll('.prof-card');

    function filterCards() {
        const term = searchInput.value.toLowerCase().trim();
        const selectedArea = areaFilter.value;
        const onlyCoord = cargoFilter.value === 'coord';

        cards.forEach(card => {
            const nome = card.getAttribute('data-nome') || '';
            const area = card.getAttribute('data-area') || '';
            const gArea = card.getAttribute('data-grande-area') || '';
            const tel = card.getAttribute('data-telefone') || '';
            const isCoord = card.getAttribute('data-coord') === '1';

            const matchesText = nome.includes(term) || area.includes(term) || tel.includes(term);
            const matchesArea = !selectedArea || gArea === selectedArea;
            const matchesCargo = !onlyCoord || isCoord;

            card.style.display = (matchesText && matchesArea && matchesCargo) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterCards);
    areaFilter.addEventListener('change', filterCards);
    cargoFilter.addEventListener('change', filterCards);
});
</script>

<?php require_once __DIR__ . '/../src/views/footer.php'; ?>
