import os

# Ocultar la mayor cantidad posible de logs de TensorFlow ANTES de importarlo
os.environ["TF_CPP_MIN_LOG_LEVEL"] = "3"
os.environ["TF_ENABLE_ONEDNN_OPTS"] = "0"

from flask import Flask, request, jsonify
import warnings
import pickle
import re
import json
import tensorflow as tf
from tensorflow.keras.preprocessing.sequence import pad_sequences

# Reducir logs de TensorFlow en Python
tf.get_logger().setLevel("ERROR")
warnings.filterwarnings("ignore")

app = Flask(__name__)
app.json.sort_keys = False
app.json.compact = False

BASE_DIR = os.path.dirname(os.path.abspath(__file__))

# Archivos generados por el notebook final
CONFIG_PATH = os.path.join(BASE_DIR, "config_modelo.json")
MODELO_PATH = os.path.join(BASE_DIR, "mejor_modelo_dl.keras")
TOKENIZER_PATH = os.path.join(BASE_DIR, "tokenizer_fake_news.pkl")
MAXLEN_PATH = os.path.join(BASE_DIR, "max_length.txt")
THRESHOLD_PATH = os.path.join(BASE_DIR, "threshold.txt")

modelo = None
tokenizer = None
max_length = None
threshold = None
config_modelo = None
nombre_modelo = "Desconocido"


def clean_text(text):
    if not isinstance(text, str):
        return ""
    text = text.lower()
    text = re.sub(r"http\S+|www\S+", " ", text)
    text = re.sub(r"[^a-zA-Z\s]", " ", text)
    text = re.sub(r"\s+", " ", text).strip()
    return text


def recortar_texto(texto, max_palabras=300):
    palabras = str(texto).split()
    return " ".join(palabras[:max_palabras])


def preparar_entrada_prediccion(titulo="", texto=""):
    titulo_limpio = clean_text(titulo)
    texto_limpio = clean_text(texto)
    texto_recortado = recortar_texto(texto_limpio, 300)
    combinado = f"{titulo_limpio} {texto_recortado}".strip()
    return combinado


try:
    with open(CONFIG_PATH, "r", encoding="utf-8") as f:
        config_modelo = json.load(f)

    nombre_modelo = config_modelo.get("mejor_modelo_nombre", "Modelo DL")

    modelo = tf.keras.models.load_model(MODELO_PATH, compile=False)

    with open(TOKENIZER_PATH, "rb") as f:
        tokenizer = pickle.load(f)

    with open(MAXLEN_PATH, "r", encoding="utf-8") as f:
        max_length = int(f.read().strip())

    with open(THRESHOLD_PATH, "r", encoding="utf-8") as f:
        threshold = float(f.read().strip())

    print("Modelo, tokenizer, max_length y threshold cargados correctamente.")
    print("Modelo cargado:", nombre_modelo)

except Exception as e:
    print(f"Error al cargar recursos: {e}")


def predecir_noticia_api(titulo="", texto=""):
    if modelo is None or tokenizer is None or max_length is None or threshold is None:
        return {"error": "Los recursos del modelo no están cargados correctamente."}

    if not isinstance(titulo, str):
        titulo = ""

    if not isinstance(texto, str) or texto.strip() == "":
        return {"error": "Debes ingresar un texto válido en el campo 'texto'."}

    entrada_modelo = preparar_entrada_prediccion(titulo=titulo, texto=texto)

    if entrada_modelo.strip() == "":
        return {"error": "El contenido procesado quedó vacío."}

    secuencia = tokenizer.texts_to_sequences([entrada_modelo])
    texto_pad = pad_sequences(
        secuencia,
        maxlen=max_length,
        truncating="post",
        padding="post"
    )

    probabilidad = float(modelo.predict(texto_pad, verbose=0)[0][0])

    clase = 1 if probabilidad >= threshold else 0
    etiqueta = "Noticia Falsa" if clase == 1 else "Noticia Real"

    return {
        "modelo": nombre_modelo,
        "titulo_ingresado": titulo,
        "texto_ingresado": texto,
        "entrada_procesada": entrada_modelo,
        "prediccion": etiqueta,
        "clase": clase,
        "threshold_usado": round(threshold, 6),
        "probabilidad_fake": round(probabilidad, 6),
        "probabilidad_real": round(1 - probabilidad, 6)
    }


@app.route("/", methods=["GET"])
def inicio():
    return jsonify({
        "mensaje": "API de detección de fake news activa",
        "modelo": nombre_modelo,
        "endpoint_prediccion": "/predecir",
        "formato_esperado": {
            "titulo": "string opcional",
            "texto": "string obligatorio"
        },
        "archivos_esperados": {
            "config": "config_modelo.json",
            "modelo": "mejor_modelo_dl.keras",
            "tokenizer": "tokenizer_fake_news.pkl",
            "max_length": "max_length.txt",
            "threshold": "threshold.txt"
        }
    })


@app.route("/predecir", methods=["POST"])
def predecir():
    try:
        data = request.get_json(silent=True)

        if not data:
            return jsonify({
                "error": "Debes enviar un JSON válido."
            }), 400

        titulo = data.get("titulo", "")
        texto = data.get("texto", "")

        if texto is None or str(texto).strip() == "":
            return jsonify({
                "error": "Debes enviar el campo 'texto'."
            }), 400

        resultado = predecir_noticia_api(titulo=titulo, texto=texto)

        if "error" in resultado:
            return jsonify(resultado), 400

        return jsonify(resultado), 200

    except Exception as e:
        return jsonify({
            "error": "Ocurrió un error interno al procesar la solicitud.",
            "detalle": str(e)
        }), 500


if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=False, use_reloader=False)