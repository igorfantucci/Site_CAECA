/**
 * Grafo Interativo de Disciplinas, Pré-requisitos e Dependências (Vis.js)
 * Estilo Grade Curricular Interativa LSA - UNIFEI
 * CAECA - UNIFEI
 */

let network = null;
let graphNodes = null;
let graphEdges = null;
let allNodesData = [];
let allEdgesData = [];
let selectedNodeId = null;
let isolateUnrelated = true; // Oculta ou esmaece matérias não relacionadas

// Mapeamento de Adjacência para busca em grafo
let predecessorsMap = {}; // disciplina -> [quem ela depende]
let successorsMap = {};   // disciplina -> [quem depende dela]

function initDisciplinaGraph(nodesData, edgesData) {
  allNodesData = JSON.parse(JSON.stringify(nodesData));
  allEdgesData = JSON.parse(JSON.stringify(edgesData));

  buildAdjacencyMaps();

  const container = document.getElementById('disciplina-graph');
  if (!container) return;

  const overlay = document.getElementById('graph-loading');

  graphNodes = new vis.DataSet(nodesData);
  graphEdges = new vis.DataSet(edgesData);

  const data = {
    nodes: graphNodes,
    edges: graphEdges
  };

  const options = {
    layout: {
      hierarchical: {
        enabled: true,
        direction: 'LR', // Esquerda para Direita (1º ao 10º período)
        sortMethod: 'directed',
        levelSeparation: 230,
        nodeSpacing: 100,
        treeSpacing: 120,
        blockShifting: true,
        edgeMinimization: true,
        parentCentralization: true
      }
    },
    nodes: {
      shape: 'box',
      margin: { top: 10, right: 12, bottom: 10, left: 12 },
      borderRadius: 8,
      borderWidth: 2,
      shadow: {
        enabled: true,
        color: 'rgba(0,0,0,0.08)',
        size: 4,
        x: 1,
        y: 2
      }
    },
    edges: {
      smooth: {
        type: 'cubicBezier',
        forceDirection: 'horizontal',
        roundness: 0.4
      },
      arrows: {
        to: { enabled: true, scaleFactor: 0.75 }
      },
      hoverWidth: 3,
      selectionWidth: 3.5
    },
    physics: {
      enabled: false
    },
    interaction: {
      hover: true,
      tooltipDelay: 120,
      zoomView: true,
      dragView: true,
      dragNodes: false, // Não permite mover os blocos de lugar
      navigationButtons: true,
      keyboard: true
    }
  };

  network = new vis.Network(container, data, options);

  network.once('afterDrawing', () => {
    if (overlay) overlay.style.display = 'none';
  });

  // Clique em nó: Seleciona e destaca cadeia de dependências
  network.on('click', (params) => {
    if (params.nodes.length > 0) {
      const nodeId = params.nodes[0];
      selecionarDisciplinaGrafo(nodeId);
    } else {
      // Clique no fundo limpa a seleção
      limparDestaqueGrafo();
    }
  });

  // Duplo clique: Abre modal de detalhes da disciplina
  network.on('doubleClick', (params) => {
    if (params.nodes.length > 0) {
      exibirDetalhesDisciplina(params.nodes[0]);
    }
  });

  setupGraphControls();
}

function buildAdjacencyMaps() {
  predecessorsMap = {};
  successorsMap = {};

  allNodesData.forEach(n => {
    predecessorsMap[n.id] = [];
    successorsMap[n.id] = [];
  });

  allEdgesData.forEach(e => {
    if (!predecessorsMap[e.to]) predecessorsMap[e.to] = [];
    if (!successorsMap[e.from]) successorsMap[e.from] = [];

    predecessorsMap[e.to].push({ id: e.from, tipo: e.tipo });
    successorsMap[e.from].push({ id: e.to, tipo: e.tipo });
  });
}

/**
 * Busca recursiva de todos os ancestrais (pré-requisitos diretos e indiretos)
 */
function getAllPredecessors(nodeId, visited = new Set()) {
  const preds = predecessorsMap[nodeId] || [];
  preds.forEach(p => {
    if (!visited.has(p.id)) {
      visited.add(p.id);
      getAllPredecessors(p.id, visited);
    }
  });
  return visited;
}

