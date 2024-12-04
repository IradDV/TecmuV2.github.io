<?php
include('../../servidor.php');
$consulta = "SELECT * FROM Usuario";
$resultado = mysqli_query($db, $consulta);

if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['nombre']}</td>
                <td>{$row['correo']}</td>
                                <td>{$row['telefono']}</td>
                                <td>
                                    <span class='edit' onclick='editUser({$row['id']})'>Editar</span> | 
                                    <span class='delete' onclick='deleteUser({$row['id']})'>Eliminar</span>
                                </td>
                            </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No hay usuarios encontrados</td></tr>";
}
