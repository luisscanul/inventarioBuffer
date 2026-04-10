<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $clasi  = $_POST['clasificacion'];
    $impor  = $_POST['importancia'];
    $valor  = $_POST['valor'];
    $unidad = $_POST['unidad'];
    $status = $_POST['estatus'];

    // 1. Insertar en tabla PRODUCTOS
$sql = "INSERT INTO PRODUCTOS (VALOR_UNITARIO, ESTATUS, IDCLASIFICACION) VALUES (?, ?, ?)";
$params = array($valor, $estatus, $id_clasificacion);       
    
    $params = array($codigo, $nombre, $clasi, $impor, $valor, $unidad, $status);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt) {
        // Obtenemos el ID generado para crear su registro en INVENTARIO con stock 0
        sqlsrv_next_result($stmt);
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $idNuevo = $row['id'];

        $sqlInv = "INSERT INTO INVENTARIO (IDPRODUCTO, STOCK, STOCK_MINIMO) VALUES (?, 0, 5)";
        sqlsrv_query($conn, $sqlInv, array($idNuevo));

        header("Location: productos.php?msj=ok");
    } else {
        die(print_r(sqlsrv_errors(), true));
    }
}
?>