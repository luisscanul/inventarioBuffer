<?php
include 'conexion.php'; 

// Recogemos los filtros si existen
$id_producto = isset($_GET['producto']) ? $_GET['producto'] : '';
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

// Base de la consulta
$query = "SELECT 
            C.NOMBRE AS NOMBRE_PRODUCTO,
            M.TIPO_MOVIMIENTO,
            M.CANTIDAD,
            M.FECHA,
            U.NOMBRE AS NOMBRE_USUARIO
          FROM MOVIMIENTOS M
          INNER JOIN PRODUCTOS P ON M.IDPRODUCTO = P.IDPRODUCTO
          INNER JOIN CLASIFICACIONES C ON P.IDCLASIFICACION = C.IDCLASIFICACION
          INNER JOIN USUARIOS U ON M.IDUSUARIO = U.IDUSUARIO
          WHERE 1=1"; // Truco para concatenar filtros fácilmente

// Aplicar filtros dinámicos
if (!empty($id_producto)) {
    $query .= " AND M.IDPRODUCTO = '$id_producto'";
}
if (!empty($fecha_inicio) && !empty($fecha_fin)) {
    $query .= " AND M.FECHA BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59'";
}

$query .= " ORDER BY M.FECHA DESC";
$resultado = sqlsrv_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Histórico - Buff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/panel.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container mt-4">
        <div class="card panel p-4 shadow-sm">
            <h5 class="text-primary mb-3">🔍 Filtros de búsqueda</h5>
            <form method="GET" action="historico.php" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Producto</label>
                    <select name="producto" class="form-select">
                        <option value="">Todos los productos</option>
                        <?php
                        // Llenar el select con productos reales
                        $q_prod = "SELECT P.IDPRODUCTO, C.NOMBRE FROM PRODUCTOS P INNER JOIN CLASIFICACIONES C ON P.IDCLASIFICACION = C.IDCLASIFICACION";
                        $r_prod = sqlsrv_query($conn, $q_prod);
                        while($p = sqlsrv_fetch_array($r_prod, SQLSRV_FETCH_ASSOC)) {
                            $selected = ($id_producto == $p['IDPRODUCTO']) ? 'selected' : '';
                            echo "<option value='".$p['IDPRODUCTO']."' $selected>".$p['NOMBRE']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Fecha inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control" value="<?php echo $fecha_inicio; ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Fecha fin</label>
                    <input type="date" name="fecha_fin" class="form-control" value="<?php echo $fecha_fin; ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </form>
        </div>

        <div class="card panel mt-4 p-4 shadow-sm">
            <h5 class="mb-4">📋 Movimientos Encontrados</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Producto</th>
                            <th>Movimiento</th>
                            <th>Cantidad</th>
                            <th>Fecha</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)): 
                            $tipo = strtoupper(trim($fila['TIPO_MOVIMIENTO']));
                            // Lógica de colores: Azul para ENTRADA, Rojo para SALIDA
                            $colorNumero = ($tipo === 'ENTRADA') ? 'text-primary' : 'text-danger';
                            $signo = ($tipo === 'ENTRADA') ? '+' : '-';
                            $badge = ($tipo === 'ENTRADA') ? 'bg-primary' : 'bg-danger';
                        ?>
                            <tr>
                                <td><strong><?php echo $fila['NOMBRE_PRODUCTO']; ?></strong></td>
                                <td><span class="badge <?php echo $badge; ?>"><?php echo $tipo; ?></span></td>
                                <td class="fw-bold <?php echo $colorNumero; ?>" style="font-size: 1.1rem;">
                                    <?php echo $signo . ' ' . $fila['CANTIDAD']; ?>
                                </td>
                                <td><?php echo $fila['FECHA']->format('d/m/Y'); ?></td>
                                <td><small>👤 <?php echo $fila['NOMBRE_USUARIO']; ?></small></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>