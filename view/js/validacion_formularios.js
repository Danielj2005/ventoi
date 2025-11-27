const formulario = document.querySelector('#formulario');

const inputs = document.querySelectorAll('#formulario input');

const expresiones = {
	/* usuario */
	usuario: /^[a-zA-Z0-9\_\-]{7,16}$/, // Letras, numeros, guion y guion_bajo
	password: /^.{7,16}$/, // 4 a 12 digitos.
	respuesta_seguridad: /^[a-zA-ZÀ-ÿ\s]{4,20}$/, // 4 a 12 digitos.

	/* datos personales */
	cedula: /^[0-9]{7,8}$/, // 7 - 8 numeros.
	nombre: /^[a-zA-ZÀ-ÿ\s]{4,255}$/, // Letras y espacios, pueden llevar acentos.
	apellido: /^[a-zA-ZÀ-ÿ\s]{4,80}$/, // Letras y espacios, pueden llevar acentos.
	correo: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/,
	telefono: /^[0-9]{11}$/, // 11 numeros.

	/* datos de una carrera */
	nombre_carrera:/^[a-zA-ZÀ-ÿ\s]{8,60}$/, // Letras y espacios, pueden llevar acentos.

	/* datos de un libro */
	cota: /^[a-zA-Z0-9]{5,6}$/, // 4 numeros.
	titulo: /^[a-zA-Z0-9,À-ÿ\-\s]{4,100}$/, // Letras y espacios, pueden llevar acentos.
	autor:/^[a-zA-ZÀ-ÿ.\-\s]{3,80}$/, // Letras y espacios, pueden llevar acentos.
	editorial:/^[a-zA-ZÀ-ÿ\-\s]{4,70}$/, // Letras y espacios, pueden llevar acentos.
	edicion:/^[a-zA-ZÀ-ÿ\s]{8,25}$/, // Letras y espacios, pueden llevar acentos.
	pais:/^[a-zA-ZÀ-ÿ\-\s]{3,25}$/, // Letras y espacios, pueden llevar acentos.
	año: /^\d{4}$/, // 4 numeros.
	paginas: /^\d{2,5}$/, // de 2 a 5 numeros.
	stock: /^\d{1,4}$/, // de 1 a 4 numeros.
	descripcion:/^[a-zA-ZÀ-ÿ\s]{8,255}$/

}

const campos = {
	/* datos personales */
	cedula: false,
	nombre: false,
	correo: false,
	telefono: false,
	
	/* datos de usuario */
	usuario: false,	
	password: false,
	respuesta_seguridad: false,

	/* datos de un producto */
	cota: false, titulo: false, autor: false, editorial: false, edicion: false,
	pais: false, año: false, paginas: false, stock: false, descripcion: false
}

const validarFormulario = (e) => {
	switch (e.target.name) {
		case "cedula_cliente" : validarCampo(expresiones.cedula, e.target); break;

		case "cedula" : validarCampo(expresiones.cedula, e.target); break;
		case "nombre" : validarCampo(expresiones.nombre, e.target); break;
		case "apellido" : validarCampo(expresiones.apellido, e.target); break;
		case "correo" : validarCampo(expresiones.correo, e.target); break;
		case "telefono" : validarCampo(expresiones.telefono, e.target); break;
		case "direccion" : validarCampo(expresiones.direccion, e.target); break;

		case "usuario":	validarCampo(expresiones.usuario, e.target); break;
		// case "password": 
		// 	validarCampo(expresiones.password, e.target);
		// 	validarPassword2(e.target);
		// 	break;
		// case "password2": validarPassword2(e.target); break;
		case "password_actual":	validarCampo(expresiones.password, e.target); break;
	};
	// const cases = {
	// 	"cedula" : validarCampo(expresiones.cedula, e.target),
	// 	"cedula_cliente" : validarCampo(expresiones.cedula, e.target),
	// 	"nombre" : validarCampo(expresiones.nombre, e.target),
	// 	"apellido" : validarCampo(expresiones.apellido, e.target),
	// 	"correo" : validarCampo(expresiones.correo, e.target),
	// 	"telefono" : validarCampo(expresiones.telefono, e.target),
	// 	"descripcion" : validarCampo(expresiones.descripcion, e.target),

	// 	"usuario":	validarCampo(expresiones.usuario, e.target),
	// 	"password": validarCampo(expresiones.password, e.target) && validarPassword2(),
	// 	"password2": validarPassword2(),
	// 	"password_actual":	validarCampo(expresiones.password, e.target),

	// };
}

const validarCampo = (expresion, input) => {

	let mensaje = document.getElementById(`mensaje_${input.id}`);

	if(expresion.test(input.value)){

		mensaje.classList.add('d-none');

		input.classList.remove('invalid');
		input.classList.add('valid');

		campos[input.name] = true;
	} else {
		mensaje.classList.remove('d-none');

		input.classList.add('invalid');
		input.classList.remove('valid');

		campos[input.name] = false;
	}
}

const validar_respuestas = () => {
	const input_respuesta_seguridad = document.getElementById('respuesta_seguridad');
	const input_repetir_respuesta = document.getElementById('repetir_respuesta');

	if(input_respuesta_seguridad.value !== input_repetir_respuesta.value){
		document.getElementById(`grupo__repetir_respuesta`).classList.add('formulario__grupo-incorrecto');
		document.getElementById(`grupo__repetir_respuesta`).classList.remove('formulario__grupo-correcto');
		document.querySelector(`#grupo__repetir_respuesta .formulario__input-error`).classList.add('formulario__input-error-activo');
		campos['respuesta_seguridad'] = false;
	} else {
		document.getElementById(`grupo__repetir_respuesta`).classList.remove('formulario__grupo-incorrecto');
		document.getElementById(`grupo__repetir_respuesta`).classList.add('formulario__grupo-correcto');
		document.querySelector(`#grupo__repetir_respuesta .formulario__input-error`).classList.remove('formulario__input-error-activo');
		campos['respuesta_seguridad'] = true;
	}
}

// const validarPassword2 = (input) => {
// 	const inputPassword1 = document.getElementById(`password`);
// 	const inputPassword2 = document.getElementById(`password2`);

// 	let mensaje = document.getElementById(`mensaje_${input.id}`);

// 	if(inputPassword1.value !== inputPassword2.value){

// 		inputPassword1.classList.add('invalid');
// 		inputPassword1.classList.remove('valid');

// 		mensaje.classList.add('d-none');

// 		campos['password'] = false;
// 	} else {
// 		input.classList.remove('invalid');
// 		input.classList.add('valid');

// 		mensaje.classList.remove('d-none');

// 		campos['password'] = true;
// 	}
// }


inputs.forEach((input) => {
	input.addEventListener('keyup', validarFormulario);
	input.addEventListener('blur', validarFormulario);
	input.addEventListener('paste', validarFormulario);
	input.addEventListener('change', validarFormulario);
	input.addEventListener('input', validarFormulario);
});