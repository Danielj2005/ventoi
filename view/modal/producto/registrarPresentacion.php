<?php 
session_start();

require_once "../../../modelo/modeloPrincipal.php";
require_once "../../../modelo/presentacion_model.php";

?>
<form id="modalSendForm"  action="../controlador/presentacion.php" method="post" class="SendFormAjax" autocomplete="off" data-type-form="save">
    <h5 class="card-title">Registrar presentación</h5>
    <input type="hidden" name="modulo" value="Guardar">          
    <div class="row mb-3 ">

        <div class="col-12 col-md-12 mb-2">
            <label class="col-form-label">Definir Presentación <span style="color:#f00;">*</span> </label>
            <input 
                type="text" 
                pattern="[0-9.]+" 
                required="" 
                placeholder="Ejemplo: 1, 500, 1.5, etc." 
                class="form-control" 
                id="nombre_presentacion" 
                name="cantidad_presentacion" 
            />
        </div>

        <div class="col-12 col-md-12 mb-2"> 
            <label class="col-form-label">Unidad de Medida<span style="color:#f00;">*</span> </label>
            <select name="representacion" id="representacion" class="form-select">
                <option selected disabled>Seleccione la unidad de medida</option>
                <?php presentacion_model::selectOptions(); ?>
            </select>
        </div>

        <div class="col-12 mb-3 text-start">
            <p class="form-p">Los campos con <span style="color:#f00;">*</span> son obligatorios</p>
        </div>
    </div>
</form>