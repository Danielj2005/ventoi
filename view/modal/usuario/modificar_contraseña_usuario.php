<?php 
session_start();

include_once "../../../modelo/modeloPrincipal.php"; // se incluye el modelo principal
include_once "../../../modelo/modelo_usuario.php";  // se incluye el modelo de usuario
include_once "../../../modelo/configuracion_model.php";  // se incluye el modelo de usuario

// se obtiene la configuracion de la base de datos
$configuracion = ['caracteres' => config_model::obtener_dato('c_caracteres'),
    'simbolos' => config_model::obtener_dato('c_simbolos'),
    'numeros' => config_model::obtener_dato('c_numeros')];



?>

<form id="modalSendForm" autocomplete="off" action="../controlador/usuario_controller.php" method="post" class="SendFormAjax" data-type-form="save">
    <input type="hidden" name="modulo" value="modificar_contraseña_usuario">
    
    <div class="row p-2 mb-3">
        <div class="col-12 mb-2">
            <label>
                Contraseña actual
                <span style="color: red; font-size: 20px;"> * </span>
            </label>
            <div class="input-group mb-3">
                <input type="password" required maxlength="16" class="p-2   passw form-control" id="current_password" name="current_password" pattern="[!@#$%A-Za-zÁÉÍÚÓáéíóúñÑ0-9\-]{8,16}" placeholder="ingrese la contraseña actual">
                
                <span class="input-group-text btn btn-secondary bi bi-eye  " id="eyeIcon" onclick="show_password('eyeIcon', 'current_password')"></span>
            </div>
        </div>
    
        <div class="col-12 mb-2">
            <label>
                Contraseña Nueva
                <span style="color: red; font-size: 20px;"> * </span>
            </label>
            <div class="input-group mb-3">
                <input type="password" required maxlength="16" class="p-2   passw form-control" id="password" name="password" pattern="[!@#$%A-Za-zÁÉÍÚÓáéíóúñÑ0-9\-]{8,16}" placeholder="ingrese la contraseña nueva">
    
                <span class="input-group-text btn btn-secondary bi bi-eye  " id="eyeIconNewPass" onclick="show_password('eyeIconNewPass', 'password')"></span>
            </div>
        </div>
    
        <div class="col-12 mb-2">
            <label>
                Repetir Contraseña 
                <span style="color: red; font-size: 20px;"> * </span>
            </label>
    
            <div class="input-group mb-3">
                <input type="password" required maxlength="16" class="p-2   passw form-control" id="password2" name="password2" pattern="[!@#$%A-Za-zÁÉÍÚÓáéíóúñÑ0-9\-]{8,16}" placeholder="repita la contraseña">
                
                <span class="input-group-text btn btn-secondary bi bi-eye  " id="eyeIconRepeatPass" onclick="show_password('eyeIconRepeatPass', 'password2')"></span>
            </div>
        </div>
    
        <div class="form-group label-floating">
            <p class="form-p alert-danger mb-2">los requisitos de seguridad para la  <span style="color:#f00;">contraseña</span> son:</p>
            <ul>
                <li>Puede contener al menos <?= $configuracion['numeros'] ?> número(s).</li>
                <li>Puede contener al menos <?= $configuracion['simbolos'] ?> de estos caracteres: !@#$%.</li>
                <li>Debe tener entre <?= $configuracion['caracteres'] ?> y 60 caracteres.</li>
            </ul>
            <strong class="form-p alert-danger mb-2">Para actualizar la <span style="color:#f00;">Contraseña</span> debes ingresar la contraseña actual.</strong>
            <p class="form-p">Todos los Campos Con <span style="color:#f00;">*</span> Son Obligatorios.</p>
        </div>
    </div>
</form>