import os

os.environ["TF_CPP_MIN_LOG_LEVEL"] = "3"
os.environ["TF_ENABLE_ONEDNN_OPTS"] = "0"

from flask import Flask, request, jsonify
import warnings
import pickle
import re
import json
import tensorflow as tf
import numpy as np
from tensorflow.keras.preprocessing.sequence import pad_sequences

try:
    import shap
    SHAP_DISPONIBLE = True
except Exception:
    shap = None
    SHAP_DISPONIBLE = False


tf.get_logger().setLevel("ERROR")
warnings.filterwarnings("ignore")

app = Flask(__name__)
app.json.sort_keys = False
app.json.compact = False

BASE_DIR = os.path.dirname(os.path.abspath(__file__))

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
    return f"{titulo_limpio} {texto_recortado}".strip()


def vectorizar_texto(texto):
    secuencia = tokenizer.texts_to_sequences([texto])
    texto_pad = pad_sequences(
        secuencia,
        maxlen=max_length,
        truncating="post",
        padding="post"
    )
    return texto_pad


def predecir_probabilidad_fake(texto):
    texto_pad = vectorizar_texto(texto)
    return float(modelo.predict(texto_pad, verbose=0)[0][0])


def obtener_direccion(valor):
    if valor > 0:
        return "Empuja hacia Noticia Falsa"
    elif valor < 0:
        return "Empuja hacia Noticia Real"
    return "Impacto neutro"


def construir_detalle(palabras, valores):
    detalle = []

    for palabra, valor in zip(palabras, valores):
        detalle.append({
            "palabra": palabra,
            "importancia": round(float(valor), 6),
            "direccion": obtener_direccion(float(valor))
        })

    return detalle


def obtener_shap_text_explicacion(texto, top_n=10):
    if not SHAP_DISPONIBLE:
        raise Exception("SHAP no está instalado o no está disponible.")

    texto_base = recortar_texto(texto, 80)

    def predict_texts(textos):
        entradas = []

        for t in textos:
            limpio = preparar_entrada_prediccion("", str(t))
            entradas.append(limpio)

        secuencias = tokenizer.texts_to_sequences(entradas)
        padded = pad_sequences(
            secuencias,
            maxlen=max_length,
            truncating="post",
            padding="post"
        )

        preds = modelo.predict(padded, verbose=0).reshape(-1)
        return preds

    masker = shap.maskers.Text(r"\W+")

    explainer_local = shap.Explainer(
        predict_texts,
        masker,
        algorithm="partition"
    )

    shap_result = explainer_local(
        [texto_base],
        max_evals=80,
        batch_size=16
    )

    words = np.array(shap_result.data[0]).astype(str)
    values = np.array(shap_result.values[0]).reshape(-1)

    pares = []

    for palabra, valor in zip(words, values):
        palabra_limpia = clean_text(palabra)

        if palabra_limpia and len(palabra_limpia) > 2:
            pares.append((palabra_limpia, float(valor)))

    pares = sorted(pares, key=lambda x: abs(x[1]), reverse=True)

    palabras = []
    valores = []

    for palabra, valor in pares:
        if palabra not in palabras:
            palabras.append(palabra)
            valores.append(round(float(valor), 6))

        if len(palabras) >= top_n:
            break

    if not palabras:
        raise Exception("SHAP no devolvió palabras explicativas.")

    return {
        "metodo": "Análisis de influencia de palabras (método SHAP)",
        "palabras": palabras,
        "valores": valores,
        "detalle": construir_detalle(palabras, valores)
    }


def obtener_occlusion_explicacion(texto, top_n=10, max_palabras_explicar=80):
    palabras_texto = texto.split()

    if len(palabras_texto) == 0:
        return {
            "metodo": "Oclusión / eliminación de una palabra",
            "palabras": [],
            "valores": [],
            "detalle": []
        }

    palabras_texto = palabras_texto[:max_palabras_explicar]
    texto_base = " ".join(palabras_texto)

    prob_original = predecir_probabilidad_fake(texto_base)

    explicaciones = []

    for i, palabra in enumerate(palabras_texto):
        texto_sin_palabra = palabras_texto[:i] + palabras_texto[i + 1:]
        texto_sin_palabra = " ".join(texto_sin_palabra)

        if texto_sin_palabra.strip() == "":
            continue

        prob_sin_palabra = predecir_probabilidad_fake(texto_sin_palabra)

        importancia = prob_original - prob_sin_palabra

        explicaciones.append({
            "palabra": palabra,
            "importancia": round(float(importancia), 6),
            "probabilidad_original": round(float(prob_original), 6),
            "probabilidad_sin_palabra": round(float(prob_sin_palabra), 6),
            "direccion": obtener_direccion(importancia)
        })

    explicaciones = sorted(
        explicaciones,
        key=lambda x: abs(x["importancia"]),
        reverse=True
    )

    top_explicaciones = []
    palabras_usadas = set()

    for item in explicaciones:
        palabra = item["palabra"]

        if palabra not in palabras_usadas:
            top_explicaciones.append(item)
            palabras_usadas.add(palabra)

        if len(top_explicaciones) >= top_n:
            break

    return {
        "metodo": "Oclusión / eliminación de una palabra",
        "palabras": [item["palabra"] for item in top_explicaciones],
        "valores": [item["importancia"] for item in top_explicaciones],
        "detalle": top_explicaciones
    }


