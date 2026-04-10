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
?>