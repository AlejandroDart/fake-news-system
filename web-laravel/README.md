# 🌐 Web Laravel - Fake News System

Este módulo corresponde al **frontend web** del proyecto **Fake News System**, desarrollado en **Laravel**, y encargado de la interacción directa con el usuario y la persistencia del historial de predicciones en **PostgreSQL**.

## 📌 Funcionalidad
- 📰 Ingreso de **título** y **contenido** de noticias
- 🔗 Consumo de la **API Flask** para enviar solicitudes de análisis
- 🤖 Visualización del resultado de clasificación generado por el modelo
- 🗄️ Almacenamiento del historial de predicciones en **PostgreSQL**
- 🎨 Interfaz con soporte para **tema claro** y **tema oscuro**

## 🛠️ Requisitos
- PHP 8+
- Composer
- Laravel 11
- PostgreSQL
- API Flask activa en `http://127.0.0.1:5000`

## ▶️ Ejecución

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
