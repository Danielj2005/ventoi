<?php

class config_model extends modeloPrincipal {

    public static function consultar($campo) {
        return modeloPrincipal::consultar("SELECT $campo FROM configuracion");
    }
    
    public static function actualizar($campo) {
        return modeloPrincipal::UpdateSQL("configuracion","$campo","id = 1");
    
    }

    public static function obtener_dato($campo) {
        $consult = modeloPrincipal::consultar("SELECT $campo FROM configuracion");

        if (!$consult) {
            alert_model::alerta_simple("¡Ha ocurrido un error!","No se pudo consultar la información de la configuración del sistema.","error");
        }
        $consult = mysqli_fetch_array($consult);
        $consult = $consult[$campo];
        return $consult;
    }

    // funcion para obtener todos los datos de la configuracion del sistema
    public static function obtener_configuracion() {
        
        // información de la configuración original
        $configuracion['c_preguntas'] = config_model::obtener_dato('c_preguntas');
        $configuracion['tiempo_inactividad'] = config_model::obtener_dato('tiempo_inactividad');
        $configuracion['intentos_inicio_sesion'] = config_model::obtener_dato('intentos_inicio_sesion');
        $configuracion['c_caracteres'] = config_model::obtener_dato('c_caracteres');
        $configuracion['c_simbolos'] = config_model::obtener_dato('c_simbolos');
        $configuracion['c_numeros'] = config_model::obtener_dato('c_numeros');
        $configuracion['porcentaje_iva'] = config_model::obtener_dato('porcentaje_iva');
        $configuracion['porcentaje_ganancia'] = config_model::obtener_dato('porcentaje_ganancia');
        
        return $configuracion;
    }
    
    private static function obtener_cambios_colores_bitacora ($datosOriginales, $datosActuales){
        $color_cambios = ['danger','success'];

        if ($datosOriginales == $datosActuales){
            $color_cambios[0] = "dark";
            $color_cambios[1] = "dark";
        }

        return $color_cambios;
    }

    public static function obtener_comparacion ($datosOriginales, $datosActuales){

        $color_cambio_original = self::obtener_cambios_colores_bitacora($datosOriginales[0], $datosActuales[0]);
        
        if ($datosOriginales[0] == $datosActuales[0]){
            return '<span>'.$datosOriginales[1].'</span>';
        }else{
            return '<span>De <b class="text-'.$color_cambio_original[0].'">'.$datosOriginales[1].'</b> a <b class="text-'.$color_cambio_original[1].'">'.$datosActuales[1].'</b></span>';
        }
    }