/**
 * Busca recursiva de todos os sucessores (disciplinas que dependem desta)
 */
function getAllSuccessors(nodeId, visited = new Set()) {
  const succs = successorsMap[nodeId] || [];
  succs.forEach(s => {
    if (!visited.has(s.id)) {
      visited.add(s.id);
      getAllSuccessors(s.id, visited);
    }
  });
  return visited;
}

/**
 * Seleciona uma matéria, esmaece/oculta as não relacionadas e destaca as dependências
 */
function selecionarDisciplinaGrafo(nodeId) {
  selectedNodeId = nodeId;
  const targetNode = allNodesData.find(n => n.id === nodeId);
  if (!targetNode) return;

  const predecessorsSet = getAllPredecessors(nodeId);
  const successorsSet = getAllSuccessors(nodeId);

  const relatedNodesSet = new Set([nodeId, ...predecessorsSet, ...successorsSet]);

  const updatedNodes = [];
  allNodesData.forEach(n => {
    const isSelected = (n.id === nodeId);
    const isPredecessor = predecessorsSet.has(n.id);
    const isSuccessor = successorsSet.has(n.id);
    const isRelated = relatedNodesSet.has(n.id);

    if (isSelected) {
      // Destaque Dourado/Glow na selecionada
      updatedNodes.push({
        id: n.id,
        color: { background: '#d4af37', border: '#92400e' },
        borderWidth: 4,
        font: { color: '#ffffff', bold: true, size: 13 },
        hidden: false,
        opacity: 1
      });
    } else if (isPredecessor) {
      // Pré-requisitos (Dependências anteriores): Vermelho / Laranja
      updatedNodes.push({
        id: n.id,
        color: { background: '#ef4444', border: '#991b1b' },
        borderWidth: 3,
        font: { color: '#ffffff', bold: true, size: 12 },
        hidden: false,
        opacity: 1
      });
    } else if (isSuccessor) {
      // Desbloqueadas (Sucessores): Verde
      updatedNodes.push({
        id: n.id,
        color: { background: '#10b981', border: '#065f46' },
        borderWidth: 3,
        font: { color: '#ffffff', bold: true, size: 12 },
        hidden: false,
        opacity: 1
      });
    } else {
      // Não relacionadas
      if (isolateUnrelated) {
        updatedNodes.push({
          id: n.id,
          hidden: true
        });
      } else {
        updatedNodes.push({
          id: n.id,
          color: { background: '#f1f5f9', border: '#cbd5e1' },
          borderWidth: 1,
          font: { color: '#94a3b8', size: 11 },
          hidden: false,
          opacity: 0.25
        });
      }
    }
  });

  // Atualizar Arestas
  const updatedEdges = [];
  allEdgesData.forEach(e => {
    const isConnectingRelated = relatedNodesSet.has(e.from) && relatedNodesSet.has(e.to);
    if (isConnectingRelated) {
      updatedEdges.push({
        id: e.id,
        hidden: false,
        width: 3,
        color: { color: e.originalColor || '#0ea5e9' },
        opacity: 1
      });
    } else {
      updatedEdges.push({
        id: e.id,
        hidden: isolateUnrelated ? true : false,
        color: { color: '#e2e8f0' },
        opacity: 0.15
      });
    }
  });

  graphNodes.update(updatedNodes);
  graphEdges.update(updatedEdges);

  // Exibir HUD com resumo de relações
  atualizarHudGrafo(targetNode, predecessorsSet, successorsSet);
}

function limparDestaqueGrafo() {
  selectedNodeId = null;
  
  const resetNodes = allNodesData.map(n => ({
    id: n.id,
    color: n.originalColor,
    borderWidth: 2,
    font: { color: '#ffffff', size: 12, face: 'Inter, sans-serif' },
    hidden: false,
    opacity: 1
  }));

  const resetEdges = allEdgesData.map(e => ({
    id: e.id,
    hidden: false,
    width: e.tipo === 'total' ? 2.2 : 1.8,
    color: { color: e.originalColor },
    opacity: 1
  }));

  graphNodes.update(resetNodes);
  graphEdges.update(resetEdges);

  const hud = document.getElementById('graph-selection-hud');
  if (hud) hud.style.display = 'none';

  if (network) network.fit({ animation: { duration: 400 } });
}

