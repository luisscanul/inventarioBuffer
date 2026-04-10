<?php
include 'conexion.php'; 

// Consulta para el historial de movimientos
// Unimos Movimientos -> Productos -> Clasificaciones (para el Nombre)
// Y Movimientos -> Usuarios (para el Nombre del usuario)
$query = "SELECT 
            M.IDMOVIMIENTO,
            C.NOMBRE AS NOMBRE_PRODUCTO,
            M.TIPO_MOVIMIENTO,
            M.CANTIDAD,
            M.FECHA,
            U.NOMBRE AS NOMBRE_USUARIO
          FROM MOVIMIENTOS M
          INNER JOIN PRODUCTOS P ON M.IDPRODUCTO = P.IDPRODUCTO
          INNER JOIN CLASIFICACIONES C ON P.IDCLASIFICACION = C.IDCLASIFICACION
          INNER JOIN USUARIOS U ON M.IDUSUARIO = U.IDUSUARIO
          ORDER BY M.FECHA DESC";

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
    <title>Movimientos - Buff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/panel.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container mt-4 mb-5">
        <div class="card panel p-4 shadow-sm">
            <h5 class="text-primary mb-3">📋 Registro de Movimiento</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tipo de movimiento</label>
                    <select class="form-select">
                        <option value="Entrada">Entrada</option>
                        <option value="Salida">Salida</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Cantidad</label>
                    <input type="number" class="form-control" min="1">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha</label>
                    <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">ID Usuario Operador</label>
                    <input type="text" class="form-control">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary w-100 fw-bold">Registrar movimiento</button>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-light w-100 border">Cancelar</button>
                </div>
            </div>
        </div>

        <div class="card panel mt-4 p-4 shadow-sm">
            <h5 class="mb-4">🕒 Historial de Movimientos</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Producto</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Fecha y Hora</th>
                            <th>Responsable</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)): 
        // Convertimos a mayúsculas y quitamos espacios por seguridad
        $tipo = strtoupper(trim($fila['TIPO_MOVIMIENTO']));

        if ($tipo === 'ENTRADA') {
            $claseTexto = 'text-primary'; // Azul para el número
            $signo = '+';
            $badgeColor = 'bg-primary'; 
        } else {
            $claseTexto = 'text-danger';  // Rojo para el número
            $signo = '-';
            $badgeColor = 'bg-danger';
        }
    ?>
        <tr>
            <td><strong><?php echo $fila['NOMBRE_PRODUCTO']; ?></strong></td>
            <td>
                <span class="badge <?php echo $badgeColor; ?> px-3">
                    <?php echo $tipo; ?>
                </span>
            </td>
            <td class="fw-bold <?php echo $claseTexto; ?>" style="font-size: 1.1rem;">
                <?php echo $signo . ' ' . $fila['CANTIDAD']; ?>
            </td>
            <td>
                <?php echo $fila['FECHA']->format('d/m/Y'); ?>
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <span class="me-2">👤</span>
                    <?php echo $fila['NOMBRE_USUARIO']; ?>
                </div>
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