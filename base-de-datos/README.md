# 🗄️ Base de Datos - Fake News System

Este módulo corresponde a la capa de **persistencia de datos** del sistema **Fake News System**, utilizando **PostgreSQL** para almacenar el historial de predicciones realizadas por el modelo de inteligencia artificial.

## 📌 Contenido del módulo

- 🗄️ Definición de la estructura de la base de datos
- 📊 Almacenamiento del historial de predicciones
- 🤖 Registro de resultados generados por el modelo BiLSTM
- 🧠 Persistencia de la explicabilidad del modelo
- 📅 Trazabilidad de análisis mediante fecha de ejecución
- 📁 Scripts SQL para creación e inserción de datos

## 📂 Archivos principales

- `schema.sql` → estructura de la base de datos (tablas, secuencias, constraints)
- `seed_data.sql` → datos de ejemplo para pruebas (opcional)

## 🧱 Estructura principal

Tabla:

prediction_history

### 📌 Campos almacenados

| Campo                  | Tipo                | Descripción |
|----------------------|---------------------|------------|
| id                   | integer (PK)        | Identificador único de la predicción |
| titulo               | text                | Título de la noticia ingresada |
| texto                | text                | Contenido completo de la noticia |
| prediccion           | varchar(50)         | Resultado: "Noticia Real" o "Noticia Falsa" |
| clase                | integer             | Clase numérica (0 = Real, 1 = Falsa) |
| probabilidad_fake    | numeric(10,6)       | Probabilidad de que la noticia sea falsa |
| probabilidad_real    | numeric(10,6)       | Probabilidad de que la noticia sea real |
| modelo               | varchar(100)        | Modelo utilizado (ej: BiLSTM) |
| explicacion          | text (JSON)         | Explicabilidad del modelo (palabras, importancia, dirección) |
| created_at           | timestamp           | Fecha y hora del análisis |

## 🛠️ Creación de la base de datos

CREATE DATABASE fake_news_db;

## 📥 Importar estructura

psql -U postgres -d fake_news_db < schema.sql

## 📥 Importar datos de ejemplo (opcional)

psql -U postgres -d fake_news_db < seed_data.sql

## 🎯 Propósito

- 📊 Consultar historial de predicciones
- 🔍 Analizar resultados del modelo
- 🧠 Evaluar comportamiento del sistema
- 📈 Facilitar futuras mejoras del modelo
- 🔁 Reutilizar datos para entrenamiento o auditoría
