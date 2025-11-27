<?php 
session_start();

include_once ("../include/modelos_include.php"); // se incluyen los modelos necesarios para la vista

// modulo a trabajar
$modulo = modeloprincipal::limpiar_cadena($_POST["modulo"]);

if (!isset($_POST["modulo"])) {
    alert_model::alerta_simple("Ocurrio un error!","Ha ocurrido un error al procesar tu solicitud","error");
    exit();
}

// modulo para Guardar un registro
if($modulo === "Guardar" ){

    $cedula = modeloPrincipal::limpiar_cadena($_POST['nacionalidad'].$_POST['cedula']);
    $nombre = modeloPrincipal::limpiar_mayusculas($_POST["nombre_proveedor"]);
    $correo = modeloPrincipal::limpiar_cadena($_POST["correo"]);
    $direccion = modeloPrincipal::limpiar_mayusculas($_POST["direccion"]);
    $telefono = modeloPrincipal::limpiar_cadena($_POST["telefono"]);

    // Se verifica que no se hayan recibido campos vacíos.
    modeloPrincipal::validar_campos_vacios([$cedula, $nombre, $correo, $direccion, $telefono]);

    // Se verifica que no el proveedor aha registrar no exista.    
    if(mysqli_num_rows(modeloPrincipal::consultar("SELECT cedula_rif, correo FROM proveedor WHERE cedula_rif = '$cedula' || correo = '$correo'")) > 0){
        alert_model::alerta_simple(
        "Ocurrio un error!", 
        'El documento de identidad o Correo ingreados ya se encuentran registrados en el sistema, le sugerimos revisar los datos o utilizar una información diferente.', 
        "error");
        exit(); 
    }

    if (modeloprincipal::verificar_datos("[V|E|R|G|J|P][0-9|-]{5,13}",$cedula)) {
        alert_model::alerta_simple("¡Ocurrio un error!","El campo cédula no cumple con el formato requerido o fue alterado. Por favor verifique e intente de nuevo ", "error");
        exit();
    }
    
    if (modeloPrincipal::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)) {
        alert_model::alert_of_format_wrong("'nombre'");
        exit();
    }
    
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        alert_model::alert_of_format_wrong("correo");
        exit();
    }
    

    if (modeloPrincipal::verificar_datos("[0-9]{11}",$telefono)) {
        alert_model::alert_of_format_wrong("'teléfono'");
        exit();
    }

    if (modeloprincipal::verificar_datos("[A-Za-zÁÉÍÚÓáéíóúñÑ0-9-, ]{10,50}",$direccion)) {
        alert_model::alert_of_format_wrong("'dirección'");
        exit();
    }

    // se registran los datos del proveedor
    try {
        $actualizar = proveedor_model::insertar_proveedor($cedula, $nombre, $correo, $telefono, $direccion);
        
        if (!$actualizar) {
            alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al registrar un proveedor en la base de datos.","error");
        }

    } catch (Exception $e) {
        alert_model::alerta_simple("Ocurrido un error!", "No se pudo registrar el proveedor en la base de datos debido a un error de consulta.", "error");
        exit();
    }
    
    // se realiza la bitácora con los datos del proveedor a registrar
    try {
        $id_proveedor = proveedor_model::obtener_id_proveedor_recien_registrado();

        $datos_originales = proveedor_model::consultar_proveedor_por_id("*", $id_proveedor);
        $datos_originales = mysqli_fetch_array($datos_originales);

        bitacora::bitacora("Registro exitoso de un proveedor",'<p class="mb-3 text-primary-emphasis text-center"><i class="bi bi-exclamation-circle-fill"></i>&nbsp;El usuario registró un Proveedor con la Siguiente Información.</p>
            <h4 class="text-center card-title"><b> Información del Proveedor </b></h4>
            <div class="d-flex justify-content-between border-bottom mb-2"> <p> Cédula / RIF</p> <span>'.$datos_originales["cedula_rif"].'</span> </div>
            <div class="d-flex justify-content-between border-bottom mb-2"> <p> Nombre</p>
                <span>'.modeloPrincipal::primeraLetraMayus($datos_originales['nombre']).' '.modeloPrincipal::primeraLetraMayus($datos_originales['apellido']).'</span>
            </div>
            <div class="d-flex justify-content-between border-bottom mb-2"> <p> Correo</p> <span>'.$datos_originales['correo'].'</span> </div>
            <div class="d-flex justify-content-between border-bottom mb-2"> <p> Dirección</p> <span>'.$datos_originales['direccion'].'</span> </div>
            <div class="d-flex justify-content-between border-bottom mb-2"> <p> Teléfono</p> <span>'.$datos_originales['telefono'].'</span> </div>');

        alert_model::alert_reg_success();
        exit();
    } catch (Exception $e) {
        alert_model::alert_reg_error();
        exit();
    }

}

// modulo para Modificar un registro

