import pandas as pd
import numpy as np
import joblib

# Cargar el modelo entrenado
modelo = joblib.load("model.pkl")

targets = ["Afluencia Turistica", "N# reservas", "% ocupacion"]

def features_from_fecha(fecha):
    """Convierte una fecha en features numéricas."""
    fecha_dt = pd.to_datetime(fecha, dayfirst=True, errors="coerce")
    if pd.isna(fecha_dt):
        raise ValueError("Formato de fecha inválido. Usa DD/MM/YYYY o YYYY-MM-DD.")

    dia = fecha_dt.day
    mes = fecha_dt.month
    anio = fecha_dt.year
    diasem = fecha_dt.weekday()
    es_fin = 1 if diasem in [5,6] else 0
    mes_sin = np.sin(2 * np.pi * (mes / 12))
    mes_cos = np.cos(2 * np.pi * (mes / 12))

    return pd.DataFrame([{
        "Dia": dia,
        "Mes": mes,
        "Año": anio,
        "DiaSemana": diasem,
        "EsFinDeSemana": es_fin,
        "Mes_sin": mes_sin,
        "Mes_cos": mes_cos
    }])

def predecir_por_fecha(fecha):
    """Genera predicción para una fecha dada."""
    X_new = features_from_fecha(fecha)
    preds = modelo.predict(X_new)[0]

    resultado = dict(zip(targets, preds))
    resultado["Afluencia Turistica"] = int(round(resultado["Afluencia Turistica"]))
    resultado["N# reservas"] = int(round(resultado["N# reservas"]))
    resultado["% ocupacion"] = round(float(resultado["% ocupacion"]), 2)

    return resultado
