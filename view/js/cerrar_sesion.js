$(document).ready(function(){
    /*------- funcion de el boton para salir del sistema -------*/
    $('.btn-exit-system').on('click', function(e){
        e.preventDefault();

        // Llamar a un archivo PHP para destruir las variables de sesión
        Swal.fire({
            title: 'Estas Seguro(a)?',
            text: "Se cerrará la sesión",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#03A9F4',
            cancelButtonColor: '#F44336',
            confirmButtonText: ' Sí, Salir!',
            cancelButtonText: ' No, Cancelar!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href="http://localhost/ventoi/controller/usuario/logout.php";
            }
        });
    });

});