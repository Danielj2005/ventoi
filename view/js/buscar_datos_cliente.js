// función para buscar datos de un proveedor ya registrado

function buscar_datos_cliente(){
    const nacionalidad = document.getElementById("nacionalidad").value;
    const cedula = document.getElementById("cedula").value == "" 
        ? 
        Swal.fire("Atención!","El campo cédula se encuentra vacío, Por favor llenar todos los campos para continuar","warning")
        : 
        document.getElementById("cedula").value ;
    
    const input_id_cliente = $("#id_cliente");
    const input_nombre = $("#nombre");
    const input_telefono = $("#telefono");

    const mensaje_cedula = $("#mensaje_cedula");
    const mensaje_nombre = $("#mensaje_nombre");
    const mensaje_telefono = $("#mensaje_telefono");
    
    $.ajax({
        data:  {
            "module": "buscar_datos_cliente",
            "cedula" : nacionalidad+cedula
        },
        url:   URL_API,
        type:  'post',
        dataType: 'json',
        success: function (datos) {
            if (datos.existe == 1) {
                
                input_id_cliente.val(datos.id_cliente);
                input_nombre.val(datos.nombre);
                input_telefono.val(datos.telefono);

                mensaje_cedula.hasClass('d-none') ? '' : mensaje_cedula.addClass('d-none');
                mensaje_nombre.hasClass('d-none') ? '' : mensaje_nombre.addClass('d-none');
                mensaje_telefono.hasClass('d-none') ? '' : mensaje_telefono.addClass('d-none');

            }else{
                Swal.fire("Cliente no Encontrado!","No se encontró un cliente registrado con esta identificación. Por favor, complete todos los campos obligatorios del formulario para registrarlo y continuar con la venta.","warning");
                
            }
        }
    });
    

}