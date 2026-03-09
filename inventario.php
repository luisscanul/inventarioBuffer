<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inventario</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/panel.css">
    </head>

    <body>
        <?php include 'header.php'; ?>

        <div class="container mt-4">

        <div class="card panel">
        <h5>Inventario Actual</h5>

        <table class="table table-striped mt-3">

        <thead>
        <tr>
        <th>Código producto</th>
        <th>Nombre producto</th>
        <th>Stock actual</th>
        <th>Stock mínimo</th>
        <th>Stock máximo</th>
        <th>Ubicación</th>
        <th>Estado del inventario</th>
        </tr>
        </thead>

        <tbody>

        <tr class="table-success">
        <td>789456123</td>
        <td>Producto1</td>
        <td>50</td>
        <td>10</td>
        <td>100</td>
        <td>Almacén A</td>
        <td>Stock normal</td>
        </tr>

        <tr class="table-warning">
        <td>321654987</td>
        <td>Producto2</td>
        <td>12</td>
        <td>10</td>
        <td>80</td>
        <td>Almacén B</td>
        <td>Stock bajo</td>
        </tr>

        <tr class="table-danger">
        <td>852963741</td>
        <td>Producto3</td>
        <td>3</td>
        <td>10</td>
        <td>60</td>
        <td>Almacén C</td>
        <td>Stock crítico</td>
        </tr>

        <tr class="table-success">
        <td>147258369</td>
        <td>Producto4</td>
        <td>40</td>
        <td>10</td>
        <td>90</td>
        <td>Almacén A</td>
        <td>Stock normal</td>
        </tr>
        </tbody>
        </table>
        </div>
        </div>

    </body>
</html>