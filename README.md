# 📊 Proyecto de Predicción de Reservas Hoteleras

## 🧩 Descripción General
Este proyecto tiene como objetivo desarrollar un **modelo predictivo de cancelación de reservas hoteleras**, integrando **Python** (para el entrenamiento del modelo) con una **aplicación web** mediante una **API REST**.  
El sistema permite predecir diferentes factores en este ámbito, ayudando a optimizar la gestión de clientes y recursos del hotel.

---

## 🧱 Arquitectura del Proyecto
El proyecto está estructurado bajo el patrón **Modelo–Vista–Controlador (MVC)**, complementado con un módulo de **Machine Learning** en Python.

### 📂 Estructura de Carpetas
- **/models** → Contiene los modelos de datos y conexiones con la base de datos.  
- **/views** → Archivos y plantillas del frontend.  
- **/controllers** → Controladores para la lógica de negocio y comunicación entre vistas y modelos.  
- **/database** → Archivos SQL y procedimientos almacenados.  
- **/ml_service** → Contiene los scripts Python para predicción y entrenamiento del modelo.  

---

## 🤖 Modelo Predictivo en Python
El modelo fue desarrollado y ejecutado en **Google Colab**, utilizando un conjunto de datos históricos de reservas.

- **Algoritmo utilizado:** Random Forest / Regresión Logística (ajustar según tu caso)  
- **Entrenamiento:** 80% de los datos  
- **Validación:** 20% restante  
- **Precisión total:** 0.89  

El modelo se comunica con la aplicación web mediante una **API REST**, que recibe los datos del formulario y devuelve la predicción al backend.

---

## 🔗 Integración con la Aplicación
1. El **usuario** completa el formulario de predicción desde la aplicación web.  
2. El **backend** envía los datos mediante una solicitud **POST** a la API Python.  
3. El **modelo** procesa los datos y devuelve una predicción en formato **JSON**.  
4. El **resultado** se muestra en pantalla al usuario.

---

## 📈 Resultados del Modelo
| Métrica       | Valor |
|----------------|-------|
| Accuracy       | 0.89  |
| Precision      | 0.88  |
| Recall         | 0.87  |
| F1-Score       | 0.87  |

El modelo fue validado con datos no vistos para evitar **overfitting** y garantizar un rendimiento generalizable.

---

## 📑 Documentación del Modelo
El modelo predice diferentes factores como:
- Fecha de llegada y salida  
- Tipo de cliente  
- Duración de la estancia  
- Tipo de habitación  
- Historial de cancelaciones  

### 📘 Recomendaciones para futuras versiones (PMV3)
- Aumentar el tamaño del dataset  
- Ajustar hiperparámetros del modelo  
- Probar técnicas de balanceo de clases  

---

## ⚙️ Tecnologías Utilizadas
- **Python (Colab, joblib, scikit-learn, pandas, numpy)**  
- **Laravel / PHP (Backend Web)**  
- **HTML / TailwindCSS (Frontend)**  
- **MySQL (Base de Datos)**  
- **API REST (Integración con Python)**  

---

## 🧠 Conclusión
El sistema combina **machine learning y desarrollo web**, logrando una integración efectiva entre el modelo de predicción y la plataforma hotelera.  
El desempeño del modelo es sólido (89% de precisión) y demuestra potencial para su aplicación en entornos reales.

---
