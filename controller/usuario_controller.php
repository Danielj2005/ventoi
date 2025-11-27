<?php 
session_start();

include_once "../include/modelos_include.php"; // se incluyen los modelos necesarios para la vista

// se obtiene la configuracion de la base de datos
$configuracion = ['caracteres' => config_model::obtener_dato('c_caracteres'),
    'simbolos' => config_model::obtener_dato('c_simbolos'),
    'numeros' => config_model::obtener_dato('c_numeros')];


// modulo a trabajar
$modulo = modeloprincipal::limpiar_cadena($_POST["modulo"]);

if (!isset($_POST["modulo"])) {
    alert_model::alerta_simple("Ocurrio un error!","Ha ocurrido un error al procesar tu solicitud","error");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// modulo para Guardar un registro de un usuario

if($modulo === "Guardar"){

    /*------------------ información personal de el usuario ------------------*/
    $cedula = modeloprincipal::limpiar_cadena($_POST["nacionalidad"].$_POST["cedula"]);
    $nombre = modeloprincipal::limpiar_mayusculas($_POST["nombre"]);
    $apellido = modeloprincipal::limpiar_mayusculas($_POST["apellido"]);
    $telefono = modeloprincipal::limpiar_cadena($_POST["telefono"]);
    $direccion = modeloprincipal::limpiar_mayusculas($_POST["direccion"]);
    
    /*------------------ datos de el usuario ------------------*/
    $correo =  modeloprincipal::limpiar_cadena($_POST["correo"]);
    $contraseña = modeloprincipal::limpiar_cadena($_POST["cedula"]);
    
    $id_rol =  modeloprincipal::decryptionId($_POST["id_tipo"]);
    $id_rol =  modeloprincipal::limpiar_cadena($id_rol);
    
    // se comprueba que no exista un registro con los mismos datos
    model_user::validar_usuario_existe("cedula, correo","correo = '$correo' AND cedula = '$cedula'");
    // Se verifica que no se hayan recibido campos vacíos.
    modeloPrincipal::validar_campos_vacios([$cedula, $nombre, $apellido, $correo, $contraseña, $telefono, $direccion, $id_rol]);

    
    if (modeloPrincipal::verificar_datos("[V|E|J|P][0-9|-]{7,10}",$cedula)) {
        alert_model::alerta_simple("¡Ocurrio un error!","El campo cédula no cumple con el formato requerido o fue alterado. Por favor verifique e intente de nuevo ", "error");
        exit();
    }

    if (modeloPrincipal::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)) {
        alert_model::alert_of_format_wrong("'nombre'");
        exit();
    }

    if (modeloPrincipal::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$apellido)) {
        alert_model::alert_of_format_wrong("'apellido'");
        exit();
    } 

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        alert_model::alert_of_format_wrong("'correo'");
        exit();
    }

    if (modeloPrincipal::verificar_datos("[0-9]{11}",$telefono)) {
        alert_model::alert_of_format_wrong("'teléfono'");
        exit();
    }

    if (modeloprincipal::verificar_datos("[A-Za-zÁÉÍÚÓáéíóúñÑ0-9|-|, ]{5,50}",$direccion)) {
        alert_model::alert_of_format_wrong("'dirección'");
        exit();
    }

    if (modeloprincipal::verificar_datos("[A-Za-zñÑÁÉÍÚÓáéíóúñÑ0-9\.\*\_\-]{8,16}", $contraseña)) {
        alert_model::alert_of_format_wrong("'contraseña'");
        exit();
    }

    $contraseña = modeloPrincipal::hashear_contrasena($contraseña);
    
    // datos verificados que se van a Registrar
    try {
        $registrar = model_user::insert_user($cedula, $nombre, $apellido, $correo, $contraseña, $telefono, $direccion, $id_rol);
        
        if (!$registrar) {
            alert_model::alerta_simple("¡Ocurrió un error!","No se pudo registrar al usuario en el sistema.","error");
        }

    } catch (Exception $e) {
        alert_model::alerta_simple("Ocurrido un error!", " ocurrio un error al registrar al usuario en el sistema.", "error");
        exit();
    }
    

    try {

        model_user::asignar_preguntas_seguridad_usuario();

    } catch (Exception $e) {
        alert_model::alerta_simple("Ocurrido un error!", "ocurrio un error al asignar preguntas de seguridad del usuario", "error");
        exit();
    }

    try {

        $id_usuario = model_user::obtener_id_usuario_recien_registrado();
        $rol_asignado = model_user::obtener_info_de_un_usuario('id_rol',$id_usuario);

        bitacora::bitacora("Registro exitoso de un nuevo usuario.",'<p class="mb-3 text-primary-emphasis text-center"><i class="bi bi-exclamation-circle-fill"></i>&nbsp;Se registró un nuevo usuario con la siguiente informacón.</p> 
            <h4 class="text-center card-title"><b> Información del usuario </b></h4>
            <div class="d-flex justify-content-between border-bottom"> <p> Cédula</p> <span>'.$cedula.'</span> </div>
            <div class="d-flex justify-content-between border-bottom"> <p> Nombre y Apellido</p> <span>'.$nombre.' '.$apellido.'</span> </div>
            <div class="d-flex justify-content-between border-bottom"> <p> Correo</p> <span>'.$correo.'</span>  </div>
            <div class="d-flex justify-content-between border-bottom"> <p> Dirección</p> <span>'.$direccion.'</span> </div>
            <div class="d-flex justify-content-between border-bottom"> <p> Teléfono</p> <span>'.$telefono.'</span> </div>
            <div class="d-flex justify-content-between border-bottom"> <p> Rol asignado</p> <span>'.$rol_asignado.'</span> </div>');

        alert_model::alert_reg_success();
        exit();
    } catch (Exception $e) {
        alert_model::alert_reg_error();
        exit();
    }


}

