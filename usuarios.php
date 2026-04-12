<?php
include 'conexion.php';

// --- LÓGICA DE PROCESAMIENTO (POST Y GET) ---

// 1. Crear Usuario
if (isset($_POST['btn_crear'])) {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $rol = $_POST['rol'];

    $sql = "INSERT INTO USUARIOS (NOMBRE, CORREO, TELEFONO, ROL) VALUES (?, ?, ?, ?)";
    $params = array($nombre, $correo, $telefono, $rol);
    sqlsrv_query($conn, $sql, $params);
    header("Location: usuarios.php"); // Recargar para limpiar el POST
}

// 2. Eliminar Usuario
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $sql = "DELETE FROM USUARIOS WHERE IDUSUARIO = ?";
    sqlsrv_query($conn, $sql, array($id));
    header("Location: usuarios.php");
}

// 3. Editar Usuario (Procesar cambio)  
// Cambia tu bloque de edición por este (con manejo de errores real)
if (isset($_POST['btn_editar'])) {
    $id = $_POST['id_usuario'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $rol = $_POST['rol'];

    // Asegúrate de que los nombres de las columnas sean IDUSUARIO, NOMBRE, etc.
    $sql = "UPDATE USUARIOS SET NOMBRE = ?, CORREO = ?, TELEFONO = ?, ROL = ? WHERE IDUSUARIO = ?";
    $params = array($nombre, $correo, $telefono, $rol, $id);
    
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        // Esto te mostrará el error exacto de SQL Server en pantalla
        die(print_r(sqlsrv_errors(), true)); 
    }

    header("Location: usuarios.php");
    exit(); 
}

// Consulta para listar
$query = "SELECT IDUSUARIO, NOMBRE, ROL, CORREO, TELEFONO FROM USUARIOS ORDER BY NOMBRE ASC";
$resultado = sqlsrv_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/panel.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container mt-4">
        <div class="card panel p-4 shadow-sm border-0">
            <h5 class="text-primary mb-3">👤 Registro de Nuevo Usuario</h5>
            <form method="POST" action="usuarios.php" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Nombre</label>
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre real" required>
                </div>
                <div class="col-md-3">  
                    <label class="form-label fw-bold">Correo Electrónico</label>
                    <input type="email" name="correo" class="form-control" placeholder="usuario@buff.com" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" placeholder="555-01XX">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Rol</label>
                    <select name="rol" class="form-select">
                        <option>Planificador Senior</option>
                        <option>Gerente Logística</option>
                        <option>Comprador</option>
                        <option>Analista de Datos</option>
                        <option>Experto Almacén</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" name="btn_crear" class="btn btn-success w-100 fw-bold">Crear usuario</button>
                </div>
            </form>
        </div>

        <div class="card panel mt-4 p-4 shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Nombre</th>
                            <th>Correo / Login</th>
                            <th>Rol</th>
                            <th>Teléfono</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center me-2" style="width: 35px; height: 35px; font-size: 0.8rem; flex-shrink: 0;">
                                        <?php echo substr($fila['NOMBRE'], 0, 1); ?>
                                    </div>
                                    <strong><?php echo $fila['NOMBRE']; ?></strong>
                                </div>
                            </td>
                            <td><code><?php echo $fila['CORREO']; ?></code></td>
                            <td><span class="badge border text-dark bg-light"><?php echo $fila['ROL']; ?></span></td>
                            <td><small>📞 <?php echo $fila['TELEFONO']; ?></small></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning fw-bold" 
    onclick="abrirModalEditar('<?php echo $fila['IDUSUARIO']; ?>', '<?php echo addslashes($fila['NOMBRE']); ?>', '<?php echo $fila['CORREO']; ?>', '<?php echo $fila['TELEFONO']; ?>', '<?php echo $fila['ROL']; ?>')">
    Editar
</button>
                                
                                <a href="usuarios.php?eliminar=<?php echo $fila['IDUSUARIO']; ?>" 
                                   class="btn btn-sm btn-danger fw-bold" 
                                   onclick="return confirm('¿Estás seguro de eliminar a este usuario?')">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="usuarios.php"> 
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="modalEditarLabel">Actualizar Datos de Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_usuario" id="edit_id">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre Completo</label>
                        <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Correo Electrónico</label>
                        <input type="email" name="correo" id="edit_correo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Teléfono</label>
                        <input type="text" name="telefono" id="edit_telefono" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Rol</label>
                        <select name="rol" id="edit_rol" class="form-select">
                            <option value="Planificador Senior">Planificador Senior</option>
                            <option value="Gerente Logística">Gerente Logística</option>
                            <option value="Comprador">Comprador</option>
                            <option value="Analista de Datos">Analista de Datos</option>
                            <option value="Experto Almacén">Experto Almacén</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" name="btn_editar" class="btn btn-warning fw-bold">Guardar Cambios</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function abrirModalEditar(id, nombre, correo, tel, rol) {
        // Llenamos los campos del formulario
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nombre').value = nombre;
        document.getElementById('edit_correo').value = correo;
        document.getElementById('edit_telefono').value = tel;
        document.getElementById('edit_rol').value = rol;
        
        // 2. Intentamos abrir el modal de forma segura
        try {
            var myModal = new bootstrap.Modal(document.getElementById('modalEditar'));
            myModal.show();
        } catch (error) {
            console.error("Error al abrir el modal:", error);
            // Opción de respaldo si la variable 'bootstrap' sigue dando guerra:
            alert("Error de carga de librería. Revisa la consola.");
        }
    }
</script>
</body>
</html>