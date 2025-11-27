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
    
    $nombre = modeloPrincipal::primeraLetraMayus(modeloPrincipal::limpiar_cadena($_POST['nombre_marca']));

    // Se verifica que no se hayan recibido campos vacíos.
    modeloPrincipal::validar_campos_vacios([$nombre]);
    
    // se comprueba que no exista un registro con los mismos datos
    marca_model::verificar_existe_marca_unica($nombre);

    if (modeloPrincipal::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{3,50}",$nombre)) {
        alert_model::alert_of_format_wrong("nombre");
        exit();
    }
    
    // se registran los datos del presentación
    try {
        $registrar = marca_model::registrar($nombre);
        
        if (!$registrar) {
            alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al registrar una presentación.","error");
        }
    } catch (Exception $e) {
        alert_model::alerta_simple("Ocurrido un error!", "No se pudo registrar la presentación debido a un error de consulta.", "error");
        exit();
    }
    
    // se realiza la bitácora con los datos del presentación a registrar
    try {
        $id_marca = marca_model::obtener_id_recien_registrada();

        $datos_originales = marca_model::consultar_por_id($id_marca);
        $datos_originales = mysqli_fetch_array($datos_originales);
        $datos_originales['estado'] = $datos_originales['estado'] == 1 ? 'Activo' : 'Inactivo';

        bitacora::bitacora("Registro exitoso de una Marca.",
        '<p class="mb-3 text-primary-emphasis text-center"><i class="bi bi-exclamation-circle-fill"></i>&nbsp;Se registró una Marca con la siguiente informacón.</p> 
            <h4 class="text-center card-title"><b> Información de la Marca </b></h4>
            <div class="d-flex justify-content-between border-bottom"> <p> Nombre</p> '.$datos_originales['nombre'].' </div>');

        alert_model::alert_reg_success_and_close_modal();
        
        exit();
    } catch (Exception $e) {
        alert_model::alert_reg_error();
        exit();
    }
}


$id_marca = modeloPrincipal::decryptionId($_POST["UID"]);
$id_marca = modeloPrincipal::limpiar_cadena($id_marca);

if ($modulo === "activo") {
    
    $datos_originales = marca_model::consultar_por_id($id_marca);
    $datos_originales = mysqli_fetch_array($datos_originales);
    $datos_originales['estado'] = $datos_originales['estado'] == 1 ? 'Activo' : 'Inactivo';

    try {
        $actualizar = marca_model::actualizar_estado("0", "$id_marca");
        
        if (!$actualizar) {
            alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al modificar el estado una categoría.","error");
        }

    } catch (Exception $e) {
        alert_model::alerta_simple("Ocurrido un error!", "No se pudo modificar el estado la categoría debido a un error de consulta.", "error");
        exit();
    }
    

    // se realiza la bitácora con los datos del categoría a registrar
    try {

        $datos_actuales = marca_model::consultar_por_id($id_marca);
        $datos_actuales = mysqli_fetch_array($datos_actuales);
        $datos_actuales['estado'] = $datos_actuales['estado'] == 1 ? 'Activo' : 'Inactivo';
        
        $cambios = [
            "nombre" => config_model::obtener_comparacion([$datos_originales['nombre'], $datos_originales['nombre']], [ $datos_actuales['nombre'], $datos_actuales['nombre']]),
            "estado" => config_model::obtener_comparacion([$datos_originales['estado'], $datos_originales['estado']], [ $datos_actuales['estado'], $datos_actuales['estado']])
        ];

        marca_model::bitacora_modificar_estado_marca ($cambios);

        alert_model::alert_mod_success_and_close_modal();
        exit();
    } catch (Exception $e) {
        alert_model::alert_mod_error();
        exit();
    }
}

if ($modulo === "inactivo") {

    $datos_originales = marca_model::consultar_por_id($id_marca);
    $datos_originales = mysqli_fetch_array($datos_originales);
    $datos_originales['estado'] = $datos_originales['estado'] == 1 ? 'Activo' : 'Inactivo';

    try {
        $actualizar = marca_model::actualizar_estado("1", "$id_marca");
        
        if (!$actualizar) {
            alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al modificar el estado una categoría.","error");
        }
    } catch (Exception $e) {
        alert_model::alerta_simple("Ocurrido un error!", "No se pudo modificar el estado la categoría debido a un error de consulta.", "error");
        exit();
    }
    

    // se realiza la bitácora con los datos del categoría a registrar
    try {

        $datos_actuales = marca_model::consultar_por_id($id_marca);
        $datos_actuales = mysqli_fetch_array($datos_actuales);
        $datos_actuales['estado'] = $datos_actuales['estado'] == 1 ? 'Activa' : 'Inactiva';

        $cambios = [
            "nombre" => config_model::obtener_comparacion([$datos_originales['nombre'], $datos_originales['nombre']], [ $datos_actuales['nombre'], $datos_actuales['nombre']]),
            "estado" => config_model::obtener_comparacion([$datos_originales['estado'], $datos_originales['estado']], [ $datos_actuales['estado'], $datos_actuales['estado']])
        ];

        marca_model::bitacora_modificar_estado_marca ($cambios);

        alert_model::alert_mod_success_and_close_modal();
        exit();
    } catch (Exception $e) {
        alert_model::alert_mod_error();
        exit();
    }
}