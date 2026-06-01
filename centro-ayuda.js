/**
 * Centro de Ayuda — Beijing ERP
 * Catálogo de videos y lógica de la interfaz
 */

// ─── Catálogo de módulos y videos ───────────────────────────────────────────

const MODULOS = [
  {
    id: 'todos',
    letra: '★',
    nombre: 'Todos los módulos',
    icono: 'bi-grid-3x3-gap-fill',
  },
  {
    id: 'a',
    letra: 'A',
    nombre: 'Recepción de Unidades',
    icono: 'bi-truck',
    videos: [
      {
        num: 1,
        titulo: 'Recepción de unidad para recepción de mineral',
        archivo: 'centro-ayuda-assets/a. recepcion de unidades/1- recepcio de unidad para recepcion de mineral.mp4',
      },
      {
        num: 21,
        titulo: 'Recepción de unidad para despacho de mineral',
        archivo: 'centro-ayuda-assets/a. recepcion de unidades/21. recepcion de unidad para despacho de mineral .mp4',
      },
      {
        num: 4,
        titulo: 'Registro de salida del vehículo',
        archivo: 'centro-ayuda-assets/a. recepcion de unidades/4- registro de salida del vehiculo.mp4',
      },
    ],
  },
  {
    id: 'b',
    letra: 'B',
    nombre: 'Balanza — Recepción de Mineral',
    icono: 'bi-bar-chart-steps',
    videos: [
      {
        num: 2,
        titulo: 'Captura de pesos en primer tramo',
        archivo: 'centro-ayuda-assets/b. balanza - recepcion de mineral/2- captura de pesos primer tramo.mp4',
      },
    ],
  },
  {
    id: 'c',
    letra: 'C',
    nombre: 'Primer Tramo — Gestión de Guías',
    icono: 'bi-file-earmark-text',
    videos: [
      {
        num: 3,
        titulo: 'Registro de guías de primer tramo',
        archivo: 'centro-ayuda-assets/c. primer tramo - gestion guias/3- registro de guias de primer tramo.mp4',
      },
      {
        num: 5,
        titulo: 'Editar y agregar lotes a una guía del primer tramo',
        archivo: 'centro-ayuda-assets/c. primer tramo - gestion guias/5- editar y agregar lotes a una guia del primer tramo.mp4',
      },
    ],
  },
  {
    id: 'd',
    letra: 'D',
    nombre: 'Cierre de Leyes',
    icono: 'bi-clipboard2-check',
    videos: [
      {
        num: 6,
        titulo: 'Registro de leyes de lotes',
        archivo: 'centro-ayuda-assets/d. cierre leyes/6- registro de leyes de lotes.mp4',
      },
    ],
  },
  {
    id: 'e',
    letra: 'E',
    nombre: 'Anticipos a Proveedores',
    icono: 'bi-cash-coin',
    videos: [
      {
        num: 7,
        titulo: 'Registro de anticipos a proveedores',
        archivo: 'centro-ayuda-assets/e. anticipos a proveedores/7- registro de anticipos a proveedores.mp4',
      },
    ],
  },
  {
    id: 'f',
    letra: 'F',
    nombre: 'Valorización de Compra',
    icono: 'bi-calculator',
    videos: [
      {
        num: 8,
        titulo: 'Registro de valorización de compra con anticipo',
        archivo: 'centro-ayuda-assets/f. valorizacion de compra/8- registro de valorizacion de compra con anticipo.mp4',
      },
      {
        num: 9,
        titulo: 'Aprobar valorización de compra',
        archivo: 'centro-ayuda-assets/f. valorizacion de compra/9- aprobar valorizacion de compra.mp4',
      },
      {
        num: 10,
        titulo: 'Visualizar PDF de la valorización de compra',
        archivo: 'centro-ayuda-assets/f. valorizacion de compra/10- visualizar pdf de la valorizacion de compra .mp4',
      },
    ],
  },
  {
    id: 'g',
    letra: 'G',
    nombre: 'Contabilidad — Compra de Mineral',
    icono: 'bi-journal-bookmark',
    videos: [
      {
        num: 11,
        titulo: 'Registro de comprobante para la valorización de compra',
        archivo: 'centro-ayuda-assets/g. contabilidad - compra mineral/11- registro de comprobante para la valorizacion de compra.mp4',
      },
      {
        num: 12,
        titulo: 'Aprobación de comprobantes para contabilidad',
        archivo: 'centro-ayuda-assets/g. contabilidad - compra mineral/12- aprobacacion de comprobantes para contabilidad.mp4',
      },
      {
        num: 14,
        titulo: 'Registro de pagos del comprobante de compra',
        archivo: 'centro-ayuda-assets/g. contabilidad - compra mineral/14- registro de pagos del comprobante de compra.mp4',
      },
      {
        num: 15,
        titulo: 'Registro de pago de detracción de comprobante de compra',
        archivo: 'centro-ayuda-assets/g. contabilidad - compra mineral/15- registro de pago de detraccion de comprobante de compra.mp4',
      },
    ],
  },
  {
    id: 'h',
    letra: 'H',
    nombre: 'Primer Tramo — Aprobación de Comprobantes',
    icono: 'bi-patch-check',
    videos: [
      {
        num: 13,
        titulo: 'Aprobación de comprobantes del primer tramo',
        archivo: 'centro-ayuda-assets/h. primer tramo - aprobacion de comprobantes/13- aprobacion de comprobantes del primer tramo.mp4',
      },
    ],
  },
  {
    id: 'i',
    letra: 'I',
    nombre: 'Blending',
    icono: 'bi-intersect',
    videos: [
      {
        num: 16,
        titulo: 'Registro de blending',
        archivo: 'centro-ayuda-assets/i. blending/16. registro de blending.mp4',
      },
    ],
  },
  {
    id: 'j',
    letra: 'J',
    nombre: 'Plantas de Destino',
    icono: 'bi-building-gear',
    videos: [
      {
        num: 17,
        titulo: 'Asociar proveedores a plantas',
        archivo: 'centro-ayuda-assets/j. plantas de destino/17. asociar proveedores a plantas.mp4',
      },
    ],
  },
  {
    id: 'k',
    letra: 'K',
    nombre: 'Programación de Despachos',
    icono: 'bi-calendar2-check',
    videos: [
      {
        num: 18,
        titulo: 'Registro de despachos',
        archivo: 'centro-ayuda-assets/k. programacion de despachos/18. registro de despachos.mp4',
      },
      {
        num: 19,
        titulo: 'Registro de distribuciones',
        archivo: 'centro-ayuda-assets/k. programacion de despachos/19. registro de distribuciones .mp4',
      },
      {
        num: 20,
        titulo: 'Cerrar y reabrir distribuciones',
        archivo: 'centro-ayuda-assets/k. programacion de despachos/20. cerrar y reabrir distribuciones.mp4',
      },
      {
        num: 24,
        titulo: 'Registrar llegada a planta',
        archivo: 'centro-ayuda-assets/k. programacion de despachos/24. registrar llegada a planta.mp4',
      },
      {
        num: 25,
        titulo: 'Registro de código, peso y leyes del cliente',
        archivo: 'centro-ayuda-assets/k. programacion de despachos/25. registro de codigo, peso y leyes del cliente.mp4',
      },
    ],
  },
  {
    id: 'l',
    letra: 'L',
    nombre: 'Balanza — Despacho de Mineral',
    icono: 'bi-speedometer',
    videos: [
      {
        num: 22,
        titulo: 'Captura de pesos del segundo tramo',
        archivo: 'centro-ayuda-assets/l. balanza - despacho de mineral/22. captura de pesos del segundo tramo.mp4',
      },
    ],
  },
  {
    id: 'm',
    letra: 'M',
    nombre: 'Segundo Tramo — Gestión de Guías',
    icono: 'bi-file-earmark-check',
    videos: [
      {
        num: 23,
        titulo: 'Registrar guías del segundo tramo',
        archivo: 'centro-ayuda-assets/m. segundo tramo - gestion guias/23. registrar guias del segundo tramo.mp4',
      },
    ],
  },
];

