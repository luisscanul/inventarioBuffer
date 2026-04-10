<?php
// 1. Conexión (Usando tus datos)
$serverName = "localhost, 1433"; 
$connectionInfo = array("Database" => "Buff", "CharacterSet" => "UTF-8");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if (!$conn) { die(print_r(sqlsrv_errors(), true)); }

// 2. Obtener nombres exactos de todas las tablas
$sql_tablas = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE'";
$res_tablas = sqlsrv_query($conn, $sql_tablas);

echo "<h1>Estructura y Datos de la Base de Datos: Buff</h1>";

while ($tabla = sqlsrv_fetch_array($res_tablas, SQLSRV_FETCH_ASSOC)) {
    $nombreTabla = $tabla['TABLE_NAME'];
    
    echo "<hr><h2>📋 Tabla: $nombreTabla</h2>";

    // --- SECCIÓN A: NOMBRES DE CAMPOS (ESTRUCTURA) ---
    echo "<strong>Campos exactos:</strong> ";
    $sql_columnas = "SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$nombreTabla'";
    $res_col = sqlsrv_query($conn, $sql_columnas);
    
    $nombres_campos = [];
    while ($col = sqlsrv_fetch_array($res_col, SQLSRV_FETCH_ASSOC)) {
        $nombres_campos[] = $col['COLUMN_NAME'] . " (" . $col['DATA_TYPE'] . ")";
    }
    echo "<code>" . implode(" | ", $nombres_campos) . "</code>";

    // --- SECCIÓN B: REGISTROS (CONTENIDO) ---
    echo "<table border='1' style='width:100%; border-collapse: collapse; margin-top:10px;'>";
    
    $sql_datos = "SELECT * FROM $nombreTabla";
    $res_datos = sqlsrv_query($conn, $sql_datos);

    // Cabecera de la tabla con nombres de campos
    echo "<tr style='background: #eee;'>";
    foreach (sqlsrv_field_metadata($res_datos) as $field) {
        echo "<th>" . $field['Name'] . "</th>";
    }
    echo "</tr>";

    // Filas de datos
    while ($fila = sqlsrv_fetch_array($res_datos, SQLSRV_FETCH_ASSOC)) {
        echo "<tr>";
        foreach ($fila as $valor) {
            if ($valor instanceof DateTime) $valor = $valor->format('Y-m-d');
            echo "<td>" . ($valor ?? 'NULL') . "</td>";
        }
        echo "</tr>";
    }
    echo "</table><br>";
}

sqlsrv_close($conn);
?>