function show_password(idIcon, idInput) {
    // const inputs = document.querySelectorAll(".input__field"); // se guardan los inputs de las contraseñas
    // const icons = document.querySelectorAll(".input__icon"); // se guardan los iconos de los inputs de las contraseñas

    // icons.forEach((icon, index) => {

    //     icon.addEventListener("click", (e) => {

    //         e.preventDefault();
    //         const input = inputs[index]; // se selecciona el input correspondiente al icono

    //         if (icon.classList.contains('bi-eye')) {
    //             icon.classList.remove('bi-eye');
    //             icon.classList.add('bi-eye-slash');
    //             input.type = 'text'; // se muestra la contraseña
    //         } else {
    //             icon.classList.remove('bi-eye-slash');
    //             icon.classList.add('bi-eye');
    //             input.type = 'password'; // se oculta la contraseña
    //         }
    //     });
    // });
    const icon = document.getElementById(`${idIcon}`);
    const input = document.getElementById(`${idInput}`);

    icon.classList.toggle('bi-eye');
    icon.classList.toggle('bi-eye-slash');
    input.type == 'text' ? input.type = 'password' : input.type = 'text';
}

// setInterval(() => {
//     show_password();
    
// }, 100);
