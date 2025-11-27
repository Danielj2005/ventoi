<?php

session_start();

require_once "../../../modelo/modeloPrincipal.php"; 
require_once "../../../modelo/bitacora_model.php"; 

$id = modeloPrincipal::decryptionId($_POST["id"]);
$id = modeloPrincipal::limpiar_cadena($id);

$bitacora = mysqli_fetch_assoc(modeloPrincipal::consultar("SELECT mensaje FROM bitacora WHERE id = $id"));

$mensaje = $bitacora['mensaje'];

?>
<div class="col-12 col-sm-12 col-md-12">
    <?= $mensaje; ?>
</div>