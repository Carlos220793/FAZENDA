<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

$conexion = new mysqli("10.110.6.148", "adminweb", "sysadm1n220793", "mantenimientos");


if ($conexion->connect_error) {
    ob_clean();
    echo json_encode([
        'success' => false,
        'error' => '❌ Error de conexión: ' . $conexion->connect_error
    ]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    ob_clean();
    echo json_encode(['success' => false, 'error' => 'No se recibió ningún dato']);
    exit;
}

// Extraer y limpiar
$tipo = $data["tipo"];
$placa = $data["placa"];
$marca = $data["marca"];
$serial = $data["serial"];
$fecha = $data["fecha"];
$tecnico = $data["tecnico"];
$tipo_mantenimiento = $data["tipoMantenimiento"];
$estado = $data["estado"];
$ubicacion = $data["ubicacion"];
$centro_costo = $data["centroCosto"];
$url_ticket = $data["urlTicket"];
$observaciones = $data["observaciones"];
$fecha_registro = $data["fechaRegistro"];
$usuario_registro = $data["usuarioRegistro"];

$stmt = $conexion->prepare("INSERT INTO registros 
(tipo, placa, marca, serial, fecha, tecnico, tipo_mantenimiento, estado, ubicacion, centro_costo, url_ticket, observaciones, fecha_registro, usuario_registro) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param(
    "ssssssssssssss",
    $tipo, $placa, $marca, $serial, $fecha, $tecnico,
    $tipo_mantenimiento, $estado, $ubicacion, $centro_costo,
    $url_ticket, $observaciones, $fecha_registro, $usuario_registro
);

ob_clean(); // Limpia cualquier salida previa
if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'mensaje' => 'Registro guardado exitosamente',
        'id' => $stmt->insert_id
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Error al guardar: ' . $stmt->error
    ]);
}

$stmt->close();
$conexion->close();
