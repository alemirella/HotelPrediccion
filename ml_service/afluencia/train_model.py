import pandas as pd
import numpy as np
from sklearn.multioutput import MultiOutputRegressor
from sklearn.ensemble import RandomForestRegressor
import joblib

# ==========================
# Cargar datos históricos
# ==========================
# Asegúrate de que Ejemplo.xlsx tenga columnas:
# Fecha, clima, afluencia_turistica, num_reservas, porcentaje_ocupacion, dia_festivo
df = pd.read_excel("Ejemplo.xlsx")

# Normalizar nombres de columna (en caso tus columnas tengan mayúsculas)
df.columns = [c.strip() for c in df.columns]

# Convertir fecha
df["Fecha"] = pd.to_datetime(df["Fecha"], dayfirst=True, errors="coerce")

# Campos a predecir (ajustados a tu Excel)
targets = ["afluencia_turistica", "num_reservas", "porcentaje_ocupacion", "clima", "dia_festivo"]

# Eliminar filas con datos faltantes en Fecha o targets
df = df.dropna(subset=["Fecha"] + targets, how='any').reset_index(drop=True)

# ==========================
# Crear características (features)
# ==========================
df["Dia"] = df["Fecha"].dt.day
df["Mes"] = df["Fecha"].dt.month
df["Año"] = df["Fecha"].dt.year
df["DiaSemana"] = df["Fecha"].dt.dayofweek
df["EsFinDeSemana"] = df["DiaSemana"].isin([5,6]).astype(int)
df["Mes_sin"] = np.sin(2 * np.pi * (df["Mes"] / 12))
df["Mes_cos"] = np.cos(2 * np.pi * (df["Mes"] / 12))

# Variables de entrada (features)
X = df[["Dia", "Mes", "Año", "DiaSemana", "EsFinDeSemana", "Mes_sin", "Mes_cos"]]

# Variables objetivo (a predecir)
y = df[targets]

# ==========================
# Entrenar modelo
# ==========================
modelo = MultiOutputRegressor(RandomForestRegressor(n_estimators=300, random_state=42))
modelo.fit(X, y)

# Guardar el modelo entrenado
joblib.dump(modelo, "model.pkl")
print("✅ Modelo entrenado correctamente y guardado como model.pkl")