// modulo para Modificar informacion personal de un usuario

if($modulo === "modificar_info_personal_usuario"){
    
    /*------------------ información personal de el usuario ------------------*/
    $cedula = modeloprincipal::limpiar_cadena($_POST["nacionalidad"].$_POST["cedula"]);
    $nombre = modeloprincipal::limpiar_mayusculas($_POST["nombres"]);
    $apellido = modeloprincipal::limpiar_mayusculas($_POST["apellido"]);
    $correo =  modeloprincipal::limpiar_cadena($_POST["email"]);
    $direccion = modeloprincipal::limpiar_mayusculas($_POST["direccion"]);
    $telefono = modeloprincipal::limpiar_cadena($_POST["telefono"]);

    // Se verifica que no se hayan recibido campos vacíos.
    modeloPrincipal::validar_campos_vacios([$cedula, $nombre, $apellido, $correo, $telefono, $direccion]);

    if (modeloprincipal::verificar_datos("[V|E|J|P][0-9|-]{7,10}",$cedula)) {
        alert_model::alert_of_format_wrong("CÉDULA");
        exit();
    }
    if (modeloprincipal::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,60}",$nombre)) {
        alert_model::alert_of_format_wrong("NOMBRE");
        exit();
    }
    if (modeloprincipal::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,60}",$apellido)) {
        alert_model::alert_of_format_wrong("APELLIDO");
        exit();
    } 
    if (modeloprincipal::verificar_datos("[A-Za-zÁÉÍÚÓáéíóúñÑ@.0-9]{11,100}",$correo)) {
        alert_model::alert_of_format_wrong("CORREO");
        exit();
    }
    if (modeloprincipal::verificar_datos("[0-9]{11}",$telefono)) {
        alert_model::alert_of_format_wrong("'teléfono'");
        exit();
    }
    if (modeloprincipal::verificar_datos("[A-Za-zÁÉÍÚÓáéíóúñÑ0-9-, ]{10,250}",$direccion)) {
        alert_model::alert_of_format_wrong("'dirección'");
        exit();
    }

    $cedula_user = $_SESSION['dataUsuario']['dni'];
    $nombre_user = $_SESSION['dataUsuario']['nombre'];
    $apellido_user = $_SESSION['dataUsuario']['apellido'];
    $correo_user = $_SESSION['dataUsuario']['correo'];
    $telefono_user = $_SESSION['dataUsuario']['telefono'];
    $direccion_user = $_SESSION['dataUsuario']['direccion'];

    // Se actualizara la información personal del usuario
    try {
        $actualizar = modeloPrincipal::UpdateSQL("usuario","cedula = '$cedula', nombre = '$nombre', apellido = '$apellido', correo = '$correo', telefono = '$telefono', direccion = '$direccion'", "id_usuario = $id_usuario");
        
        $_SESSION['dataUsuario']['dni'] = $cedula;
        $_SESSION['dataUsuario']['nombre'] = $nombre ;
        $_SESSION['dataUsuario']['apellido'] = $apellido ;
        $_SESSION['dataUsuario']['correo'] = $correo ;
        $_SESSION['dataUsuario']['telefono'] = $telefono ;
        $_SESSION['dataUsuario']['direccion'] = $direccion ;

        if (!$actualizar) {
            alert_model::alerta_simple("Ha ocurrido un error!", "ocurrio un error al actualizar la información personal del usuario.", "error");
            exit();
        }

        $cambios = [
            "dni" => config_model::obtener_comparacion([$cedula_user, $cedula_user], [ $cedula, $cedula]),

            "nombre" => config_model::obtener_comparacion([$nombre_user, $nombre_user], [ $nombre, $nombre]),

            "apellido" => config_model::obtener_comparacion([$apellido_user, $apellido_user], [ $apellido, $apellido]),

            "correo" => config_model::obtener_comparacion([$correo_user, $correo_user], [ $correo, $correo]),

            "telefono" => config_model::obtener_comparacion([$telefono_user, $telefono_user], [ $telefono, $telefono]),

            "direccion" => config_model::obtener_comparacion([$direccion_user, $direccion_user], [ $direccion, $direccion])
        ];

        $bitacora_modificacion_info_usuario = bitacora::bitacora("Modificación exitosa del perfil de usuario",
        '<p class="mb-3 text-primary-emphasis text-center"><i class="bi bi-exclamation-circle-fill"></i>&nbsp;El usuario actualizó la configuración del sistema.</p> 
                <h4 class="text-center card-title"><b> Información del usuario </b></h4>
                <div class="d-flex justify-content-between border-bottom">
                    <p> Cédula</p>
                    '.$cambios['dni'].'
                </div>
                <div class="d-flex justify-content-between border-bottom">
                    <p> Nombre</p>
                    '.$cambios['nombre'].'
                </div>
                <div class="d-flex justify-content-between border-bottom">
                    <p> Apellido</p>
                    '.$cambios['apellido'].'
                </div>
                <div class="d-flex justify-content-between border-bottom">
                    <p> Correo</p>
                    '.$cambios['correo'].'
                </div>
                <div class="d-flex justify-content-between border-bottom">
                    <p> Dirección</p>
                    '.$cambios['telefono'].'
                </div>
                <div class="d-flex justify-content-between border-bottom">
                    <p> Teléfono</p>
                    '.$cambios['direccion'].'
                </div>');

        if (!$bitacora_modificacion_info_usuario) {
            alert_model::alerta_simple("Ha ocurrido un error!", "ocurrio un error al guardar la modificación en bitácora.", "error");
            exit();
        }

        alert_model::alert_mod_success();
        exit();
    } catch (Exception $e) {
        alert_model::alert_mod_error();
        exit();
    }
    
}

