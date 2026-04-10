<?php 
include 'conexion.php'; 

/**
 * CONSULTA PARA LA TABLA (Con JOIN para el nombre)
 */
$sql = "SELECT 
            p.IDPRODUCTO, 
            c.NOMBRE AS CATEGORIA_NOMBRE, 
            p.VALOR_UNITARIO, 
            p.ESTATUS, 
            p.CLASIFICACION_ABC, 
            i.STOCK 
        FROM PRODUCTOS p 
        INNER JOIN CLASIFICACIONES c ON p.IDCLASIFICACION = c.IDCLASIFICACION
        LEFT JOIN INVENTARIO i ON p.IDPRODUCTO = i.IDPRODUCTO";

$res = sqlsrv_query($conn, $sql);

/**
 * CONSULTA PARA EL SELECT DEL FORMULARIO
 */
$sql_cat = "SELECT IDCLASIFICACION, NOMBRE FROM CLASIFICACIONES";
$res_cat = sqlsrv_query($conn, $sql_cat);

if ($res === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos - Sistema Buff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/panel.css">
    <style>
        .badge-abc { font-weight: bold; padding: 5px 12px; border-radius: 6px; display: inline-block; min-width: 35px; text-align: center; }
        .abc-A { background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; }
        .abc-B { background-color: #fff3cd; color: #664d03; border: 1px solid #ffecb5; }
        .abc-C { background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container mt-4">
        <div class="card panel p-4 shadow-sm border-0">
            <h5 class="text-primary mb-3">📦 Registro de Producto</h5>
            <form action="insertar_producto.php" method="POST">
                <div class="row g-3">
                    
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Nombre o Categoría</label>
                        <select name="id_clasificacion" class="form-select" required>
                            <option value="" selected disabled>Seleccione una categoría...</option>
                            <?php while($cat = sqlsrv_fetch_array($res_cat, SQLSRV_FETCH_ASSOC)): ?>
                                <option value="<?php echo $cat['IDCLASIFICACION']; ?>">
                                    <?php echo $cat['NOMBRE']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold">Clasificación</label>
                        <select name="clasificacion_abc" class="form-select">
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C" selected>C</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold">Valor Unitario</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" name="valor" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Estatus</label>
                        <select name="estatus" class="form-select">
                            <option value="ACTIVO">ACTIVO</option>
                            <option value="INACTIVO">INACTIVO</option>
                        </select>
                    </div>

                    <div class="col-md-12 text-end mt-3">
                        <button type="submit" class="btn btn-success px-5 fw-bold">Agregar Producto</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card panel mt-4 p-4 shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre (Categoría)</th>
                            <th>Valor Unitario</th>
                            <th class="text-center">Clasificacion</th>
                            <th>Stock</th>
                            <th>Estatus</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)): 
                            $abc = trim($row['CLASIFICACION_ABC'] ?? 'C');
                        ?>
                        <tr>
                            <td><small class="text-muted">#<?php echo $row['IDPRODUCTO']; ?></small></td>
                            <td><strong><?php echo $row['CATEGORIA_NOMBRE']; ?></strong></td>
                            <td>$<?php echo number_format($row['VALOR_UNITARIO'], 2); ?></td>
                            <td class="text-center">
                                <span class="badge-abc abc-<?php echo $abc; ?>">
                                    <?php echo $abc; ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <?php echo $row['STOCK'] ?? 0; ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?php echo (trim($row['ESTATUS']) == 'ACTIVO') ? 'bg-success' : 'bg-secondary'; ?>">
                                    <?php echo $row['ESTATUS']; ?>
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