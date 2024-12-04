<?php
include('../../../servidor.php');
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../../login/index.html');
    exit();
}
$id = $_SESSION['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $estado = $_POST['estado'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $modelo = $_POST['nombre'];
    $tipo = $_POST['etiqueta'];

    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == UPLOAD_ERR_OK) {
        $nombre_archivo = $_FILES["imagen"]["name"];
        $tipo_archivo = $_FILES["imagen"]["type"];
        $tamano_archivo = $_FILES["imagen"]["size"];
        $archivo_temporal = $_FILES["imagen"]["tmp_name"];
        $contenido_imagen = file_get_contents($archivo_temporal);

        $descripcion = $db->real_escape_string($descripcion);
        $modelo = $db->real_escape_string($modelo);

        // Check if the product already exists
        $checkQuery = $db->prepare("SELECT COUNT(*) FROM Producto WHERE modelo = ? and id_user = ? and descripcion = ? and precio = ? and imagen = ?");
        $checkQuery->bind_param('sisis', $modelo, $id, $descripcion, $precio, $contenido_imagen);
        $checkQuery->execute();
        $checkQuery->bind_result($count);
        $checkQuery->fetch();
        $checkQuery->close();

        if ($count > 0) {
            echo json_encode('error: producto ya existe');
        } else {
            // Insert new product
            $consulta = $db->prepare("INSERT INTO Producto (estado, descripcion, precio, fecha, modelo, imagen, id_user,tipo,visible) VALUES (?, ?, ?, CURDATE(), ?, ?, ?,?,0)");
            if ($consulta) {
                $consulta->bind_param('ssissii', $estado, $descripcion, $precio, $modelo, $contenido_imagen, $id,$tipo);
                if ($consulta->execute()) {
                    echo json_encode('exito');
                } else {
                    echo json_encode('exito');
                }
                $consulta->close();
            } else {
                echo json_encode('error: ' . $db->error);
            }
        }
    } else {
        echo json_encode('error: no file uploaded or file upload error');
    }
    $db->close();
} else {
    echo json_encode('No se ha enviado el formulario.');
}
?>
