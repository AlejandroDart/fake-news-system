<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escaner - Fake News</title>

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
                <a href="{{ route('index') }}" class="menu-item active">ESCANEO NEURAL</a>
                <a href="{{ route('history') }}" class="menu-item">BASE DE DATOS</a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="topbar">
                <nav class="topnav">
                    <a href="{{ route('index') }}" class="topnav-link active">ESCANER</a>
                    <a href="{{ route('history') }}" class="topnav-link">HISTORIAL</a>
                </nav>

                <div class="top-actions">
                    <button type="button" id="themeToggle" class="theme-indicator" title="Cambiar tema"></button>
                </div>
            </header>

            <section class="hero">
                <h2>FAKE <span>NEWS</span> CHECKER</h2>
                <p>
                    Motor de verificación basado en red neuronal para detectar desinformación,
                    contenido manipulado y posibles noticias falsas. Ingresa el título y el texto
                    sospechoso en el panel inferior para su análisis.
                </p>
            </section>

            <section class="analysis-layout">
                <div class="input-panel">
                    <div class="panel-header">
                        <span class="panel-dot"></span>
                        <span>FLUJO DE ENTRADA</span>
                        <span class="buffer">RED NEURONAL</span>
                    </div>

                    <form class="analysis-form" method="POST" action="{{ route('analizar') }}">
                        @csrf

                        <input
                            type="text"
                            id="news_title"
                            name="news_title"
                            class="title-input"
                            placeholder="INGRESA AQUÍ EL TÍTULO DE LA NOTICIA..."
                            value="{{ old('news_title', $tituloIngresado ?? '') }}">

                        <textarea
                            id="news_text"
                            name="news_text"
                            placeholder="PEGA AQUÍ EL TEXTO DE LA NOTICIA...">{{ old('news_text', $textoIngresado ?? '') }}</textarea>

                        <div class="form-actions">
                            <div class="left-controls">
                                <a href="{{ route('reset') }}" class="mini-btn active-mini">
                                    RESET CAMPOS
                                </a>
                                <button type="button" class="mini-btn">BILSTM: ON</button>
                            </div>

                            <button type="submit" id="analyzeBtn" class="analyze-btn">
                                ANALIZAR NOTICIA
                            </button>
                        </div>
                    </form>

                    @if ($errors->any())
                    <div class="alert-box">
                        {{ $errors->first() }}
                    </div>
                    @endif

                    @if (session('error_api'))
                    <div class="alert-box">
                        {{ session('error_api') }}
                    </div>
                    @endif
                </div>

                <aside class="output-panel">
                    <div class="output-title">
                        <span class="square-icon"></span>
                        <span>SALIDA DEL ANÁLISIS</span>
                    </div>

                    <div class="output-box">
                        @if (isset($resultado))
                        <div class="result-box" id="openExplainFromResult">
                            <h3 class="result-label {{ ($resultado['clase'] ?? 0) == 1 ? 'fake' : 'real' }}">
                                {{ $resultado['prediccion'] ?? 'Sin resultado' }}
                            </h3>

                            <div class="result-stats">
                                <div class="stat-item">
                                    <span>Modelo</span>
                                    <strong>{{ $resultado['modelo'] ?? 'BiLSTM' }}</strong>
                                </div>

                                <div class="stat-item">
                                    <span>Clase</span>
                                    <strong>{{ $resultado['clase'] ?? '-' }}</strong>
                                </div>

                                <div class="stat-item">
                                    <span>Probabilidad falsa</span>
                                    <strong>
                                        {{ isset($resultado['probabilidad_fake']) ? sprintf('%.4f%%', $resultado['probabilidad_fake'] * 100) : '-' }}
                                    </strong>
                                </div>

                                <div class="stat-item">
                                    <span>Probabilidad real</span>
                                    <strong>
                                        {{ isset($resultado['probabilidad_real']) ? sprintf('%.4f%%', $resultado['probabilidad_real'] * 100) : '-' }}
                                    </strong>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="output-placeholder">
                            <div class="symbol">⧉</div>
                            <p>ESPERANDO DATOS...</p>
                        </div>
                        @endif
                    </div>
                </aside>
            </section>

            <footer class="footer">

                <div>© 2026 APRENDIZAJE DE MÁQUINA | GRUPO 7 | SEMANA 9</div>

            </footer>
        </main>
    </div>

    @if (!empty($resultado['explain_detail']))
    <div id="explainModal"
        class="modal-overlay auto-open"
        data-metodo="{{ $resultado['metodo_explicabilidad'] ?? 'SHAP / Occlusion' }}"
        data-detail='@json($resultado["explain_detail"])'>

        @php
        $detalle = $resultado['explain_detail'] ?? [];

        $palabrasReal = collect($detalle)
        ->filter(fn($item) => ($item['importancia'] ?? 0) < 0)
            ->sortBy(fn($item) => abs($item['importancia'] ?? 0))
            ->reverse()
            ->take(3)
            ->pluck('palabra')
            ->toArray();

            $palabrasFalsa = collect($detalle)
            ->filter(fn($item) => ($item['importancia'] ?? 0) > 0)
            ->sortByDesc(fn($item) => abs($item['importancia'] ?? 0))
            ->take(3)
            ->pluck('palabra')
            ->toArray();
            @endphp

            <div class="modal-box">
                <div class="modal-header">
                    <div>
                        <span class="panel-dot"></span>
                        <span>EXPLICABILIDAD DEL MODELO</span>
                    </div>

                    <button type="button" id="closeExplainModal" class="modal-close">×</button>
                </div>

                <div class="modal-body">
                    <div class="explain-message">
                        <h3 id="explainMethod">Método de explicabilidad</h3>

                        <div class="explain-auto-text">
                            @if (!empty($palabrasReal))
                            <p>
                                Las palabras
                                <strong>{{ implode(', ', $palabrasReal) }}</strong>
                                fueron las que más ayudaron al modelo a considerar la noticia como
                                <strong>Noticia Real</strong>.
                            </p>
                            @endif

                            @if (!empty($palabrasFalsa))
                            <p>
                                Las palabras
                                <strong>{{ implode(', ', $palabrasFalsa) }}</strong>
                                fueron las que más empujaron la predicción hacia
                                <strong>Noticia Falsa</strong>.
                            </p>
                            @endif

                            @if (empty($palabrasReal) && empty($palabrasFalsa))
                            <p>
                                El modelo no encontró palabras con impacto fuerte en la explicación.
                            </p>
                            @endif
                        </div>

                        <p>
                            Las barras muestran qué palabras influyeron más en la clasificación.
                            Los valores positivos empujan hacia <strong>Noticia Falsa</strong> y los negativos hacia
                            <strong>Noticia Real</strong>.
                        </p>
                    </div>

                    <div id="explainChart" class="explain-chart"></div>

                    <div class="explain-legend">
                        <span class="legend-real">● Empuja hacia Noticia Real</span>
                        <span class="legend-fake">● Empuja hacia Noticia Falsa</span>
                    </div>
                </div>
            </div>
    </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.analysis-form');
            const analyzeBtn = document.getElementById('analyzeBtn');

            if (!form || !analyzeBtn) return;

            form.addEventListener('submit', function() {
                analyzeBtn.disabled = true;
                analyzeBtn.innerHTML = '<span class="loader"></span> ANALIZANDO...';
                analyzeBtn.classList.add('loading-btn');
            });
        });
    </script>

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
            const modal = document.getElementById('explainModal');
            const openFromResult = document.getElementById('openExplainFromResult');

            if (!modal) return;

            const closeBtn = document.getElementById('closeExplainModal');
            const chart = document.getElementById('explainChart');
            const method = document.getElementById('explainMethod');

            const metodo = modal.dataset.metodo;
            const detail = JSON.parse(modal.dataset.detail || '[]');

            method.textContent = metodo;
            chart.innerHTML = '';

            const maxAbs = Math.max(
                ...detail.map(item => Math.abs(item.importancia || 0)),
                0.000001
            );

            detail.forEach(item => {
                const value = Number(item.importancia || 0);
                const percent = Math.min((Math.abs(value) / maxAbs) * 100, 100);
                const isFake = value > 0;

                const row = document.createElement('div');
                row.className = 'explain-row';

                row.innerHTML = `
                <div class="explain-word">${item.palabra}</div>
                <div class="explain-bar-wrap">
                    <div class="explain-bar ${isFake ? 'bar-fake' : 'bar-real'}" style="width:${percent}%"></div>
                </div>
                <div class="explain-value">${value.toFixed(4)}</div>
            `;

                chart.appendChild(row);
            });

            if (openFromResult) {
                openFromResult.addEventListener('click', function() {
                    modal.classList.add('show');
                });
            }

            setTimeout(() => {
                modal.classList.add('show');
            }, 300);

            closeBtn.addEventListener('click', function() {
                modal.classList.remove('show');
            });

            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.remove('show');
                }
            });
        });
    </script>


</body>

</html>