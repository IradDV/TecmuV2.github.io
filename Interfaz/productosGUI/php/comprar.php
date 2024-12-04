<?php

include ('../../../servidor.php');
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../../login/index.html');
    exit();
}
$id_comprador = $_SESSION['id'];
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id > 0) {
    $consulta = $db->prepare("UPDATE producto SET visible = 1 , comprador = ? WHERE id = ?");
    $consulta->bind_param('ii',$id_comprador ,$id);

    if ($consulta->execute()) {
        echo json_encode('exito');

    } else {
        echo json_encode('error');
    }

    $consulta->close();
} else {
    echo json_encode(['success' => false, 'error' => 'ID inválido']);
}

$db->close();
?>
