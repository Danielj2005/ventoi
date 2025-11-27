//constantes para el div contenedor y los imputs y el boton agregar
const contenedor = document.querySelector('#dinamic');
const contenedor = document.querySelector('#agregar');

// variable para el total de elementos agregados

let total = 1;

/*

Metodo que se ejecuta al dar click sobre el boton agregar

*/

btnAgregar.addEventListener('click', e =>{
	let div = document.createElement('div');
	div.innerHTML = '<lavel>${total++}</label> - <input type="text" name="nombre[]" placeholder="Nombre" required><button onclick="eliminar(this)">Eliminar</button>';
	contenedor.appendChild(div); 
})


//Metodo eliminar

@param {this} e

const eliminar = (e) => {
	const divPadre = e.parentNode;
	contenedor.removeChild(divPadre);
	actualizarContenedor();
};

const actualizarContenedor = () => {
	let divs = contenedor.children;
	total = 1;
	for (let i = 0; i < divs.length; i++) {
		divs[i].children[0].innerHTML = total++;
	}
};