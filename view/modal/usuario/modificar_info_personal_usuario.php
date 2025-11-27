<?php 
session_start();

include_once "../../../modelo/modeloPrincipal.php"; // se incluye el modelo principal
include_once "../../../modelo/modelo_usuario.php";  // se incluye el modelo de usuario

$id_usuario = $_SESSION['id_usuario'];
$cedula = $_SESSION['dataUsuario']['dni'];
$nombre = $_SESSION['dataUsuario']['nombre'];
$apellido = $_SESSION['dataUsuario']['apellido'];
$correo = $_SESSION['dataUsuario']['correo'];
$telefono = $_SESSION['dataUsuario']['telefono'];
$direccion = $_SESSION['dataUsuario']['direccion'];

$nacionalidad = $cedula[0].$cedula[1];
$select = strval($cedula[0]);

$cedula = trim($cedula);
$cedula = str_ireplace("V", "", $cedula);
$cedula = str_ireplace("E", "", $cedula);
$cedula = str_ireplace("-", "", $cedula);
$cedula = stripslashes($cedula);
$cedula = trim($cedula);

?>


<form id="modalSendForm" autocomplete="off" action="../controlador/usuario_controller.php" method="post" class="SendFormAjax" data-type-form="save">
    
    <fieldset class="row mb-3">
        <input type="hidden" name="modulo" value="modificar_info_personal_usuario">

        <div class="col-12 mb-3">
            <div class="form-group">
                <label class="control-label">Cédula  <span style="color:#f00;">*</span></label>
                <div class="input-group">
                <select name="nacionalidad" class="form-select-sm col-sm-3 input-group-text" aria-label="Default select example">
                    <option name="nacionalidad" <?= ( $select == 'V') ? 'selected' : '' ?> value="V-">V</option>
                    <option name="nacionalidad" <?= ( $select == 'E') ? 'selected' : '' ?> value="E-">E</option>
                </select>
                <input class="form-control" required pattern="[0-9]{7,8}" type="text" value="<?= $cedula ?>" name="cedula" id="cedula" maxlength="8" placeholder="Ingrese la Cédula">
                </div>
            </div>
        </div>

        <div class="col-12 mb-3">
            <div class="form-group">
                <label class="control-label">Nombres  <span style="color:#f00;">*</span></label>
                <input type="text" pattern="[A-Za-zÁÉÍÚÓáéíóúñÑ ]{3,30}" class="form-control" value="<?= $nombre; ?>" id="nombres" name="nombres" maxlength="50">
            </div>
        </div>

        <div class="col-12 mb-3">
            <div class="form-group">
                <label class="control-label text-black">Apellidos  <span style="color:#f00;">*</span></label>
                <input type="text" pattern="[A-Za-zÁÉÍÚÓáéíóúñÑ ]{3,30}" class="form-control" value="<?= $apellido; ?>" id="apellido" name="apellido" maxlength="50">
            </div>
        </div>

        <div class="col-12 mb-3">
            <div class="form-group">
                <label class="control-label">Correo  <span style="color:#f00;">*</span></label>
                <input type="email" pattern="[A-Za-zÁÉÍÚÓáéíóúñÑ\@\.\0-9]{3,30}" class="form-control" value="<?= $correo; ?>" id="email" name="email" maxlength="150">
            </div>
        </div>

        <div class="col-12 mb-3">
            <div class="form-group">
                <label class="control-label text-black">Teléfono <span style="color:#f00;">*</span></label>
                <input type="text" pattern="[0-9]{11}" class="form-control" value="<?= $telefono; ?>" id="telefono" name="telefono" maxlength="11">
            </div>
        </div>

        <div class="col-12 mb-3">
            <div class="form-group">
                <label class="control-label">Dirección <span style="color:#f00;">*</span></label>
                <input type="text" maxlength="250" required="" placeholder="Ingrese la Dirección" value="<?= $direccion; ?>" class="form-control" id="direccion" name="direccion">
            </div>
        </div>

        <div class="col-12 mb-3">
            <div class="form-group">
                <p class="form-p">Los Campos Con <span style="color:#f00;">*</span> Son Obligatorios</p>
            </div>
        </div>
    </fieldset>

</form>