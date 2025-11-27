<?php 
session_start();

include_once "../../../modelo/modeloPrincipal.php"; // se incluye el modelo principal
include_once "../../../modelo/modelo_usuario.php";  // se incluye el modelo de usuario
include_once "../../../modelo/configuracion_model.php";  // se incluye el modelo de usuario

$id_usuario = $_SESSION['id_usuario'];

$cantidad_perguntas = mysqli_fetch_array(config_model::consultar("c_preguntas"));
$cantidad_perguntas = intval($cantidad_perguntas['c_preguntas']);

?>

<form id="modalSendForm" autocomplete="off" action="../controlador/usuario_controller.php" method="post" class="SendFormAjax" data-type-form="save">

    <input type="hidden" name="modulo" value="modificar_preguntas_seguridad">
    <div class="row ">
        <?php 
            $numero_pregunta = 1;
            for ($i=0; $i < $cantidad_perguntas; $i++) { 

                $preguntas_usuario = modeloPrincipal::consultar("SELECT P.id_pregunta, P.respuesta, S.pregunta
                    FROM preguntas_secretas AS P 
                    INNER JOIN seguridad AS S ON S.id_seguridad = P.id_pregunta 
                    WHERE numero_pregunta = ".$numero_pregunta." AND id_usuario = '$id_usuario'");
                $numero_pregunta++;

                $preguntas_usuario = mysqli_fetch_array($preguntas_usuario);

                $respuesta = modeloPrincipal::decryption($preguntas_usuario['respuesta']);
                $respuesta = modeloPrincipal::limpiar_cadena($respuesta);

                $id_pregunta = $preguntas_usuario['id_pregunta']; 

                $preguntas = modeloPrincipal::consultar("SELECT * FROM seguridad"); 
            ?>

                <div class="col-12 mb-2">
                    <label for="" class="control-label h6" style="font-size: 1em;">
                        Pregunta Nº <?= $i + 1 ?>
                        <span style="color:#f00;">*</span>
                    </label>
                    <select name="pregunta[]" id="select_pregunta" class="form-select">
                        <option value="" disabled="">Selecciona una pregunta </option>
                        <?php
                            while ($row = mysqli_fetch_array($preguntas)) { ?>
                                <option <?= $selected = ($row['id_seguridad'] == $preguntas_usuario['id_pregunta']) ? 'selected' : ''; ?> value="<?= modeloPrincipal::decryption($row['pregunta']); ?>">
                                    <?= modeloPrincipal::decryption($row['pregunta']); ?>
                                </option>
                            
                        <?php } ?>
                    </select>
                </div>
                
                <div class="col-12 mb-2">
                    <div class="text-start col-12">
                        <label>
                            Respuesta Nº <?= $i + 1 ?>
                            <span style="color: red;">*</span>
                        </label>

                        <div class="input-group mb-3">
                            <input 
                                type="password"
                                class="form-control"
                                name="respuesta[]" 
                                id="respuesta<?= $i + 1 ?>"
                                pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,50}$"
                                >
                                
                            <button 
                                id="btnEyeIcon" 
                                type="button" 
                                class="input-group-text btn btn-secondary" 
                                title="Mostrar contraseña"
                                onclick="show_password('eyeIcon', 'respuesta<?= $i + 1 ?>')"
                            >
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>
        <?php } ?>
        <div class="col-12 mb-2">
            <div class="form-group">
                <p class="form-p">Los Campos Con <span style="color:#f00;">*</span> Son Obligatorios</p>
            </div>
        </div>
    </div>
</form>

