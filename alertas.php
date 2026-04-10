<?php
include 'conexion.php'; 

// Consulta que filtra solo los productos por debajo del stock mínimo
// Lógica de Alerta:
// Si stock es 0 -> CRÍTICA
// Si stock es <= 50% del mínimo -> MEDIA
// Si stock es < mínimo -> BAJA
$query = "SELECT 
            C.NOMBRE AS NOMBRE_PRODUCTO,
            I.STOCK,
            I.STOCK_MINIMO,
            CASE 
                WHEN I.STOCK <= 0 THEN 'CRÍTICA'
                WHEN I.STOCK <= (I.STOCK_MINIMO / 2) THEN 'MEDIA'
                ELSE 'BAJA'
            END AS NIVEL_ALERTA
          FROM INVENTARIO I
          INNER JOIN PRODUCTOS P ON I.IDPRODUCTO = P.IDPRODUCTO
          INNER JOIN CLASIFICACIONES C ON P.IDCLASIFICACION = C.IDCLASIFICACION
          WHERE I.STOCK <= I.STOCK_MINIMO
          ORDER BY I.STOCK ASC";

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
    <title>Alertas de Inventario - Buff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/panel.css">
    <style>
        /* Estilos específicos para niveles de alerta */
        .alerta-critica { background-color: #f8d7da !important; color: #842029; }
        .alerta-media { background-color: #fff3cd !important; color: #664d03; }
        .alerta-baja { background-color: #e2e3e5 !important; color: #41464b; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container mt-4">
        <div class="card panel p-4 shadow-sm border-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-danger">Alertas de Inventario Recrítico</h5>
                <span class="badge bg-danger rounded-pill">Acción requerida</span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mt-3 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Producto</th>
                            <th>Nivel Actual</th>
                            <th>Mínimo Requerido</th>
                            <th>Estado / Nivel de Alerta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $hayAlertas = false;
                        while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)): 
                            $hayAlertas = true;
                            $nivel = $fila['NIVEL_ALERTA'];
                            
                            // Asignación de clases CSS según el nivel
                            $claseFila = '';
                            $badgeColor = '';
                            
                            if ($nivel == 'CRÍTICA') {
                                $claseFila = 'table-danger';
                                $badgeColor = 'bg-danger';
                            } elseif ($nivel == 'MEDIA') {
                                $claseFila = 'alerta-media'; // Usando el color naranja suave que pediste
                                $badgeColor = 'bg-warning text-dark';
                            } else {
                                $claseFila = 'table-warning';
                                $badgeColor = 'bg-secondary';
                            }
                        ?>
                            <tr class="<?php echo $claseFila; ?>">
                                <td class="fw-bold"><?php echo $fila['NOMBRE_PRODUCTO']; ?></td>
                                <td>
                                    <span class="fs-5"><?php echo $fila['STOCK']; ?></span> 
                                    <small class="text-muted"></small>
                                </td>
                                <td><?php echo $fila['STOCK_MINIMO']; ?></td>
                                <td>
                                    <span class="badge <?php echo $badgeColor; ?> p-2 px-3 w-100">
                                        <?php echo $nivel; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; 

                        if (!$hayAlertas): ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-success fw-bold">
                                    Todo el inventario está por encima del nivel mínimo.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>