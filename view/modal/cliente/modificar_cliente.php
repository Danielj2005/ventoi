<?php 
session_start();

include_once ("../../../modelo/modeloPrincipal.php"); // se incluye el modelo principal
include_once ("../../../modelo/cliente_model.php");  // se incluye el modelo de usuario
include_once ("../../../modelo/rol_model.php"); // se incluye el modelo principal

$id_usuario = $_SESSION['id_usuario'];

$id_cliente = modeloPrincipal::decryptionId($_POST['id']);
$id_cliente = modeloPrincipal::limpiar_cadena($id_cliente);

$consulta = modeloPrincipal::consultar("SELECT * FROM cliente where id_cliente ='$id_cliente'");
$mostrar = mysqli_fetch_array($consulta);

$cedula = $mostrar['cedula'];

$nacionalidad = $cedula[0].$cedula[1];

$select = strval($cedula[0]);

$cedula = trim($cedula);
$cedula = str_ireplace("V", "", $cedula);
$cedula = str_ireplace("E", "", $cedula);
$cedula = str_ireplace("-", "", $cedula);
$cedula = stripslashes($cedula);
$cedula = trim($cedula);

$m_cliente = modeloPrincipal::verificar_permisos_requeridos(['m_cliente']);
?>
<form id="modalSendForm" action="../controlador/cliente_controller.php" method="post" class="SendFormAjax" autocomplete="off" data-type-form="update">
	<input type="hidden" name="UIC" value="<?= modeloPrincipal::encryptionId($id_cliente); ?>">
    <input type="hidden" name="modulo" value="modificar">
    
    <div class="row mb-3 m-0">
        <div class="col-12 mb-3">
            <label class="form-label">Cédula <span style="color:#f00;">*</span> </label>
            <div class="input-group">
                <select name="nacionalidad" class="form-select-sm col-sm-3 input-group-text" aria-label="Default select example">
                    <option name="nacionalidad" <?= ( $select == 'V') ? 'selected' : '' ?> value="V-">V</option>
                    <option name="nacionalidad" <?= ( $select == 'E') ? 'selected' : '' ?> value="E-">E</option>
                </select>
                <input type="text" class=" <?php ($m_cliente == 1) ? '' : 'bg-dark-subtle' ?> form-control" id="cedula" value="<?= $cedula ?>" <?php ($m_cliente == 1) ? '' : 'readonly' ?> name="cedula">
            </div>
        </div>
        <div class="col-12 mb-3">
            <label class="form-label">Nombre y Apellido <span style="color:#f00;">*</span></label>
            <input type="text" class=" <?php ($m_cliente == 1) ? '' : 'bg-dark-subtle' ?> form-control" value="<?= $mostrar['nombre']; ?>" <?php ($m_cliente == 1) ? '' : 'readonly' ?> name="nombre">
        </div>
        <div class="col-12 mb-3">
            <label class="form-label">Teléfono <span style="color:#f00;">*</span></label>
            <input type="text" class="form-control" value="<?= $mostrar['telefono']; ?>" name="telefono">
        </div>
    </div>
    <div class="col-12 mb-1">
        <div class="form-group">
            <p class="form-p">Los campos con <span style="color:#f00;">*</span> son obligatorios</p>
        </div>
    </div>
</form>