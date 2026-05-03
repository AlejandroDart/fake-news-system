<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

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

            DB::table('prediction_history')->insert([
                'titulo' => $request->news_title ?? '',
                'texto' => $request->news_text,
                'prediccion' => $resultado['prediccion'] ?? 'Sin predicción',
                'clase' => $resultado['clase'] ?? 0,
                'probabilidad_fake' => $resultado['probabilidad_fake'] ?? null,
                'probabilidad_real' => $resultado['probabilidad_real'] ?? null,
                'modelo' => $resultado['modelo'] ?? 'BiLSTM',
                'explicacion' => json_encode([
                    'metodo' => $resultado['metodo_explicabilidad'] ?? 'Sin método',
                    'interpretacion' => $resultado['interpretacion_explicabilidad'] ?? [],
                    'detalle' => $resultado['explain_detail'] ?? [],
                    'palabras' => $resultado['explain_words'] ?? [],
                    'valores' => $resultado['explain_values'] ?? [],
                ], JSON_UNESCAPED_UNICODE),
                'created_at' => now(),
            ]);

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
        $historial = DB::table('prediction_history')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('history', compact('historial'));
    }

    public function reescanear($id)
    {
        $registro = DB::table('prediction_history')->where('id', $id)->first();

        if (!$registro) {
            return redirect()
                ->route('history')
                ->with('error', 'No se encontró el registro seleccionado.');
        }

        try {
            $response = Http::timeout(60)->post('http://127.0.0.1:5000/predecir', [
                'titulo' => $registro->titulo ?? '',
                'texto'  => $registro->texto
            ]);

            if ($response->failed()) {
                return redirect()
                    ->route('history')
                    ->with('error', 'No se pudo volver a escanear la noticia.');
            }

            $resultado = $response->json();

            DB::table('prediction_history')
                ->where('id', $id)
                ->update([
                    'prediccion' => $resultado['prediccion'] ?? 'Sin predicción',
                    'clase' => $resultado['clase'] ?? 0,
                    'probabilidad_fake' => $resultado['probabilidad_fake'] ?? null,
                    'probabilidad_real' => $resultado['probabilidad_real'] ?? null,
                    'modelo' => $resultado['modelo'] ?? 'BiLSTM',
                    'explicacion' => json_encode([
                        'metodo' => $resultado['metodo_explicabilidad'] ?? 'Sin método',
                        'interpretacion' => $resultado['interpretacion_explicabilidad'] ?? [],
                        'detalle' => $resultado['explain_detail'] ?? [],
                        'palabras' => $resultado['explain_words'] ?? [],
                        'valores' => $resultado['explain_values'] ?? [],
                    ], JSON_UNESCAPED_UNICODE),
                    'created_at' => now(),
                ]);

            return redirect()
                ->route('history')
                ->with('success', 'La noticia fue reescaneada y actualizada correctamente.');
        } catch (\Exception $e) {
            return redirect()
                ->route('history')
                ->with('error', 'Error al conectar con la API Flask: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        DB::table('prediction_history')->where('id', $id)->delete();

        return redirect()
            ->route('history')
            ->with('success', 'Registro eliminado correctamente.');
    }
}
