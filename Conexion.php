<?php
// 1. Verificación de la Extensión
if (!function_exists('sqlsrv_connect')) {
    die("<h2 style='color:red;'>❌ ERROR: La extensión 'sqlsrv' no está cargada en PHP.</h2>
         <p>Revisa que en tu php.ini tengas: <b>extension=php_sqlsrv_82_ts_x64.dll</b></p>");
}

// 2. Datos de Conexión
$serverName = "localhost, 1433"; 
$connectionInfo = array(
    "Database" => "Buff",
    "CharacterSet" => "UTF-8"
);

// 3. Intento de Conexión
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn) {
    echo "<h2 style='color:green;'>✅ ¡CONEXIÓN EXITOSA!</h2>";
    echo "<p>PHP ya puede leer la base de datos <b>Buff</b>.</p>";

    // 4. Prueba de Lectura (Traer un usuario del sistema)
    $sql = "SELECT TOP 1 NOMBRE, ROL FROM USUARIOS";
    $query = sqlsrv_query($conn, $sql);

    if ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        echo "<div style='border:1px solid #ccc; padding:10px; background:#f9f9f9;'>";
        echo "<strong>Usuario detectado:</strong> " . $row['NOMBRE'] . "<br>";
        echo "<strong>Rol:</strong> " . $row['ROL'];
        echo "</div>";
        echo "<p><i>El sistema está listo para monitorear los 1,800 productos.</i></p>";
    }

} else {
    echo "<h2 style='color:red;'>❌ ERROR DE CONEXIÓN</h2>";
    echo "<pre>";
    print_r(sqlsrv_errors());
    echo "</pre>";
}
?>