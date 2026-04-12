<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto = $_POST['id_producto'];
    $tipo = $_POST['tipo'];
    $cantidad = $_POST['cantidad'];
    $id_usuario = $_POST['id_usuario'];
    $fecha = date('Y-m-d H:i:s');

    // 1. Obtener el stock actual
    $sql_stock = "SELECT STOCK FROM INVENTARIO WHERE IDPRODUCTO = ?";
    $res_stock = sqlsrv_query($conn, $sql_stock, array($id_producto));
    $row_stock = sqlsrv_fetch_array($res_stock, SQLSRV_FETCH_ASSOC);
    $stock_actual = $row_stock['STOCK'] ?? 0;

    // 2. Validar si es salida y hay suficiente stock
    if ($tipo == "Salida" && $cantidad > $stock_actual) {
        die("Error: No hay suficiente stock. Disponible: " . $stock_actual);
    }

    // 3. Calcular nuevo stock
    $nuevo_stock = ($tipo == "Entrada") ? $stock_actual + $cantidad : $stock_actual - $cantidad;

    // Iniciar Transacción
    sqlsrv_begin_transaction($conn);

    // 4. Insertar el movimiento
    $sql_mov = "INSERT INTO MOVIMIENTOS (IDPRODUCTO, IDUSUARIO, TIPO_MOVIMIENTO, CANTIDAD, FECHA) VALUES (?, ?, ?, ?, ?)";
    $stmt1 = sqlsrv_query($conn, $sql_mov, array($id_producto, $id_usuario, $tipo, $cantidad, $fecha));

    // 5. Actualizar el inventario
    $sql_inv = "UPDATE INVENTARIO SET STOCK = ? WHERE IDPRODUCTO = ?";
    $stmt2 = sqlsrv_query($conn, $sql_inv, array($nuevo_stock, $id_producto));

    if ($stmt1 && $stmt2) {
        sqlsrv_commit($conn);
        header("Location: movimientos.php?status=success");
    } else {
        sqlsrv_rollback($conn);
        die(print_r(sqlsrv_errors(), true));
    }
}