<?php
//cerrar sesion de php 
session_start();
session_destroy();

//comprobacion de que se cerro la sesion
if (session_status() === PHP_SESSION_NONE) {
    echo json_encode('exito');
} else {
    echo json_encode('error al cerrar sesion');
}

?>