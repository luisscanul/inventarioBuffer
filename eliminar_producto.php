<?php
include 'conexion.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Iniciamos una transacción para asegurar que se borre todo o nada
    if (sqlsrv_begin_transaction($conn) === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    try {
        // 1. Borrar de ALERTAS (si existen)
        $sql1 = "DELETE FROM ALERTAS WHERE IDPRODUCTO = ?";
        sqlsrv_query($conn, $sql1, array($id));

        // 2. Borrar de HISTORIAL
        $sql2 = "DELETE FROM HISTORIAL WHERE IDPRODUCTO = ?";
        sqlsrv_query($conn, $sql2, array($id));

        // 3. Borrar de MOVIMIENTOS
        $sql3 = "DELETE FROM MOVIMIENTOS WHERE IDPRODUCTO = ?";
        sqlsrv_query($conn, $sql3, array($id));

        // 4. Borrar de INVENTARIO
        $sql4 = "DELETE FROM INVENTARIO WHERE IDPRODUCTO = ?";
        sqlsrv_query($conn, $sql4, array($id));

        // 5. Finalmente, borrar el PRODUCTO
        $sql5 = "DELETE FROM PRODUCTOS WHERE IDPRODUCTO = ?";
        $res = sqlsrv_query($conn, $sql5, array($id));

        if ($res) {
            sqlsrv_commit($conn);
            echo "ok";
        } else {
            sqlsrv_rollback($conn);
            echo "error_db";
        }

    } catch (Exception $e) {
        sqlsrv_rollback($conn);
        echo "error_exception";
    }
} else {
    echo "no_id";
}
?>