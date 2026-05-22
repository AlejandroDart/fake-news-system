# 🤖 Fake News AI - Módulo de Inteligencia Artificial

Este módulo contiene todo el flujo de **análisis, entrenamiento, selección y despliegue del modelo de inteligencia artificial** utilizado en el sistema **Fake News System**.

## 📌 Contenido del módulo
- 📊 **EDA y preprocesamiento del dataset**
- 🧠 **Entrenamiento, evaluación y comparación de 6 modelos**
- ⚖️ **Evaluación experimental de técnicas de balanceo de datos**
- 🔬 **Comparación de Undersampling, Oversampling y Class Weight**
- 🏆 **Selección automática del mejor modelo (BiLSTM)**
- 🚀 **API Flask para predicción en tiempo real**
- 💾 **Artefactos serializados para inferencia**
- 📰 **Predicción con nuevas noticias**

## 🧠 Modelos implementados
- Multinomial Naive Bayes
- Logistic Regression
- Linear SVC
- CNN 1D
- ✅ **BiLSTM (mejor modelo)**
- LSTM

## ⚖️ Técnicas de balanceo evaluadas
- Undersampling
- Oversampling
- Class Weight

## 📂 Archivos principales
- `01_EDA_Preprocesamiento_FakeNews.ipynb` → análisis exploratorio y limpieza
- `02_Modelado_Comparacion_FakeNews.ipynb` → entrenamiento y evaluación
- `03_Prediccion_CargaModelo_FakeNews.ipynb` → pruebas con modelo final
- `fake_news_api.py` → API Flask
- `mejor_modelo_dl.keras` → modelo final exportado
- `tokenizer_fake_news.pkl` → tokenizer serializado
- `max_length.txt` → longitud máxima
- `threshold.txt` → threshold óptimo

## ▶️ Ejecución API Flask

```bash
python fake_news_api.py
