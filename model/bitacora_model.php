<?php 

require_once __DIR__ . '/modelo_usuario.php'; // se incluye el modelo principal
require_once __DIR__ . '/alert_model.php'; // se incluye el modelo principal
error_reporting(E_PARSE);

class bitacora extends model_user {

    /*********************************************************************/
    /*********************** funciones de bitácora ***********************/
    /*********************************************************************/
    

    /******funcion para registrar movimientos del sistema en la bitácora  ******/ 
    public static function bitacora($accion, $mensaje) {
        $fechas = date('Y-m-d H:i:s');
        $id_usuario = $_SESSION["id_usuario"];

        if (!$consul = Self::InsertSQL("bitacora","fecha_hora,accion,mensaje,id_usuario","'$fechas','$accion','$mensaje',$id_usuario")) {
            die("Ha ocurrido un error al guardar la bitacora");
        }
        return $consul;
    }

    // funcion para registrar inicio de sesion del sistema en la bitácora
    public static function login() {
        // Registra en la bitácora el inicio de sesión del usuario
        
        return Self::bitacora("Inicio de sesión exitoso", '<p class="h2 mb-3 text-primary-emphasis text-center"><i class="bi bi-exclamation-circle-fill"></i>&nbsp;El usuario Inició sesión correctamente.</p> ');
    }

    
    // funcion para registrar Intento de acceso no autorizado a la pantalla especificada en la bitácora
    public static function intento_de_acceso_a_vista_sin_permisos($pantalla) {
        // Registra en la bitácora el intento de acceso no autorizado
        Self::bitacora("Intento de acceso no autorizado a la pantalla $pantalla.", '<p class="h5 mb-3 text-center text-warning-emphasis alert alert-warning"><i class="bi bi-exclamation-circle-fill"></i>&nbsp;Se ha registrado un intento de acceso incorrecto a la pantalla '.$pantalla.' por parte de un usuario sin los permisos necesarios. Por motivos de seguridad, el usuario fue redirigido a la pantalla de inicio.</p> ');
        header('Location: ./login');
    }
    
}