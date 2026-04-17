<?php 
include 'conexion.php'; 

/**
 * CONSULTA PARA LA TABLA (Con JOIN para el nombre de categoría y LEFT JOIN para stock)
 */
$sql = "SELECT 
            p.IDPRODUCTO,
            p.NOMBRE AS PRODUCTO_NOMBRE,
            c.NOMBRE AS CATEGORIA_NOMBRE,
            p.VALOR_UNITARIO,
            p.ESTATUS,
            p.CLASIFICACION_ABC,
            i.STOCK
        FROM PRODUCTOS p
        INNER JOIN CLASIFICACIONES c 
            ON p.IDCLASIFICACION = c.IDCLASIFICACION
        LEFT JOIN INVENTARIO i 
            ON p.IDPRODUCTO = i.IDPRODUCTO";

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
    <title>Productos</title>
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
            <h5 class="text-primary mb-3">Registro de Nuevo Producto</h5>
            <form action="insertar_producto.php" method="POST">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Nombre del Producto</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Categoría</label>
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
                        <label class="form-label fw-bold">Clasificación ABC</label>
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
                        <label class="form-label fw-bold">Estatus Inicial</label>
                        <select name="estatus" class="form-select">
                            <option value="ACTIVO">ACTIVO</option>
                            <option value="INACTIVO">INACTIVO</option>
                        </select>
                    </div>

                    <div class="col-md-12 text-end mt-3">
                        <button type="submit" class="btn btn-success px-5 fw-bold">Guardar Producto</button>
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
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Valor Unitario</th>
                            <th class="text-center">ABC</th>
                            <th>Stock</th>
                            <th>Estatus</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)): 
                            $abc = trim($row['CLASIFICACION_ABC'] ?? 'C');
                            $id = $row['IDPRODUCTO'];
                            $valor = $row['VALOR_UNITARIO'];
                            $estatus = trim($row['ESTATUS']);
                        ?>
                        <tr>
                            <td><small class="text-muted">#<?php echo $id; ?></small></td>
                            <td>
                                <strong><?php echo $row['PRODUCTO_NOMBRE']; ?></strong>
                            </td>

                            <td>
                                <?php echo $row['CATEGORIA_NOMBRE']; ?>
                            </td>
                            <td>$<?php echo number_format($valor, 2); ?></td>
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
                                <span class="badge <?php echo ($estatus == 'ACTIVO') ? 'bg-success' : 'bg-secondary'; ?>">
                                    <?php echo $estatus; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning fw-bold" 
                                        onclick="prepararEdicion(<?php echo $id; ?>, <?php echo $valor; ?>, '<?php echo $estatus; ?>')">
                                    Editar
                                </button>
                                <button class="btn btn-sm btn-danger fw-bold" 
                                        onclick="eliminarProducto(<?php echo $id; ?>, this)">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formEditar" action="editar_producto.php" method="POST" class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">Editar Producto #<span id="edit_id_label"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Valor Unitario</label>
                        <input type="number" step="0.01" name="valor" id="edit_valor" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Estatus</label>
                        <select name="estatus" id="edit_estatus" class="form-select">
                            <option value="ACTIVO">ACTIVO</option>
                            <option value="INACTIVO">INACTIVO</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Actualizar Datos</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/acciones.js"></script>
</body>
</html>