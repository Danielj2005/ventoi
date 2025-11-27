
 const temaOscuro = () => {
 	document.querySelector("body").setAttribute("data-bs-theme","dark");
    document.querySelector("html").setAttribute("data-bs-theme","dark");

 }
  const temaClaro = () => {
 	document.querySelector("body").setAttribute("data-bs-theme","light");
    document.querySelector("html").setAttribute("data-bs-theme","light");

 }

  const cambiarTema = () => {
 	document.querySelector("body").getAttribute("data-bs-theme") === "dark"?
 	temaClaro() : temaOscuro();
 	
 }