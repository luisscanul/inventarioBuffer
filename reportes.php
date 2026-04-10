<?php
include 'conexion.php';

// Consulta base para mostrar información general en la tabla (Historial reciente)
$query = "SELECT TOP 10
            C.NOMBRE AS NOMBRE_PRODUCTO,
            M.TIPO_MOVIMIENTO,
            M.CANTIDAD,
            M.FECHA,
            U.NOMBRE AS NOMBRE_USUARIO
          FROM MOVIMIENTOS M
          INNER JOIN PRODUCTOS P ON M.IDPRODUCTO = P.IDPRODUCTO
          INNER JOIN CLASIFICACIONES C ON P.IDCLASIFICACION = C.IDCLASIFICACION
          INNER JOIN USUARIOS U ON M.IDUSUARIO = U.IDUSUARIO
          ORDER BY M.FECHA DESC";

$resultado = sqlsrv_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes Avanzados - Buff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/panel.css">
    <style>
        .report-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-color: #0d6efd;
            background-color: #f8f9fa;
        }
        .report-card.active {
            background-color: #e7f1ff;
            border-color: #0d6efd;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container mt-4 mb-5">
        <div class="card panel p-4 shadow-sm">
            <h5 class="text-primary mb-3">🛠️ Filtros de Reporte</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Fecha inicio</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha fin</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Producto</label>
                    <select class="form-select">
                        <option>Todos los productos</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button class="btn btn-success w-100">Generar</button>
                    <button class="btn btn-danger w-50">PDF</button>
                    <button class="btn btn-primary w-50">Excel</button>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <h5 class="mb-3">Seleccione el Tipo de Reporte</h5>
            <div class="row g-3">
    <div class="col-md-2 offset-md-1"> <div class="card report-card text-center p-3"><h6>Ventas</h6></div>
    </div>
    <div class="col-md-2">
        <div class="card report-card text-center p-3"><h6>Entradas</h6></div>
    </div>
    <div class="col-md-2">
        <div class="card report-card text-center p-3"><h6>Historial</h6></div>
    </div>
    <div class="col-md-2">
        <div class="card report-card text-center p-3"><h6>Stock Actual</h6></div>
    </div>
    <div class="col-md-2">
        <div class="card report-card text-center p-3"><h6>Alertas</h6></div>
    </div>
</div>
        </div>

        <div class="card panel mt-4 p-4 shadow-sm">
            <h5 class="mb-4">📊 Vista previa del Reporte</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark" id="thead-reporte">
                        <tr>
                            <th>Producto</th>
                            <th>Tipo Movimiento</th>
                            <th>Cantidad</th>
                            <th>Fecha</th>
                            <th>Usuario Responsable</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-reporte-body">
                        <?php while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)): 
                            $tipo = strtoupper(trim($fila['TIPO_MOVIMIENTO']));
                            $colorNum = ($tipo === 'ENTRADA') ? 'text-primary' : 'text-danger';
                            $signo = ($tipo === 'ENTRADA') ? '+' : '-';
                        ?>
                            <tr>
                                <td><strong><?php echo $fila['NOMBRE_PRODUCTO']; ?></strong></td>
                                <td>
                                    <span class="badge <?php echo ($tipo === 'ENTRADA') ? 'bg-primary' : 'bg-danger'; ?>">
                                        <?php echo $tipo; ?>
                                    </span>
                                </td>
                                <td class="fw-bold <?php echo $colorNum; ?>">
                                    <?php echo $signo . ' ' . $fila['CANTIDAD']; ?>
                                </td>
                                <td><?php echo $fila['FECHA']->format('d/m/Y H:i'); ?></td>
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

<script>
function cargarReporte(tipo) {
    const thead = document.getElementById("thead-reporte"); // Cambiado a getElementById
    const tbody = document.getElementById("tabla-reporte-body");
    
    if(!thead || !tbody) return; // Validación de seguridad

    // Cambiar encabezados según el reporte
    if (tipo === 'stock') {
        thead.innerHTML = '<tr><th>Producto</th><th>Descripción</th><th>Stock Actual</th><th>Mínimo</th><th>Precio</th></tr>';
    } else if (tipo === 'alertas') {
        thead.innerHTML = '<tr><th>Producto</th><th>Estado Actual</th><th>Mínimo</th><th>Nivel Alerta</th></tr>';
    } else {
        thead.innerHTML = '<tr><th>Producto</th><th>Movimiento</th><th>Cantidad</th><th>Fecha</th><th>Usuario</th></tr>';
    }

    tbody.innerHTML = '<tr><td colspan="5" class="text-center">Procesando información...</td></tr>';

    // Llamada al archivo que creamos antes
    fetch(`obtener_reporte.php?tipo=${tipo}`)
        .then(r => {
            if (!r.ok) throw new Error('Error en la red');
            return r.text();
        })
        .then(data => { 
            tbody.innerHTML = data; 
        })
        .catch(error => {
            console.error('Error:', error);
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error al cargar los datos</td></tr>';
        });
}

// Configuración de las tarjetas
document.querySelectorAll('.report-card').forEach((card, index) => {
    const tipos = ['ventas', 'entradas', 'historial', 'stock', 'alertas'];
    card.addEventListener('click', () => {
        // Efecto visual de selección
        document.querySelectorAll('.report-card').forEach(c => c.classList.remove('active', 'border-primary'));
        card.classList.add('active', 'border-primary');
        
        // Ejecutar carga
        cargarReporte(tipos[index]);
    });
});
</script>