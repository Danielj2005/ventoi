
function detectar_actividad() {
    let tiempo_id; // Almacena el ID del temporizador de inactividad
    let advertencia_tiempo_id; // Almacena el ID del temporizador de advertencia

    function resetear_temporizador() {
        clearTimeout(tiempo_id);
        clearTimeout(advertencia_tiempo_id); // Limpia también el temporizador de advertencia si existía
        tiempo_id = setTimeout( mostrar_advertencia, tiempo_config);
    }

    function mostrar_advertencia() {
        const tiempo_advertencia = 30000;
		Swal.fire({
            title: '¡Estás inactivo!',
            text: `Tu sesión se cerrará automáticamente en ${tiempo_advertencia / 1000} segundos debido a la inactividad.`,
            icon: 'warning',
			showCancelButton: true,
			confirmButtonText: "Seguir aquí!",
            showCancelButton: true,
			animation: "slide-from-top",
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cerrar sesión ahora',
            allowOutsideClick: false,
            allowEscapeKey: false, 
            timer: tiempo_advertencia, 
            timerProgressBar: true,
            didOpen: () => {
                const b = Swal.getHtmlContainer().querySelector('b');
                advertencia_tiempo_id = setTimeout(() => {
                    location.href = "../include/bitacora_tiempo_inactividad.php"; 
                }, tiempo_advertencia);
            }
        }).then((result) => {
            if (result.isConfirmed) {
                resetear_temporizador();
            }  else if (result.dismiss === Swal.DismissReason.timer || result.isDenied || result.dismiss === Swal.DismissReason.cancel){
                location.href = "../include/bitacora_tiempo_inactividad.php"; 
            }
        });
    }

    // Eventos que reinician el temporizador de inactividad
    const events = ['mousemove', 'mousedown', 'keypress', 'scroll', 'touchstart'];
    events.forEach(event => {
        document.addEventListener(event, resetear_temporizador);
    });
    // Inicia el temporizador cuando se carga la página
    resetear_temporizador();
}

document.addEventListener('DOMContentLoaded', () => {
    detectar_actividad(); 
});