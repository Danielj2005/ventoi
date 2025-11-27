<?php 
session_start();

include_once "../include/modelos_include.php"; // se incluyen los modelos necesarios para la vista

if (!isset($_POST["modulo"]) || $_POST['modulo'] == "") {
    alert_model::alerta_simple("Ocurrio un error!","Ha ocurrido un error al procesar tu solicitud, asegurese de no alterar la información del sistema","error");
    exit();
}

// modulo a trabajar
$modulo = modeloPrincipal::limpiar_cadena($_POST["modulo"]);

$id_usuario = $_SESSION['id_usuario'];

// modulo para Modificar los parametros de contaseñas del sistema
if($modulo === "Contraseña"){
    
    /*------------------ datos de la configuración del sistema ------------------*/
    $c_caracteres =  intval(modeloPrincipal::limpiar_cadena($_POST["c_caracteres"]));
    $c_simbolos = intval(modeloPrincipal::limpiar_mayusculas($_POST["c_simbolos"]));
    $c_numeros = intval(modeloPrincipal::limpiar_cadena($_POST["c_numeros"]));
    
    // Se verifica que no se hayan recibido campos vacíos.
    modeloPrincipal::validar_campos_vacios([$c_caracteres, $c_simbolos, $c_numeros]);

    if (modeloPrincipal::verificar_datos("[0-9]{1,2}",$c_caracteres)) {
        alert_model::alert_of_format_wrong("'Cantidad de caracteres'");
        exit();
    }
    if (modeloPrincipal::verificar_datos("[0-9]{1,2}",$c_simbolos)) {
        alert_model::alert_of_format_wrong("'Cantidad de símbolos'");
        exit();
    }
    if (modeloPrincipal::verificar_datos("[0-9]{1,2}",$c_numeros)) {
        alert_model::alert_of_format_wrong("'Cantidad de números'");
        exit();
    }

    // información de la configuración original
    $configuracion_original = config_model::obtener_configuracion();

    // Se actualizara la información personal del usuario
    try {
        $actualizar = config_model::actualizar("c_caracteres = '$c_caracteres', c_simbolos = '$c_simbolos', c_numeros = '$c_numeros'");
                
        if (!$actualizar) {
            alert_model::alerta_simple("Ha ocurrido un error!", "ocurrio un error al actualizar la configuración del sistema.", "error");
            exit();
        }

    } catch (Exception $e) {
        alert_model::alert_mod_error();
        exit();
    }
    

    // Se registra la bitacora de las modificaciones realizadas
    try {
        $bitacora_configuracion = config_model::bitacora_configuracion_modificada($id_usuario,$configuracion_original);
        
        if (!$bitacora_configuracion) {
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

// modulo para crear una copia de seguridad de la base de datos del sistema
if($modulo === "backup"){
    
    /*** Función para hacer respaldo de una base de datos MySQL            */

    function backupDatabaseLaragon($host, $user, $password, $dbname, $backupDir) {
        // Verificar que la ruta backupDir es absoluta y existe (o crearla)
        if (!is_dir($backupDir)) {
            if (!mkdir($backupDir, 0755, true)) {
                echo "No se pudo crear el directorio de backup: $backupDir\n";
                return false;
            }
        }

        $date = date('d-m-Y - h-i-a'); // obtenemos la fecha de exportacion
    
        // Asegurar que backupDir termina sin barra inversa final
        $backupDir = rtrim($backupDir, DIRECTORY_SEPARATOR);
        $backupFile = $backupDir . DIRECTORY_SEPARATOR . "backup_{$dbname}_{$date}.sql";
    
        // Ruta al mysqldump.exe en Laragon - AJUSTAR según tu instalación
        $mysqldumpPath = 'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe';
        // Componer comando. 
        // En Windows, para que redirección > funcione con exec, es mejor usar cmd /c
        // Construir comando
        $command = "cmd /c \"\"{$mysqldumpPath}\" --user={$user} --password={$password} --host={$host} {$dbname} > \"{$backupFile}\"\"";
    
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            return false;
        }
    
        if (file_exists($backupFile)) {
            return $backupFile;
        } else {
            return false;
        }
    
    }
    
    // Ejemplo de uso - Ajusta la ruta a donde quieres guardar el backup (ruta absoluta)
    
    $backupDir = 'C:\\laragon\\www\\sistema_de_control_de_ventas_y_inventario_la_chinita\\bd_backups';
    
    $backupPath = backupDatabaseLaragon(SERVER, USER, PASSWORD, DB, $backupDir);
    
    if ($backupPath === false) {
        alert_model::alerta_simple("¡Ocurrio un error!","Ha ocurrido un error al crear un respaldo de la base de datos del sistema","error");
        exit();
    } else {
        alert_model::alerta_simple("Respaldo creado exitosamente!","Se ha creado un respaldo de la base de datos del sistema, Respaldo guardado en la carpeta 'bd_backups' del sistema","success");
        bitacora::bitacora("Copia de seguridad creada exitosamente","El usuario a creado una copia de segurida de la base de datos del sistema.");
        exit();
    }


}

// modulo para Modificar configuración del módulo de producto en el sistema
if($modulo === "producto"){
    
    /*------------------ datos de la configuración del sistema ------------------*/
    $porcentaje_iva = intval(modeloPrincipal::limpiar_cadena($_POST["porcentaje_iva"]));
    $porcentaje_ganancia = intval(modeloPrincipal::limpiar_mayusculas($_POST["porcentaje_ganancia"]));
    
    // Se verifica que no se hayan recibido campos vacíos.
    modeloPrincipal::validar_campos_vacios([$porcentaje_iva, $porcentaje_ganancia]);

    if (modeloPrincipal::verificar_datos("[0-9]{1,3}",$porcentaje_iva)) {
        alert_model::alert_of_format_wrong("'Porcentaje de IVA'");
        exit();
    }
    if (modeloPrincipal::verificar_datos("[0-9]{1,3}",$porcentaje_ganancia)) {
        alert_model::alert_of_format_wrong("'Porcentaje de ganancia'");
        exit();
    }

    // información de la configuración original
    $configuracion_original = config_model::obtener_configuracion();

    // Se actualizara la configuracion del sistema
    try {
        $actualizar = config_model::actualizar("porcentaje_iva = '$porcentaje_iva', porcentaje_ganancia = '$porcentaje_ganancia'");
        
        if (!$actualizar) {
            alert_model::alerta_simple("Ha ocurrido un error!", "ocurrio un error al actualizar la configuración del sistema.", "error");
            exit();
        }

    } catch (Exception $e) {
        alert_model::alert_mod_error();
        exit();
    }

    // Se registra la bitacora de las modificaciones realizadas
    try {
        $bitacora_configuracion = config_model::bitacora_configuracion_modificada($id_usuario,$configuracion_original);
        
        if (!$bitacora_configuracion) {
            alert_model::alerta_simple(
                "Ocurrido un error!", 
                "No se puede guardar sin haber hecho cambios en la información.", 
                "error");
                
            exit();
        }

        alert_model::alert_mod_success();
        exit();
    } catch (Exception $e) {
        alert_model::alert_mod_error();
        exit();
    }
}

// modulo para Modificar configuración de la sesion en el sistema
if($modulo === "sesion"){
    
    /*------------------ datos de la configuración del sistema ------------------*/
    $c_preguntas = intval(modeloPrincipal::limpiar_cadena($_POST["c_preguntas"]));
    $tiempo_inactividad = intval(modeloPrincipal::limpiar_mayusculas($_POST["tiempo_inactividad"]));
    $intentos_inicio_sesion = intval(modeloPrincipal::limpiar_mayusculas($_POST["intentos_inicio_sesion"]));
    
    // Se verifica que no se hayan recibido campos vacíos.
    modeloPrincipal::validar_campos_vacios([$c_preguntas, $tiempo_inactividad, $intentos_inicio_sesion]);

    if (modeloPrincipal::verificar_datos("[0-9]{1,2}",$c_preguntas)) {
        alert_model::alert_of_format_wrong("'Cantidad de preguntas de seguridad'");
        exit();
    }
    if (modeloPrincipal::verificar_datos("[0-9]{1,2}",$tiempo_inactividad)) {
        alert_model::alert_of_format_wrong("'Tiempo de inactividad de sesión'");
        exit();
    }
    if (modeloPrincipal::verificar_datos("[0-9]{1,2}",$intentos_inicio_sesion)) {
        alert_model::alert_of_format_wrong("'Intentos de inicio de sesión para los usuarios'");
        exit();
    } 
    // información de la configuración original
    $configuracion_original = config_model::obtener_configuracion();

    // Se actualizara la configuracion del sistema
    try {
        $actualizar = config_model::actualizar("c_preguntas = '$c_preguntas', tiempo_inactividad = '$tiempo_inactividad', intentos_inicio_sesion = '$intentos_inicio_sesion'");

        if (!$actualizar) {
            alert_model::alerta_simple("Ha ocurrido un error!", "ocurrio un error al actualizar la configuración del sistema.", "error");
            exit();
        }

    } catch (Exception $e) {
        alert_model::alert_mod_error();
        exit();
    }

    // Se registra la bitacora de las modificaciones realizadas
    try {
        $bitacora_configuracion = config_model::bitacora_configuracion_modificada($id_usuario,$configuracion_original);
        
        if (!$bitacora_configuracion) {
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

