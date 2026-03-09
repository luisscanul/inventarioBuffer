<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Movimientos</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/panel.css">
    </head>

    <body>
        <?php include 'header.php'; ?>

        <div class="container mt-4">

        <div class="card panel">
        <h5>Registro de Movimiento</h5>

        <div class="row g-3 mt-2">

        <div class="col-md-4">
        <label class="form-label">Escaneo código de barras</label>
        <input type="text" class="form-control">
        </div>

        <div class="col-md-3">
        <label class="form-label">Tipo de movimiento</label>
        <select class="form-control">
        <option>Entrada</option>
        <option>Salida</option>
        </select>
        </div>

        <div class="col-md-2">
        <label class="form-label">Cantidad</label>
        <input type="number" class="form-control">
        </div>

        <div class="col-md-3">
        <label class="form-label">Fecha</label>
        <input type="date" class="form-control">
        </div>

        <div class="col-md-4">
        <label class="form-label">Usuario</label>
        <input type="text" class="form-control">
        </div>

        <div class="col-md-4 d-flex align-items-end">
        <button class="btn btn-success w-100">Registrar movimiento</button>
        </div>

        <div class="col-md-4 d-flex align-items-end">
        <button class="btn btn-secondary w-100">Cancelar</button>
        </div>

        </div>
        </div>

        <div class="card panel mt-4">
        <h5>Historial de Movimientos</h5>

        <table class="table table-striped mt-3">

        <thead>
        <tr>
        <th>Producto</th>
        <th>Tipo movimiento</th>
        <th>Cantidad</th>
        <th>Fecha</th>
        <th>Usuario</th>
        </tr>
        </thead>

        <tbody>

        <tr>
        <td>producto1</td>
        <td>Entrada</td>
        <td>20</td>
        <td>10/03/2026</td>
        <td>usuario1</td>
        </tr>

        <tr>
        <td>producto2</td>
        <td>Salida</td>
        <td>5</td>
        <td>09/03/2026</td>
        <td>usuario2</td>
        </tr>

        <tr>
        <td>producto3</td>
        <td>Entrada</td>
        <td>15</td>
        <td>08/03/2026</td>
        <td>usuario3</td>
        </tr>

        <tr>
        <td>producto4</td>
        <td>Salida</td>
        <td>3</td>
        <td>07/03/2026</td>
        <td>usuario1</td>
        </tr>

        </tbody>
        </table>
        </div>
        </div>

    </body>
</html>