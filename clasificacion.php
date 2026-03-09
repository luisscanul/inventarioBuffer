<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Clasificación de Productos</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/panel.css">
    </head>

    <body>
        <?php include 'header.php'; ?>

        <div class="container mt-4">
        <div class="card panel">
        <h5>Registro de Clasificación</h5>

        <div class="row g-3 mt-2">

        <div class="col-md-4">
        <label class="form-label">Nombre clasificación</label>
        <input type="text" class="form-control">
        </div>

        <div class="col-md-4">
        <label class="form-label">Nivel de servicio</label>
        <input type="text" class="form-control">
        </div>

        <div class="col-md-4">
        <label class="form-label">Descripción</label>
        <input type="text" class="form-control">
        </div>

        <div class="col-md-3 d-flex align-items-end">
        <button class="btn btn-success w-100">Agregar clasificación</button>
        </div>

        </div>
        </div>

        <div class="card panel mt-4">

        <div class="row">
        <div class="col-md-4">
        <input type="text" class="form-control" placeholder="Buscar clasificación">
        </div>

        <div class="col-md-8 text-end">
        <button class="btn btn-success">Agregar</button>
        <button class="btn btn-warning">Editar</button>
        <button class="btn btn-danger">Eliminar</button>
        </div>
        </div>

        <table class="table table-striped mt-3">

        <thead>
        <tr>
        <th>Clasificación</th>
        <th>Nivel servicio</th>
        <th>Descripción</th>
        <th>Acciones</th>
        </tr>
        </thead>

        <tbody>

        <tr>
        <td>A</td>
        <td>Alto</td>
        <td>Productos críticos para la operación</td>
        <td>
        <button class="btn btn-sm btn-warning">Editar</button>
        <button class="btn btn-sm btn-danger">Eliminar</button>
        </td>
        </tr>

        <tr>
        <td>B</td>
        <td>Medio</td>
        <td>Productos importantes con rotación moderada</td>
        <td>
        <button class="btn btn-sm btn-warning">Editar</button>
        <button class="btn btn-sm btn-danger">Eliminar</button>
        </td>
        </tr>

        <tr>
        <td>C</td>
        <td>Bajo</td>
        <td>Productos con baja prioridad</td>
        <td>
        <button class="btn btn-sm btn-warning">Editar</button>
        <button class="btn btn-sm btn-danger">Eliminar</button>
        </td>
        </tr>

        </tbody>

        </table>
        </div>
        </div>
    </body>
</html>