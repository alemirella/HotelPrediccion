import pandas as pd
from sklearn.ensemble import RandomForestRegressor
import pickle

# Leer Excel de entrenamiento
df = pd.read_excel("historico_reservas.xlsx")

# Mapear columnas categóricas
# (igual que antes: dias, meses, tipo, clima)
# ...

X = df[["precio_actual","ocupacion_hotel","ocupacion_zona",
        "anticipacion_reserva","dia_semana","mes","tipo_habitacion",
        "competencia_precio_promedio","evento_ciudad","clima",
        "demanda_historica","feriado"]]

y = df["precio_ideal"]

modelo = RandomForestRegressor(n_estimators=200)
modelo.fit(X, y)

# Guardar modelo
with open("model.pkl", "wb") as f:
    pickle.dump(modelo, f)