// modulo para Modificar contraseña de un usuario

if($modulo === "modificar_contraseña_usuario"){
    
    $contraseña_actual = modeloprincipal::limpiar_cadena($_POST["current_password"]);
    
    modeloprincipal::validar_campos_vacios([$_POST["current_password"], $_POST['password2'], $_POST['password']]); // se verifica si se recibieron campos vacios
    
    // primero se evalua si la contraseña actual ingresada es correcta para proceder con las validaciones de la contraseña nueva
    $contraseña_haseada = modeloPrincipal::hashear_contrasena($contraseña_actual);
    $hash_guardado_en_bd = mysqli_fetch_assoc(modeloprincipal::consultar("SELECT contraseña FROM usuario WHERE id_usuario = '$id_usuario'"))["contraseña"];
    // se verifica que la contraseña coincida con la guardad en la base de datos
    if(password_verify($contraseña_actual, $hash_guardado_en_bd)){
        alert_model::alerta_simple("¡Ocurrio un error!", "La contraseña actual que ingresaste es incorrecta, verifique e intente nuevamente.","error");
        exit();
    }
    // se verifica que se está recibiendo la contraseña nueva
    if (isset($_POST['password']) && isset($_POST['password2'])) {
        $contraseña_nueva = modeloprincipal::limpiar_cadena($_POST["password"]);
        $contraseña_nueva2 = modeloprincipal::limpiar_cadena($_POST['password2']);
    }
    
    if($contraseña_nueva !== $contraseña_nueva2){
        alert_model::alerta_simple("¡Ocurrió un error!","Las contraseñas que ingresaste no coinciden. Por favor, verifica que las hayas escrito correctamente.","error");
        exit();
    }

    // if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{'.$configuracion['caracteres'].',60}$/', $contraseña_nueva)) {
    //     alert_model::alerta_simple("Ocurrio un error!", "La contraseña no cumple con los requisitos de seguridad, Puede contener menos 1 número y 1 letra, Puede contener al menos ".$configuracion['simbolos']." de estos caracteres:!@#$% y Debe tener entre ".$configuracion['caracteres']." y 60 caracteres., verifique e intente nuevamente.","error");
    //     exit();
    // }
    
    if (modeloprincipal::verificar_datos("[!@#$%A-Za-z0-9\-]{".$configuracion['caracteres'].",60}", $contraseña_nueva)) {
        // alert_model::alert_of_format_wrong("'contraseña nueva'");
        alert_model::alerta_simple("Ocurrio un error!", "La contraseña no cumple con los requisitos de seguridad, Puede contener menos 1 número y 1 letra, Puede contener al menos ".$configuracion['simbolos']." de estos caracteres:!@#$% y Debe tener entre ".$configuracion['caracteres']." y 60 caracteres., verifique e intente nuevamente.","error");

        exit();
    }

    // Contar símbolos (no alfanuméricos)
    $simbolosContraseña = preg_match_all("/\W/", $contraseña_nueva);
    if($simbolosContraseña < $configuracion['simbolos']){
        alert_model::alerta_simple("¡Ocurrio un error!", "la contraseña no cumple con la cantidad de caracteres mínima que es ".$configuracion['simbolos'].", verifique e intente nuevamente.","error");
        exit();
    }
    // Contar números
    $numeros = preg_match_all("/[0-9]/", $contraseña_nueva);

    if($numeros < $configuracion['numeros']){
        alert_model::alerta_simple("¡Ocurrio un error!", "la contraseña no cumple con la cantidad de números mínima que es ".$configuracion['numeros'].", verifique e intente nuevamente.","error");
        exit();
    }

    try {

        $actualizar = modeloprincipal::UpdateSQL("usuario","contraseña = '$contraseña_haseada'","id_usuario = $id_usuario");

        if (!$actualizar) {
            alert_model::alerta_simple("Ha ocurrido un error!", "ocurrio un error al guardar la nueva contraseña .", "error");
            exit();
        }

        $primer_inicio = model_user::verificar_primer_inicio();

        if ($primer_inicio) {
            modeloPrincipal::UpdateSQL("usuario", "primer_inicio = 1", "id_usuario = '$id_usuario'");
        }else{
            modeloPrincipal::UpdateSQL("usuario", "primer_inicio = 0", "id_usuario = '$id_usuario'");
        }

        model_user::bitacora_modificacion_contraseña();

        alert_model::alert_mod_success();
        exit();
    } catch (Exception $e) {
        alert_model::alert_mod_error();
        exit();
    }
}

