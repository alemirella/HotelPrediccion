## 📊 Resultados del Modelo Predictivo

El modelo fue desarrollado en Python y entrenado con datos históricos de reservas de hotel.  
A través del Colab [aquí](https://colab.research.google.com/drive/1g8Re4mvgIW-dsGKAggXP--zNQgNM19OU?usp=sharing), se implementó la lógica de predicción y se integró con la aplicación mediante una API REST.

### 🔹 Integración
- La API recibe los datos del formulario web y los envía al modelo en Python.
- El modelo devuelve la predicción al backend, donde se muestra al usuario.

### 🔹 Resultados de Precisión
- Accuracy: **0.89**
- Precision: **0.88**
- Recall: **0.87**
- F1-Score: **0.87**
- Se validó con un conjunto de datos no visto para evitar sobreajuste.

### 🔹 Conclusión
El modelo ofrece un desempeño sólido y consistente, adecuado para el prototipo actual.  
Se recomienda ampliar el conjunto de datos para futuras versiones (PMV3) y recalibrar parámetros para mejorar la precisión.
