/**
 * @file scripIndex.js
 * @brief Archivo de inicialización de la página index.html
 * @version 1.0
 * @date 2024-12-05
 * 
 */
//declaramos variables para poder acceder a los botones de la página
const ingreso = document.getElementById('btn-ingresar');
const registro = document.getElementById('btn-registrarse');

ingreso.addEventListener('click', () => {
    window.location.href = 'ingresar.html';
});

registro.addEventListener('click', () => {
    window.location.href = 'registro.html';
});