    // funcion para obtener todos los datos de la configuracion del sistema
    public static function bitacora_configuracion_modificada($id_usuario, $configuracion_original) {
        
        $cedula = $_SESSION['dataUsuario']['dni'];
        $nombre = $_SESSION['dataUsuario']['nombre'];
        $apellido = $_SESSION['dataUsuario']['apellido'];
        $telefono = $_SESSION['dataUsuario']['telefono'];
        $rol = $_SESSION['dataUsuario']['nombreRolUsuario'];
        
        // información de la configuración actual
        $configuracion_actual = self::obtener_configuracion();

        if ($configuracion_original['porcentaje_iva'] !== $configuracion_actual['porcentaje_iva'] || $configuracion_original['porcentaje_ganancia'] !== $configuracion_actual['porcentaje_ganancia']) {
            
            
            $cambios_ganancia = self::obtener_comparacion(
                [ $configuracion_original['porcentaje_ganancia'], $configuracion_original['porcentaje_ganancia'].'%'], 
                [ $configuracion_actual['porcentaje_ganancia'], $configuracion_actual['porcentaje_ganancia'].'%']);

            $cambios_iva = self::obtener_comparacion(
                [ $configuracion_original['porcentaje_iva'], $configuracion_original['porcentaje_iva'].'%'],
                [$configuracion_actual['porcentaje_iva'], $configuracion_actual['porcentaje_iva'].'%' ]);

            $modulo_productos_originales = '<h4 class="text-center card-title"><b> Configuración del módulo de Gestión de productos </b></h4>
                <div class="d-flex justify-content-between border-bottom">
                    <p> Porcentaje de IVA</p> 
                    '.$cambios_iva.'
                </div>
                <div class="d-flex justify-content-between border-bottom">
                    <p> Porcentaje de Ganancia</p> 
                    '.$cambios_ganancia.'
                </div>
                <hr>';
        }


        if ($configuracion_original['c_preguntas'] !== $configuracion_actual['c_preguntas'] || $configuracion_original['tiempo_inactividad'] !== $configuracion_actual['tiempo_inactividad'] || $configuracion_original['intentos_inicio_sesion'] !== $configuracion_actual['intentos_inicio_sesion']) {
            
            $cambios_c_preguntas = self::obtener_comparacion(
                [ $configuracion_original['c_preguntas'], $configuracion_original['c_preguntas']], 
                [ $configuracion_actual['c_preguntas'], $configuracion_actual['c_preguntas']]);

            $cambios_tiempo_inactividad = self::obtener_comparacion(
                [ $configuracion_original['tiempo_inactividad'], $configuracion_original['tiempo_inactividad'].' minutos'], 
                [ $configuracion_actual['tiempo_inactividad'], $configuracion_actual['tiempo_inactividad'].' minutos']);

            $cambios_intentos_inicio_sesion = self::obtener_comparacion(
                [ $configuracion_original['intentos_inicio_sesion'], $configuracion_original['intentos_inicio_sesion']], 
                [ $configuracion_actual['intentos_inicio_sesion'], $configuracion_actual['intentos_inicio_sesion']]);

            $modulo_sesion_original = '<h4 class="text-center card-title"><b> Configuración de Sesión del usuario </b></h4>
                <div class="d-flex justify-content-between border-bottom">
                    <p> Cantidad de Preguntas de Seguridad</p> 
                    '.$cambios_c_preguntas.'
                </div>
                <div class="d-flex justify-content-between border-bottom">
                    <p> Tiempo de Sesión (inactividad)</p> 
                    '.$cambios_tiempo_inactividad.'
                </div>
                <div class="d-flex justify-content-between border-bottom">
                    <p> Intentos de Sesión</p> 
                    '.$cambios_intentos_inicio_sesion.'
                </div>
                ';

        }

        if ($configuracion_original['c_caracteres'] !== $configuracion_actual['c_caracteres'] || $configuracion_original['c_simbolos'] !== $configuracion_actual['c_simbolos'] || $configuracion_original['c_numeros'] !== $configuracion_actual['c_numeros']) {
            
            $cambios_c_caracteres = self::obtener_comparacion(
                [ $configuracion_original['c_caracteres'], $configuracion_original['c_caracteres']], 
                [ $configuracion_actual['c_caracteres'], $configuracion_actual['c_caracteres']]);

            $cambios_c_simbolos = self::obtener_comparacion(
                [ $configuracion_original['c_simbolos'], $configuracion_original['c_simbolos']], 
                [ $configuracion_actual['c_simbolos'], $configuracion_actual['c_simbolos']]);

            $cambios_c_numeros = self::obtener_comparacion(
                [ $configuracion_original['c_numeros'], $configuracion_original['c_numeros']], 
                [ $configuracion_actual['c_numeros'], $configuracion_actual['c_numeros']]);

            $parametros_contraseña_originales = '<h4 class="text-center card-title"><b> Configuración de parámetros de contraseña de usuario </b></h4>
                <div class="d-flex justify-content-between border-bottom">
                    <p> Cantidad de caracteres</p>
                    '.$cambios_c_caracteres.'
                </div>
                <div class="d-flex justify-content-between border-bottom">
                    <p> Cantidad de símbolos (! @ # $ %)</p>
                    '.$cambios_c_simbolos.'
                </div>
                <div class="d-flex justify-content-between border-bottom">
                    <p> Cantidad de números</p>
                    '.$cambios_c_numeros.'
                </div>
                ';
        }




        if ($configuracion_original['porcentaje_iva'] !== $configuracion_actual['porcentaje_iva'] || $configuracion_original['porcentaje_ganancia'] !== $configuracion_actual['porcentaje_ganancia'] || $configuracion_original['c_preguntas'] !== $configuracion_actual['c_preguntas'] || $configuracion_original['tiempo_inactividad'] !== $configuracion_actual['tiempo_inactividad'] || $configuracion_original['intentos_inicio_sesion'] !== $configuracion_actual['intentos_inicio_sesion'] || $configuracion_original['c_caracteres'] !== $configuracion_actual['c_caracteres'] || $configuracion_original['c_simbolos'] !== $configuracion_actual['c_simbolos'] || $configuracion_original['c_numeros'] !== $configuracion_actual['c_numeros']) {
        
            $bitacora_configuracion = bitacora::bitacora("Modificación exitosa de la configuración del sistema",
            '<p class="mb-3 text-primary-emphasis text-center"><i class="bi bi-exclamation-circle-fill"></i>&nbsp;El usuario actualizó la configuración del sistema.</p> 
                    <h4 class="text-center card-title"><b> Información del usuario que realizó la modificación </b></h4>
                    <div class="d-flex justify-content-between border-bottom">
                        <p> Cédula:</p> 
                        <span class="fw-bold"> '.$cedula.' </span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom">
                        <p> Nombre:</p> 
                        <span class="fw-bold"> '.$nombre.' </span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom">
                        <p> Apellido:</p> 
                        <span class="fw-bold"> '.$apellido.' </span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom">
                        <p> Teléfono:</p> 
                        <span class="fw-bold"> '.$telefono.' </span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom">
                        <p> Rol asignado:</p> 
                        <span class="fw-bold"> '.$rol.' </span>
                    </div>
    
                    '.$modulo_productos_originales.'
    
                    '.$modulo_sesion_original.'
    
                    '.$parametros_contraseña_originales.'
    
            ');
        }

        return $bitacora_configuracion;
    }
    
    /**
     * Verifica si se actualizaron los parámetros de configuración relacionados a la contraseña
     * y la cantidad de preguntas de seguridad, y notifica al usuario si debe actualizar sus datos.
     *
     */
    public static function verificar_actualizacion_configuracion($perfil = 0) {
                
        $id_usuario = $_SESSION['id_usuario'];

        try {
            // Obtener los parámetros de configuración actuales
            $configuracion = modeloPrincipal::consultar("SELECT * FROM configuracion");
    
            if (!$configuracion) {
                alert_model::alerta_simple("¡Error!", "No se pudo obtener la configuración del sistema.", "error");
                return;
            }
    
            $configuracion = mysqli_fetch_array($configuracion);
    
            $cant_caracteres = intval($configuracion['c_caracteres']);
            $c_preguntas = intval($configuracion['c_preguntas']);

        } catch (Exception $e) {
            alert_model::alerta_simple("¡Error!", "Error al consultar la configuración del sistema.", "error");
            exit();
        }
        
        // Obtener la información del usuario
        try {
            $usuario = modeloPrincipal::consultar("SELECT contraseña FROM usuario WHERE id_usuario = '$id_usuario'");
    
            if (!$usuario) {
                alert_model::alerta_simple("¡Error!", "No se pudo obtener la información del usuario.", "error");
                return;
            }
    
            $usuario = mysqli_fetch_array($usuario);
            $contraseña = modeloPrincipal::decryption($usuario['contraseña']);

        } catch (Exception $e) {
            alert_model::alerta_simple("¡Error!", "Error al consultar la información del usuario.", "error");
            exit();
        }

        // Obtener la cantidad de preguntas de seguridad del usuario
        try {
            $preguntas_seguridad = modeloPrincipal::consultar("SELECT COUNT(*) AS cantidad FROM preguntas_secretas WHERE id_usuario = '$id_usuario'");
            if (!$preguntas_seguridad) {
                alert_model::alerta_simple("¡Error!", "No se pudo obtener la cantidad de preguntas de seguridad del usuario.", "error");
                return;
            }

            $preguntas_seguridad = mysqli_fetch_assoc($preguntas_seguridad);
            $cantidad_preguntas = intval($preguntas_seguridad['cantidad']);

        } catch (Exception $e) {
            alert_model::alerta_simple("¡Error!", "Error al consultar la cantidad de preguntas de seguridad del usuario.", "error");
            exit();
        }

        if ($perfil == 0) {
            // Verificar si la contraseña cumple con la nueva longitud mínima
            if (strlen($contraseña) < $cant_caracteres) {
                alert_model::alert_redirect(
                    "¡Advertencia!",
                    "La longitud de su contraseña actual no cumple con los nuevos requisitos del sistema. Por favor, actualice su contraseña a una longitud mínima de $cant_caracteres caracteres.",
                    "warning",
                    'mi_perfil.php'
                );
                return;
            }

            // Verificar si la cantidad de preguntas de seguridad cumple con los nuevos requisitos
            if ($cantidad_preguntas < $c_preguntas && $cantidad_preguntas > $c_preguntas) {
                alert_model::alert_redirect(
                    "¡Advertencia!",
                    "La cantidad de preguntas de seguridad configuradas no cumplen con los nuevos requisitos del sistema. Por favor, configure al menos $c_preguntas preguntas de seguridad.",
                    "warning",
                    'mi_perfil.php'
                );
                return;
            }
             // Verificar si la cantidad de preguntas de seguridad cumple con los nuevos requisitos
        
        }
        
        if ($perfil == 1) {
            // Verificar si la contraseña cumple con la nueva longitud mínima
            if (strlen($contraseña) < $cant_caracteres) {
                alert_model::alerta_simple(
                    "¡Advertencia!",
                    "La longitud de su contraseña actual no cumple con los nuevos requisitos del sistema. Por favor, actualice su contraseña a una longitud mínima de $cant_caracteres caracteres.",
                    "warning"
                );
                return;
            }

            // Verificar si la cantidad de preguntas de seguridad cumple con los nuevos requisitos
            if ($cantidad_preguntas < $c_preguntas && $cantidad_preguntas > $c_preguntas) {
                alert_model::alerta_simple(
                    "¡Advertencia!",
                    "La cantidad de preguntas de seguridad configuradas no cumplen con los nuevos requisitos del sistema. Por favor, configure al menos $c_preguntas preguntas de seguridad.",
                    "warning"
                );
                return;
            }
            
        }
    }
    

}