// ─── Estado de la aplicación ─────────────────────────────────────────────────

const state = {
  filtroActivo: 'todos',
  busqueda: '',
  modalIndex: -1,   // índice en la lista plana de videos
  videosFlat: [],   // lista plana: { modulo, video, moduloId, moduloNombre }
};

// ─── Construir lista plana de videos ─────────────────────────────────────────

function buildFlat() {
  state.videosFlat = [];
  MODULOS.filter(m => m.id !== 'todos').forEach(modulo => {
    modulo.videos.forEach(video => {
      state.videosFlat.push({ modulo, video });
    });
  });
}

// ─── Filtrar videos según estado ─────────────────────────────────────────────

function getVideosFiltrados() {
  const query = state.busqueda.toLowerCase().trim();

  return state.videosFlat.filter(({ modulo, video }) => {
    const porModulo = state.filtroActivo === 'todos' || modulo.id === state.filtroActivo;
    const porBusqueda = !query
      || video.titulo.toLowerCase().includes(query)
      || modulo.nombre.toLowerCase().includes(query);
    return porModulo && porBusqueda;
  });
}

// ─── Render sidebar ──────────────────────────────────────────────────────────

function renderSidebar() {
  const list = document.getElementById('ca-filter-list');
  list.innerHTML = '';

  MODULOS.forEach(modulo => {
    const count = modulo.id === 'todos'
      ? state.videosFlat.length
      : (modulo.videos ? modulo.videos.length : 0);

    const li = document.createElement('li');
    li.className = 'ca-filter-item' + (modulo.id === state.filtroActivo ? ' active' : '');
    li.innerHTML = `
      <i class="${modulo.icono} ca-filter-icon"></i>
      <span class="ca-filter-name">${modulo.nombre}</span>
      <span class="ca-filter-badge">${count}</span>
    `;
    li.addEventListener('click', () => {
      state.filtroActivo = modulo.id;
      renderSidebar();
      renderContent();
    });
    list.appendChild(li);
  });
}

