<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

$conexion = new mysqli("10.110.6.148", "adminweb", "sysadm1n220793", "mantenimientos");

if ($conexion->connect_error) {
    echo json_encode(['success' => false, 'error' => '❌ Error de conexión: ' . $conexion->connect_error]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["id"])) {
    echo json_encode(['success' => false, 'error' => 'ID no proporcionado']);
    exit;
}

$id = $data["id"];

$stmt = $conexion->prepare("DELETE FROM registros WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'mensaje' => '✅ Registro eliminado']);
} else {
    echo json_encode(['success' => false, 'error' => '❌ Error al eliminar: ' . $stmt->error]);
}

$stmt->close();
$conexion->close();
?>
