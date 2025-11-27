
function convertir_usd_a_bs (id_precio_dolar) {
    let tasa = document.getElementById('tasa_dolar').textContent;
    tasa = parseFloat(tasa);

    const precios_venta_dolar = document.getElementById(`precio_venta_dolar_${id_precio_dolar}`);
    const precio_bs = document.getElementById(`precio_unidad_bs_${id_precio_dolar}`);
    const precio_dolar = document.getElementById(`precio_unidad_dolar_${id_precio_dolar}`).value;

    const precio = precio_dolar * tasa;
    precio_bs.value = parseFloat(precio).toFixed(2);
    
    let porcentaje_iva = precio_dolar * IVA;
    let margen_de_ganancia = precio_dolar * PORCENTAJE_GANANCIA;
    let precio_venta = parseFloat(precio_dolar) + parseFloat(margen_de_ganancia);

    precio_venta = (parseFloat(precio_venta) + parseFloat(porcentaje_iva)).toFixed(2);

    precios_venta_dolar.value = precio_venta;
}

function calcular_total () {
    let tasa = document.getElementById('tasa_dolar').textContent;
    tasa = parseFloat(tasa);
    
    // console.log('tasa_dolar es: ' + tasa); 
    const cantidad_item = document.querySelectorAll('.cantidad');
    // console.log('cantidad_item es: ' + cantidad_item);
    const precio_unidad_dolar = document.querySelectorAll(`.precio_unidad_dolar`);
    // console.log('<br> precio_bs es: ' + precio_bs);

    const totalDolar = document.getElementById('totalDolar');
    const totalBolivar = document.getElementById('totalBolivar');

    const input_dolar = document.querySelector(".totalDolar");
    const input_bolivar = document.querySelector(".totalBolivar");

    let total_dolar = 0;
    let cantidad = 0;

    for (let i = 0; i < cantidad_item.length; i++) {
        cantidad += (cantidad_item[i].value !== '') ? parseInt(cantidad_item[i].value) : 0;
        total_dolar += (precio_unidad_dolar[i].value !== '') ? parseFloat(precio_unidad_dolar[i].value) * cantidad_item[i].value : 0;
    }

    totalDolar.textContent = total_dolar.toFixed(2);
    totalBolivar.textContent = (total_dolar * tasa).toFixed(2);
    
    input_dolar.value = total_dolar.toFixed(2);
    input_bolivar.value = (total_dolar * tasa).toFixed(2);
}
