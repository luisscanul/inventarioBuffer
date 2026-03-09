<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Usuarios</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/panel.css">
    </head>

    <body>
        <?php include 'header.php'; ?>

        <div class="container mt-4">
        <div class="card panel">
        <h5>Registro de Usuario</h5>
        <div class="row g-3 mt-2">

        <div class="col-md-3">
        <label class="form-label">Nombre</label>
        <input type="text" class="form-control">
        </div>

        <div class="col-md-3">
        <label class="form-label">Usuario</label>
        <input type="text" class="form-control">
        </div>

        <div class="col-md-3">
        <label class="form-label">Contraseña</label>
        <input type="password" class="form-control">
        </div>

        <div class="col-md-3">
        <label class="form-label">Rol</label>
        <select class="form-control">
        <option>Administrador</option>
        <option>Vendedor</option>
        <option>Gerente</option>
        <option>Planificador</option>
        </select>
        </div>

        <div class="col-md-3 d-flex align-items-end">
        <button class="btn btn-success w-100">Crear usuario</button>
        </div>

        </div>
        </div>

        <div class="card panel mt-4">

        <div class="row">
        <div class="col-md-4">
        <input type="text" class="form-control" placeholder="Buscar usuario">
        </div>

        <div class="col-md-8 text-end">
        <button class="btn btn-success">Crear</button>
        <button class="btn btn-warning">Editar</button>
        <button class="btn btn-danger">Eliminar</button>
        </div>
        </div>

        <table class="table table-striped mt-3">

        <thead>
        <tr>
        <th>Nombre</th>
        <th>Usuario</th>
        <th>Rol</th>
        <th>Estatus</th>
        <th>Acciones</th>
        </tr>
        </thead>

        <tbody>

        <tr>
        <td>Juan Pérez</td>
        <td>jperez</td>
        <td>Administrador</td>
        <td>Activo</td>
        <td>
        <button class="btn btn-sm btn-warning">Editar</button>
        <button class="btn btn-sm btn-danger">Eliminar</button>
        </td>
        </tr>

        <tr>
        <td>María López</td>
        <td>mlopez</td>
        <td>Vendedor</td>
        <td>Activo</td>
        <td>
        <button class="btn btn-sm btn-warning">Editar</button>
        <button class="btn btn-sm btn-danger">Eliminar</button>
        </td>
        </tr>

        <tr>
        <td>Carlos Gómez</td>
        <td>cgomez</td>
        <td>Gerente</td>
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