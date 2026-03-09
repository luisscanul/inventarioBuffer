<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Panel Principal</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/panel.css">
    </head>

    <body>
        <?php include 'header.php'; ?>

        <div class="container mt-4">
        <div class="row g-3">
        <div class="col-md-4">
        <div class="card indicador">
        <h5>Total de productos</h5>
        <h2>320</h2>
        </div>
        </div>

        <div class="col-md-4">
        <div class="card indicador">
        <h5>Productos con stock bajo</h5>
        <h2>18</h2>
        </div>
        </div>

        <div class="col-md-4">
        <div class="card indicador">
        <h5>Productos críticos</h5>
        <h2>6</h2>
        </div>
        </div>
        </div>

        <div class="row mt-4">
        <div class="col-md-8">
        <div class="card panel">

        <h5>Entradas y salidas</h5>
        <canvas id="graficaInventario"></canvas>
        </div>
        </div>

        <div class="col-md-4">
        <div class="card panel">
        <h5>Alertas del sistema</h5>
        <ul class="list-group">
        <li class="list-group-item alerta">
        Producto "Producto1" con stock bajo
        </li>

        <li class="list-group-item alerta">
        Producto "Producto2" en nivel crítico
        </li>
        <li class="list-group-item alerta">
        Producto "Producto3" agotado
        </li>
        </ul>
        </div>
        </div>
        </div>

        <div class="row mt-4">
        <div class="col">
        <div class="card panel">
        <h5>Últimos movimientos de inventario</h5>
        <table class="table table-striped mt-3">
        <thead>
        <tr>
        <th>Producto</th>
        <th>Tipo</th>
        <th>Cantidad</th>
        <th>Fecha</th>
        </tr>
        </thead>

        <tbody>
        <tr>
        <td>Producto4</td>
        <td>Entrada</td>
        <td>20</td>
        <td>10/03/2026</td>
        </tr>

        <tr>
        <td>Producto5</td>
        <td>Salida</td>
        <td>5</td>
        <td>09/03/2026</td>
        </tr>

        <tr>
        <td>Producto6</td>
        <td>Entrada</td>
        <td>10</td>
        <td>08/03/2026</td>
        </tr>
        </tbody>
        </table>
        </div>
        </div>
        </div>

        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="js/panel.js"></script>
    </body>
</html>