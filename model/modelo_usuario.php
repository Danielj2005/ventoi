<?php

class model_user extends modeloPrincipal {
    
    
    /********************************************************************************************************/ 
    /*********************************     CRUD de usuarios         *****************************************/
    /********************************************************************************************************/
    

    public static function consultar_usuario($fields) {
        $consul = modeloPrincipal::consultar("SELECT $fields FROM usuario");
        modeloPrincipal::verificar_consulta($consul,'usuario'); // se verifica si la consulta fue exitosa
        return $consul;
    }

    public static function consulta_usuario_id($fields, $id_usuario) {
        $consul = modeloPrincipal::consultar("SELECT $fields FROM usuario WHERE id_usuario = '$id_usuario'");
        modeloPrincipal::verificar_consulta($consul, 'usuario'); // se verifica si la consulta fue exitosa
        return $consul;
    }

    public static function consulta_usuario_condicion($fields,$condition) {
        $consul = modeloPrincipal::consultar("SELECT $fields FROM usuario WHERE $condition");
        modeloPrincipal::verificar_consulta($consul,'usuario'); // se verifica si la consulta fue exitosa
        return $consul;
    }

    public static function consulta_usuario_existe($query,$condition) {
        $consul = modeloPrincipal::consultar("SELECT $query FROM usuario AS U INNER JOIN rol AS R ON U.id_rol = R.id_rol WHERE $condition");
        modeloPrincipal::verificar_consulta($consul,'usuario'); // se verifica si la consulta fue exitosa
        return $consul;
    }

    
    /*************************************************v*********************/
    /*** funciones para consultar preguntas de seguridad de los usuarios ***/
    /***********************************************************************/

    public static function consultar_todas_las_preguntas_seguridad () {
        $preguntas_sistema = modeloPrincipal::consultar("SELECT pregunta FROM seguridad");
        modeloPrincipal::verificar_consulta($preguntas_sistema,'preguntas de seguridad');
        return $preguntas_sistema;
    }
    
    
    public static function consultar_preguntas_seguridad_por_pregunta($pregunta) {
        $preguntas_sistema = modeloPrincipal::consultar("SELECT pregunta FROM seguridad WHERE pregunta = '$pregunta'");
        modeloPrincipal::verificar_consulta($preguntas_sistema,'preguntas de seguridad');
        return $preguntas_sistema;
    }
    

    /****************************************************************************/ 
    /*       funciones de insertar datos de los usuarios   */
    /****************************************************************************/ 

    public static function insert_user($cedula, $nombre, $apellido, $correo, $contraseña, $telefono, $direccion, $id_rol){

        $actualizar = modeloPrincipal::InsertSQL( "usuario","cedula, nombre, apellido, correo, contraseña, telefono, direccion, sesion_activa, bloqueado, primer_inicio, id_rol, estado", "'$cedula', '$nombre', '$apellido', '$correo', '$contraseña', '$telefono', '$direccion', 0, 0, 1, $id_rol, 1");
        
        if (!$actualizar) {
            alert_model::alerta_simple("¡Ocurrió un error inesperado!","No se pudo registrar al usuario, por favor verifique e intente nuevamente","error");
        }
        return $actualizar;
    }

    
    /**************************************************************/ 
    /*       funciones de modificación de datos de los usuarios   */
    /**************************************************************/ 

    public static function actualizar_usuario_por_su_id ($campos, $id_usuario) {
        
        $actualizar = modeloPrincipal::UpdateSQL("usuario",$campos,"id_usuario = $id_usuario");
        if (!$actualizar) {
            alert_model::alerta_simple("¡Error!", "ocurrio un error al realizar la operación de actualizar las características de acceso del usuario.", "error");
            exit();
        } 
        return $actualizar;
    
    }

    public static function modificar_sesion_ultima_sesion_fecha($id_usuario, $fecha_ultima_sesion, $estado_sesion) {
        // se modifica la fecha de la ultima sesion del usuario
        // se actualiza el estado de la sesión del usuario a activa
        if (!modeloPrincipal::UpdateSQL("usuario","ultima_sesion = '$fecha_ultima_sesion', sesion_activa = '$estado_sesion'","id_usuario = $id_usuario")) {
            return exit();
        }
    }

    
    /************************************************************/ 
    /*       funciones de asignación de datos de los usuarios   */
    /************************************************************/ 

