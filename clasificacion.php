<?php
include 'conexion.php';

// --- 1. LÓGICA DE PROCESAMIENTO ---

// Agregar Clasificación
if (isset($_POST['btn_agregar'])) {
    $letra = $_POST['letra_abc']; // Obtiene A, B o C del select
    $descripcion = $_POST['descripcion'];
    $nivel = $_POST['nivel_servicio'];

    $sql = "INSERT INTO CLASIFICACIONES (NOMBRE, DESCRIPCION, NIVEL_SERVICIO) VALUES (?, ?, ?)";
    sqlsrv_query($conn, $sql, array($letra, $descripcion, $nivel));
    header("Location: clasificacion.php");
    exit();
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $sql = "DELETE FROM CLASIFICACIONES WHERE IDCLASIFICACION = ?";
    sqlsrv_query($conn, $sql, array($id));
    header("Location: clasificacion.php");
    exit();
}

// Editar
if (isset($_POST['btn_editar'])) {
    $id = $_POST['id_clasificacion'];
    $letra = $_POST['letra_abc'];
    $descripcion = $_POST['descripcion'];
    $nivel = $_POST['nivel_servicio'];

    $sql = "UPDATE CLASIFICACIONES SET NOMBRE = ?, DESCRIPCION = ?, NIVEL_SERVICIO = ? WHERE IDCLASIFICACION = ?";
    sqlsrv_query($conn, $sql, array($letra, $descripcion, $nivel, $id));
    header("Location: clasificacion.php");
    exit();
}

// --- 2. CONSULTA ---
$query = "SELECT IDCLASIFICACION, NOMBRE, DESCRIPCION, NIVEL_SERVICIO FROM CLASIFICACIONES ORDER BY NOMBRE ASC";
$resultado = sqlsrv_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clasificación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/panel.css">
    <style>
        .badge-abc { 
            width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; 
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
        <div class="card panel p-4 shadow-sm border-0 mb-4">
            <h5 class="text-primary mb-3">Registro de Clasificación</h5>
            <form method="POST" action="clasificacion.php" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Clasificación (Categoría)</label>
                    <select name="letra_abc" class="form-select" required>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Descripción</label>
                    <input type="text" name="descripcion" class="form-control" placeholder="Ej: Productos críticos" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Nivel Servicio</label>
                    <select name="nivel_servicio" class="form-select">
                        <option value="Alto">Alto</option>
                        <option value="Medio">Medio</option>
                        <option value="Bajo">Bajo</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" name="btn_agregar" class="btn btn-success w-100 fw-bold">Agregar clasificación</button>
                </div>
            </form>
        </div>

        <div class="card panel p-4 shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Clasificación</th>
                            <th>Nivel servicio</th>
                            <th>Descripción</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)): 
                            $letra = trim($fila['NOMBRE']); 
                            $clase = 'bg-' . strtolower($letra);
                        ?>
                        <tr>
                            <td><span class="badge-abc <?php echo $clase; ?>"><?php echo $letra; ?></span></td>
                            <td><span class="badge bg-light text-dark border"><?php echo $fila['NIVEL_SERVICIO']; ?></span></td>
                            <td class="text-muted"><?php echo $fila['DESCRIPCION']; ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning fw-bold" 
                                    onclick="abrirModalClasificacion('<?php echo $fila['IDCLASIFICACION']; ?>', '<?php echo $letra; ?>', '<?php echo addslashes($fila['DESCRIPCION']); ?>', '<?php echo $fila['NIVEL_SERVICIO']; ?>')">
                                    Editar
                                </button>
                                <a href="clasificacion.php?eliminar=<?php echo $fila['IDCLASIFICACION']; ?>" 
                                   class="btn btn-sm btn-danger fw-bold" onclick="return confirm('¿Eliminar?')">Eliminar</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditarClas" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="clasificacion.php" class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">Editar Clasificación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_clasificacion" id="edit_id_cl">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Clasificación</label>
                        <select name="letra_abc" id="edit_letra_cl" class="form-select">
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Descripción</label>
                        <input type="text" name="descripcion" id="edit_desc_cl" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nivel de Servicio</label>
                        <select name="nivel_servicio" id="edit_nivel_cl" class="form-select">
                            <option value="Alto">Alto</option>
                            <option value="Medio">Medio</option>
                            <option value="Bajo">Bajo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" name="btn_editar" class="btn btn-warning fw-bold">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function abrirModalClasificacion(id, letra, desc, nivel) {
            document.getElementById('edit_id_cl').value = id;
            document.getElementById('edit_letra_cl').value = letra;
            document.getElementById('edit_desc_cl').value = desc;
            document.getElementById('edit_nivel_cl').value = nivel;
            
            new bootstrap.Modal(document.getElementById('modalEditarClas')).show();
        }
    </script>
</body>
</html>