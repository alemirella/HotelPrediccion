import pandas as pd
import numpy as np
import joblib

# Cargar el modelo entrenado (asegúrate de que model.pkl esté en la misma carpeta)
modelo = joblib.load("model.pkl")

# Campos de salida que devolveremos en el JSON
targets = ["afluencia_turistica", "num_reservas", "porcentaje_ocupacion", "clima", "dia_festivo"]

def features_from_fecha(fecha):
    """Convierte una fecha en las variables necesarias para el modelo."""
    fecha_dt = pd.to_datetime(fecha, dayfirst=True, errors="coerce")
    if pd.isna(fecha_dt):
        # intenta con ISO
        fecha_dt = pd.to_datetime(fecha, errors="coerce")
    if pd.isna(fecha_dt):
        raise ValueError("Formato de fecha inválido. Usa DD/MM/YYYY o YYYY-MM-DD.")

    dia = fecha_dt.day
    mes = fecha_dt.month
    anio = fecha_dt.year
    diasem = fecha_dt.weekday()
    es_fin = 1 if diasem in [5, 6] else 0
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
    """Genera una predicción completa para una fecha dada."""
    X_new = features_from_fecha(fecha)
    preds = modelo.predict(X_new)[0]

    resultado = dict(zip(targets, preds))

    # Redondear y limpiar valores
    resultado["afluencia_turistica"] = max(0, int(round(resultado.get("afluencia_turistica", 0))))
    resultado["num_reservas"] = max(0, int(round(resultado.get("num_reservas", 0))))
    resultado["porcentaje_ocupacion"] = round(float(resultado.get("porcentaje_ocupacion", 0.0)), 2)
    clima_val = resultado.get("clima", None)
    resultado["clima"] = int(round(clima_val)) if clima_val is not None and not np.isnan(clima_val) else None
    dia_val = resultado.get("dia_festivo", None)
    resultado["dia_festivo"] = int(round(dia_val)) if dia_val is not None and not np.isnan(dia_val) else 0

    return resultado
