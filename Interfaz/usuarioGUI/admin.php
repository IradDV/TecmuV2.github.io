<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .edit, .delete, .save {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Tabla de Usuarios</h1>
    <div class="tabla" style="width: 80%; margin: auto 50px;">
        <table class="table table table-borderless">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Correo</th>
                    <th scope="col">Teléfono</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include ('../../servidor.php');
                $consulta = "SELECT * FROM Usuario";
                $resultado = mysqli_query($db, $consulta);

                if ($resultado->num_rows > 0) {
                    while ($row = $resultado->fetch_assoc()) {
                        echo "<tr id='row-{$row['id_usuario']}'>
                                <td>{$row['id_usuario']}</td>
                                <td class='editable' data-column='nombre'>{$row['nombre']}</td>
                                <td class='editable' data-column='Correo'>{$row['Correo']}</td>
                                <td class='editable' data-column='num_telefono'>{$row['num_telefono']}</td>
                                <td>
                                    <span class='edit btn btn-primary' style='color:white;' onclick='editUser({$row['id_usuario']})'>Editar</span> | 
                                    <span class='delete btn btn-primary' style='color:white;' onclick='deleteUser({$row['id_usuario']})'>Eliminar</span>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No hay usuarios encontrados</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function editUser(id) {
            const row = document.getElementById(`row-${id}`);
            const cells = row.querySelectorAll('.editable');
            const actionsCell = row.querySelector('td:last-child');

            // Convertir celdas en campos editables
            cells.forEach(cell => {
                const originalValue = cell.textContent;
                const column = cell.getAttribute('data-column');
                cell.innerHTML = `<input type="text" value="${originalValue}" data-column="${column}">`;
            });

            // Cambiar acciones a "Guardar"
            actionsCell.innerHTML = `<span class='save' onclick='saveUser(${id})'>Guardar</span>`;
        }

        function saveUser(id) {
            const row = document.getElementById(`row-${id}`);
            const inputs = row.querySelectorAll('input');
            const data = { id };

            // Recopilar datos editados
            inputs.forEach(input => {
                const column = input.getAttribute('data-column');
                data[column] = input.value;
            });

            // Enviar datos al servidor
            fetch('../usuarioGUI/php/update_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    // Actualizar las celdas con los nuevos valores
                    inputs.forEach(input => {
                        const cell = input.parentElement;
                        cell.textContent = input.value;
                    });

                    // Restaurar las acciones
                    row.querySelector('td:last-child').innerHTML = `
                        <span class='edit' onclick='editUser(${id})'>Editar</span> |
                        <span class='delete' onclick='deleteUser(${id})'>Eliminar</span>
                    `;
                    alert('Usuario actualizado correctamente');
                } else {
                    alert('Error al actualizar el usuario');
                }
            })
            .catch(error => {
                console.error('Error al guardar el usuario:', error);
            });
        }

        function deleteUser(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
                fetch('../usuarioGUI/php/delete_user.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Usuario eliminado exitosamente');
                        location.reload(); // Recarga la página para actualizar la tabla
                    } else {
                        alert('Error al eliminar el usuario');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>
