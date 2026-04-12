<?php
include 'conexion.php'; 

// 1. Consulta para el historial
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

// 2. Consulta para llenar el selector de productos
$sql_prod = "SELECT P.IDPRODUCTO, C.NOMBRE FROM PRODUCTOS P 
             INNER JOIN CLASIFICACIONES C ON P.IDCLASIFICACION = C.IDCLASIFICACION 
             WHERE P.ESTATUS = 'ACTIVO'";
$res_prod = sqlsrv_query($conn, $sql_prod);

if ($resultado === false) { die(print_r(sqlsrv_errors(), true)); }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Movimientos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/panel.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container mt-4 mb-5">
        <div class="card panel p-4 shadow-sm">
            <h5 class="text-primary mb-3">Registro de Movimiento</h5>
            <form action="registrar_movimiento.php" method="POST">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Producto</label>
                        <select name="id_producto" class="form-select" required>
                            <option value="" disabled selected>Seleccione producto...</option>
                            <?php while($p = sqlsrv_fetch_array($res_prod, SQLSRV_FETCH_ASSOC)): ?>
                                <option value="<?php echo $p['IDPRODUCTO']; ?>"><?php echo $p['NOMBRE']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" class="form-select" required>
                            <option value="Entrada">Entrada</option>
                            <option value="Salida">Salida</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Cantidad</label>
                        <input type="number" name="cantidad" class="form-control" min="1" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">ID Usuario</label>
                        <input type="number" name="id_usuario" class="form-control" required placeholder="Tu ID">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Registrar</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card panel mt-4 p-4 shadow-sm">
            <h5 class="mb-4">Historial de Movimientos</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Producto</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Fecha</th>
                            <th>Responsable</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)): 
                            $tipo = strtoupper(trim($fila['TIPO_MOVIMIENTO']));
                            $claseTexto = ($tipo === 'ENTRADA') ? 'text-primary' : 'text-danger';
                            $signo = ($tipo === 'ENTRADA') ? '+' : '-';
                            $badgeColor = ($tipo === 'ENTRADA') ? 'bg-primary' : 'bg-danger';
                        ?>
                        <tr>
                            <td><strong><?php echo $fila['NOMBRE_PRODUCTO']; ?></strong></td>
                            <td><span class="badge <?php echo $badgeColor; ?> px-3"><?php echo $tipo; ?></span></td>
                            <td class="fw-bold <?php echo $claseTexto; ?>"><?php echo $signo . ' ' . $fila['CANTIDAD']; ?></td>
                            <td><?php echo $fila['FECHA']->format('d/m/Y H:i'); ?></td>
                            <td>👤<?php echo $fila['NOMBRE_USUARIO']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>