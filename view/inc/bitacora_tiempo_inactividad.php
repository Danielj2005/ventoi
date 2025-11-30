<?php
session_start();

include_once "../modelo/modeloPrincipal.php";
include_once "../modelo/bitacora_model.php";

$id_usuario = $_SESSION["id_usuario"];

modeloPrincipal::UpdateSQL("usuario", "sesion_activa = '0'", "id_usuario = '$id_usuario'");
bitacora::bitacora("Cierre de sesión exitoso","Se cerró la sesión del usuario debido a que se cumplío el tiempo de inactividad dentro del sistema.");
// se modifica el estado de la sesion activa/inactiva del usuario

session_unset(); // remueve o elimina las variables de sesion
session_destroy(); // Destruye la sesión actual

header("location: ../");