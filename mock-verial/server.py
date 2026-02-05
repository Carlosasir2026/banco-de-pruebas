"""
Servidor mock de Verial para pruebas
Simula las respuestas de la API sin tocar el Verial real
"""
from flask import Flask, request, jsonify
from flask_cors import CORS
import random

app = Flask(__name__)
CORS(app)

# Base de datos en memoria
db = {
    'clientes': [],
    'articulos': [
        {'Id': 1001, 'ReferenciaBarras': '8412345678901', 'Nombre': 'Producto Test 1', 'PVP': 29.99},
        {'Id': 1002, 'ReferenciaBarras': '8412345678902', 'Nombre': 'Producto Test 2', 'PVP': 49.99},
        {'Id': 1003, 'ReferenciaBarras': '8412345678903', 'Nombre': 'Producto Test 3', 'PVP': 19.99},
    ],
    'stock': [
        {'IdArticulo': 1001, 'Stock': 100},
        {'IdArticulo': 1002, 'Stock': 50},
        {'IdArticulo': 1003, 'Stock': 75},
    ],
    'pedidos': []
}

@app.route('/WcfServiceLibraryVerial/GetClientesWS', methods=['GET'])
def get_clientes():
    nif = request.args.get('nif', '')
    clientes = [c for c in db['clientes'] if c.get('NIF') == nif] if nif else db['clientes']
   
    return jsonify({
        'InfoError': {'Codigo': 0, 'Descripcion': None},
        'Clientes': clientes
    })

@app.route('/WcfServiceLibraryVerial/NuevoClienteWS', methods=['POST'])
def nuevo_cliente():
    data = request.json
    cliente_id = random.randint(10000, 99999)
   
    cliente = {
        'Id': cliente_id,
        'NIF': data.get('NIF', ''),
        'Nombre': data.get('Nombre', ''),
        'Email': data.get('Email', '')
    }
   
    db['clientes'].append(cliente)
   
    return jsonify({
        'InfoError': {'Codigo': 0, 'Descripcion': None},
        'Clientes': [cliente]
    })

@app.route('/WcfServiceLibraryVerial/GetArticulosWS', methods=['GET'])
def get_articulos():
    return jsonify({
        'InfoError': {'Codigo': 0, 'Descripcion': None},
        'Articulos': db['articulos']
    })

@app.route('/WcfServiceLibraryVerial/GetStockArticulosWS', methods=['GET'])
def get_stock():
    id_articulo = request.args.get('id_articulo', 0, type=int)
    stock = [s for s in db['stock'] if s['IdArticulo'] == id_articulo] if id_articulo else db['stock']
   
    return jsonify({
        'InfoError': {'Codigo': 0, 'Descripcion': None},
        'StockArticulos': stock
    })

@app.route('/WcfServiceLibraryVerial/NuevoDocClienteWS', methods=['POST'])
def nuevo_pedido():
    data = request.json
    pedido_id = random.randint(90000, 99999)
   
    pedido = {
        'Id': pedido_id,
        'Referencia': data.get('Referencia', ''),
        'Numero': f'PED-{pedido_id}',
        'ID_Cliente': data.get('ID_Cliente')
    }
   
    db['pedidos'].append(pedido)
   
    print(f"📦 Pedido creado: {pedido}")
   
    return jsonify({
        'InfoError': {'Codigo': 0, 'Descripcion': None},
        'Id': pedido_id,
        'Referencia': pedido['Referencia'],
        'Numero': pedido['Numero']
    })

@app.route('/debug', methods=['GET'])
def debug():
    """Endpoint para ver el estado de la base de datos mock"""
    return jsonify({
        'clientes': len(db['clientes']),
        'articulos': len(db['articulos']),
        'pedidos': len(db['pedidos']),
        'ultimo_pedido': db['pedidos'][-1] if db['pedidos'] else None
    })

if __name__ == '__main__':
    print("🚀 Mock Verial Server iniciado en http://0.0.0.0:8080")
    app.run(host='0.0.0.0', port=8080, debug=True)