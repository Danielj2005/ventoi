
function monto_total_productos() {
    const subtotal_dolar = document.getElementById('totalDolar');
    const subtotal_bs = document.getElementById('totalBolivar');

    let cantidad_total = document.querySelectorAll('.cantidad');
    let precio_dolar_total = document.querySelectorAll('.precio_dolar');
    let precio_bolivar_total = document.querySelectorAll('.precio_bs');

    let total_dolar = 0;
    let total_bolivar = 0;
    
    for (let i = 0; i < cantidad_total.length; i++) {
        // se evalua si el campo de la cantidad de los productos en la lista esta vacío o no, en caso de que si se le asigna el valor de cero
        let cantidad = (cantidad_total[i].value !== "")  ? cantidad_total[i].value : 0;

        // se evalua si el campo del precio del dolar de los productos en la lista esta vacío o no, en caso de que si se le asigna el valor de cero
        let dolar_total = (precio_dolar_total[i].value !== "")  ? precio_dolar_total[i].value : 0;
        total_dolar += parseFloat(dolar_total) * cantidad;

        // se evalua si el campo del precio del bolivar de los productos en la lista esta vacío o no, en caso de que si se le asigna el valor de cero
        let bolivar_total = (precio_bolivar_total[i].value !== "")  ? precio_bolivar_total[i].value : 0;
        total_bolivar += parseFloat(bolivar_total) * cantidad;

        subtotal_dolar.value = total_dolar;
        subtotal_bs.value = total_bolivar.toFixed(2);
        total_bolivar = total_bolivar;
    }
    add_iva(total_bolivar,total_dolar);
}

// funcion para agregar el iva a la venta
function add_iva(sub_total_bs,sub_total_dolar){

    const input_dolares = document.getElementById('totalDolar_iva');
    const input_bolivares = document.getElementById('totalBolivar_iva');

    const strong_dolares = document.getElementById('strong_dolares');
    const strong_bolivares = document.getElementById('strong_bolivares');

    let total_dolares = parseFloat(sub_total_dolar);
    let total_bolivares = parseFloat(sub_total_bs);

    total_dolares = total_dolares + (total_dolares * IVA);
    total_bolivares = total_bolivares + (total_bolivares * IVA);

    input_dolares.value = total_dolares.toFixed(2);
    input_bolivares.value = total_bolivares.toFixed(2);

    strong_dolares.textContent = total_dolares.toFixed(2);
    strong_bolivares.textContent = total_bolivares.toFixed(2);
    

}
