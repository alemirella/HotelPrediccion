import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.multioutput import MultiOutputRegressor
from sklearn.ensemble import RandomForestRegressor
import joblib

# ==========================
# Cargar datos históricos
# ==========================
df = pd.read_excel("Ejemplo.xlsx")  # Reemplaza con tu dataset real

df["Fecha"] = pd.to_datetime(df["Fecha"], dayfirst=True, errors="coerce")
targets = ["Afluencia Turistica", "N# reservas", "% ocupacion"]
df = df.dropna(subset=["Fecha"] + targets).reset_index(drop=True)

# Features
df["Dia"] = df["Fecha"].dt.day
df["Mes"] = df["Fecha"].dt.month
df["Año"] = df["Fecha"].dt.year
df["DiaSemana"] = df["Fecha"].dt.dayofweek
df["EsFinDeSemana"] = df["DiaSemana"].isin([5,6]).astype(int)
df["Mes_sin"] = np.sin(2 * np.pi * (df["Mes"] / 12))
df["Mes_cos"] = np.cos(2 * np.pi * (df["Mes"] / 12))

X = df[["Dia","Mes","Año","DiaSemana","EsFinDeSemana","Mes_sin","Mes_cos"]]
y = df[targets]

# ==========================
# Entrenar
# ==========================
modelo = MultiOutputRegressor(RandomForestRegressor(n_estimators=200, random_state=42))
modelo.fit(X, y)

joblib.dump(modelo, "model.pkl")
print("✅ Modelo entrenado y guardado como model.pkl")