if($modulo === "Modificar"){

    $id_proveedor = modeloPrincipal::limpiar_cadena($_POST["id"]);
    $cedula = modeloPrincipal::limpiar_mayusculas($_POST['nacionalidad'].$_POST["cedula"]);
    $nombre = modeloPrincipal::limpiar_mayusculas($_POST["nombre"]);
    $correo = modeloPrincipal::limpiar_cadena($_POST["correo"]);
    $direccion = modeloPrincipal::limpiar_mayusculas($_POST["direccion"]);
    $telefono = modeloPrincipal::limpiar_cadena($_POST["telefono"]);

    // Se verifica que no se hayan recibido campos vacíos.
    modeloPrincipal::validar_campos_vacios([$cedula, $nombre, $correo, $direccion, $telefono, $id_proveedor]);

    if (modeloprincipal::verificar_datos("[V|E|R|G|J|P][0-9|-]{5,13}",$cedula)) {
        alert_model::alerta_simple("¡Ocurrio un error!","El campo cédula no cumple con el formato requerido o fue alterado. Por favor verifique e intente de nuevo ", "error");
        exit();
    }
    
    if (modeloPrincipal::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)) {
        alert_model::alert_of_format_wrong("'nombre'");
        exit();
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        alert_model::alert_of_format_wrong("correo");
        exit();
    }

    if (modeloPrincipal::verificar_datos("[0-9]{11}",$telefono)) {
        alert_model::alert_of_format_wrong("'teléfono'");
        exit();
    }

    if (modeloprincipal::verificar_datos("[A-Za-zÁÉÍÚÓáéíóúñÑ0-9-, ]{5,50}",$direccion)) {
        alert_model::alert_of_format_wrong("'dirección'");
        exit();
    }

    // se obtienen los datos originales del proveedor antes de la actualización para realizar la bitácora
    try {
        $actualizar = proveedor_model::consultar_proveedor_por_id("*", $id_proveedor);
        
        if (!$actualizar) {
            alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al obtener los datos originals del proveedor, revise la syntaxis de la consulta.","error");
        }
        $datos_originales = mysqli_fetch_array($actualizar);

    } catch (Exception $e) {
        alert_model::alerta_simple("Ocurrido un error!", "ocurrio un error al obtener los datos originals del proveedor.", "error");
        exit();
    }

    // se actualizan los datos del proveedor
    try {
        $actualizar = proveedor_model::actualizar_proveedor($cedula, $nombre, $correo, $telefono, $direccion, $id_proveedor);
        
        if (!$actualizar) {
            alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al modificar el proveedor en la base de datos.","error");
        }

    } catch (Exception $e) {
        alert_model::alerta_simple("Ocurrido un error!", "No se pudo modificar el proveedor en la base de datos debido a un error de consulta.", "error");
        exit();
    }
    
    // se realiza la bitácora con los cambios de los datos del proveedor
    try {
        $datos_actuales = proveedor_model::consultar_proveedor_por_id("*", $id_proveedor);

        $datos_actuales = mysqli_fetch_array($datos_actuales);

        $cambios = [
            "dni" => config_model::obtener_comparacion([$datos_originales["cedula_rif"], $datos_originales["cedula_rif"]], [ $datos_actuales["cedula_rif"], $datos_actuales["cedula_rif"]]),
            "nombre" => config_model::obtener_comparacion([$datos_originales["nombre"], $datos_originales["nombre"]], [ $datos_actuales["nombre"], $datos_actuales["nombre"]]),
            "correo" => config_model::obtener_comparacion([$datos_originales["correo"], $datos_originales["correo"]], [ $datos_actuales["correo"], $datos_actuales["correo"]]),
            "direccion" => config_model::obtener_comparacion([$datos_originales["direccion"], $datos_originales["direccion"]], [ $datos_actuales["direccion"], $datos_actuales["direccion"]]),
            "telefono" => config_model::obtener_comparacion([$datos_originales["telefono"], $datos_originales["telefono"]], [ $datos_actuales["telefono"], $datos_actuales["telefono"]])
        ];

        bitacora::bitacora("Modificación exitosa de un proveedor.",'<p class="mb-3 text-primary-emphasis text-center"><i class="bi bi-exclamation-circle-fill"></i>&nbsp;El usuario modificó un Proveedor con la Siguiente Información.</p>
            <h4 class="text-center card-title"><b> Información del Proveedor </b></h4>
            <div class="d-flex justify-content-between border-bottom mb-2"> <p> Cédula / RIF</p> <span>'.$cambios["dni"].'</span> </div>
            <div class="d-flex justify-content-between border-bottom mb-2"> <p> Nombre</p> <span>'.$cambios['nombre'].'</span> </div>
            <div class="d-flex justify-content-between border-bottom mb-2"> <p> Correo</p> <span>'.$cambios['correo'].'</span> </div>
            <div class="d-flex justify-content-between border-bottom mb-2"> <p> Dirección</p> <span>'.$cambios['direccion'].'</span> </div>
            <div class="d-flex justify-content-between border-bottom mb-2"> <p> Teléfono</p> <span>'.$cambios['telefono'].'</span> </div>');

        alert_model::alert_mod_success();
        exit();
    } catch (Exception $e) {
        alert_model::alert_mod_error();
        exit();
    }

}
