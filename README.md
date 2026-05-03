# 🚨 Fake News System — Detección Inteligente de Noticias Falsas

**Fake News System** es un sistema web inteligente desarrollado con **Laravel + Flask API + Deep Learning**, diseñado para detectar si una noticia es **real o falsa** mediante un modelo de **BiLSTM entrenado con TensorFlow/Keras**.

El sistema incluye un módulo de persistencia de datos en **PostgreSQL**, donde se almacena el historial de predicciones, probabilidades y explicabilidad del modelo, permitiendo trazabilidad, análisis posterior y reutilización de resultados.

El proyecto integra:

- 🌐 **Aplicación web en Laravel**
- 🤖 **API de predicción en Flask**
- 🧠 **Modelo Deep Learning BiLSTM**
- 📊 **Proceso completo de EDA, entrenamiento y comparación de modelos**
- 📰 **Predicción usando título + contenido de la noticia**

Este repositorio corresponde al **sistema completo Full Stack + Inteligencia Artificial** del proyecto académico.

---

## 📌 Características Principales

- 📰 **Clasificación automática de noticias reales o fake news**
- 🧠 **Modelo BiLSTM como mejor modelo seleccionado**
- ⚡ **API REST en Flask para predicción en tiempo real**
- 🌐 **Interfaz web desarrollada en Laravel**
- 📊 **Entrenamiento y comparación entre 6 modelos**
- 🧪 **Evaluación con Accuracy, Precision, Recall y F1-score**
- 📁 **Modelo serializado y reutilizable**
- 🔗 **Integración Laravel → Flask API → Modelo IA**

---

## 🛠️ Tecnologías Utilizadas

### 🌐 Frontend / Web
- Laravel 11
- Blade Templates
- Bootstrap / CSS
- PHP 8+

### 🤖 Módulo IA / API
- Python 3
- Flask
- TensorFlow / Keras
- Scikit-learn
- Pandas
- NumPy

### 🧠 Modelos implementados
- Multinomial Naive Bayes
- Logistic Regression
- Linear SVC
- CNN 1D
- ✅ **BiLSTM (mejor modelo)**
- LSTM

---

## 🧪 Mejor Modelo Seleccionado

El modelo con mejor desempeño fue:

**BiLSTM**

### 📈 Métricas finales
- **Accuracy:** 0.998965
- **Precision:** 0.998284
- **Recall:** 0.999427
- **F1-score:** 0.998856
- **Threshold:** 0.60

Este modelo fue exportado y reutilizado en la API Flask para realizar inferencias sobre nuevas noticias.

---

## 📂 Estructura del Proyecto

```bash
fake-news-system/
│
├── fake-news-ai/
│   ├── 01_EDA_Preprocesamiento_FakeNews.ipynb
│   ├── 02_Modelado_Comparacion_FakeNews.ipynb
│   ├── 03_Prediccion_CargaModelo_FakeNews.ipynb
│   ├── Fake.csv
│   ├── True.csv
│   ├── config_modelo.json
│   ├── fake_news_api.py
│   ├── max_length.txt
│   ├── mejor_modelo_dl.keras
│   ├── threshold.txt
│   └── tokenizer_fake_news.pkl
│
├── web-laravel/
│   ├── app/
│   ├── resources/
│   ├── routes/
│   └── public/
│
└── README.md
```

---

## 🎓 Proyecto Académico
**Asignatura:** Aprendizaje de Máquina  
**Grupo:** 7  
**Año:** 2026  

Proyecto desarrollado con fines académicos, integrando **Machine Learning, Deep Learning, API REST y desarrollo web con Laravel** para la detección automática de noticias falsas.
