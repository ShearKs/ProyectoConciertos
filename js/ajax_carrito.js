// window.addEventListener("click", function () {
//     obtenerYMostrarCarrito();
// });

//Al cargar la página nos encargamos de actualizar el carrito de la Compra
document.addEventListener("DOMContentLoaded", function () {
    obtenerYMostrarCarrito();
    const btnAddCarrito = document.querySelector(".botonCarrito");
    if (btnAddCarrito) {
        btnAddCarrito.addEventListener("click", () => addCarrito())
    }

    const botonVaciarCarrito = document.getElementById('vaciarCarrito');
    if (botonVaciarCarrito) {
        botonVaciarCarrito.addEventListener("click", () => vaciarCarritoServidor());
    }


    //Asignamos el evento de pago
    eventoPago();

});



function addCarrito() {
    var xhr = new XMLHttpRequest();

    // Obtener el botón directamente por su clase
    let botonCompra = document.querySelector(".botonCarrito");

    // Obtener el ID del botón
    let idBoton = botonCompra.id;

    // Configurar la solicitud para añadir al carrito
    //xhr.open('POST', '../Controladores/procesaCarrito.php?conciertoId=' + idBoton, true);
    xhr.open('POST', '../Controladores/procesaCarrito.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Manejar la respuesta del servidor
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Añadir entrada al carrito
            alert("Entrada agregada correctamente al carrito!");

            // Hacer una segunda solicitud AJAX para obtener y mostrar el contenido del carrito
            obtenerYMostrarCarrito();
        }
    };

    // Enviar la solicitud para añadir al carrito con los datos
    xhr.send('añadirAlCarrito=' + idBoton);
    //xhr.send();

}

function obtenerYMostrarCarrito() {
    let xhr = new XMLHttpRequest();

    // Configurar la solicitud para obtener el contenido del carrito
    xhr.open('GET', '../Controladores/obtenerCarrito.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    // Manejar la respuesta del servidor
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Manejar la respuesta del servidor
            let respuesta = JSON.parse(xhr.responseText);

            // Acceder a la información de la sesión
            let carritoCompra = respuesta.sesionCarrito;

            //console.log(carritoCompra)
            crearCarritoCompra(carritoCompra);

        }
    }

    // Enviar la solicitud para obtener el contenido del carrito
    xhr.send();
}

function vaciarCarritoServidor() {

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../Controladores/vaciarCarrito.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Maneja la respuesta del servidor
            const response = JSON.parse(xhr.responseText);

            if (response.success) {
                alert("Carrito vaciado exitosamente!");
                //Una vez hemos vaciado el carrito volvemos a mostrarlo

                //Visualizamos el mensaje que dice que no hay nada en el carrito
                let mensajePre = document.getElementById('mensajeDefault');
                mensajePre.style.display = 'block';
                obtenerYMostrarCarrito();
            } else {
                alert("Error al vaciar el carrito...");
            }
        }
    };

    xhr.send();
}


function crearCarritoCompra(carritoCompra) {


    //Antes de mostrar esta funcón eliminamos lo que tenemos en el carrito para su correcta visualización
    vaciarCarrito()

    //Obtiene el submenú 
    let carrito = document.getElementById("submenu");

    let mensajePredeterminado = document.getElementById("mensajeDefault");

    //Si el carrito de la compra no se ha creado porque no hemos añadido ningún entrada no tendremos que crear ningun nodo.. 
    if (carritoCompra !== null) {

        //Como el carrito no va estar vacio nos encargarmos de ocultar el mensaje de no hay nada en el carrito
        mensajePredeterminado.style.display = 'none';

        console.log("Longitud " + carritoCompra.length);
        console.log("tipo: " + (typeof carritoCompra))
        console.log(carritoCompra)

        for (let i = 0; i < carritoCompra.length; i++) {


            let concierto = carritoCompra[i];


            //Creamos un div que va ser donde se va añadir cada entrada
            let div = crearNodo("div", "", "divCarrito", carrito);

            let divContenido = crearNodo("div", "", "divContenido", div);
            let divBoton = crearNodo("div", "", "divBotonMenos", div)

            //crearNodo("hr", "", "", div)
            //Le vamos añadir la información de la entrada
            crearNodo("p", "Nombre: " + concierto.nombreArtistico, "", divContenido);
            crearNodo("p", "Lugar: " + concierto.lugar, "", divContenido);
            crearNodo("p", "Número de Entradas: " + concierto.entrada, "", divContenido);

            crearNodo("p", "Precio total evento: " + (concierto.precio * concierto.entrada) + "€", "", divContenido);

            //Creamos el botón para eliminar un elemento del carrito
            let btnMenos = crearNodo("img", "", "eliminaCarrito", divBoton);

            //Le indicamos la ruta a la imagen
            btnMenos.src = "../static/img/boton-menos.png";

            //Le adimos un id al evento 
            btnMenos.id = concierto.codigo;

            crearNodo("hr", "", "", div)


        }

    }

    //Indico el evento aquí ya que va a ser cuando se cargue el dom del carrito si no el el restar carrito no podrá hacer nada
    eventoRestarCarrito();


}


