<?php 
session_start();

require_once "../../../modelo/modeloPrincipal.php";
require_once "../../../modelo/productos_model.php";
require_once "../../../modelo/presentacion_model.php";
require_once "../../../modelo/categoria_model.php";
require_once "../../../modelo/marca_model.php";

?>

<form id="SendForm" action="../controlador/producto_controlador.php" method="post" class="SendFormAjax row" autocomplete="off" data-type-form="save">
    <input type="hidden" name="modulo" value="Guardar">
    <input type="hidden" name="vista" value="1">
    <div class="col-12 mb-3">
        <label class="col-form-label">Nombre del Producto <span style="color:#f00;"> *</span></label>
        <input type="text" class="form-control mb-3" list="datalist_nombre_productos" name="nombre_producto[]" id="input_nombre_producto2" placeholder="Escribe el nombre del producto" autocomplete="off">
        
        <datalist id="datalist_nombre_productos">
            <?php producto_model::options_nombres_productos(); ?> 
        </datalist>
    </div>

    <!-- selector de Marca  -->
    <div class="col-12 mb-3">
        <label class="col-form-label">Seleccione una Marca <span style="color:#f00;"> * </span> </label>
        <input type="text" class="form-control mb-3" list="datalist_marca" name="marcas[]" id="input_nombre_marca" placeholder="Seleccione una Marca" autocomplete="off">
        <datalist id="datalist_marca">
            <?php marca_model::options(); ?>
        </datalist>
    </div>

    <!-- selector de presentacion  -->
    <div class="col-12 mb-3">
        <label class="col-form-label">Seleccione una Presentación <span style="color:#f00;"> * </span> </label>
        <input type="text" class="form-control mb-3" list="datalist_nombre_presentacion" name="presentacion[]" id="input_nombre_presentacion" placeholder="Seleccione una Presentación" autocomplete="off">
        <datalist id="datalist_nombre_presentacion">
            <?php presentacion_model::options(); ?>
        </datalist>
    </div>

    <!-- selector de categoría   -->
    <div class="col-12 mb-3">
        <label class="col-form-label">Seleccione una Categoría <span style="color:#f00;"> * </span> </label>
        <input type="text" class="form-control mb-3" list="datalist_nombre_categoria" name="categoria[]" id="input_nombre_categoria" placeholder="Seleccione una Categoría" autocomplete="off">
        <datalist id="datalist_nombre_categoria">
            <?php category_model::options(); ?>
        </datalist>
    </div>
    <div class="col-12 mb-1">
        <div class="form-group">
            <p class="form-p">Los campos con <span style="color:#f00;">*</span> son obligatorios</p>
        </div>
    </div>
</form>