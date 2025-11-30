<?php

require_once "../../../model/modeloPrincipal.php"; 
require_once "../../../model/proveedor_model.php";
require_once "../../../model/alert_model.php"; 

# realizar revision para saber esta donde llega o implementar try catch
if (!isset($_POST['id'])) {
    alert_model::alerta_simple("¡Ocurrio un error!","No se está recibiendo correctamente el identificador del proveedor","error");
    exit();
}

$id_proveedor = modeloPrincipal::decryptionId($_POST["id"]);
$id_proveedor = modeloPrincipal::limpiar_cadena($id_proveedor);

try{
    $datos_proveedor = modeloPrincipal::consultar("SELECT * FROM proveedor WHERE id_proveedor = $id_proveedor");


    if (!$datos_proveedor) {
        alert_model::alerta_simple("¡Ocurrió un error!","ocurrio un error al consultar los datos del proveedor.","error");
    }

} catch (Exception $e) {
    alert_model::alerta_simple("Ocurrido un error!", "No se pudo obtener los datos del proveedor.", "error");
    exit();
}

$datos_proveedor = mysqli_fetch_array($datos_proveedor);

$cedula = $datos_proveedor['cedula_rif'];

$nacionalidad = $cedula[0].$cedula[1];

$select = strval($cedula[0]);

$cedula = trim($cedula);
$cedula = str_ireplace("V", "", $cedula);
$cedula = str_ireplace("E", "", $cedula);
$cedula = str_ireplace("R", "", $cedula);
$cedula = str_ireplace("J", "", $cedula);
$cedula = str_ireplace("-", "", $cedula);
$cedula = stripslashes($cedula);
$cedula = trim($cedula);

?>

<form id="modalSendForm" action="../controlador/proveedor_controller.php" method="post" class="SendFormAjax" data-type-form="update">   
    <div class="row m-0 p-0">

        <input type="hidden" name="id" value="<?= $datos_proveedor['id_proveedor']; ?>">
        <input type="hidden" name="modulo" value="Modificar">

        <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-3">
            <label class="form-label">Cédula o RIF <span style="color:#f00;">*</span> </label>
            <div class="input-group">
                <select name="nacionalidad" class="form-select-sm col-sm-3 input-group-text" aria-label="Default select example">
                    <option name="nacionalidad" <?= ( $select == 'V') ? 'selected' : '' ?> value="V-">V</option>
                    <option name="nacionalidad" <?= ( $select == 'E') ? 'selected' : '' ?> value="E-">E</option>
                    <option name="nacionalidad" <?= ( $select == 'R') ? 'selected' : '' ?> value="R-">RIF</option>
                    <option name="nacionalidad" <?= ( $select == 'J') ? 'selected' : '' ?> value="J-">J</option>
                </select>
                <input type="text" class="form-control" id="cedula" value="<?= $cedula ?>" name="cedula">
            </div>
        </div>

        <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-3">
            <label> Nombre<span style="color: red; font-size: 20px;"> * </span></label>
            <input type="text" maxlength="30" value="<?= $datos_proveedor['nombre'] ?>" class=" form-control" id="nombre" name="nombre" pattern="[A-Za-zÁÉÍÚÓáéíóúñÑ ]{3,30}" placeholder="ingrese el nombre">
        </div>

        <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-3">
            <label> Teléfono <span style="color: red; font-size: 20px;"> * </span></label>
            <input type="text" maxlength="11" value="<?= $datos_proveedor['telefono'] ?>"  class=" form-control telefono" id="telefono" name="telefono" pattern="[0-9]{11}" placeholder="ingrese el teléfono" >
        </div>

        <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-3">
            <label> Correo <span style="color: red; font-size: 20px;"> * </span></label>
            <input type="email" maxlength="30" value="<?= $datos_proveedor['correo'] ?>"  class=" form-control correo" id="correo" name="correo" placeholder="ingrese el correo" >
        </div>

        <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-3">
            <label> Dirrección <span style="color: red; font-size: 20px;"> * </span></label>
            <input type="text" maxlength="30" value="<?= $datos_proveedor['direccion'] ?>"  class=" form-control" id="direccion" name="direccion" pattern="[A-Za-zÁÉÍÚÓáéíóúñÑ0-9\-\. ]{5,70}" placeholder="ingrese la dirección">
        </div>

        <div class="col-12 mb-3">
            <div class="form-group col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-3">
                <p> Los campos con <span style="color: red; font-size: 20px;"> * </span> son obligatorios </p>
            </div>
        </div>
    </div>
</form>