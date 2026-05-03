<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial - Fake News</title>

    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');

            if (savedTheme === 'light') {
                document.documentElement.classList.add('light-theme');
            } else {
                document.documentElement.classList.remove('light-theme');
            }
        })();
    </script>

    <link rel="icon" type="image/png" href="{{ asset('img/fakeIcon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/history.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800&family=Rajdhani:wght@400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-icon">🛡</div>
                <div>
                    <h1>FAKE NEWS</h1>
                </div>
            </div>

            <div class="core-box">
                <p class="core-title">DEEP LEARNING</p>
                <span>V.1.1.0 ACTIVO</span>
            </div>

            <nav class="menu">
                <a href="{{ route('index') }}" class="menu-item">ESCANEO NEURAL</a>
                <a href="{{ route('history') }}" class="menu-item active">BASE DE DATOS</a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="topbar">
                <nav class="topnav">
                    <a href="{{ route('index') }}" class="topnav-link">ESCANER</a>
                    <a href="{{ route('history') }}" class="topnav-link active">HISTORIAL</a>
                </nav>

                <div class="top-actions">
                    <button type="button" id="themeToggle" class="theme-indicator" title="Cambiar tema"></button>
                </div>
            </header>

            <section class="hero">
                <h2>HISTORIAL <span>DE ANÁLISIS</span></h2>
                <p>
                    Registro de noticias procesadas por el sistema de detección de fake news.
                    Esta sección permite evidenciar el almacenamiento de predicciones, probabilidades,
                    modelo utilizado y fecha de ejecución para apoyar la trazabilidad y monitoreo del sistema.
                </p>
            </section>

            <section class="history-panel">
                <div class="panel-header">
                    <span class="panel-dot"></span>
                    <span>BASE DE DATOS DE PREDICCIONES</span>
                    <span class="buffer">POSTGRESQL</span>
                </div>

                <div class="history-summary">
                    <div class="summary-card">
                        <span>Total análisis</span>
                        <strong>{{ $historial->count() }}</strong>
                    </div>

                    <div class="summary-card">
                        <span>Noticias falsas</span>
                        <strong>{{ $historial->where('clase', 1)->count() }}</strong>
                    </div>

                    <div class="summary-card">
                        <span>Noticias reales</span>
                        <strong>{{ $historial->where('clase', 0)->count() }}</strong>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Texto</th>
                                <th>Predicción</th>
                                <th>Modelo</th>
                                <th>Prob. Falsa</th>
                                <th>Prob. Real</th>
                                <th>Explicación</th>
                                <th>Fecha</th>
                                <th>Acción</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($historial as $item)
                            <tr class="history-row"
                                data-id="{{ $item->id }}"
                                data-titulo="{{ e($item->titulo ?: 'Sin título') }}"
                                data-texto="{{ e($item->texto) }}"
                                data-prediccion="{{ e($item->prediccion) }}"
                                data-clase="{{ $item->clase }}"
                                data-fake="{{ $item->probabilidad_fake !== null ? number_format($item->probabilidad_fake * 100, 4) . '%' : '-' }}"
                                data-real="{{ $item->probabilidad_real !== null ? number_format($item->probabilidad_real * 100, 4) . '%' : '-' }}"
                                data-modelo="{{ e($item->modelo ?? 'BiLSTM') }}"
                                data-fecha="{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i') }}"
                                data-explicacion='@json(json_decode($item->explicacion, true))'
                                data-action="{{ route('history.reescanear', $item->id) }}">

                                <td>{{ $item->id }}</td>

                                <td class="title-cell" title="{{ $item->titulo }}">
                                    {{ \Illuminate\Support\Str::limit($item->titulo ?: 'Sin título', 65) }}
                                </td>

                                <td class="text-cell" title="{{ $item->texto }}">
                                    {{ \Illuminate\Support\Str::limit($item->texto, 90) }}
                                </td>

                                <td>
                                    <span class="badge {{ $item->clase == 1 ? 'badge-fake' : 'badge-real' }}">
                                        {{ $item->prediccion }}
                                    </span>
                                </td>

                                <td>{{ $item->modelo ?? 'BiLSTM' }}</td>

                                <td>
                                    {{ $item->probabilidad_fake !== null ? number_format($item->probabilidad_fake * 100, 4) . '%' : '-' }}
                                </td>

                                <td>
                                    {{ $item->probabilidad_real !== null ? number_format($item->probabilidad_real * 100, 4) . '%' : '-' }}
                                </td>

                                <td class="explanation-cell">
                                    @php
                                    $explicacion = json_decode($item->explicacion, true);
                                    @endphp

                                    @if (is_array($explicacion) && !empty($explicacion['detalle']))
                                    <ul>
                                        @foreach (array_slice($explicacion['detalle'], 0, 3) as $detalle)
                                        <li>
                                            <strong>{{ $detalle['palabra'] ?? '-' }}</strong>:
                                            {{ $detalle['direccion'] ?? 'Sin dirección' }}
                                        </li>
                                        @endforeach
                                    </ul>
                                    @else
                                    <span class="muted-text">Sin explicación registrada</span>
                                    @endif
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i') }}
                                </td>

                                <td class="action-cell">
                                    <button type="button"
                                        class="delete-btn open-delete-modal"
                                        data-delete-action="{{ route('history.delete', $item->id) }}"
                                        data-delete-title="{{ e($item->titulo ?: 'Sin título') }}">
                                        🗑
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="empty-table">
                                    No existen predicciones almacenadas todavía.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <div id="historyModal" class="modal-overlay">
                <div class="modal-box">
                    <div class="modal-header">
                        <div>
                            <span class="panel-dot"></span>
                            <span>DETALLE DEL ANÁLISIS</span>
                        </div>
                        <button type="button" id="closeModal" class="modal-close">×</button>
                    </div>

                    <div class="modal-body">
                        <div class="modal-status">
                            <span id="modalPrediccion" class="badge"></span>
                            <span id="modalModelo"></span>
                            <span id="modalFecha"></span>
                        </div>

                        <div class="modal-section">
                            <h3>Título completo</h3>
                            <p id="modalTitulo"></p>
                        </div>

                        <div class="modal-section">
                            <h3>Texto completo</h3>
                            <p id="modalTexto"></p>
                        </div>

                        <div class="modal-grid">
                            <div class="summary-card">
                                <span>Probabilidad falsa</span>
                                <strong id="modalFake"></strong>
                            </div>

                            <div class="summary-card">
                                <span>Probabilidad real</span>
                                <strong id="modalReal"></strong>
                            </div>
                        </div>

                        <div class="modal-section">
                            <h3>Explicabilidad del modelo</h3>
                            <p id="modalExplicacion">
                                Sin explicación registrada.
                            </p>
                        </div>
                    </div>

                    <div class="modal-actions">
                        <form id="reescanForm" method="POST">
                            @csrf
                            <button type="submit" id="reescanBtn" class="analyze-btn">
                                VOLVER A ESCANEAR
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div id="deleteModal" class="modal-overlay">
                <div class="confirm-modal-box">
                    <div class="modal-header">
                        <div>
                            <span class="panel-dot"></span>
                            <span>CONFIRMAR ELIMINACIÓN</span>
                        </div>
                        <button type="button" id="closeDeleteModal" class="modal-close">×</button>
                    </div>

                    <div class="modal-body">
                        <div class="modal-section">
                            <h3>Registro seleccionado</h3>
                            <p id="deleteTitle"></p>
                        </div>

                        <p class="delete-warning">
                            Esta acción eliminará permanentemente el registro del historial en PostgreSQL.
                        </p>
                    </div>

                    <div class="modal-actions">
                        <button type="button" id="cancelDeleteBtn" class="mini-btn">
                            CANCELAR
                        </button>

                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="submit" id="deleteConfirmBtn" class="delete-confirm-btn">
                                ELIMINAR REGISTRO
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <footer class="footer">
                <div>© 2026 APRENDIZAJE DE MÁQUINA | GRUPO 7 | SEMANA 9</div>
            </footer>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            const root = document.documentElement;

            themeToggle.addEventListener('click', function() {
                root.classList.toggle('light-theme');

                if (root.classList.contains('light-theme')) {
                    localStorage.setItem('theme', 'light');
                } else {
                    localStorage.setItem('theme', 'dark');
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('historyModal');
            const closeModal = document.getElementById('closeModal');
            const rows = document.querySelectorAll('.history-row');

            const modalTitulo = document.getElementById('modalTitulo');
            const modalTexto = document.getElementById('modalTexto');
            const modalPrediccion = document.getElementById('modalPrediccion');
            const modalModelo = document.getElementById('modalModelo');
            const modalFecha = document.getElementById('modalFecha');
            const modalFake = document.getElementById('modalFake');
            const modalReal = document.getElementById('modalReal');
            const modalExplicacion = document.getElementById('modalExplicacion');
            const reescanForm = document.getElementById('reescanForm');
            const reescanBtn = document.getElementById('reescanBtn');

            rows.forEach(row => {
                row.addEventListener('click', function() {
                    modalTitulo.textContent = this.dataset.titulo;
                    modalTexto.textContent = this.dataset.texto;
                    modalPrediccion.textContent = this.dataset.prediccion;
                    modalModelo.textContent = 'Modelo: ' + this.dataset.modelo;
                    modalFecha.textContent = 'Fecha: ' + this.dataset.fecha;
                    modalFake.textContent = this.dataset.fake;
                    modalReal.textContent = this.dataset.real;

                    let explicacion = {};

                    try {
                        explicacion = JSON.parse(this.dataset.explicacion || '{}');
                    } catch (e) {
                        explicacion = {};
                    }

                    const detalle = explicacion.detalle || [];
                    const esFalsa = this.dataset.clase == 1;

                    const palabrasReal = detalle
                        .filter(item => Number(item.importancia || 0) < 0)
                        .sort((a, b) => Math.abs(Number(b.importancia || 0)) - Math.abs(Number(a.importancia || 0)))
                        .slice(0, 3)
                        .map(item => item.palabra);

                    const palabrasFalsa = detalle
                        .filter(item => Number(item.importancia || 0) > 0)
                        .sort((a, b) => Math.abs(Number(b.importancia || 0)) - Math.abs(Number(a.importancia || 0)))
                        .slice(0, 3)
                        .map(item => item.palabra);

                    let textoExplicacion = '';

                    if (esFalsa) {
                        if (palabrasFalsa.length > 0) {
                            textoExplicacion += `El modelo clasificó esta noticia como falsa principalmente por palabras como "${palabrasFalsa.join(', ')}". `;
                        }

                        if (palabrasReal.length > 0) {
                            textoExplicacion += `Sin embargo, palabras como "${palabrasReal.join(', ')}" empujaban hacia una interpretación más real.`;
                        }
                    } else {
                        if (palabrasReal.length > 0) {
                            textoExplicacion += `El modelo clasificó esta noticia como real principalmente por palabras como "${palabrasReal.join(', ')}". `;
                        }

                        if (palabrasFalsa.length > 0) {
                            textoExplicacion += `Algunas palabras como "${palabrasFalsa.join(', ')}" generaban señales hacia noticia falsa, pero con menor impacto.`;
                        }
                    }

                    modalExplicacion.textContent = textoExplicacion || 'No hay suficientes datos de explicabilidad para este registro.';

                    modalPrediccion.className = 'badge';
                    modalPrediccion.classList.add(
                        esFalsa ? 'badge-fake' : 'badge-real'
                    );

                    reescanForm.action = this.dataset.action;

                    reescanBtn.disabled = false;
                    reescanBtn.innerHTML = 'VOLVER A ESCANEAR';
                    reescanBtn.classList.remove('loading-btn');

                    modal.classList.add('show');
                });
            });

            reescanForm.addEventListener('submit', function(e) {
                e.preventDefault();

                reescanBtn.disabled = true;
                reescanBtn.innerHTML = '<span class="loader"></span> ESCANEANDO...';
                reescanBtn.classList.add('loading-btn');

                setTimeout(() => {
                    reescanForm.submit();
                }, 400);
            });

            closeModal.addEventListener('click', function() {
                modal.classList.remove('show');
            });

            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.remove('show');
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteModal');
            const closeDeleteModal = document.getElementById('closeDeleteModal');
            const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
            const deleteForm = document.getElementById('deleteForm');
            const deleteTitle = document.getElementById('deleteTitle');
            const deleteButtons = document.querySelectorAll('.open-delete-modal');
            const deleteConfirmBtn = document.getElementById('deleteConfirmBtn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();

                    deleteForm.action = this.dataset.deleteAction;
                    deleteTitle.textContent = this.dataset.deleteTitle;

                    deleteConfirmBtn.disabled = false;
                    deleteConfirmBtn.innerHTML = 'ELIMINAR REGISTRO';
                    deleteConfirmBtn.classList.remove('delete-loading-btn');

                    deleteModal.classList.add('show');
                });
            });

            deleteForm.addEventListener('submit', function(e) {
                e.preventDefault();

                deleteConfirmBtn.disabled = true;
                deleteConfirmBtn.innerHTML = '<span class="delete-loader"></span> ELIMINANDO...';
                deleteConfirmBtn.classList.add('delete-loading-btn');

                setTimeout(() => {
                    deleteForm.submit();
                }, 400);
            });

            closeDeleteModal.addEventListener('click', function() {
                deleteModal.classList.remove('show');
            });

            cancelDeleteBtn.addEventListener('click', function() {
                deleteModal.classList.remove('show');
            });

            deleteModal.addEventListener('click', function(e) {
                if (e.target === deleteModal) {
                    deleteModal.classList.remove('show');
                }
            });
        });
    </script>
</body>

</html>