    public static function asignar_preguntas_seguridad_usuario() {

        // Obtener la cantidad de preguntas configuradas en el sistema
        $configuracion = modeloPrincipal::consultar("SELECT c_preguntas FROM configuracion");
        if (!$configuracion || mysqli_num_rows($configuracion) == 0) {
            alert_model::alerta_simple('¡Error!', 'No se pudo obtener la configuración de preguntas de seguridad.', 'error');
            exit();
        }
        $cantidad_preguntas = intval(mysqli_fetch_array($configuracion)['c_preguntas']);

        // Obtener el ID del usuario recién registrado
        $id_usuario = self::obtener_id_usuario_recien_registrado();
        if (!$id_usuario) {
            alert_model::alerta_simple('¡Error!', 'No se pudo obtener el ID del usuario recién registrado.', 'error');
            exit();
        }

        // Obtener la información personal del usuario (por ejemplo, cédula) y encriptarla
        $respuesta = self::obtener_info_personal_usuario('cedula', $id_usuario);
        if (!$respuesta) {
            alert_model::alerta_simple('¡Error!', 'No se pudo obtener la información personal del usuario.', 'error');
            exit();
        }
        $cedula_reseteo = trim($respuesta);
        $cedula_reseteo = str_ireplace("V", "", $cedula_reseteo);
        $cedula_reseteo = str_ireplace("E", "", $cedula_reseteo);
        $cedula_reseteo = str_ireplace("-", "", $cedula_reseteo);
        $cedula_reseteo = stripslashes($cedula_reseteo);
        $cedula_reseteo = trim($cedula_reseteo);
        $respuesta_encriptada = modeloPrincipal::encryption($cedula_reseteo);

        // Obtener la cantidad total de preguntas disponibles en el sistema
        $preguntas_disponibles = modeloPrincipal::consultar("SELECT id_seguridad FROM seguridad");
        if (!$preguntas_disponibles || mysqli_num_rows($preguntas_disponibles) == 0) {
            alert_model::alerta_simple('¡Error!', 'No hay preguntas de seguridad disponibles en el sistema.', 'error');
            exit();
        }

        // Convertir las preguntas disponibles en un array
        $ids_preguntas = [];
        while ($row = mysqli_fetch_assoc($preguntas_disponibles)) {
            $ids_preguntas[] = $row['id_seguridad'];
        }

        // Seleccionar preguntas aleatorias y asignarlas al usuario
        $preguntas_asignadas = [];
        for ($i = 1; $i <= $cantidad_preguntas; $i++) {
            do {
                // Seleccionar una pregunta aleatoria
                $id_pregunta = $ids_preguntas[array_rand($ids_preguntas)];
            } while (in_array($id_pregunta, $preguntas_asignadas)); // Evitar duplicados

            // Registrar la pregunta en la base de datos
            $resultado = modeloPrincipal::InsertSQL(
                "preguntas_secretas",
                "id_pregunta, respuesta, numero_pregunta, id_usuario",
                "'$id_pregunta', '$respuesta_encriptada', '$i', '$id_usuario'"
            );

            if (!$resultado) {
                alert_model::alerta_simple('¡Error!', 'No se pudo asignar la pregunta de seguridad al usuario.', 'error');
                exit();
            }

            // Agregar la pregunta a las asignadas
            $preguntas_asignadas[] = $id_pregunta;
        }
    }

    /*************************************************************/ 
    /*       funciones de componentes de datos de los usuarios   */
    /*************************************************************/ 

    //  Funcion para pedir una lista de empleados del negocio 

