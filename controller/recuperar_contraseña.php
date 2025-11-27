<?php 
session_start();

include_once "../include/modelos_include.php"; // se incluyen los modelos necesarios para la vista

/* se recibe el modulo a trabajar */
$modulo = modeloPrincipal::LimpiarCadenaTexto($_POST['modulo']);

/*******************************************************************/ 
/* MODULO DE RECUPERACION DE CONTRASEÑA POR PREGUNTAS SECRETAS     */
/*******************************************************************/ 
if($modulo == 'verificar_preguntas'){
    
    // Se verifica que no se hayan recibido campos vacíos.
    modeloPrincipal::validar_campos_vacios([$_POST['respuesta_seguridad']]);
    
    $id_usuario = modeloPrincipal::decryptionId($_POST['UUID']);
    $_SESSION['UUID'] = modeloPrincipal::decryptionId($_POST['UUID']);

    $numero_pregunta = modeloPrincipal::decryptionId($_POST['NPU']);
    
    $respuesta_pregunta = modeloPrincipal::Limpiar_mayusculas($_POST['respuesta_seguridad']); 
    
    // se hace una consulta para saber si el preguntas que inicia sesion esta registrado
    $existe_respuesta = mysqli_fetch_array(modeloPrincipal::consultar("SELECT respuesta FROM preguntas_secretas WHERE id_usuario = '$id_usuario' AND numero_pregunta = '$numero_pregunta'"));
    $existe_respuesta = modeloPrincipal::decryption($existe_respuesta['respuesta']);

    // si las respuestas coinciden se envia una alerta de validacion exitosa
    if ($existe_respuesta == $respuesta_pregunta) {
        alert_model::alerta_condicional('¡Verificación Exitosa!','','success',"show_form_password();");
        exit();
    }else{
        alert_model::alerta_simple("¡Ocurrió un error!","La respuesta ingresada es incorrecta, verifique he intente nuevamente.","error");
        exit();
    }
}


/*******************************************************************/ 
/*          modulo para Cambiar Contraseña del usuario             */
/*******************************************************************/ 
if($modulo === "cambiar_contraseña"){

    $contraseña = modeloPrincipal::LimpiarCadenaTexto($_POST['nueva_contraseña']);
    $contraseña2 = modeloPrincipal::LimpiarCadenaTexto($_POST['repite_nueva_contraseña2']);
    $id_usuario =  modeloPrincipal::decryptionId($_POST['UUID']);
    $id_usuario =  modeloPrincipal::LimpiarCadenaTexto($id_usuario);

    modeloprincipal::validar_campos_vacios([$_POST["nueva_contraseña"], $_POST['repite_nueva_contraseña2'], $_POST['UUID']]); // se verifica si se recibieron campos vacios

    // se obtiene la configuracion de la base de datos
    $configuracion = ['caracteres' => config_model::obtener_dato('c_caracteres'),
        'simbolos' => config_model::obtener_dato('c_simbolos'),
        'numeros' => config_model::obtener_dato('c_numeros')];
    
    if (isset($_POST['nueva_contraseña']) && isset($_POST['repite_nueva_contraseña2'])) {
        $contraseña = modeloprincipal::limpiar_cadena($_POST["nueva_contraseña"]);
        $contraseña2 = modeloprincipal::limpiar_cadena($_POST['repite_nueva_contraseña2']);
    }
    
    if($contraseña !== $contraseña2){
        alert_model::alerta_simple("¡Ocurrió un error!","Las contraseñas que ingresaste no coinciden. Por favor, verifica que las hayas escrito correctamente.","error");
        exit();
    }

    if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{'.$configuracion['caracteres'].',60}$/', $contraseña)) {
        alert_model::alerta_simple("Ocurrio un error!", "La contraseña no cumple con los requisitos de seguridad, Puede contener menos 1 número y 1 letra, Puede contener al menos ".$configuracion['simbolos']." de estos caracteres:!@#$% y Debe tener entre ".$configuracion['caracteres']." y 60 caracteres., verifique e intente nuevamente.","error");
        exit();
    }
    
    // Contar símbolos (no alfanuméricos)
    $simbolosContraseña = preg_match_all("/\W/", $contraseña);
    if($simbolosContraseña < $configuracion['simbolos']){
        alert_model::alerta_simple("¡Ocurrio un error!", "la contraseña no cumple con la cantidad de caracteres mínima que es ".$configuracion['simbolos'].", verifique e intente nuevamente.","error");
        exit();
    }
    
    // Contar números
    // echo $numeros = preg_match_all("/[0-9]/", $contraseña); // no creo que debas dejarle el echo
    $numeros = preg_match_all("/[0-9]/", $contraseña);

    if($numeros < $configuracion['numeros']){
        alert_model::alerta_simple("¡Ocurrio un error!", "la contraseña no cumple con la cantidad de números mínima que es ".$configuracion['numeros'].", verifique e intente nuevamente.","error");
        exit();
    }

    // verificar datos
    if (modeloPrincipal::verificar_datos("[!@#$%A-Za-zñÑÁÉÍÚÓáéíóúñÑ0-9\*\.]{3,200}",$contraseña)) {
        alert_model::alerta_simple("¡Ocurrió un error!","La contraseña no cumple con el formato establecido. ","error");
        exit();
    }

    // Verificar si la contraseña cumple con la nueva longitud mínima
    if(strlen($contraseña) < $configuracion['caracteres']){
        alert_model::alerta_simple("¡Ocurrio un error!", "la contraseña no cumple con la logitud mínima establecida que es ".$configuracion['caracteres'].", verifique e intente nuevamente.","error");
        exit();
    }
    
    $contraseña = modeloPrincipal::hashear_contrasena($contraseña);

    // actualizar contraseña
    if(modeloPrincipal::UpdateSQL("usuario","contraseña = '$contraseña'","id_usuario = '$id_usuario'")){
        alert_model::alert_redirect('Modificación exitosa!','La contraseña se modificó correctamente.','success',"../");
        session_unset();
        session_destroy();
        exit();
    }else {
        alert_model::alert_mod_error();
        exit();
    }
}