function atualizarHudGrafo(targetNode, predecessorsSet, successorsSet) {
  const hud = document.getElementById('graph-selection-hud');
  if (!hud) return;

  hud.style.display = 'block';
  document.getElementById('hud-disciplina-nome').textContent = `${targetNode.codigo} - ${targetNode.nome}`;
  document.getElementById('hud-disciplina-info').textContent = `${targetNode.periodo}º Semestre • ${targetNode.carga_horaria_total}h • ${targetNode.tipo.toUpperCase()}`;

  // Predecessores (Pré-requisitos)
  const predContainer = document.getElementById('hud-predecessores');
  predContainer.innerHTML = '';
  if (predecessorsSet.size === 0) {
    predContainer.innerHTML = '<span style="color: #64748b; font-size: 0.82rem;">Nenhum (Matéria de Entrada)</span>';
  } else {
    predecessorsSet.forEach(id => {
      const node = allNodesData.find(n => n.id === id);
      if (node) {
        const btn = document.createElement('button');
        btn.className = 'req-pill pill-total';
        btn.style.cursor = 'pointer';
        btn.textContent = node.codigo;
        btn.title = `${node.codigo} - ${node.nome}`;
        btn.onclick = () => {
          selecionarDisciplinaGrafo(node.id);
          network.focus(node.id, { scale: 1.1, animation: { duration: 400 } });
        };
        predContainer.appendChild(btn);
      }
    });
  }

  // Sucessores (Desbloqueadas)
  const succContainer = document.getElementById('hud-sucessores');
  succContainer.innerHTML = '';
  if (successorsSet.size === 0) {
    succContainer.innerHTML = '<span style="color: #64748b; font-size: 0.82rem;">Nenhuma dependente</span>';
  } else {
    successorsSet.forEach(id => {
      const node = allNodesData.find(n => n.id === id);
      if (node) {
        const btn = document.createElement('button');
        btn.className = 'req-pill';
        btn.style.backgroundColor = '#dcfce7';
        btn.style.color = '#166534';
        btn.style.border = '1px solid #bbf7d0';
        btn.style.cursor = 'pointer';
        btn.textContent = node.codigo;
        btn.title = `${node.codigo} - ${node.nome}`;
        btn.onclick = () => {
          selecionarDisciplinaGrafo(node.id);
          network.focus(node.id, { scale: 1.1, animation: { duration: 400 } });
        };
        succContainer.appendChild(btn);
      }
    });
  }

  // Botão de Detalhes no HUD
  const detailBtn = document.getElementById('hud-detalhes-btn');
  if (detailBtn) {
    detailBtn.onclick = () => exibirDetalhesDisciplina(targetNode.id);
  }
}

