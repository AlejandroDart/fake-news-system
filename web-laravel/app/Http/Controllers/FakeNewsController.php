<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FakeNewsController extends Controller
{
    public function index()
    {
        return view('index', [
            'resultado'       => session('resultado'),
            'textoIngresado'  => session('textoIngresado'),
            'tituloIngresado' => session('tituloIngresado'),
        ]);
    }

    public function analizar(Request $request)
    {
        $request->validate([
            'news_title' => 'nullable|string|max:500',
            'news_text'  => 'required|string'
        ], [
            'news_text.required' => 'Debes ingresar el texto de la noticia para analizar.',
            'news_title.max'     => 'El título no debe superar los 500 caracteres.'
        ]);

        try {
            $response = Http::timeout(60)->post('http://127.0.0.1:5000/predecir', [
                'titulo' => $request->news_title ?? '',
                'texto'  => $request->news_text
            ]);

            if ($response->failed()) {
                return redirect()
                    ->route('index')
                    ->withInput()
                    ->with('error_api', 'No se pudo obtener una respuesta válida desde la API Flask.');
            }

            $resultado = $response->json();

            return redirect()
                ->route('index')
                ->with('resultado', $resultado)
                ->with('textoIngresado', $request->news_text)
                ->with('tituloIngresado', $request->news_title);
        } catch (\Exception $e) {
            return redirect()
                ->route('index')
                ->withInput()
                ->with('error_api', 'Error al conectar con la API Flask: ' . $e->getMessage());
        }
    }

    public function reset()
    {
        return redirect()
            ->route('index')
            ->with([
                'resultado' => null,
                'textoIngresado' => '',
                'tituloIngresado' => '',
            ]);
    }

    public function history()
    {
        $historial = [
            [
                'id' => 1,
                'titulo' => 'Gobierno anuncia nueva reforma educativa nacional',
                'resultado' => 'Noticia Real',
                'modelo' => 'BiLSTM',
                'fecha' => '2026-04-09 09:10'
            ],
            [
                'id' => 2,
                'titulo' => 'Descubren cura inmediata para todas las enfermedades',
                'resultado' => 'Noticia Falsa',
                'modelo' => 'BiLSTM',
                'fecha' => '2026-04-09 09:22'
            ],
            [
                'id' => 3,
                'titulo' => 'Nueva tecnología permite traducir pensamientos en tiempo real',
                'resultado' => 'Noticia Falsa',
                'modelo' => 'BiLSTM',
                'fecha' => '2026-04-09 09:40'
            ],
            [
                'id' => 4,
                'titulo' => 'Ministerio de Salud actualiza protocolo de vacunación',
                'resultado' => 'Noticia Real',
                'modelo' => 'BiLSTM',
                'fecha' => '2026-04-09 10:05'
            ],
            [
                'id' => 5,
                'titulo' => 'Se confirma apagón mundial de internet por 3 días',
                'resultado' => 'Noticia Falsa',
                'modelo' => 'BiLSTM',
                'fecha' => '2026-04-09 10:18'
            ],
            [
                'id' => 6,
                'titulo' => 'Universidad presenta nuevo avance en inteligencia artificial',
                'resultado' => 'Noticia Real',
                'modelo' => 'BiLSTM',
                'fecha' => '2026-04-09 10:44'
            ],
            [
                'id' => 7,
                'titulo' => 'El océano desaparecerá en 10 años, afirman redes sociales',
                'resultado' => 'Noticia Falsa',
                'modelo' => 'BiLSTM',
                'fecha' => '2026-04-09 11:02'
            ],
            [
                'id' => 8,
                'titulo' => 'Nueva ley regulará el uso de datos biométricos',
                'resultado' => 'Noticia Real',
                'modelo' => 'BiLSTM',
                'fecha' => '2026-04-09 11:30'
            ],
            [
                'id' => 9,
                'titulo' => 'Hallan ciudad secreta bajo la luna',
                'resultado' => 'Noticia Falsa',
                'modelo' => 'BiLSTM',
                'fecha' => '2026-04-09 11:48'
            ],
            [
                'id' => 10,
                'titulo' => 'Científicos mejoran precisión de sistemas de detección automática',
                'resultado' => 'Noticia Real',
                'modelo' => 'BiLSTM',
                'fecha' => '2026-04-09 12:10'
            ],
        ];

        return view('history', compact('historial'));
    }
}
