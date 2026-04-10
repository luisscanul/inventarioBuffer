<?php
include 'conexion.php';

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'historial';

switch ($tipo) {
    case 'stock':
        // Reporte de Inventario Completo
        $query = "SELECT C.NOMBRE, C.DESCRIPCION, I.STOCK, I.STOCK_MINIMO, P.VALOR_UNITARIO 
                  FROM INVENTARIO I
                  INNER JOIN PRODUCTOS P ON I.IDPRODUCTO = P.IDPRODUCTO
                  INNER JOIN CLASIFICACIONES C ON P.IDCLASIFICACION = C.IDCLASIFICACION";
        break;

    case 'alertas':
        // Solo productos por debajo del mínimo
        $query = "SELECT C.NOMBRE, I.STOCK, I.STOCK_MINIMO, 
                  CASE WHEN I.STOCK <= 0 THEN 'CRÍTICA' WHEN I.STOCK <= (I.STOCK_MINIMO/2) THEN 'MEDIA' ELSE 'BAJA' END AS NIVEL
                  FROM INVENTARIO I
                  INNER JOIN PRODUCTOS P ON I.IDPRODUCTO = P.IDPRODUCTO
                  INNER JOIN CLASIFICACIONES C ON P.IDCLASIFICACION = C.IDCLASIFICACION
                  WHERE I.STOCK <= I.STOCK_MINIMO";
        break;

    default:
        // Entradas, Salidas o Historial (basados en Movimientos)
        $where = "";
        if ($tipo == 'entradas') $where = "WHERE M.TIPO_MOVIMIENTO = 'ENTRADA'";
        if ($tipo == 'ventas') $where = "WHERE M.TIPO_MOVIMIENTO = 'SALIDA'";
        
        $query = "SELECT C.NOMBRE, M.TIPO_MOVIMIENTO, M.CANTIDAD, M.FECHA, U.NOMBRE AS USUARIO 
                  FROM MOVIMIENTOS M
                  INNER JOIN PRODUCTOS P ON M.IDPRODUCTO = P.IDPRODUCTO
                  INNER JOIN CLASIFICACIONES C ON P.IDCLASIFICACION = C.IDCLASIFICACION
                  INNER JOIN USUARIOS U ON M.IDUSUARIO = U.IDUSUARIO
                  $where ORDER BY M.FECHA DESC";
        break;
}

$res = sqlsrv_query($conn, $query);

// Renderizado dinámico según el tipo
while ($f = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) {
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
                <td>".$f['FECHA']->format('d/m/Y')."</td>
                <td>👤 {$f['USUARIO']}</td>
              </tr>";
    }
}
?>