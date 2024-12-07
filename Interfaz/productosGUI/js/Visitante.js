const cerrar_sesion = document.getElementById('cerrar_sesion');
const productos = document.getElementById('Contenedor_Producto');
const filtro_productos = document.getElementById('filtro_productos');
const inicio = document.getElementById('inicio');
document.addEventListener('DOMContentLoaded', () => {
    function enviarCalificacion(id) {
        const calificacion = document.querySelector('input[name="rating"]:checked').value;
        const comentario = document.getElementById('comentario').value;

        fetch('../productosGUI/php/calificar_producto.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id=${id}&calificacion=${calificacion}&comentario=${comentario}`
        })
            .then(response => response.json())
            .then(data => {
                if (data === 'exito') {
                    alert(`¡Producto calificado con éxito! Gracias por tu opinión.`);
                    document.getElementById('formulario-calificacion').classList.add('ocultar');
                } else {
                    alert('Error al calificar el producto.');
                }
            })
            .catch(error => console.error('Error:', error));
    }
    var filtro_categoria;
    var filtro_precio;
    if (filtro_categoria === undefined) {
        filtro_categoria = '';
    }
    if (filtro_precio === undefined) {
        filtro_precio = '';
    }
    filtro_productos.addEventListener('submit', event => {
        event.preventDefault();
        filtro_categoria = document.getElementById('categoria').value;
        filtro_precio = document.getElementById('precio').value;
        console.log(`Filtrando por categoría: ${filtro_categoria} y precio: ${filtro_precio}`);
        fetch('../productosGUI/php/producto.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                filtro_categoria: filtro_categoria,
                filtro_precio: filtro_precio
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Error:', data.error);
                    productos.innerHTML = `<p>Error: ${data.error}</p>`;
                } else {
                    productos.innerHTML = ''; // Clear the container
                    data.forEach(producto => {
                        const div = document.createElement('div');
                        div.classList.add('product');

                        div.innerHTML = `
                        <span class="product__price">$${producto.precio}</span>
                        <img class="product__image" src="data:image/jpeg;base64,${producto.imagen}" alt="${producto.modelo}"/>
                        <h1 class="product__title">${producto.modelo}</h1>
                        <hr/>
                        <p>${producto.descripcion}</p>
                        <a class="btn product__btn" data-id="${producto.id}" href="#">COMPRAR</a>
                    `;

                        productos.appendChild(div);
                    });

                    document.querySelectorAll('.product__btn').forEach(button => {
                        button.addEventListener('click', function (event) {
                            event.preventDefault();
                            const productId = this.getAttribute('data-id');
                            //buyProduct(productId);
                        });
                    });
                }
            })
    }
    );
    fetch('../productosGUI/php/productoV.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            filtro_categoria: filtro_categoria,
            filtro_precio: filtro_precio
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error:', data.error);
                productos.innerHTML = `<p>Error: ${data.error}</p>`;
            } else {
                productos.innerHTML = ''; // Clear the container
                data.forEach(producto => {
                    const div = document.createElement('div');
                    div.classList.add('product');

                    div.innerHTML = `
                    <span class="product__price">$${producto.precio}</span>
                    <img class="product__image" src="data:image/jpeg;base64,${producto.imagen}" alt="${producto.modelo}"/>
                    <h1 class="product__title">${producto.modelo}</h1>
                    <hr/>
                    <p>${producto.descripcion}</p>
                    <p>Para comprar el producto, registrate o inicia sesion en la plataforma</p>
                    <a class="btn product__btn" onClick="Sesion()">Iniciar Sesion</a>
                `;

                    productos.appendChild(div);
                });
            
                document.querySelectorAll('.product__btn').forEach(button => {
                    button.addEventListener('click', function (event) {
                        event.preventDefault();
                        const productId = this.getAttribute('data-id');
                        //buyProduct(productId);
                    });
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });

        
    function buyProduct(id) {
        console.log(`Comprando producto con ID: ${id}`);
        fetch('../productosGUI/php/comprar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id=${id}`
        })
            .then(response => response.json())
            .then(data => {
                if (data === 'exito') {
                    alert(`Producto comprado con éxito! Recuerda revisar antes de comprar ${data}`);
                    document.getElementById('formulario-calificacion').classList.remove('ocultar');
                    mostrarFormularioCalificacion(id); // Llamar a la función de calificación después de comprar
                } else {
                    alert('Error comprando el producto.');
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function mostrarFormularioCalificacion(id) {
        // Crear el formulario de calificación
        const formularioCalificacion = `
        <h2>Calificar producto</h2>
        <div>
            <p>Por favor, califique este producto:</p>
            <fieldset class="rating">
                <input type="radio" id="star5" name="rating" value="5" /><label for="star5" title="Excelente">5</label>
                <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="Muy bueno">4</label>
                <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="Bueno">3</label>
                <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="Regular">2</label>
                <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="Malo">1</label>
            </fieldset>
            <textarea id="comentario" placeholder="Escribe tu comentario aquí"></textarea>
            <button id="enviarCalificacionBtn">Enviar calificación</button>
        </div>
        `;
    
        // Mostrar el formulario en algún lugar del documento HTML
        const formularioDiv = document.getElementById('formulario-calificacion');
        formularioDiv.innerHTML = formularioCalificacion;
    
        // Asignar evento onclick al botón de enviar calificación
        const enviarCalificacionBtn = document.getElementById('enviarCalificacionBtn');
        enviarCalificacionBtn.addEventListener('click', function() {
            enviarCalificacion(id);
        });
    }    
    // cerrar_sesion.addEventListener('click', () => {
    //     fetch('../usuarioGUI/php/cerrarSesion.php')
    //         .then(response => response.json())
    //         .then(data => {
    //             if (data === 'exito') {
    //                 window.location.href = '../../login/index.html';
    //             } else {
    //                 console.error('Error cerrando la sesión');

    //             }
    //         })
    //         .catch(error => {
    //             console.error('Error:', error);
    //         });
    // });
    inicio.addEventListener('click',()=>{
        console.log('inicio');
        window.location.href = '../../login/index.html';
    });
});
function Sesion(){
    window.location.href = '../../login/index.html';
}