//Función que se encarga de crearl modal y el formulario de la aplicación
function eventoPago() {

    const botonPago = document.getElementById("btnPago");

    //Hacemos evento solo si existe , es decir si se ha creado ya en el dom
    if (botonPago) {
        botonPago.addEventListener("click", () => {

            //Obtenemos lo que hay en el carrito
            let carrito = document.getElementById('submenu');

            //Solo contamos con el mensaje predeterminado ,botón 'Cerrar' y el vaciar Carrito significará que nuestro carrito está vació
            if (carrito.children.length <= 3) {
                alert("Tienes que agregar algún elemento al carrito antes de pagar")
                return
            }
            console.log(carrito.children.length);

            //Para que nos añada en el dom dialogos de más
            let modalExistente = document.getElementById('modal');
            console.log(modalExistente);

            //Si tenemos algún modal ya creado lo eliminamos
            if (modalExistente) {
                modalExistente.remove();
            }

            let modal = crearNodo("dialog", "", "modal", document.body);
            modal.id = 'modal';

            //Le introducimos el formulario al modal para que el usuario puedo pagar
            modal.innerHTML =
                '<form class = "formularioPago" method="post" action="../Controladores/pago.php">' +
                '<label for="nombre">Nombre:</label>' +
                '<input type="text" id="nombre" name="nombre" class ="campos" Srequired>' +
                '<label for="email">Introduce tu correo Electronico</label>' +
                '<input type="email" id="email" name="email" class="campos" required>' +
                '<button class="botonPago" type="submit">Proceder al Pago</button>' +
                '</form>';

            //Agremos un botón al modal para que pueda salir
            let botonCerrar = crearNodo("button", "Cerrar", "cerrarModal", modal);


            //Añadimos el evento de Cerrar
            botonCerrar.addEventListener("click", () => modal.close());

            modal.showModal();

        });
    }

}

function eventoRestarCarrito() {
    let botonesMenos = document.getElementsByClassName("eliminaCarrito");

    for (let i = 0; i < botonesMenos.length; i++) {
        botonesMenos[i].addEventListener("click", function () {

            //console.log("Has pulsado el botón " + botonesMenos[i].id);

            let xhr = new XMLHttpRequest();

            //Botón que hemos clicado
            let btnRestar = botonesMenos[i].id

            // Configurar la solicitud para mandar nuestro id con el codigo del concierto
            xhr.open('POST', '../Controladores/restarElementoCarrito.php', true);
            //Cabecera codificada
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            // Manejamos la respuesta del servidor,,
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {

                    //Una vez hemos restado el elemento del carrito volvemos a actualizar nuestro carrito..
                    obtenerYMostrarCarrito();
                }
            };

            xhr.send('eventoRestarCarrito=' + btnRestar);
        });
    }
}


//Esta función solo se encarga de visualizar la parte del cliente , nuestra sesión de carrito que es lo que importa no le afecta
function vaciarCarrito() {

    let carrito = document.getElementById("submenu")

    //Visualizamos el mensaje que dice que no hay nada en el carrito
    let mensajePre = document.getElementById('mensajeDefault');
    mensajePre.style.display = 'block';

    while (carrito.children.length > 3) {
        carrito.lastChild.remove();
    }


}

function crearNodo(elemento, contenido, nombreClase, padre) {

    let nodo = document.createElement(elemento);
    nodo.textContent = contenido
    if (nombreClase != "") nodo.className = nombreClase
    padre.appendChild(nodo)

    return nodo

}
