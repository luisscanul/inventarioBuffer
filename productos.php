<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Productos</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/panel.css">
    </head>

    <body>
        <?php include 'header.php'; ?>

        <div class="container mt-4">

        <div class="card panel">
        <h5>Registro de Producto</h5>

        <div class="row g-3 mt-2">

        <div class="col-md-4">
        <label class="form-label">Código de barras</label>
        <input type="text" class="form-control">
        </div>

        <div class="col-md-4">
        <label class="form-label">Nombre o descripción</label>
        <input type="text" class="form-control">
        </div>

        <div class="col-md-2">
        <label class="form-label">Clasificación</label>
        <select class="form-control">
        <option>A</option>
        <option>B</option>
        <option>C</option>
        <option>D</option>
        </select>
        </div>

        <div class="col-md-2">
        <label class="form-label">Importancia</label>
        <input type="text" class="form-control">
        </div>

        <div class="col-md-3">
        <label class="form-label">Valor unitario</label>
        <input type="number" class="form-control">
        </div>

        <div class="col-md-3">
        <label class="form-label">Unidad de medida</label>
        <input type="text" class="form-control">
        </div>

        <div class="col-md-3">
        <label class="form-label">Estatus</label>
        <select class="form-control">
        <option>Activo</option>
        <option>Baja</option>
        </select>
        </div>

        <div class="col-md-3 d-flex align-items-end">
        <button class="btn btn-success w-100">Agregar producto</button>
        </div>
        </div>
        </div>

        <div class="card panel mt-4">
        <div class="row">
        <div class="col-md-4">
        <input type="text" class="form-control" placeholder="Buscar producto">
        </div>

        <div class="col-md-8 text-end">
        <button class="btn btn-success">Agregar</button>
        <button class="btn btn-warning">Editar</button>
        <button class="btn btn-danger">Eliminar</button>
        <button class="btn btn-secondary">Buscar</button>
        </div>

        </div>

        <table class="table table-striped mt-3">

        <thead>
        <tr>
        <th>Código</th>
        <th>Nombre</th>
        <th>Clasificación</th>
        <th>Importancia</th>
        <th>Stock</th>
        <th>Estatus</th>
        <th>Acciones</th>
        </tr>
        </thead>

        <tbody>

        <tr>
        <td>789456123</td>
        <td>Teclado</td>
        <td>A</td>
        <td>Alta</td>
        <td>50</td>
        <td>Activo</td>
        <td>
        <button class="btn btn-sm btn-warning">Editar</button>
        <button class="btn btn-sm btn-danger">Eliminar</button>
        </td>
        </tr>

        <tr>
        <td>321654987</td>
        <td>Mouse</td>
        <td>B</td>
        <td>Media</td>
        <td>30</td>
        <td>Activo</td>
        <td>
        <button class="btn btn-sm btn-warning">Editar</button>
        <button class="btn btn-sm btn-danger">Eliminar</button>
        </td>
        </tr>

        <tr>
        <td>852963741</td>
        <td>Monitor</td>
        <td>A</td>
        <td>Alta</td>
        <td>12</td>
        <td>Activo</td>
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