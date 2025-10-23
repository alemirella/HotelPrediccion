from flask import Flask, request, jsonify
from predictor import predecir_por_fecha

app = Flask(__name__)

@app.route('/', methods=['GET'])
def home():
    return "✅ API de predicción para hotel está funcionando correctamente"

@app.route('/predict', methods=['POST'])
def predict():
    data = request.get_json(force=True, silent=True)
    if not data:
        return jsonify({'error': 'Request body vacío o JSON inválido'}), 400

    fecha = data.get('fecha')
    if not fecha:
        return jsonify({'error': 'Falta la fecha en la solicitud'}), 400

    try:
        resultado = predecir_por_fecha(fecha)
        return jsonify({'fecha': fecha, 'prediccion': resultado})
    except ValueError as e:
        return jsonify({'error': str(e)}), 400
    except Exception as e:
        return jsonify({'error': f"Error interno: {str(e)}"}), 500

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)
