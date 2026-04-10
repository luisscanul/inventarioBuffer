<?php
include 'conexion.php'; 

// Consulta con doble JOIN incluyendo DESCRIPCION y NIVEL_SERVICIO
$query = "SELECT 
            P.IDPRODUCTO, 
            C.NOMBRE AS NOMBRE_ESPECIFICO, 
            C.DESCRIPCION AS CATEGORIA, 
            C.NIVEL_SERVICIO,
            P.ESTATUS, 
            P.VALOR_UNITARIO,
            I.STOCK, 
            I.STOCK_MINIMO
          FROM INVENTARIO I
          INNER JOIN PRODUCTOS P ON I.IDPRODUCTO = P.IDPRODUCTO
          INNER JOIN CLASIFICACIONES C ON P.IDCLASIFICACION = C.IDCLASIFICACION";

$resultado = sqlsrv_query($conn, $query);

if ($resultado === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario Buff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/panel.css">
</head>
<body>

        <?php include 'header.php'; ?>
    <div class="container mt-4">
        <div class="card panel p-4 shadow-sm">
            <h4 class="mb-4 text-primary">📦 Gestión de Inventario</h4>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Producto / Nombre</th>
                            <th>Familia / Descripción</th>
                            <th>Prioridad</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Mín.</th>
                            <th>Situación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)): 
                            $claseFila = '';
                            $mensajeStock = 'Óptimo';
                            $badgeColor = 'bg-success';

                            if ($fila['STOCK'] <= 0) {
                                $claseFila = 'table-danger';
                                $mensajeStock = 'Agotado';
                                $badgeColor = 'bg-danger';
                            } elseif ($fila['STOCK'] <= $fila['STOCK_MINIMO']) {
                                $claseFila = 'table-warning';
                                $mensajeStock = 'Reabastecer';
                                $badgeColor = 'bg-warning text-dark';
                            }
                        ?>
                            <tr class="<?php echo $claseFila; ?>">
                                <td><span class="text-muted">#<?php echo $fila['IDPRODUCTO']; ?></span></td>
                                <td><strong><?php echo $fila['NOMBRE_ESPECIFICO']; ?></strong></td>
                                <td><span class="text-secondary"><?php echo $fila['CATEGORIA']; ?></span></td>
                                <td>
                                    <span class="badge border text-dark">
                                        <?php echo $fila['NIVEL_SERVICIO']; ?>
                                    </span>
                                </td>
                                <td>$<?php echo number_format($fila['VALOR_UNITARIO'], 2); ?></td>
                                <td class="fw-bold"><?php echo $fila['STOCK']; ?></td>
                                <td><?php echo $fila['STOCK_MINIMO']; ?></td>
                                <td>
                                    <span class="badge <?php echo $badgeColor; ?>">
                                        <?php echo $mensajeStock; ?>
                                    </span>
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