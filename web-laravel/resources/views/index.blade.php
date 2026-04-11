<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detector de Fake News</title>

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
                <span>V.1.0.0 ACTIVO</span>
            </div>

            <nav class="menu">
                <a href="#" class="menu-item active">ESCANEO NEURAL</a>
                <a href="#" class="menu-item">BASE DE DATOS</a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="topbar">
                <nav class="topnav">
                    <a href="#" class="topnav-link active">ESCANER</a>
                    <a href="#" class="topnav-link">HISTORIAL</a>
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
                            value="{{ old('news_title', $tituloIngresado ?? '') }}"
                        >

                        <textarea
                            id="news_text"
                            name="news_text"
                            placeholder="PEGA AQUÍ EL TEXTO DE LA NOTICIA..."
                        >{{ old('news_text', $textoIngresado ?? '') }}</textarea>

                        <div class="form-actions">
                            <div class="left-controls">
                                <a href="{{ route('reset') }}" class="mini-btn active-mini">
                                    RESET CAMPOS
                                </a>
                                <button type="button" class="mini-btn">BILSTM: ON</button>
                            </div>

                            <button type="submit" class="analyze-btn">ANALIZAR NOTICIA</button>
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
                            <div class="result-box">
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
                
                <div>© 2026 APRENDIZAJE DE MÁQUINA | GRUPO 7 | SEMANA 6</div>
                
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
</body>

</html>