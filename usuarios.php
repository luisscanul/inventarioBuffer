<?php
include 'conexion.php';

// Consulta con los nombres exactos de tu tabla
$query = "SELECT IDUSUARIO, NOMBRE, ROL, CORREO, TELEFONO FROM USUARIOS ORDER BY NOMBRE ASC";
$resultado = sqlsrv_query($conn, $query);

if ($resultado === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Buff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/panel.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container mt-4">
        <div class="card panel p-4 shadow-sm">
            <h5 class="text-primary mb-3">👤 Registro de Nuevo Usuario</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Nombre</label>
                    <input type="text" class="form-control" placeholder="Nombre real">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Correo Electrónico</label>
                    <input type="email" class="form-control" placeholder="usuario@buff.com">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Teléfono</label>
                    <input type="text" class="form-control" placeholder="555-01XX">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Rol</label>
                    <select class="form-select">
                        <option>Planificador Senior</option>
                        <option>Gerente Logística</option>
                        <option>Comprador</option>
                        <option>Analista de Datos</option>
                        <option>Experto Almacén</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-success w-100 fw-bold">Crear usuario</button>
                </div>
            </div>
        </div>

        <div class="card panel mt-4 p-4 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Nombre</th>
                            <th>Correo / Login</th>
                            <th>Rol</th>
                            <th>Teléfono</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center me-2" style="width: 35px; height: 35px; font-size: 0.8rem; flex-shrink: 0;">
                                        <?php echo substr($fila['NOMBRE'], 0, 1); ?>
                                    </div>
                                    <strong><?php echo $fila['NOMBRE']; ?></strong>
                                </div>
                            </td>
                            <td><code><?php echo $fila['CORREO']; ?></code></td>
                            <td>
                                <span class="badge border text-dark bg-light">
                                    <?php echo $fila['ROL']; ?>
                                </span>
                            </td>
                            <td><small>📞 <?php echo $fila['TELEFONO']; ?></small></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning">Editar</button>
                                <button class="btn btn-sm btn-danger">Eliminar</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>