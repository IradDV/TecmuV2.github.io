const fomulario_ingresar = document.getElementById('formulario_ingresar');
const mensaje = document.getElementById('Mensaje');
const btn_regresar = document.getElementById('btn_regresar');

fomulario_ingresar.addEventListener('submit', (e) => {
    e.preventDefault();
    const correo = document.getElementById('correo').value;
    const password = document.getElementById('password').value;
    if (correo === '' || password === '') {
        mensaje.innerHTML = 'Todos los campos son obligatorios';
        return;
    }
    const usuario = {
        correo: correo,
        password: password
    };
    fetch('../login/php/ingresar.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(usuario)
    }).then(res => res.json())
        .then(data => {
            if (data === 'error') {
                mensaje.innerHTML = 'Usuario o contraseña incorrectos';
                return;
            }
            if (data === 'admin') {
                window.location.href = '../Interfaz/usuarioGUI/admin.php';
                return;
            }
            if (data === 'visitante') {
                window.location.href = '../Interfaz/productosGUI/visitante.html';
                return;
            }
            if (data === 'exito') {
                window.location.href = '../Interfaz/usuarioGUI/usuario.html';
                return;
            }
        })
        .catch(err => console.log(err));
});

btn_regresar.addEventListener('click', () => {
    window.location.href = '../login/index.html';
});
