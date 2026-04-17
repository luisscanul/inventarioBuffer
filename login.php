<?php

include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$usuario = trim($_POST['usuario']);
$password = trim($_POST['password']);

$sql = "SELECT COUNT(*) AS total
        FROM USUARIOS
        WHERE USUARIO = ?
        AND PASSWORD = ?";

$params = array($usuario, $password);

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
die(print_r(sqlsrv_errors(), true));
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if ($row['total'] > 0) {

echo "OK";

} else {

echo "ERROR";

}

}

?>