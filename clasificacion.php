<?php
include 'conexion.php';

/**
 * Consulta que agrupa los productos por su clasificación ABC.
 * El nivel de servicio se calcula automáticamente:
 * A -> Alto, B -> Medio, C -> Bajo
 */
$query = "SELECT 
            CLASIFICACION_ABC, 
            CASE 
                WHEN CLASIFICACION_ABC = 'A' THEN 'Alto'
                WHEN CLASIFICACION_ABC = 'B' THEN 'Medio'
                ELSE 'Bajo'
            END AS NIVEL_SERVICIO,
            COUNT(*) AS TOTAL_PRODUCTOS
          FROM PRODUCTOS 
          GROUP BY CLASIFICACION_ABC 
          ORDER BY CLASIFICACION_ABC ASC";

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
    <title>Clasificación de Productos - Buff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/panel.css">
    <style>
        .badge-abc { 
            width: 40px; height: 40px; 
            display: inline-flex; align-items: center; justify-content: center; 
            font-weight: bold; border-radius: 8px; font-size: 1.2rem;
        }
        .bg-a { background-color: #f8d7da; color: #842029; border: 2px solid #f5c2c7; }
        .bg-b { background-color: #fff3cd; color: #664d03; border: 2px solid #ffecb5; }
        .bg-c { background-color: #d1e7dd; color: #0f5132; border: 2px solid #badbcc; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container mt-4">
        <div class="card panel p-4 shadow-sm">
            <h5 class="text-primary mb-3">Registro de Clasificación</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Clasificación</label>
                    <select class="form-select">
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label fw-bold">Descripción</label>
                    <input type="text" class="form-control" placeholder="Escriba la descripción aquí...">
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-success w-100 fw-bold">Agregar clasificación</button>
                </div>
            </div>
        </div>

        <div class="card panel mt-4 p-4 shadow-sm">
            <div class="row mb-3 align-items-center">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Buscar clasificación">
                </div>
                <div class="col-md-8 text-end">
                    <button class="btn btn-success">Buscar</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Clasificación</th>
                            <th>Nivel servicio</th>
                            <th>Descripción</th>
                            <th>Cant. Items</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)): 
                            $letra = trim($fila['CLASIFICACION_ABC']);
                            $clase = 'bg-' . strtolower($letra);
                            
                            // Asignación de descripción según la letra de clasificación
                            $desc = "";
                            if($letra == 'A') $desc = "Productos críticos para la operación";
                            elseif($letra == 'B') $desc = "Productos importantes con rotación moderada";
                            else $desc = "Productos con baja prioridad";
                        ?>
                        <tr>
                            <td><span class="badge-abc <?php echo $clase; ?>"><?php echo $letra; ?></span></td>
                            <td><strong><?php echo $fila['NIVEL_SERVICIO']; ?></strong></td>
                            <td class="text-muted"><?php echo $desc; ?></td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <?php echo $fila['TOTAL_PRODUCTOS']; ?> relación(es)
                                </span>
                            </td>
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