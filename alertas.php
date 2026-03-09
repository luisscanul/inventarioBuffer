<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Alertas de Inventario</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/panel.css">
    </head>

    <body>
        <?php include 'header.php'; ?>

        <div class="container mt-4">

        <div class="card panel">
        <h5>Alertas de Inventario</h5>

        <table class="table table-striped mt-3">

        <thead>
        <tr>
        <th>Producto</th>
        <th>Nivel actual</th>
        <th>Buffer recomendado</th>
        <th>Nivel de alerta</th>
        </tr>
        </thead>

        <tbody>

        <tr class="table-warning">
        <td>producto1</td>
        <td>15</td>
        <td>20</td>
        <td>Baja</td>
        </tr>

        <tr style="background-color:#ffd8a8;">
        <td>producto2</td>
        <td>8</td>
        <td>20</td>
        <td>Media</td>
        </tr>

        <tr class="table-danger">
        <td>producto3</td>
        <td>3</td>
        <td>20</td>
        <td>Crítica</td>
        </tr>

        <tr class="table-warning">
        <td>producto4</td>
        <td>12</td>
        <td>20</td>
        <td>Baja</td>
        </tr>
        </tbody>
        </table>
        </div>
        </div>

    </body>
</html>