// ─── Render contenido principal ──────────────────────────────────────────────

function renderContent() {
  const container = document.getElementById('ca-content');
  const noResults = document.getElementById('ca-no-results');
  const sectionTitle = document.getElementById('ca-section-title-text');
  const sectionCount = document.getElementById('ca-section-count');

  const filtrados = getVideosFiltrados();

  // Título dinámico
  const modActivo = MODULOS.find(m => m.id === state.filtroActivo);
  sectionTitle.textContent = modActivo ? modActivo.nombre : 'Todos los módulos';
  sectionCount.textContent = `${filtrados.length} video${filtrados.length !== 1 ? 's' : ''}`;

  container.innerHTML = '';

  if (filtrados.length === 0) {
    noResults.style.display = 'block';
    return;
  }
  noResults.style.display = 'none';

  // Agrupar por módulo
  const grupos = {};
  filtrados.forEach(item => {
    if (!grupos[item.modulo.id]) {
      grupos[item.modulo.id] = { modulo: item.modulo, items: [] };
    }
    grupos[item.modulo.id].items.push(item);
  });

  Object.values(grupos).forEach(grupo => {
    const groupEl = document.createElement('div');
    groupEl.className = 'ca-module-group';

    groupEl.innerHTML = `
      <div class="ca-module-label">
        <div class="ca-module-letter">${grupo.modulo.letra}</div>
        <span class="ca-module-name">${grupo.modulo.nombre}</span>
        <span class="ca-module-count-badge">${grupo.items.length} video${grupo.items.length !== 1 ? 's' : ''}</span>
      </div>
      <div class="ca-video-grid" id="grid-${grupo.modulo.id}"></div>
    `;

    container.appendChild(groupEl);

    const grid = groupEl.querySelector(`#grid-${grupo.modulo.id}`);
    grupo.items.forEach(item => {
      grid.appendChild(buildVideoCard(item));
    });
  });
}

// ─── Construir card de video ─────────────────────────────────────────────────

