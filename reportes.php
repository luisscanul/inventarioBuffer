<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reportes</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/panel.css">
    </head>

    <body>
        <?php include 'header.php'; ?>

        <div class="container mt-4">
        <div class="card panel">
        <h5>Filtros de Reporte</h5>
        <div class="row g-3 mt-2">
        <div class="col-md-4">
        <label class="form-label">Fecha inicio</label>
        <input type="date" class="form-control">
        </div>

        <div class="col-md-4">
        <label class="form-label">Fecha fin</label>
        <input type="date" class="form-control">
        </div>

        <div class="col-md-4">
        <label class="form-label">Producto</label>
        <select class="form-control">
        <option>Producto1</option>
        <option>Producto2</option>
        <option>Producto3</option>
        <option>Producto4</option>
        </select>
        </div>

        <div class="col-md-4 d-flex align-items-end">
        <button class="btn btn-success w-100">Generar reporte</button>
        </div>

        <div class="col-md-4 d-flex align-items-end">
        <button class="btn btn-danger w-100">Exportar PDF</button>
        </div>

        <div class="col-md-4 d-flex align-items-end">
        <button class="btn btn-primary w-100">Exportar Excel</button>
        </div>
        </div>
        </div>

        <div class="card panel mt-4">
        <h5>Tipos de Reporte</h5>
        <div class="row mt-3">

        <div class="col-md-3">
        <div class="card text-center p-3">
        <h6>Productos vendidos</h6>
        </div>
        </div>

        <div class="col-md-3">
        <div class="card text-center p-3">
        <h6>Entradas de inventario</h6>
        </div>
        </div>

        <div class="col-md-3">
        <div class="card text-center p-3">
        <h6>Salidas de inventario</h6>
        </div>
        </div>

        <div class="col-md-3">
        <div class="card text-center p-3">
        <h6>Historial de productos</h6>
        </div>
        </div>

        </div>

        <table class="table table-striped mt-4">
        <thead>
        <tr>
        <th>Producto</th>
        <th>Movimiento</th>
        <th>Cantidad</th>
        <th>Fecha</th>
        <th>Usuario</th>
        </tr>
        </thead>

        <tbody>
        <tr>
        <td>Producto1</td>
        <td>Entrada</td>
        <td>20</td>
        <td>2026-03-01</td>
        <td>Admin</td>
        </tr>

        <tr>
        <td>Producto2</td>
        <td>Salida</td>
        <td>5</td>
        <td>2026-03-02</td>
        <td>Admin</td>
        </tr>

        <tr>
        <td>Producto3</td>
        <td>Entrada</td>
        <td>15</td>
        <td>2026-03-03</td>
        <td>Usuario1</td>
        </tr>

        </tbody>
        </table>
        </div>
        </div>

    </body>
</html>