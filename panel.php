<?php 
// 1. Incluimos la conexión que ya configuraste
include 'conexion.php'; 

// 2. Consultas para los indicadores superiores
// Total de productos
$resTotal = sqlsrv_query($conn, "SELECT COUNT(*) as total FROM PRODUCTOS");
$rowTotal = sqlsrv_fetch_array($resTotal, SQLSRV_FETCH_ASSOC);

// Stock bajo (Ejemplo: menor a 10 unidades)
$resBajo = sqlsrv_query($conn, "SELECT COUNT(*) as bajo FROM INVENTARIO WHERE STOCK < STOCK_MINIMO");
$rowBajo = sqlsrv_fetch_array($resBajo, SQLSRV_FETCH_ASSOC);

// Críticos (Ejemplo: productos con stock menor a 3)
$resCritico = sqlsrv_query($conn, "SELECT COUNT(*) as critico FROM INVENTARIO WHERE STOCK <= 3");
$rowCritico = sqlsrv_fetch_array($resCritico, SQLSRV_FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Panel Principal - Sistema Buff</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/panel.css">
    </head>

    <body>
        <?php include 'header.php'; ?>

        <div class="container mt-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card indicador p-3 shadow-sm">
                        <h5>Total de productos</h5>
                        <h2 class="text-primary"><?php echo $rowTotal['total']; ?></h2>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card indicador p-3 shadow-sm">
                        <h5>Productos con stock bajo</h5>
                        <h2 class="text-warning"><?php echo $rowBajo['bajo']; ?></h2>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card indicador p-3 shadow-sm">
                        <h5>Productos críticos</h5>
                        <h2 class="text-danger"><?php echo $rowCritico['critico']; ?></h2>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-8">
                    <div class="card panel p-3 shadow-sm">
                        <h5>Entradas y salidas</h5>
                        <canvas id="graficaInventario"></canvas>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card panel p-3 shadow-sm">
                        <h5>Alertas del sistema</h5>
                        <ul class="list-group list-group-flush">
                            <?php 
                            $sqlAlertas = "SELECT TOP 5 MENSAJE, NIVEL_ALERTA FROM ALERTAS ORDER BY FECHA DESC";
                            $resAlertas = sqlsrv_query($conn, $sqlAlertas);
                            while($alerta = sqlsrv_fetch_array($resAlertas, SQLSRV_FETCH_ASSOC)): 
                                $clase = ($alerta['NIVEL_ALERTA'] == 'CRITICO') ? 'list-group-item-danger' : 'list-group-item-warning';
                            ?>
                                <li class="list-group-item <?php echo $clase; ?>">
                                    <?php echo $alerta['MENSAJE']; ?>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
            </div>      

            <div class="row mt-4">
                <div class="col">
                    <div class="card panel p-3 shadow-sm">
                        <h5>Últimos movimientos de inventario</h5>
                        <table class="table table-hover mt-3">
                            <thead class="table-dark">
                                <tr>
                                    <th>Producto</th>
                                    <th>Tipo</th>
                                    <th>Cantidad</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Asumiendo que tienes una tabla llamada MOVIMIENTOS
                                $sqlMov = "SELECT TOP 5 p.NOMBRE, m.TIPO, m.CANTIDAD, m.FECHA 
                                           FROM MOVIMIENTOS m 
                                           JOIN PRODUCTOS p ON m.IDPRODUCTO = p.IDPRODUCTO 
                                           ORDER BY m.FECHA DESC";
                                           /* Un producto es crítico si su stock actual es menor o igual a la mitad de su mínimo permitido */
                                // Definimos "Crítico" como cualquier producto con 3 unidades o menos
                                // (O podrías usar el 10% del Stock Mínimo)
                                $resCritico = sqlsrv_query($conn, "SELECT COUNT(*) as critico FROM INVENTARIO WHERE STOCK <= 3");
                                $resMov = sqlsrv_query($conn, $sqlMov);
                                if($resMov):
                                    while($mov = sqlsrv_fetch_array($resMov, SQLSRV_FETCH_ASSOC)): ?>
                                        <tr>
                                            <td><?php echo $mov['NOMBRE']; ?></td>
                                            <td>
                                                <span class="badge <?php echo ($mov['TIPO'] == 'Entrada') ? 'bg-success' : 'bg-danger'; ?>">
                                                    <?php echo $mov['TIPO']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo $mov['CANTIDAD']; ?></td>
                                            <td><?php echo $mov['FECHA']->format('d/m/Y'); ?></td>
                                        </tr>
                                    <?php endwhile; 
                                endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="js/panel.js"></script>
    </body>
</html>