<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Recoger datos del formulario
    $id_clasificacion = $_POST['id_clasificacion'];
    $clasificacion_abc = $_POST['clasificacion_abc'];
    $valor = $_POST['valor'];
    $estatus = $_POST['estatus'];

    // 2. Iniciar una transacción (Opcional pero recomendado)
    // Usamos transacción porque insertaremos en dos tablas: PRODUCTOS e INVENTARIO
    if (sqlsrv_begin_transaction($conn) === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // 3. Insertar en la tabla PRODUCTOS
    // Nota: SQL Server suele tener el IDPRODUCTO como IDENTITY (autoincrementable)
    $sql_prod = "INSERT INTO PRODUCTOS (IDCLASIFICACION, VALOR_UNITARIO, ESTATUS, CLASIFICACION_ABC) 
                 VALUES (?, ?, ?, ?); SELECT SCOPE_IDENTITY() AS id";
    
    $params_prod = array($id_clasificacion, $valor, $estatus, $clasificacion_abc);
    $stmt_prod = sqlsrv_query($conn, $sql_prod, $params_prod);

    if ($stmt_prod) {
        // Obtenemos el ID del producto que se acaba de insertar
        sqlsrv_next_result($stmt_prod); 
        $row = sqlsrv_fetch_array($stmt_prod, SQLSRV_FETCH_ASSOC);
        $nuevo_id = $row['id'];

        // 4. Insertar en la tabla INVENTARIO (Inicializar stock en 0)
        // Según tus capturas, INVENTARIO necesita IDPRODUCTO, STOCK y STOCK_MINIMO
        $sql_inv = "INSERT INTO INVENTARIO (IDPRODUCTO, STOCK, STOCK_MINIMO) VALUES (?, ?, ?)";
        $params_inv = array($nuevo_id, 0, 10); // 10 como stock mínimo por defecto
        $stmt_inv = sqlsrv_query($conn, $sql_inv, $params_inv);

        if ($stmt_inv) {
            sqlsrv_commit($conn); // Todo salió bien, guardamos cambios
            header("Location: productos.php?msj=ok"); // Redirigir de vuelta
        } else {
            sqlsrv_rollback($conn); // Algo falló en inventario, deshacemos todo
            die(print_r(sqlsrv_errors(), true));
        }
    } else {
        sqlsrv_rollback($conn); // Falló la inserción del producto
        die(print_r(sqlsrv_errors(), true));
    }
}
?>