    public static function lista_de_usuarios() {
        $id_usuario = $_SESSION['id_usuario']; // se obtiene el id del usuario que inicio sesion

        $lista_usuario = modeloPrincipal::consultar("SELECT *
            FROM usuario 
            WHERE id_usuario != '$id_usuario' 
            AND id_rol != 1 
            ORDER BY nombre ASC");
        
        // se imprimen los resultados de la consulta
        while ( $mostrar = mysqli_fetch_array($lista_usuario)) { ?>    
            <tr>
                <th class="col text-center"></th>
                <th class="col text-center"><?= $mostrar["cedula"]; ?></th>
                <th class="col text-center"><?= $mostrar["nombre"]." ".$mostrar["apellido"]; ?></th>
                <th class="col text-center"><?= $mostrar["telefono"]; ?></th>

                <?php if (modeloPrincipal::verificar_permisos_requeridos(['m_empleado']) == 1): ?>
                    <th scope="col" class="col text-center">
                        <button
                            modal="usuarioModificar" 
                            data-bs-toggle="modal" 
                            data-bs-target="#modal" 
                            value="<?= modeloPrincipal::encryptionId($mostrar["id_usuario"]); ?>" 
                            class="btn_modal btn btn-warning <?= ICONO_MODIFICAR ?>">
                        </button>
                    </th>
                    <th scope="col" class="col text-center">
                        <button class="btn w-100 <?= ($mostrar["estado"] === "1") ? 'btn-success' : 'btn-danger' ?>" 
                            type="button" 
                            disabled
                        >
                            <i class="bi <?= ($mostrar["estado"] === "1") ? 'bi-check-circle-fill' : 'bi-x-circle-fill' ?> me-1"></i>
                        
                            <?= ($mostrar["estado"] === "1") ? 'Activo' : 'Inactivo' ?>
                        </button>
                    </th>
                <?php endif; ?>
            </tr>
        <?php }
    } 

    public static function options_usuarios() {
        $lista_usuario = modeloPrincipal::consultar("SELECT * FROM usuario WHERE id_rol != 1 ORDER BY nombre ASC");

        while($row = mysqli_fetch_array($lista_usuario)) { ?>

            <option value="<?= modeloPrincipal::encryption($row['id_usuario'] * 10); ?>"> <?= $row["cedula"]." - ".$row["nombre"]." ".$row["apellido"]; ?></option>

        <?php }
    }


    /********************************************************************/ 
    /*       MODULO de verificar / validar datos del usuarios           */
    /********************************************************************/ 
    
    // funcion para verificar coincidencia de contraseña de un usuario

    public static function verificar_coincidencia_de_contraseña($contraseña,$contraseña2){
        if ($contraseña !== $contraseña2) {
            alert_model::alerta_simple('¡Ocurrio un Error!','Las contraseñas no coinciden, por favor verifica e intenta nuevamente','error');
            exit();    
        }
    }


    public static function validar_primer_inicio($id_usuario){

        $primer_inicio = Self::obtener_info_personal_usuario("primer_inicio",$id_usuario);

        if($primer_inicio == '1'){
            echo "<script type='text/javascript'>
                    window.location.href='./mi_perfil.php';
                </script>";
            exit();
        }
    }

    public static function validar_sesion_activa($id_usuario){
        $sesion_activa = Self::obtener_info_personal_usuario("sesion_activa",$id_usuario);

        if($sesion_activa == '0'){
            modeloPrincipal::UpdateSQL("usuario","sesion_activa = '0'","id_usuario = $id_usuario");
            alert_model::alert_redirect(
                '¡Sesión activa detectada!', 
                'Se ha detectado un intento de inicio de sesión desde otro dispositivo asociado a su cuenta. Para garantizar la seguridad de su información, la sesión actual se cerrará automáticamente en breve.',
                'warning', 
                '../controlador/salir.php');
            exit();
        }
    }
    
    public static function verificar_preguntas_seguridad_alterada($pregunta){
        $pregunta = Self::encryption($pregunta);
        $preguntas_sistema = self::consultar_preguntas_seguridad_por_pregunta("$pregunta");
        
        if (mysqli_num_rows($preguntas_sistema) < '1'){
            alert_model::alerta_condicional("Atención!","Alguna de las preguntas fue alterada de manera incorrecta y no coinciden con las que están registradas en el sistema. Se cerrará tu sesión por motivos de seguridad.","","window.location = '../controlador/salir.php';");
            exit();
        }
    }

    public static function verificar_intento_de_acceso_al_sistema(){
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) { 
            // Redirigir el acceso a la página sino inició de sesión
            header('Location: ../');
            exit();
        }
    }

    public static function verificar_primer_inicio(){
        $id_usuario = $_SESSION['id_usuario'];

        $cedula = $_SESSION['dataUsuario']['dni'];
        $cedula = trim($cedula);
        $cedula = str_ireplace("V", "", $cedula);
        $cedula = str_ireplace("E", "", $cedula);
        $cedula = str_ireplace("-", "", $cedula);
        $cedula = stripslashes($cedula);
        $cedula = trim($cedula);
        $cedula = modeloPrincipal::encryption($cedula);

        $respuestas = mysqli_fetch_array(modeloPrincipal::consultar("SELECT respuesta FROM preguntas_secretas WHERE id_usuario = '$id_usuario'"));
        
        foreach ($respuestas as $key){
            if ($key == $cedula) {
                return true;
            }
        }
        return false;
    }

    public static function validar_preguntas_de_seguridad($preguntas,$respuestas) {
        
        //datos verificados modificar

        for ($i = 0; $i < count($preguntas); $i++) { 
            // Verificamos si la pregunta es automática o no
            $j=1;
            // se verifica si se recibieron campos vacios
            
            if($respuestas[$i] == ""){
                alert_model::alerta_simple('¡Atención!','La respuesta nº'.($j+1).' no puede estar vacia','warning');
                exit();
            }
            if($preguntas[$i] == ""){
                alert_model::alerta_simple('¡Atención!','La pregunta nº'.$preguntas[$i].' no puede estar vacia','warning');
                exit();
            }

            if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0 ]{3,50}$/",$respuestas[$i])) {
                alert_model::alerta_simple('Atención!',"La respuesta nº ".($j+1)." no cumple con el formato establecido",'warning');
                exit();
            }

