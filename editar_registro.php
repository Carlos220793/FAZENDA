<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Conexión a la base de datos
$conexion = new mysqli("10.110.6.148", "BaseDatos", "sysadm1n2207", "mantenimientos");

if ($conexion->connect_error) {
    echo json_encode([
        'success' => false,
        'error' => '❌ Error de conexión: ' . $conexion->connect_error
    ]);
    exit;
}

// Leer el contenido JSON enviado por JavaScript
$datos = json_decode(file_get_contents("php://input"), true);

// Validar si se recibió el ID
if (!isset($datos['id'])) {
    echo json_encode([
        'success' => false,
        'error' => 'ID no proporcionado para la edición'
    ]);
    exit;
}

// Preparar y ejecutar la actualización
$stmt = $conexion->prepare("UPDATE registros SET 
    tipo = ?, placa = ?, marca = ?, serial = ?, fecha = ?, tecnico = ?, tipo_mantenimiento = ?, estado = ?, ubicacion = ?, centro_costo = ?, url_ticket = ?, observaciones = ?
    WHERE id = ?");

$stmt->bind_param(
    "ssssssssssssi",
    $datos['tipo'],
    $datos['placa'],
    $datos['marca'],
    $datos['serial'],
    $datos['fecha'],
    $datos['tecnico'],
    $datos['tipoMantenimiento'],
    $datos['estado'],
    $datos['ubicacion'],
    $datos['centroCosto'],
    $datos['urlTicket'],
    $datos['observaciones'],
    $datos['id']
);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'mensaje' => 'Registro actualizado correctamente'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => '❌ Error al actualizar: ' . $stmt->error
    ]);
}

$stmt->close();
$conexion->close();
?>
