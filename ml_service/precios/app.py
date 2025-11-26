from flask import Flask, request, jsonify
import pandas as pd
import pickle

app = Flask(__name__)

# Cargar modelo entrenado (model.pkl)
with open("model.pkl", "rb") as f:
    modelo = pickle.load(f)

# Mapas (opcional, si quieres recibir strings desde Laravel)
mapa_dias = {"Lunes":1, "Martes":2, "Miércoles":3, "Jueves":4,
             "Viernes":5, "Sábado":6, "Domingo":7}
mapa_meses = {"Enero":1, "Febrero":2, "Marzo":3, "Abril":4,
              "Mayo":5, "Junio":6, "Julio":7, "Agosto":8,
              "Septiembre":9, "Octubre":10, "Noviembre":11, "Diciembre":12}
mapa_tipo = {"Individual":1, "Doble":2, "Triple":3}
mapa_clima = {"Soleado":1, "Lluvioso":2, "Nublado":3}

@app.route('/')
def home():
    return "API de precios funcionando. Usa POST en /predict-price"
@app.route('/predict-price', methods=['POST'])
def predict_price():
    data = request.json

    # Crear DataFrame con una fila
    df = pd.DataFrame([{
        "precio_actual": data.get("precio_actual"),
        "ocupacion_hotel": data.get("ocupacion_hotel"),
        "ocupacion_zona": data.get("ocupacion_zona"),
        "anticipacion_reserva": data.get("anticipacion_reserva"),
        "dia_semana": mapa_dias.get(data.get("dia_semana"), data.get("dia_semana")),
        "mes": mapa_meses.get(data.get("mes"), data.get("mes")),
        "tipo_habitacion": mapa_tipo.get(data.get("tipo_habitacion"), data.get("tipo_habitacion")),
        "competencia_precio_promedio": data.get("competencia_precio_promedio"),
        "evento_ciudad": data.get("evento_ciudad"),
        "clima": mapa_clima.get(data.get("clima"), data.get("clima")),
        "demanda_historica": data.get("demanda_historica"),
        "feriado": data.get("feriado")
    }])

    # Predecir
    precio_recomendado = modelo.predict(df)[0]
    return jsonify({"precio_recomendado": round(precio_recomendado,2)})

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5001)