function setupGraphControls() {
  const searchInput = document.getElementById('graph-search');
  const periodSelect = document.getElementById('graph-periodo-filter');
  const tipoSelect = document.getElementById('graph-tipo-filter');
  const isolateToggle = document.getElementById('graph-isolate-toggle');
  const resetBtn = document.getElementById('graph-reset-btn');
  const clearHudBtn = document.getElementById('hud-clear-btn');

  if (clearHudBtn) {
    clearHudBtn.addEventListener('click', limparDestaqueGrafo);
  }

  if (isolateToggle) {
    isolateToggle.addEventListener('change', (e) => {
      isolateUnrelated = e.target.checked;
      if (selectedNodeId) {
        selecionarDisciplinaGrafo(selectedNodeId);
      }
    });
  }

  if (searchInput) {
    searchInput.addEventListener('input', (e) => {
      const term = e.target.value.toLowerCase().trim();
      if (!term) return;

      const found = allNodesData.find(n => 
        n.codigo.toLowerCase().includes(term) || 
        n.nome.toLowerCase().includes(term)
      );

      if (found && network) {
        selecionarDisciplinaGrafo(found.id);
        network.focus(found.id, {
          scale: 1.15,
          animation: { duration: 500 }
        });
      }
    });
  }

  function applyGlobalFilters() {
    const periodo = periodSelect ? periodSelect.value : 'todos';
    const tipo = tipoSelect ? tipoSelect.value : 'todos';

    const filtered = allNodesData.filter(n => {
      const matchPeriod = (periodo === 'todos') || (n.periodo === parseInt(periodo));
      const matchTipo = (tipo === 'todos') || (n.tipo === tipo);
      return matchPeriod && matchTipo;
    });

    graphNodes.clear();
    graphNodes.add(filtered);
    if (network) network.fit({ animation: { duration: 400 } });
  }

  if (periodSelect) periodSelect.addEventListener('change', applyGlobalFilters);
  if (tipoSelect) tipoSelect.addEventListener('change', applyGlobalFilters);

  if (resetBtn) {
    resetBtn.addEventListener('click', () => {
      limparDestaqueGrafo();
      if (periodSelect) periodSelect.value = 'todos';
      if (tipoSelect) tipoSelect.value = 'todos';
      applyGlobalFilters();
    });
  }
}

function exibirDetalhesDisciplina(disciplinaId) {
  const node = allNodesData.find(n => n.id === disciplinaId);
  if (!node) return;

  document.getElementById('modal-disc-titulo').textContent = `${node.codigo} - ${node.nome}`;
  document.getElementById('modal-disc-periodo').textContent = `${node.periodo}º Semestre (${node.tipo.toUpperCase()})`;
  document.getElementById('modal-disc-ch').textContent = `${node.carga_horaria_total}h (Teórica: ${node.carga_horaria_teorica}h / Prática: ${node.carga_horaria_pratica}h)`;
  document.getElementById('modal-disc-ementa').textContent = node.ementa || 'Ementa em processo de digitalização pelo CAECA.';
  document.getElementById('modal-disc-objetivos').textContent = node.objetivos || 'Objetivos de aprendizagem definidos pelo PPC do curso de ECA.';

  // Pré-requisitos
  const reqsFrom = allEdgesData.filter(e => e.to === disciplinaId);
  const prereqContainer = document.getElementById('modal-disc-prereqs');
  prereqContainer.innerHTML = '';

  if (reqsFrom.length === 0) {
    prereqContainer.innerHTML = '<span style="color: #64748b; font-size: 0.88rem;">Nenhum pré-requisito necessário (disciplina de entrada).</span>';
  } else {
    reqsFrom.forEach(r => {
      const origNode = allNodesData.find(n => n.id === r.from);
      if (!origNode) return;

      let pillClass = 'pill-total';
      let tipoTxt = 'Total';
      if (r.tipo === 'parcial') { pillClass = 'pill-parcial'; tipoTxt = 'Parcial'; }
      if (r.tipo === 'co-requisito') { pillClass = 'pill-coreq'; tipoTxt = 'Co-requisito'; }

      const pill = document.createElement('span');
      pill.className = `req-pill ${pillClass}`;
      pill.textContent = `${origNode.codigo} (${tipoTxt})`;
      prereqContainer.appendChild(pill);
    });
  }

  // Disciplinas que esta desbloqueia
  const reqsTo = allEdgesData.filter(e => e.from === disciplinaId);
  const unlocksContainer = document.getElementById('modal-disc-unlocks');
  unlocksContainer.innerHTML = '';

  if (reqsTo.length === 0) {
    unlocksContainer.innerHTML = '<span style="color: #64748b; font-size: 0.88rem;">Etapa final ou optativa.</span>';
  } else {
    reqsTo.forEach(r => {
      const destNode = allNodesData.find(n => n.id === r.to);
      if (!destNode) return;

      const pill = document.createElement('span');
      pill.className = 'req-pill';
      pill.style.backgroundColor = '#dcfce7';
      pill.style.color = '#166534';
      pill.style.border = '1px solid #bbf7d0';
      pill.textContent = destNode.codigo;
      unlocksContainer.appendChild(pill);
    });
  }

  window.openModal('modal-disciplina-detalhes');
}
