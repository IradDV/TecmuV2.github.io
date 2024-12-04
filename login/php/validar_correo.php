<?php
include ('../../servidor.php');

// Obtener el contenido JSON del cuerpo de la solicitud
$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);

// Verificar si el índice 'correo' está presente en los datos decodificados
if (isset($data['correo'])) {
    $correo = $data['correo'];

    // Validar que sea un correo institucional
    if (preg_match('/^\d{8}@leon\.tecnm\.mx$/', $correo)) {
        // Consulta para saber si el correo ya existe en la BD
        $consulta = "SELECT Correo FROM usuario WHERE Correo = ?";
        $res = $db->prepare($consulta);
        $res->bind_param('s', $correo);
        $res->execute();
        $res->bind_result($resultado);
        $res->fetch();
        $res->close();

        if ($resultado) {
            echo json_encode('error'); // El correo ya existe
        } else {
            echo json_encode('exito'); // El correo no existe y es válido
        }
    } else {
        echo json_encode('correo_invalido'); // El correo no cumple con el formato esperado
    }
} else {
    echo json_encode('sin_correo'); // El campo 'correo' no fue enviado
}
?>