function buildVideoCard({ modulo, video }) {
  // Índice global para navegación en modal
  const globalIdx = state.videosFlat.findIndex(
    f => f.modulo.id === modulo.id && f.video.num === video.num
  );

  const card = document.createElement('div');
  card.className = 'ca-video-card';
  card.setAttribute('data-idx', globalIdx);

  card.innerHTML = `
    <div class="ca-card-thumb">
      <video class="ca-card-video-preview" src="${video.archivo}#t=2"
             preload="metadata" muted playsinline></video>
      <div class="ca-card-play-overlay">
        <div class="ca-card-play-btn">
          <i class="bi bi-play-fill"></i>
        </div>
      </div>
      <span class="ca-card-num">#${video.num}</span>
    </div>
    <div class="ca-card-body">
      <div class="ca-card-module-tag">
        <i class="${modulo.icono}"></i> ${modulo.letra}. ${modulo.nombre}
      </div>
      <p class="ca-card-title">${video.titulo}</p>
    </div>
  `;

  card.addEventListener('click', () => openModal(globalIdx));
  return card;
}

// ─── Modal player ─────────────────────────────────────────────────────────────

function openModal(idx) {
  state.modalIndex = idx;
  const item = state.videosFlat[idx];
  if (!item) return;

  const overlay   = document.getElementById('ca-modal-overlay');
  const videoEl   = document.getElementById('ca-modal-video');
  const titleEl   = document.getElementById('ca-modal-title');
  const tagEl     = document.getElementById('ca-modal-tag');
  const progressEl = document.getElementById('ca-modal-progress');
  const btnPrev   = document.getElementById('ca-modal-prev');
  const btnNext   = document.getElementById('ca-modal-next');

  titleEl.textContent = item.video.titulo;
  tagEl.textContent   = `${item.modulo.letra}. ${item.modulo.nombre}`;
  videoEl.src         = item.video.archivo;
  videoEl.load();
  videoEl.play();

  const total = state.videosFlat.length;
  progressEl.textContent = `${idx + 1} de ${total}`;
  btnPrev.disabled = idx === 0;
  btnNext.disabled = idx === total - 1;

  overlay.classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeModal() {
  const overlay = document.getElementById('ca-modal-overlay');
  const videoEl = document.getElementById('ca-modal-video');
  videoEl.pause();
  videoEl.src = '';
  overlay.classList.remove('open');
  document.body.style.overflow = '';
  state.modalIndex = -1;
}

function navigateModal(dir) {
  const nextIdx = state.modalIndex + dir;
  if (nextIdx >= 0 && nextIdx < state.videosFlat.length) {
    openModal(nextIdx);
  }
}

// ─── Búsqueda ─────────────────────────────────────────────────────────────────

function initSearch() {
  const input     = document.getElementById('ca-search-input');
  const clearBtn  = document.getElementById('ca-search-clear');

  input.addEventListener('input', () => {
    state.busqueda = input.value;
    clearBtn.classList.toggle('visible', input.value.length > 0);
    renderContent();
  });

  clearBtn.addEventListener('click', () => {
    input.value    = '';
    state.busqueda = '';
    clearBtn.classList.remove('visible');
    input.focus();
    renderContent();
  });
}

// ─── Stats en hero ────────────────────────────────────────────────────────────

function renderStats() {
  document.getElementById('stat-videos').textContent = state.videosFlat.length;
  document.getElementById('stat-modulos').textContent = MODULOS.length - 1; // sin "todos"
}

// ─── Keyboard support ────────────────────────────────────────────────────────

document.addEventListener('keydown', e => {
  const overlay = document.getElementById('ca-modal-overlay');
  if (!overlay.classList.contains('open')) return;

  if (e.key === 'Escape')      closeModal();
  if (e.key === 'ArrowLeft')   navigateModal(-1);
  if (e.key === 'ArrowRight')  navigateModal(1);
});

// ─── Click fuera del modal ────────────────────────────────────────────────────

document.getElementById('ca-modal-overlay').addEventListener('click', e => {
  if (e.target === document.getElementById('ca-modal-overlay')) closeModal();
});

// ─── Init ────────────────────────────────────────────────────────────────────

function init() {
  buildFlat();
  renderStats();
  renderSidebar();
  renderContent();
  initSearch();

  // Botones del modal
  document.getElementById('ca-modal-close').addEventListener('click', closeModal);
  document.getElementById('ca-modal-prev').addEventListener('click', () => navigateModal(-1));
  document.getElementById('ca-modal-next').addEventListener('click', () => navigateModal(1));
}

document.addEventListener('DOMContentLoaded', init);
