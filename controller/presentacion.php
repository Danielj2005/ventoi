<?php 
session_start();

include_once "../include/modelos_include.php"; // se incluyen los modelos necesarios para la vista

// modulo a trabajar
$modulo = modeloprincipal::limpiar_cadena($_POST["modulo"]);

if (!isset($_POST["modulo"])) {
    alert_model::alerta_simple("Ocurrio un error!","Ha ocurrido un error al procesar tu solicitud","error");
    exit();
}

if($modulo === "Guardar"){
    $cantidad = modeloPrincipal::limpiar_cadena($_POST['cantidad_presentacion']); // se le coloca la primera letra en mayúscula
    $representacion = modeloPrincipal::decryptionId($_POST['representacion']);

    // Se verifica que no se hayan recibido campos vacíos.
    modeloPrincipal::validar_campos_vacios([$cantidad, $representacion]);

    // se comprueba que no exista un registro con los mismos datos
    if(mysqli_num_rows(modeloPrincipal::consultar("SELECT id FROM presentacion WHERE cantidad = '$cantidad' and id_representacion = $representacion")) > 0){
        /********** No se puede registrar un usuario si ya existe **********/
        alert_model::alerta_simple("¡Ocurrio un error!","La Presentación que intentas registrar ya se encuentra en nuestro sistema.","error");
        exit(); 
    }

    if (modeloPrincipal::verificar_datos("[0-9\.]{1,5}",$cantidad)) {
        alert_model::alert_of_format_wrong("Cantidad");
        exit();
    }

    // se registran los datos del presentación
    try {
        $registrar = modeloPrincipal::InsertSQL("presentacion", "cantidad, id_representacion, estado" ,"'$cantidad', $representacion, 1");

        if (!$registrar) {
            alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al registrar una presentación.","error");
        }

    } catch (Exception $e) {
        alert_model::alerta_simple("¡Ocurrió un error!", "No se pudo registrar la presentación, intente nuevamente.", "error");
        exit();
    }
    
    try {
        // se realiza la bitácora con los datos del presentación recien registrada

        $id = presentacion_model::obtener_id_recien_registrada();

        $datos = presentacion_model::consultar_por_id($id);
        $datos = mysqli_fetch_array($datos);
        $datos['estado'] = $datos['estado'] == 1 ? 'Activo' : 'Inactivo';

        $bitacora = bitacora::bitacora("Registro Exitoso de una Presentación.",
        '<p class="mb-3 text-primary-emphasis text-center"><i class="bi bi-exclamation-circle-fill"></i>&nbsp;Se registró una Presentación con la Siguiente Informacón.</p> 
            <h4 class="text-center card-title"><b> Información de la Presentación </b></h4>
            <div class="d-flex justify-content-between border-bottom"> <p> Descripción</p>'.$datos['presentacion'].' '.$datos['representacion'].' </div>
            <div class="d-flex justify-content-between border-bottom"> <p> Estado</p> '.$datos['estado'].' </div>');
        
        alert_model::alert_reg_success_and_close_modal();
        
        exit();
    } catch (Exception $e) {
        alert_model::alert_reg_error();
        exit();
    }
}

$id_presentacion = modeloPrincipal::decryptionId($_POST["UID"]);
$id_presentacion = modeloPrincipal::limpiar_cadena($id_presentacion);

if ($modulo === "activo") {
    
    $datos_originales = presentacion_model::consultar_por_id($id_presentacion);
    $datos_originales = mysqli_fetch_array($datos_originales);
    $datos_originales['estado'] = $datos_originales['estado'] == 1 ? 'Activo' : 'Inactivo';

    try {
        $actualizar = presentacion_model::actualizar_estado("0", $id_presentacion);

        if (!$actualizar) {
            alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al modificar el estado una presentación.","error");
        }

    } catch (Exception $e) {
        alert_model::alerta_simple("Ocurrido un error!", "No se pudo modificar el estado la presentación debido a un error de consulta.", "error");
        exit();
    }
    

    // se realiza la bitácora con los datos del presentación a registrar
    try {

        $datos_actuales = presentacion_model::consultar_por_id($id_presentacion);
        $datos_actuales = mysqli_fetch_array($datos_actuales);
        $datos_actuales['estado'] = $datos_actuales['estado'] == 1 ? 'Activo' : 'Inactivo';
        
        $cambios = [
            "descripocion" => config_model::obtener_comparacion(
                [$datos_originales['presentacion'].' '.$datos['representacion'], $datos_originales['presentacion'].' '.$datos['representacion']], 
                [$datos_actuales['presentacion'].' '.$datos_actuales['representacion'], $datos_actuales['presentacion'].' '.$datos_actuales['representacion']]),

            "estado" => config_model::obtener_comparacion(
                [$datos_originales['estado'], $datos_originales['estado']], 
                [$datos_actuales['estado'], $datos_actuales['estado']])
        ];

        presentacion_model::bitacora_modificar_estado_presentacion($cambios);

        alert_model::alert_mod_success_and_close_modal();

        exit();
    } catch (Exception $e) {
        alert_model::alert_mod_error();
        exit();
    }
}

if ($modulo === "inactivo") {
    
    $datos_originales = presentacion_model::consultar_por_id($id_presentacion);
    $datos_originales = mysqli_fetch_array($datos_originales);
    $datos_originales['estado'] = $datos_originales['estado'] == 1 ? 'Activo' : 'Inactivo';

    try {
        $actualizar = presentacion_model::actualizar_estado("1", "$id_presentacion");

        if (!$actualizar) {
            alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al modificar el estado una presentación.","error");
        }

    } catch (Exception $e) {
        alert_model::alerta_simple("Ocurrido un error!", "No se pudo modificar el estado la presentación debido a un error de consulta.", "error");
        exit();
    }
    

    // se realiza la bitácora con los datos del presentación a registrar
    try {

        $datos_actuales = presentacion_model::consultar_por_id($id_presentacion);
        $datos_actuales = mysqli_fetch_array($datos_actuales);
        $datos_actuales['estado'] = $datos_actuales['estado'] == 1 ? 'Activo' : 'Inactivo';

        $presentacion_original = $datos_originales['presentacion'].' '.$datos_originales['representacion'];
        $presentacion_actual = $datos_actuales['presentacion'].' '.$datos_actuales['representacion'];
        
        $cambios = [
            "descripcion" => config_model::obtener_comparacion(
                [$presentacion_original, $presentacion_original], 
                [$presentacion_actual, $presentacion_actual]),

            "estado" => config_model::obtener_comparacion(
                [$datos_originales['estado'], $datos_originales['estado']], 
                [$datos_actuales['estado'], $datos_actuales['estado']])
        ];

        presentacion_model::bitacora_modificar_estado_presentacion($cambios);

        alert_model::alert_mod_success_and_close_modal();

        exit();
    } catch (Exception $e) {
        alert_model::alert_mod_error();
        exit();
    }
}
