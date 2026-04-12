<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $valor = $_POST['valor'];
    $estatus = $_POST['estatus'];

    $sql = "UPDATE PRODUCTOS SET VALOR_UNITARIO = ?, ESTATUS = ? WHERE IDPRODUCTO = ?";
    $params = array($valor, $estatus, $id);
    
    if (sqlsrv_query($conn, $sql, $params)) {
        header("Location: productos.php?msj=editado");
    } else {
        die(print_r(sqlsrv_errors(), true));
    }
}
?>