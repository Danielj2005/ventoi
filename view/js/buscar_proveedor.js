// función para buscar datos de un proveedor ya registrado

function buscar_proveedor(){
    const nacionalidad = document.getElementById('nacionalidad').value;
    const cedula = document.getElementById("cedula").value == "" 
        ? 
        Swal.fire("Atención!","El campo cédula se encuentra vacío, Por favor llenar todos los campos para continuar","warning")
        : 
        document.getElementById("cedula").value ;

    $.ajax({
        data:  {
            "cedula" : nacionalidad+cedula,
            module: "buscar_proveedor_compra"
        },
        url:   URL_API,
        type:  'post',
        dataType: 'json',
        success: function (datos) {
            if (datos.existe == 1) {
                $("#nombre_proveedor").val(datos.nombre);
                $("#telefono").val(datos.telefono);
                $("#correo").val(datos.correo);
                $("#direccion").val(datos.direccion);
            }
            if (datos.existe == 0) {
                Swal.fire("Atención!","No se encontró ningún proveedor registrado con ese documento de identidad, por favor, verifica he intenta nuevamente","warning");
            }
        },
        error: function () {
            Swal.fire("Atención!","Ha ocurrido un error al procesar tu solicitud, por favor, recargue la página y intenta nuevamente","warning");
        }
    });
}