// modulo para modificar preguntas de seguridad de un usuario

if ($modulo === "modificar_preguntas_seguridad") {

    $id_usuario = $_SESSION['id_usuario']; // ID del usuario actual
    $id_usuario = modeloprincipal::limpiar_cadena($id_usuario); // Limpiar el ID del usuario

    // Obtener la cantidad de preguntas configuradas en el sistema
    $configuracion = modeloPrincipal::consultar("SELECT c_preguntas FROM configuracion");
    if (!$configuracion || mysqli_num_rows($configuracion) == 0) {
        alert_model::alerta_simple("Ha ocurrido un error!", "No se pudo obtener la configuración de preguntas de seguridad.", "error");
        exit();
    }

    $cantidad_preguntas = intval(mysqli_fetch_array($configuracion)['c_preguntas']);
    
    // Obtener las preguntas y respuestas enviadas por el usuario
    $preguntas = $_POST['pregunta'] ?? [];
    $respuestas = $_POST['respuesta'] ?? [];

    model_user::validar_preguntas_de_seguridad($preguntas,$respuestas);

    // Validar que las preguntas y respuestas sean la cantidad correcta
    if (count($preguntas) < $cantidad_preguntas || count($respuestas) < $cantidad_preguntas) {
        alert_model::alerta_simple("Ha ocurrido un error!", "Debe completar todas las preguntas de seguridad.", "error");
        exit();
    }

    // Validar que las preguntas y respuestas no estén vacías
    try {
        modeloPrincipal::validar_campos_vacios([$preguntas, $respuestas]);
        
        if (count($preguntas) !== count(array_unique($preguntas))) {
            alert_model::alerta_simple("Ha ocurrido un error!", "Las preguntas de seguridad no pueden estar repetidas.", "error");
            exit();
        }
        if (count($preguntas) !== count(array_unique($respuestas))) {
            alert_model::alerta_simple("Ha ocurrido un error!", "Las respuestas de seguridad no pueden estar repetidas.", "error");
            exit();
        }
    } catch (Exception $e) {
        alert_model::alerta_simple("Ha ocurrido un error!", "Debe completar todas las preguntas de seguridad.", "error");
        exit();
    }

    $id_seguridad = [];
    $id_preguntas = [];

    // se obtiene las id de las preguntas de seguridad
    try {
        
            for ($i = 0; $i < $cantidad_preguntas; $i++) {
                // Obtener la pregunta actual
                $pregunta_encriptada = modeloPrincipal::encryption($preguntas[$i]);
                        
                $pregunta_encriptada = trim($pregunta_encriptada);
                $pregunta_encriptada = stripslashes($pregunta_encriptada);
                $pregunta_encriptada = str_ireplace(" ", "", $pregunta_encriptada);
                $pregunta_encriptada = stripslashes($pregunta_encriptada);
                $pregunta_encriptada = trim($pregunta_encriptada);

                $id_seguridades = modeloPrincipal::consultar("SELECT id_seguridad FROM seguridad WHERE pregunta = '$pregunta_encriptada'");

                if (!$id_seguridades || mysqli_num_rows($id_seguridades) == 0) {
                    alert_model::alerta_simple("Ha ocurrido un error!", "ocurrio un error al consultar la ID de la pregunta de seguridad.", "error");
                    exit();
                }
                
                $id_seguridades = mysqli_fetch_array($id_seguridades)['id_seguridad'];
                
                $id_seguridad[$i] = $id_seguridades;

            }
    } catch (Exception $e) {
        alert_model::alerta_simple("Ha ocurrido un error!", "No se pudo obtener la ID de las preguntas de seguridad.", "error");
        exit();
    }
    
    // 2. Borrar todos las preguntas y respuestas del usuario
    modeloPrincipal::DeleteSQL("preguntas_secretas", "id_usuario = $id_usuario");
    
    try {

        $numero_pregunta = 1;
        for ($i = 0; $i < $cantidad_preguntas; $i++) {

            // Encriptar la nueva respuesta
            $respuesta_encriptada = modeloPrincipal::limpiar_mayusculas_encriptar($respuestas[$i]);
            
            $actualizar = modeloPrincipal::InsertSQL("preguntas_secretas", "id_pregunta, respuesta, numero_pregunta, id_usuario", "".$id_seguridad[$i].", '$respuesta_encriptada', $numero_pregunta, $id_usuario");
            
            $numero_pregunta++;
            
            if (!$actualizar) {
                alert_model::alerta_simple("Ha ocurrido un error!", "ocurrio un error al actualizar la pregunta de seguridad.", "error");
                exit();
            } 
        }

        $primer_inicio = model_user::verificar_primer_inicio();

        if ($primer_inicio) {
            modeloPrincipal::UpdateSQL("usuario", "primer_inicio = 1", "id_usuario = '$id_usuario'");
            $_SESSION['dataUsuario']["primerInicio"] = 1;
        }else{
            modeloPrincipal::UpdateSQL("usuario", "primer_inicio = 0", "id_usuario = '$id_usuario'");
            $_SESSION['dataUsuario']["primerInicio"] = 0;
        }
        // Registrar la modificación en la bitácora
        bitacora::bitacora("Modificación exitosa del perfil de usuario",'<p class="mb-3 h2 text-primary-emphasis text-center"><i class="bi bi-exclamation-circle-fill"></i>&nbsp;El usuario actualizó sus preguntas de seguridad.</p> ');
        // Mostrar mensaje de éxito
        alert_model::alert_mod_success();
    } catch (Exception $e) {
        alert_model::alerta_simple("¡Error inesperado!", "Ocurrió un error al actualizar las preguntas de seguridad. Por favor, intente nuevamente.", "error");
        exit();
    }
}

