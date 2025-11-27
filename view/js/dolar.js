function obtener_precio_dolar_auto() {
	$.ajax({
        data:  '',
        url:   '../include/obtener_precio_dolar.php',
        type:  'get',
        dataType: 'JSON',
        success: function (datos) {
			if (datos.existe == 1) {
				document.getElementById('tasa_dolar').innerText = datos.usd_price_bs;
				guardar_precio_dolar_auto(parseFloat(datos.usd_price_bs));
			}
        },
        error: function () {
			Swal.fire("Ocurrio un error!","Ocurrido un error al procesar tu solicitud, por favor revise su conexión a internet he intente nuevamente o actualice la tasa de manera manual.","error");
        }
    });
}


function guardar_precio_dolar_auto(datos) {
	$.ajax({
        data:  {'priceDolar': datos, 'manera': "automática"},
        url:   '../controlador/dolar.php',
        type:  'post',
        success: function (data) {
			$('.msjFormSend').html(data);
        }, error: function () {
			Swal.fire("Ocurrio un error!","Ocurrido un error al procesar tu solicitud, por favor recargue la página he intente nuevamente.","error");
		}
    });
}

const btn_update_dolar_auto = document.querySelector("#btn_update_dolar_auto");

if (btn_update_dolar_auto) {
	btn_update_dolar_auto.addEventListener("click", function(e){
		e.preventDefault();
		obtener_precio_dolar_auto();
	});
}