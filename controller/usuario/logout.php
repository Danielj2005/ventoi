<?php
/*------- configuración y conexión a base de datos -------*/
//iniciamos la sesion 
session_start();

include_once "../../model/modeloPrincipal.php";
include_once "../../model/modelo_usuario.php";
include_once "../../model/bitacora_model.php";

$id_usuario = $_SESSION['id_usuario'];

//registramos los movimientos en la bitacora
bitacora::bitacora(
    "Cierre de sesión exitoso", 
'<p class="h2 mb-3 text-primary-emphasis text-center"><i class="bi bi-exclamation-circle-fill"></i>&nbsp;El usuario ha cerrado sesión correctamente.</p>');

// se modifica el estado de la sesion activa/inactiva del usuario
modeloPrincipal::UpdateSQL(
    "usuario", 
    "sesion_activa = '0'", 
    "id_usuario = '$id_usuario'");

session_unset(); // remueve o elimina las variables de sesion
session_destroy(); // Destruye la sesión actual

header("location: ../../login");
