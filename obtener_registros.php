<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$conexion = new mysqli("10.110.6.148", "BaseDatos", "sysadm1n2207", "mantenimientos");

if ($conexion->connect_error) {
    echo json_encode([
        'success' => false,
        'error' => '❌ Error de conexión: ' . $conexion->connect_error
    ]);
    exit;
}

$sql = "SELECT * FROM registros ORDER BY fecha_registro DESC";
$resultado = $conexion->query($sql);

$registros = [];

if ($resultado->num_rows > 0) {
   while ($fila = $resultado->fetch_assoc()) {
    // Renombrar claves para que coincidan con el frontend
    $fila['tipoMantenimiento'] = $fila['tipo_mantenimiento'];
    $fila['centroCosto'] = $fila['centro_costo'];
    $fila['usuarioRegistro'] = $fila['usuario_registro'];
   $fila['urlTicket'] = $fila['url_ticket'] ?? '';



    // Eliminar claves originales con guion bajo
    unset(
        $fila['tipo_mantenimiento'],
        $fila['centro_costo'],
        $fila['usuario_registro'],
        $fila['url_ticket']
    );

    $registros[] = $fila;
}

}

echo json_encode($registros);

$conexion->close();
?>