// modulo para modificar las caracteristicas de acceso de un usuario

if ($modulo === 'caracteristicas_de_acceso'){
    // caracteristicas a actualizar del usuario
    $id_usuario = modeloPrincipal::decryptionId($_POST['UIDTM']);
    $id_usuario = modeloPrincipal::LimpiarCadenaTexto($id_usuario);

    $nuevo_estado = modeloPrincipal::LimpiarCadenaTexto($_POST['cambiar_estado']);
    $rol_asignado = modeloPrincipal::LimpiarCadenaTexto($_POST['asignar_rol']);

    $cedula = modeloPrincipal::LimpiarCadenaTexto($_POST['cedula_user']); 
    $nombre = modeloPrincipal::LimpiarCadenaTexto($_POST['nombre_completo']); 
    $telefono = modeloPrincipal::LimpiarCadenaTexto($_POST['telefono_user']);

    // se evaluan los campos y que no estén vacíos
    modeloPrincipal::validar_campos_vacios([$id_usuario, $nuevo_estado, $rol_asignado, $cedula, $nombre, $telefono]);
    
    // se evaluan que los campos cumplan con el formato establecido
    if (modeloprincipal::verificar_datos("[0-1]{1}",$nuevo_estado)) {
        alert_model::alert_of_format_wrong("'estado'");
        exit();
    }
    
    if (modeloprincipal::verificar_datos("[0-9]{1,5}",$rol_asignado)) {
        alert_model::alert_of_format_wrong("'rol'");
        exit();
    }

    
    try {
        // caracteristicas originales del usuario
        $estado_original = model_user::obtener_info_personal_usuario('estado',$id_usuario);
        $rol_original = model_user::obtener_info_de_un_usuario('id_rol',$id_usuario);
        $bloqueado_original = model_user::obtener_info_personal_usuario('bloqueado',$id_usuario);
    
        $estado_original_usuario = ($estado_original == 1) ? 'Activo' : 'Inactivo' ;
        $bloqueado_original = ($bloqueado_original == 1) ? 'Sí' : 'No' ;

        
    } catch (Exception $e) {
        alert_model::alerta_simple("Ha ocurrido un error!", "ocurrio un error al obtener las caracteristicas originales.", "error");
        exit();
    }

    // se actualizan las caracteristicas del usuario
    try {
        
        $actualizar_usuario = model_user::actualizar_usuario_por_su_id ("estado = $nuevo_estado, id_rol = $rol_asignado",$id_usuario);
        
        if (!$actualizar_usuario) {
            alert_model::alerta_simple("Ha ocurrido un error!", "ocurrio un error al actualizar las características de acceso del usuario.", "error");
            exit();
        }

        // caracteristicas actuales del usuario
        $estado_actual = model_user::obtener_info_personal_usuario('estado',$id_usuario);
        $rol_actual = model_user::obtener_info_de_un_usuario('id_rol',$id_usuario);
        $bloqueado_actual = model_user::obtener_info_personal_usuario('bloqueado',$id_usuario);

        $estado_user_actual = ($estado_actual == 1) ? 'Activo' : 'Inactivo' ;
        $bloqueado_actual = ($bloqueado_actual == 1) ? 'Sí' : 'No' ;

        $cambios = [
            "estado" => config_model::obtener_comparacion([$estado_original_usuario, $estado_original_usuario], [ $estado_user_actual, $estado_user_actual]),
            "rol" => config_model::obtener_comparacion([$rol_original, $rol_original], [ $rol_actual, $rol_actual]),
            "bloqueado" => config_model::obtener_comparacion([$bloqueado_original, $bloqueado_original], [ $bloqueado_actual, $bloqueado_actual]),
        ];

        $bitacora = bitacora::bitacora("Modificación exitosa de las características de acceso de un usuario",
        '<p class="mb-3 text-primary-emphasis"><i class="bi bi-exclamation-circle-fill"></i>&nbsp;Se Restableció el Acceso al Sistema del Usuario con la Siguiente Información: </p> 
            <h4 class="text-center card-title"><b> Información del Usuario Modificado: </b></h4>
            <div class="d-flex justify-content-between border-bottom">
                <p> Cédula</p>
                '.$cedula.'
            </div>
            <div class="d-flex justify-content-between border-bottom">
                <p> Nombre y Apellido</p>
                '.$nombre.'
            </div>
            <div class="d-flex justify-content-between border-bottom">
                <p> Teléfono</p>
                '.$telefono.'
            </div>
            <div class="d-flex justify-content-between border-bottom">
                <p> Estado</p>
                '.$cambios['estado'].'
            </div>
            <div class="d-flex justify-content-between border-bottom">
                <p> Rol asignado</p>
                '.$cambios['rol'].'
            </div>
            <div class="d-flex justify-content-between border-bottom">
                <p> Bloqueado</p>
                '.$cambios['bloqueado'].'
            </div>');

        if (!$bitacora) {
            alert_model::alerta_simple("Ha ocurrido un error!", "ocurrio un error al registrar las características de acceso del usuario en la bitácora.", "error");
            exit();
        }

        alert_model::alert_mod_success();
        exit();
    } catch (Exception $e) {
        alert_model::alert_mod_error();
        exit();
    }
}

// modulo para resetear el acceso de un usuario

if ($modulo === 'resetear_contraseña'){

    // caracteristicas a actualizar del usuario
    $id_usuario = modeloPrincipal::decryptionId($_POST["UUIDU"]);
    
    modeloPrincipal::validar_campos_vacios([$id_usuario]);

    $existe_usuario = model_user::consulta_usuario_id("nombre, apellido, 
        primer_inicio, bloqueado, estado, id_rol",$id_usuario);

    if (!$existe_usuario) {
        alert_model::alerta_simple("¡Ocurrió un error inesperado!","No se encontraron datos del usuario asegúrese de que esté se encuentre registrado en el sistema, por favor verifique e intente nuevamente","error");
    }
    
    $cedula = model_user::obtener_info_personal_usuario('cedula', $id_usuario);
    $nombre = model_user::obtener_info_personal_usuario('nombre', $id_usuario);
    $apellido = model_user::obtener_info_personal_usuario('apellido', $id_usuario);
    $telefono = model_user::obtener_info_personal_usuario('telefono', $id_usuario);
    
    $cedula_reseteo = trim($cedula);
    $cedula_reseteo = str_ireplace("V", "", $cedula_reseteo);
    $cedula_reseteo = str_ireplace("E", "", $cedula_reseteo);
    $cedula_reseteo = str_ireplace("-", "", $cedula_reseteo);
    $cedula_reseteo = stripslashes($cedula_reseteo);
    $cedula_reseteo = trim($cedula_reseteo);
    $cedula_reseteo = modeloPrincipal::hashear_contrasena($cedula_reseteo);


    try {
        // caracteristicas originales del usuario
        $estado_original = model_user::obtener_info_personal_usuario('estado',$id_usuario);
        $rol_original = model_user::obtener_info_de_un_usuario('id_rol',$id_usuario);
        $bloqueado_original = model_user::obtener_info_personal_usuario('bloqueado',$id_usuario);

        $estado_original_usuario = ($estado_original == 1) ? 'Activo' : 'Inactivo' ;
        $bloqueado_original = ($bloqueado_original == 1) ? 'Sí' : 'No' ;
        
    } catch (Exception $e) {
        alert_model::alerta_simple("Ha ocurrido un error!", "ocurrio un error al obtener las caracteristicas originales.", "error");
        exit();
    }


    try {
        $desbloquear_usuario = modeloPrincipal::UpdateSQL("usuario", "contraseña = '$cedula_reseteo', sesion_activa = 0, primer_inicio = 1, bloqueado = 0, estado = 1", "id_usuario = '$id_usuario'");

        if (!$desbloquear_usuario) {
            alert_model::alerta_simple("¡Ocurrió un error inesperado!","No se pudo desbloquear al usuario debido a un error interno o alteracion de la información ya registrada, por favor verifique e intente nuevamente","error");
        }

        $actualizar = modeloPrincipal::UpdateSQL("preguntas_secretas", "respuesta = '$cedula_reseteo'", "id_usuario = '$id_usuario'");

        if (!$actualizar) {
            alert_model::alerta_simple("Ha ocurrido un error!", "ocurrio un error al resetear las preguntas de seguridad.", "error");
            exit();
        } 
        
        // caracteristicas actuales del usuario
        $estado_actual = model_user::obtener_info_personal_usuario('estado',$id_usuario);
        $rol_actual  = model_user::obtener_info_de_un_usuario('id_rol',$id_usuario);
        $bloqueado_actual = model_user::obtener_info_personal_usuario('bloqueado',$id_usuario);

        $estado_user_actual = ($estado_actual == 1) ? 'Activo' : 'Inactivo' ;
        $bloqueado_actual = ($bloqueado_actual == 1) ? 'Sí' : 'No' ;

        $cambios = [
            "estado" => config_model::obtener_comparacion([$estado_original_usuario, $estado_original_usuario], [ $estado_user_actual, $estado_user_actual]),
            "rol" => config_model::obtener_comparacion([$rol_original, $rol_original], [ $rol_actual, $rol_actual]),
            "bloqueado" => config_model::obtener_comparacion([$bloqueado_original, $bloqueado_original], [ $bloqueado_actual, $bloqueado_actual]),
        ];

        bitacora::bitacora("Modificación Exitosa del Acceso de un Usuario.", 
        '<p class="mb-3 text-primary-emphasis text-center"><i class="bi bi-exclamation-circle-fill"></i>&nbsp;Se Restableció el Acceso al Sistema del Usuario con la Siguiente Información </p> 
            <h4 class="text-center card-title"><b> Información del Usuario Modificado: </b></h4>
            <div class="d-flex justify-content-between border-bottom">
                <p> Cédula</p>
                '.$cedula.'
            </div>
            <div class="d-flex justify-content-between border-bottom">
                <p> Nombre y Apellido</p>
                '.$nombre.' '.$apellido.'
            </div>
            <div class="d-flex justify-content-between border-bottom">
                <p> Teléfono</p>
                '.$telefono.'
            </div>
            <div class="d-flex justify-content-between border-bottom">
                <p> Estado</p>
                '.$cambios['estado'].'
            </div>
            <div class="d-flex justify-content-between border-bottom">
                <p> Rol asignado</p>
                '.$cambios['rol'].'
            </div>
            <div class="d-flex justify-content-between border-bottom">
                <p> Bloqueado</p>
                '.$cambios['bloqueado'].'
            </div>');

        alert_model::alert_mod_success();
        exit();
    } catch (Exception $e) {
        alert_model::alert_mod_error();
        exit();
    }

    
}
