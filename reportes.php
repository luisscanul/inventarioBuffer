<?php
include 'conexion.php';

/**
 * Consulta inicial: Mostramos los últimos 10 movimientos por defecto 
 * para que la tabla no aparezca vacía al cargar.
 */
$query_inicial = "SELECT TOP 10
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

$resultado_inicial = sqlsrv_query($conn, $query_inicial);
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
            box-shadow: 0 0 10px rgba(13, 110, 253, 0.2);
        }
        /* Optimización para impresión */
        @media print {
            .no-print, .report-card, .btn, .form-label, .form-control, .form-select, h5.text-primary {
                display: none !important;
            }
            .container { width: 100%; max-width: 100%; }
            .card { border: none !important; shadow: none !important; }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container mt-4 mb-5">
        <div class="card panel p-4 shadow-sm no-print">
            <h5 class="text-primary mb-3">🛠️ Filtros de Reporte</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Fecha inicio</label>
                    <input type="date" id="f_inicio" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Fecha fin</label>
                    <input type="date" id="f_fin" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Producto</label>
                    <select id="f_producto" class="form-select">
                        <option value="">Todos los productos</option>
                        <?php
                        $q_p = "SELECT P.IDPRODUCTO, C.NOMBRE FROM PRODUCTOS P INNER JOIN CLASIFICACIONES C ON P.IDCLASIFICACION = C.IDCLASIFICACION";
                        $res_p = sqlsrv_query($conn, $q_p);
                        while($p = sqlsrv_fetch_array($res_p, SQLSRV_FETCH_ASSOC)) {
                            echo "<option value='".$p['IDPRODUCTO']."'>".$p['NOMBRE']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button class="btn btn-success w-100 fw-bold" onclick="ejecutarFiltro()">Generar</button>
                    <button class="btn btn-danger w-50" onclick="window.print()">Imprimir</button>
                </div>
            </div>
        </div>

        <div class="mt-4 no-print">
            <h5 class="mb-3">Seleccione el Tipo de Reporte</h5>
            <div class="row g-3 justify-content-center">
                <div class="col-md-2"> 
                    <div class="card report-card text-center p-3 active" data-tipo="historial"><h6>Historial</h6></div>
                </div>
                <div class="col-md-2">
                    <div class="card report-card text-center p-3" data-tipo="ventas"><h6>Ventas</h6></div>
                </div>
                <div class="col-md-2">
                    <div class="card report-card text-center p-3" data-tipo="entradas"><h6>Entradas</h6></div>
                </div>
                <div class="col-md-2">
                    <div class="card report-card text-center p-3" data-tipo="stock"><h6>Stock Actual</h6></div>
                </div>
                <div class="col-md-2">
                    <div class="card report-card text-center p-3" data-tipo="alertas"><h6>Alertas</h6></div>
                </div>
            </div>
        </div>

        <div class="card panel mt-4 p-4 shadow-sm">
            <h5 class="mb-4">📊 Vista previa del Reporte: <span id="titulo-reporte" class="text-muted">Historial Reciente</span></h5>
            <div class="table-responsive" id="area-reporte">
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
                        <?php while ($fila = sqlsrv_fetch_array($resultado_inicial, SQLSRV_FETCH_ASSOC)): 
                            $tipo = strtoupper(trim($fila['TIPO_MOVIMIENTO']));
                            $colorNum = ($tipo === 'ENTRADA') ? 'text-primary' : 'text-danger';
                        ?>
                            <tr>
                                <td><strong><?php echo $fila['NOMBRE_PRODUCTO']; ?></strong></td>
                                <td><span class="badge <?php echo ($tipo === 'ENTRADA') ? 'bg-primary' : 'bg-danger'; ?>"><?php echo $tipo; ?></span></td>
                                <td class="fw-bold <?php echo $colorNum; ?>"><?php echo $fila['CANTIDAD']; ?></td>
                                <td><?php echo $fila['FECHA']->format('d/m/Y H:i'); ?></td>
                                <td>👤 <?php echo $fila['NOMBRE_USUARIO']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    let tipoSeleccionado = 'historial';

    function ejecutarFiltro() {
        cargarReporte(tipoSeleccionado);
    }

    function cargarReporte(tipo) {
        tipoSeleccionado = tipo;
        const thead = document.getElementById("thead-reporte");
        const tbody = document.getElementById("tabla-reporte-body");
        const titulo = document.getElementById("titulo-reporte");
        
        // Capturar valores de los filtros
        const inicio = document.getElementById("f_inicio").value;
        const fin = document.getElementById("f_fin").value;
        const producto = document.getElementById("f_producto").value;

        // Cambiar diseño de cabecera según reporte
        if (tipo === 'stock') {
            titulo.innerText = "Stock Actual";
            thead.innerHTML = '<tr><th>Producto</th><th>Estatus</th><th>Stock Actual</th><th>Precio</th></tr>';
        } else if (tipo === 'alertas') {
            titulo.innerText = "Alertas de Inventario";
            thead.innerHTML = '<tr><th>Producto</th><th>Estado Actual</th><th>Stock</th><th>Mensaje</th></tr>';
        } else {
            titulo.innerText = tipo.charAt(0).toUpperCase() + tipo.slice(1);
            thead.innerHTML = '<tr><th>Producto</th><th>Movimiento</th><th>Cantidad</th><th>Fecha</th><th>Usuario</th></tr>';
        }

        tbody.innerHTML = '<tr><td colspan="5" class="text-center">Generando reporte...</td></tr>';

        // Petición AJAX
        fetch(`obtener_reporte.php?tipo=${tipo}&inicio=${inicio}&fin=${fin}&producto=${producto}`)
            .then(response => response.text())
            .then(data => {
                tbody.innerHTML = data;
            })
            .catch(error => {
                console.error('Error:', error);
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error al cargar datos</td></tr>';
            });
    }

    // Manejador de clics en las tarjetas
    document.querySelectorAll('.report-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.report-card').forEach(c => c.classList.remove('active', 'border-primary'));
            this.classList.add('active', 'border-primary');
            
            const tipo = this.getAttribute('data-tipo');
            cargarReporte(tipo);
        });
    });
    </script>
</body>
</html>