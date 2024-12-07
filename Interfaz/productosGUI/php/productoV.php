<?php
header('Content-Type: application/json');
include ('../../../servidor.php');

$data = json_decode(file_get_contents('php://input'), true);
$filtro_categoria = isset($data['filtro_categoria']) ? $data['filtro_categoria'] : '';
$filtro_precio = isset($data['filtro_precio']) ? $data['filtro_precio'] : '';

if ($filtro_precio != '') {
    list($filtro_precio_1, $filtro_precio_2) = explode('-', $filtro_precio);
} else {
    $filtro_precio_1 = $filtro_precio_2 = '';
}

$consulta = "SELECT * FROM producto WHERE 1=1";
$params = [];
$types = "";

if ($filtro_categoria != '') {
    $consulta .= " AND tipo = ?";
    $types .= "i";
    $params[] = $filtro_categoria;
}

if ($filtro_precio != '') {
    $consulta .= " AND precio BETWEEN ? AND ?";
    $types .= "dd";
    $params[] = $filtro_precio_1;
    $params[] = $filtro_precio_2;
}
$stmt = $db->prepare($consulta);

if ($stmt) {
    if (!empty($types)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $resultado = $stmt->get_result();

    $productos = [];

    while ($fila = $resultado->fetch_assoc()) {
        //verifica si el producto esta disponible
        if ($fila['visible'] == 1) {
            continue;
        }
        $fila['imagen'] = base64_encode($fila['imagen']); // Encode image to base64
        $productos[] = $fila;
    }

    echo json_encode($productos);

    $stmt->close();
} else {
    echo json_encode(['error' => $db->error]);
}

$db->close();
?>
