const btn_ingresar_tecmu = document.getElementById('btn_ingresar_tecmu');
const btn_agregar_producto = document.getElementById('btn_agregar_producto');
const formulario_producto = document.getElementById('Formulario_agregar_producto');
const cerrar_sesion = document.getElementById('Cerrar_Sesion');
const mensaje = document.getElementById('mensaje_res');
const btn_productos = document.getElementById('btn_ver_productos');
document.addEventListener('DOMContentLoaded', () => {
    const div_form_producto = document.getElementById('producto');
    fetch('../usuarioGUI/php/producto.php').
        then(response => response.json()).
        then(data => {
            if (data.error) {
                console.error('Error:', data.error);
                div_form_producto.innerHTML = `<p>Error: ${data.error}</p>`;
            } else {
                div_form_producto.innerHTML = ''; // Clear the container
                data.forEach(producto => {
                    const div = document.createElement('div');
                    div.classList.add('product');

                    const estadoProducto = producto.comprado ? 'Comprado' : 'Disponible';
                    const botonTexto = producto.comprado ? 'QUITAR ANUNCIO' : 'DISPONIBLE';

                    div.innerHTML = `
                    <span class="product__price">$${producto.precio}</span>
                    <img class="product__image" src="data:image/jpeg;base64,${producto.imagen}" alt="${producto.modelo}"/>  
                    <h1 class="product__title">${producto.modelo}</h1>
                    <hr/>
                    <p>${producto.descripcion}</p>
                    <p>Estado: ${estadoProducto}</p>
                    <a class="btn product__btn" data-id="${producto.id}">${botonTexto}</a>
                `;
                    div_form_producto.appendChild(div);
                });
                document.querySelectorAll('.product__btn').forEach(button => {
                    button.addEventListener('click', function (event) {
                        event.preventDefault();
                        const productId = this.getAttribute('data-id');
                        buyProduct(productId);
                    });
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    fetch('../usuarioGUI/php/usuario.php')
        .then(response => response.json())
        .then(data => {
            if (data.sesion === 'exito') {
                const nombre = document.getElementById('h1_bienvenida');
                nombre.innerHTML = `Bienvenido ${data.nom}`;
            } else {
                console.error('Error en la sesión');
                window.location.href = '../../../login/index.html';
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });

    btn_ingresar_tecmu.addEventListener('click', () => {
        console.log('Ingresar a la tienda');
        window.location.href = '../productosGUI/producto.html';
    });
});

formulario_producto.addEventListener('click', () => {
    const form = document.getElementById('Formulario_agregar_producto');
    const boton_submit = form.querySelector('button[type="submit"]');

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        // Deshabilitar el botón de envío
        //  boton_submit.disabled = true;

        const formData = new FormData(form);
        if (formData.get('nombre') === '' || formData.get('precio') === '' || formData.get('cantidad') === '' || formData.get('descripcion') === '' || formData.get('imagen') === '') {
            mensaje.innerHTML = 'Por favor llene todos los campos';
            mensaje.style.display = 'block';
            return;
        }
        fetch('../usuarioGUI/php/agregar_producto.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data === 'error') {
                    mensaje.innerHTML = 'Producto agregado con éxito';
                    return;
                }
                mensaje.innerHTML = 'Producto agregado con éxito';
                form.reset();
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
});

cerrar_sesion.addEventListener('click', () => {
    fetch('../usuarioGUI/php/cerrarSesion.php')
        .then(response => response.json())
        .then(data => {
            if (data === 'exito') {
                window.location.href = '../../login/index.html';
            } else {
                console.error('Error al cerrar sesión');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}
);

function abririnterface(){
    const productos = document.getElementById('productosGUI');
    productos.classList.remove('ocultar');
};
function ocultarinterface(){
    const productos = document.getElementById('productosGUI');
    productos.classList.add('ocultar');
}
// Path: Interfaz/usuarioGUI/js/usuario.js