            if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ?¿]{8,150}$/",$preguntas[$i])) {
                // si no cumple con el formato establecido se muestra un mensaje de error
                alert_model::alerta_simple('Atención!',"La pregunta nº ".$preguntas[$i]." no cumple con el formato establecido",'warning');
                exit(); // fijar exit() position
            }

            $respuestas[$i] = strtoupper($respuestas[$i]);  

            //lo hice de esta manera porque no me queria agarrar de otra forma la vidacion si lo logran acomodar en un futuro seria bueno jajajajjaja ;D suerte 
            self::verificar_preguntas_seguridad_alterada($preguntas[$i]);
            
        }

    }

    public static function validar_usuario_existe($campos,$condicion){
        // se comprueba que no exista un registro con los mismos datos
        modeloPrincipal::validacion_registro_existente($campos,"usuario","$condicion");

    }

    /**********************************************************************************/
    /********************** funciones obtener datos de un usuario  ********************/
    /**********************************************************************************/
    
    // funcion para obtener_info_personal_usuario

    public static function obtener_info_personal_usuario($info,$id_usuario) {
        if ($info == 'id_rol') {
            $id_rol = rol_model::obtener_id_rol_usuario();
            $nombre_rol = rol_model::obtener_nombre_rol_usuario($id_rol);
            $info_usuario[$info] = $nombre_rol;
        }else{
            $info_usuario = mysqli_fetch_array(modeloPrincipal::consultar("SELECT $info FROM usuario WHERE id_usuario = $id_usuario"));
        }

        return $info_usuario[$info];
    }
    // funcion para obtener_info_personal_usuario

    public static function obtener_info_de_un_usuario($info,$id_usuario) {
        if ($info == 'id_rol') {
            $id_rol = modeloPrincipal::consultar("SELECT id_rol FROM usuario WHERE id_usuario = $id_usuario");

            if (!$id_rol) {
                alert_model::alerta_simple("¡Ocurrió un error inesperado!","No se encontró el rol del usuario, por favor verifique e intente nuevamente","error");
            }
            
            $id_rol = mysqli_fetch_array($id_rol);
            $id_rol = $id_rol['id_rol'];

            $nombre_rol = rol_model::obtener_nombre_rol_usuario($id_rol);
            $info_usuario[$info] = $nombre_rol;
        }else{
            $info_usuario = mysqli_fetch_array(modeloPrincipal::consultar("SELECT $info FROM usuario WHERE id_usuario = $id_usuario"));
        }

        return $info_usuario[$info];
    }

    // funcion para obtener el id de un usuario

    public static function obtener_id_usuario_recien_registrado(){
        $id_usaurio = mysqli_fetch_array(modeloPrincipal::consultar("SELECT MAX(id_usuario) AS id FROM usuario"));
        $id_usaurio = $id_usaurio['id'];
        return $id_usaurio;
    }
    

    /*********************************************************************************************************/
    /*********************** funciones para el CRUD de la bitácora de registro de información ****************/
    /********************************************************************************************************/

    public static function bitacora_info_personal_usuario_modificada($cedula_original, $nombre_original, $apellido_original, $correo_original, $direccion_original, $telefono_original, $id_usuario) {
        
        bitacora::bitacora("Modificación del perfil de usuario","El usuario actualizó su información personal\n
        Información original:\n
        Cédula: ".$cedula_original."\n
        Nombre: ".$nombre_original."\n
        Apellido: ".$apellido_original."\n
        Correo: ".$correo_original."\n
        Dirección: ".$direccion_original."\n
        Teléfono: ".$telefono_original."\n

        Información Actual:\n
        Cédula: ".self::obtener_info_personal_usuario('cedula',$id_usuario)."\n
        Nombre: ".self::obtener_info_personal_usuario('nombre',$id_usuario)."\n
        Apellido: ".self::obtener_info_personal_usuario('apellido',$id_usuario)."\n
        Correo: ".self::obtener_info_personal_usuario('correo',$id_usuario)."\n
        Dirección: ".self::obtener_info_personal_usuario('direccion',$id_usuario)."\n
        Teléfono: ".self::obtener_info_personal_usuario('telefono',$id_usuario)."
        ");
    }

    public static function bitacora_modificacion_contraseña() {

        bitacora::bitacora("Modificación exitosa del perfil de usuario.",'<p class="h2 mb-3 text-primary-emphasis text-center"><i class="bi bi-exclamation-circle-fill"></i>&nbsp;El usuario actualizó su contraseña.</p> ');
    }




}