def obtener_explicabilidad_solida(texto):
    try:
        return obtener_shap_text_explicacion(texto, top_n=10)
    except Exception as e:
        print("SHAP falló. Usando Oclusión:", e)
        return obtener_occlusion_explicacion(
            texto,
            top_n=10,
            max_palabras_explicar=80
        )


def cargar_recursos():
    global modelo, tokenizer, max_length, threshold, config_modelo, nombre_modelo

    with open(CONFIG_PATH, "r", encoding="utf-8") as f:
        config_modelo = json.load(f)

    nombre_modelo = config_modelo.get("mejor_modelo_nombre", "BiLSTM")

    modelo = tf.keras.models.load_model(MODELO_PATH, compile=False)

    with open(TOKENIZER_PATH, "rb") as f:
        tokenizer = pickle.load(f)

    with open(MAXLEN_PATH, "r", encoding="utf-8") as f:
        max_length = int(f.read().strip())

    with open(THRESHOLD_PATH, "r", encoding="utf-8") as f:
        threshold = float(f.read().strip())

    print("Modelo, tokenizer, max_length y threshold cargados correctamente.")
    print("Modelo cargado:", nombre_modelo)

    if SHAP_DISPONIBLE:
        print("SHAP disponible.")
    else:
        print("SHAP no disponible. Se usará Oclusión.")


try:
    cargar_recursos()
except Exception as e:
    print(f"Error al cargar recursos: {e}")


def predecir_noticia_api(titulo="", texto=""):
    if modelo is None or tokenizer is None or max_length is None or threshold is None:
        return {
            "error": "Los recursos del modelo no están cargados correctamente."
        }

    if not isinstance(titulo, str):
        titulo = ""

    if not isinstance(texto, str) or texto.strip() == "":
        return {
            "error": "Debes ingresar un texto válido en el campo 'texto'."
        }

    entrada_modelo = preparar_entrada_prediccion(titulo=titulo, texto=texto)

    if entrada_modelo.strip() == "":
        return {
            "error": "El contenido procesado quedó vacío."
        }

    probabilidad = predecir_probabilidad_fake(entrada_modelo)

    clase = 1 if probabilidad >= threshold else 0
    etiqueta = "Noticia Falsa" if clase == 1 else "Noticia Real"

    explicacion = obtener_explicabilidad_solida(entrada_modelo)

    return {
        "modelo": nombre_modelo,
        "tipo_modelo": "BiLSTM",
        "titulo_ingresado": titulo,
        "texto_ingresado": texto,
        "entrada_procesada": entrada_modelo,

        "prediccion": etiqueta,
        "clase": clase,
        "threshold_usado": round(float(threshold), 6),
        "probabilidad_fake": round(float(probabilidad), 6),
        "probabilidad_real": round(float(1 - probabilidad), 6),

        "metodo_explicabilidad": explicacion["metodo"],
        "interpretacion_explicabilidad": {
            "valor_positivo": "La palabra aumenta la probabilidad de Noticia Falsa.",
            "valor_negativo": "La palabra reduce la probabilidad de Noticia Falsa y empuja hacia Noticia Real.",
            "valor_cercano_a_cero": "La palabra tiene poco impacto en la predicción."
        },

        "explain_words": explicacion["palabras"],
        "explain_values": explicacion["valores"],
        "explain_detail": explicacion["detalle"],

        "shap_words": explicacion["palabras"],
        "shap_values": explicacion["valores"]
    }


@app.route("/", methods=["GET"])
def inicio():
    return jsonify({
        "mensaje": "API de detección de fake news activa",
        "modelo": nombre_modelo,
        "tipo_modelo": "BiLSTM",
        "endpoint_prediccion": "/predecir",
        "metodo_explicabilidad_principal": "Análisis de influencia de palabras (método SHAP)",
        "metodo_explicabilidad_respaldo": "Oclusión / eliminación de una palabra",
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
    app.run(
        host="0.0.0.0",
        port=5000,
        debug=False,
        use_reloader=False
    )