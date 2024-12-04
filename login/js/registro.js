document.addEventListener('DOMContentLoaded', () => {
    const formularioregistro = document.getElementById('fomulario_registro');
    const mensaje = document.getElementById('Mensaje');
    const btn_regresar = document.getElementById('btn_regresar');

    // Evento para enviar el formulario
    formularioregistro.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Obtener valores de los campos
        const nombre = document.getElementById('nombre_completo').value.trim();
        const correo = document.getElementById('correo').value.trim();
        const carrera = document.getElementById('Carrera').value.trim();
        const password = document.getElementById('password').value.trim();

        // Validaciones de campos vacíos
        if (!nombre || !correo || !carrera || !password) {
            mostrarMensaje('Todos los campos son obligatorios');
            return;
        }

        // Validación del formato del correo institucional
        if (!/^\d{8}@leon\.tecnm\.mx$/.test(correo)) {
            mostrarMensaje('El correo debe ser un correo institucional');
            return;
        }

        try {
            // Verificar si el correo ya está registrado
            const correoExiste = await validarCorreo(correo);
            if (!correoExiste) {
                mostrarMensaje('El correo ya está registrado');
                return;
            }

            // Registrar usuario
            const registroExitoso = await registrarUsuario({ nombre, correo, carrera, password });
            if (registroExitoso) {
                mostrarMensaje('Usuario registrado correctamente');
                formularioregistro.reset();
            } else {
                mostrarMensaje('Error al registrar usuario');
            }
        } catch (error) {
            console.error(error);
            mostrarMensaje('Ocurrió un error, intenta nuevamente');
        }
    });

    // Evento para regresar a la página anterior
    btn_regresar.addEventListener('click', () => {
        window.location.href = '../login/index.html';
    });

    // Función para mostrar mensajes en pantalla
    function mostrarMensaje(texto) {
        mensaje.innerHTML = texto;
    }

    // Función para validar si el correo ya existe
    async function validarCorreo(correo) {
        try {
            const response = await fetch('../login/php/validar_correo.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ correo })
            });
    
            const data = await response.json();
            console.log(data);
            switch (data) {
                case 'error':
                    console.error('El correo ya está registrado.');
                    return false;
                case 'exito':
                    console.log('El correo es válido y no está registrado.');
                    return true;
                case 'correo_invalido':
                    console.error('El correo no cumple con el formato institucional.');
                    return false;
                case 'sin_correo':
                    console.error('No se envió el correo al servidor.');
                    return false;
                default:
                    console.error('Respuesta inesperada del servidor.');
                    return false;
            }
        } catch (error) {
            console.error('Error al validar el correo:', error);
            return false;
        }
    }    

    // Función para registrar al usuario
    async function registrarUsuario(usuario) {
        const response = await fetch('../login/php/registro_ususarios.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(usuario)
        });
        const data = await response.json();
        return data === 'exito'; // 'exito' indica que el registro fue exitoso
    }
});
