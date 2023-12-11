const botonCarrito = document.getElementById('cartIcon');
const closeButton = document.getElementById('closeButton');
const submenu = document.getElementById('submenu');


//Se encarga de mostrar el carrito
botonCarrito.addEventListener('click', () => {
    submenu.style.display = (submenu.style.display === 'none' || submenu.style.display === '') ? 'block' : 'none';
});

//Se encarga de cerrar el carrito
closeButton.addEventListener('click', () => {
    submenu.style.display = 'none';
});





