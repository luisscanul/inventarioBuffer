<?php
include 'conexion.php';

// Capturamos los datos del GET que envía el Fetch de reportes.php
$tipo = $_GET['tipo'] ?? 'historial';
$inicio = $_GET['inicio'] ?? '';
$fin = $_GET['fin'] ?? '';
$producto = $_GET['producto'] ?? '';

$params = array();
$where_filtros = "";

// 1. Construir filtros dinámicos (solo si el usuario los llenó)
if (!empty($producto)) {
    // Nota: Usamos alias 'P' o 'M' dependiendo de la tabla en el switch
    $where_filtros .= " AND P.IDPRODUCTO = ? ";
    $params[] = $producto;
}

if (!empty($inicio) && !empty($fin)) {
    $where_filtros .= " AND M.FECHA BETWEEN ? AND ? ";
    $params[] = $inicio . " 00:00:00";
    $params[] = $fin . " 23:59:59";
}

// 2. Definir la consulta según el tipo de reporte
switch ($tipo) {
    case 'stock':
        // En Stock y Alertas no filtramos por fecha (porque es una foto del momento actual)
        $where_stock = !empty($producto) ? " WHERE P.IDPRODUCTO = ?" : "";
        $p_stock = !empty($producto) ? array($producto) : array();
        
        $query = "SELECT C.NOMBRE, C.DESCRIPCION, I.STOCK, I.STOCK_MINIMO, P.VALOR_UNITARIO 
                  FROM INVENTARIO I
                  INNER JOIN PRODUCTOS P ON I.IDPRODUCTO = P.IDPRODUCTO
                  INNER JOIN CLASIFICACIONES C ON P.IDCLASIFICACION = C.IDCLASIFICACION
                  $where_stock";
        $res = sqlsrv_query($conn, $query, $p_stock);
        break;

    case 'alertas':
        $where_alertas = " WHERE I.STOCK <= I.STOCK_MINIMO ";
        $p_alertas = array();
        if (!empty($producto)) {
            $where_alertas .= " AND P.IDPRODUCTO = ? ";
            $p_alertas[] = $producto;
        }

        $query = "SELECT C.NOMBRE, I.STOCK, I.STOCK_MINIMO, 
                  CASE WHEN I.STOCK <= 0 THEN 'CRÍTICA' WHEN I.STOCK <= (I.STOCK_MINIMO/2) THEN 'MEDIA' ELSE 'BAJA' END AS NIVEL
                  FROM INVENTARIO I
                  INNER JOIN PRODUCTOS P ON I.IDPRODUCTO = P.IDPRODUCTO
                  INNER JOIN CLASIFICACIONES C ON P.IDCLASIFICACION = C.IDCLASIFICACION
                  $where_alertas";
        $res = sqlsrv_query($conn, $query, $p_alertas);
        break;

    default: // Ventas, Entradas e Historial
        $condicion_tipo = "";
        if ($tipo == 'entradas') $condicion_tipo = " AND M.TIPO_MOVIMIENTO = 'ENTRADA'";
        if ($tipo == 'ventas') $condicion_tipo = " AND M.TIPO_MOVIMIENTO = 'SALIDA'";
        
        $query = "SELECT C.NOMBRE, M.TIPO_MOVIMIENTO, M.CANTIDAD, M.FECHA, U.NOMBRE AS USUARIO 
                  FROM MOVIMIENTOS M
                  INNER JOIN PRODUCTOS P ON M.IDPRODUCTO = P.IDPRODUCTO
                  INNER JOIN CLASIFICACIONES C ON P.IDCLASIFICACION = C.IDCLASIFICACION
                  INNER JOIN USUARIOS U ON M.IDUSUARIO = U.IDUSUARIO
                  WHERE 1=1 $condicion_tipo $where_filtros 
                  ORDER BY M.FECHA DESC";
        $res = sqlsrv_query($conn, $query, $params);
        break;
}

if ($res === false) {
    die("<tr><td colspan='5'>Error en consulta: ".print_r(sqlsrv_errors(), true)."</td></tr>");
}

// 3. Renderizado de la tabla (Exactamente como lo tenías, está perfecto)
$hay_datos = false;
while ($f = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) {
    $hay_datos = true;
    if ($tipo == 'stock') {
        echo "<tr>
                <td><strong>{$f['NOMBRE']}</strong></td>
                <td>{$f['DESCRIPCION']}</td>
                <td class='fw-bold'>{$f['STOCK']}</td>
                <td>{$f['STOCK_MINIMO']}</td>
                <td>$".number_format($f['VALOR_UNITARIO'], 2)."</td>
              </tr>";
    } elseif ($tipo == 'alertas') {
        $badge = ($f['NIVEL'] == 'CRÍTICA') ? 'bg-danger' : (($f['NIVEL'] == 'MEDIA') ? 'bg-warning text-dark' : 'bg-secondary');
        echo "<tr>
                <td><strong>{$f['NOMBRE']}</strong></td>
                <td><span class='badge bg-light text-dark border'>Actual: {$f['STOCK']}</span></td>
                <td>Min: {$f['STOCK_MINIMO']}</td>
                <td><span class='badge $badge w-100'>{$f['NIVEL']}</span></td>
              </tr>";
    } else {
        $tipo_m = strtoupper(trim($f['TIPO_MOVIMIENTO']));
        $clase = ($tipo_m === 'ENTRADA') ? 'text-primary' : 'text-danger';
        $badge = ($tipo_m === 'ENTRADA') ? 'bg-primary' : 'bg-danger';
        echo "<tr>
                <td><strong>{$f['NOMBRE']}</strong></td>
                <td><span class='badge $badge'>$tipo_m</span></td>
                <td class='fw-bold $clase'>".(($tipo_m == 'ENTRADA') ? '+' : '-')." {$f['CANTIDAD']}</td>
                <td>".$f['FECHA']->format('d/m/Y H:i')."</td>
                <td>👤 {$f['USUARIO']}</td>
              </tr>";
    }
}

if (!$hay_datos) {
    echo "<tr><td colspan='5' class='text-center text-muted'>No se encontraron registros con los filtros seleccionados.</td></tr>";
}
?>