
function evaluar_casillas () { // Función para evaluar las casillas
    const inputsCasillas = document.querySelectorAll('.vista'); // Selecciona todos los divs con la clase 'vista'

    inputsCasillas.forEach((input) => { // Itera sobre cada div

        input.addEventListener('click', ()=>{  // Añade un evento de clic a cada input
        
            let casillas = document.querySelectorAll(`.${input.value}`); // Selecciona todas las casillas dentro del div que se ha clicado
            let estado_casilla = input.checked; // Estado de la casilla que se ha marcado o desmarcado
            
            casillas.forEach((li)=>{
                li.checked = estado_casilla; // Cambia el estado de la casilla
            });
        })
    });
}
