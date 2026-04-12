<?php
include 'conexion.php'; 

// Recogemos los filtros
$id_producto = isset($_GET['producto']) ? $_GET['producto'] : '';
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

// Base de la consulta y array de parámetros
$params = array();
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
          WHERE 1=1";

// Filtro por Producto
if (!empty($id_producto)) {
    $query .= " AND M.IDPRODUCTO = ?";
    $params[] = $id_producto;
}

// Filtro por Rango de Fechas
if (!empty($fecha_inicio) && !empty($fecha_fin)) {
    $query .= " AND M.FECHA BETWEEN ? AND ?";
    $params[] = $fecha_inicio . " 00:00:00";
    $params[] = $fecha_fin . " 23:59:59";
}

$query .= " ORDER BY M.FECHA DESC";
$resultado = sqlsrv_query($conn, $query, $params);

if ($resultado === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Movimientos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/panel.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container mt-4 mb-5">
        <div class="card panel p-4 shadow-sm border-0">
            <h5 class="text-primary mb-3">🔍 Filtros de Búsqueda Histórica</h5>
            <form method="GET" action="historico.php" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Producto</label>
                    <select name="producto" class="form-select">
                        <option value="">-- Todos los productos --</option>
                        <?php
                        $q_prod = "SELECT P.IDPRODUCTO, C.NOMBRE 
                                   FROM PRODUCTOS P 
                                   INNER JOIN CLASIFICACIONES C ON P.IDCLASIFICACION = C.IDCLASIFICACION
                                   ORDER BY C.NOMBRE ASC";
                        $r_prod = sqlsrv_query($conn, $q_prod);
                        while($p = sqlsrv_fetch_array($r_prod, SQLSRV_FETCH_ASSOC)) {
                            $selected = ($id_producto == $p['IDPRODUCTO']) ? 'selected' : '';
                            echo "<option value='".$p['IDPRODUCTO']."' $selected>".$p['NOMBRE']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Desde</label>
                    <input type="date" name="fecha_inicio" class="form-control" value="<?php echo $fecha_inicio; ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Hasta</label>
                    <input type="date" name="fecha_fin" class="form-control" value="<?php echo $fecha_fin; ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Filtrar</button>
                </div>
            </form>
            
            <?php if(!empty($fecha_inicio)): ?>
            <div class="mt-3">
                <a href="historico.php" class="btn btn-sm btn-outline-secondary">Limpiar filtros</a>
            </div>
            <?php endif; ?>
        </div>

        <div class="card panel mt-4 p-4 shadow-sm border-0">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="m-0">📋 Movimientos Encontrados</h5>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Producto</th>
                            <th>Movimiento</th>
                            <th class="text-center">Cantidad</th>
                            <th>Fecha y Hora</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $contador = 0;
                        while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)): 
                            $contador++;
                            $tipo = strtoupper(trim($fila['TIPO_MOVIMIENTO']));
                            $colorNumero = ($tipo === 'ENTRADA') ? 'text-primary' : 'text-danger';
                            $signo = ($tipo === 'ENTRADA') ? '+' : '-';
                            $badge = ($tipo === 'ENTRADA') ? 'bg-primary' : 'bg-danger';
                        ?>
                            <tr>
                                <td><strong><?php echo $fila['NOMBRE_PRODUCTO']; ?></strong></td>
                                <td><span class="badge <?php echo $badge; ?> px-3"><?php echo $tipo; ?></span></td>
                                <td class="fw-bold <?php echo $colorNumero; ?> text-center" style="font-size: 1.1rem;">
                                    <?php echo $signo . ' ' . $fila['CANTIDAD']; ?>
                                </td>
                                <td><?php echo $fila['FECHA']->format('d/m/Y H:i'); ?></td>
                                <td><small class="text-muted">👤 <?php echo $fila['NOMBRE_USER'] ?? $fila['NOMBRE_USUARIO']; ?></small></td>
                            </tr>
                        <?php endwhile; 
                        
                        if($contador == 0): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No se encontraron movimientos en este periodo.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html> 