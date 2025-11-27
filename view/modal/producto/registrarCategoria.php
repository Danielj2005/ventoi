<form id="modalSendForm" action="../controlador/categoria_controller.php" method="post" class="SendFormAjax" autocomplete="off" data-type-form="save">
    <h5 class="card-title">Registrar Categoría</h5>
    <input type="hidden" name="modulo" value="Guardar">          
    <div class="row mb-3 justify-content-center text-start">
        <div class="col-12 mb-3">
            <label class="col-form-label">Nombre <span style="color:#f00;">*</span> </label>
            <input type="text" pattern="[A-Za-zñÑÁÉÍÚÓáéíóú ]{4,30}" required="" placeholder="Ejemplo: Lácteos y Refrigerados" class="form-control" id="input_añadir_categoria" name="nombre_categoria">
        </div>
        
        <div class="col-12 mb-3">
            <label class="col-form-label">Descripción <span style="color:#f00;">*</span> </label>
            <input type="text" pattern="[A-Za-zñÑÁÉÍÚÓáéíóú ]{4,30}" required="" placeholder="Ejemplo: Leche, yogur, queso, mantequilla, huevos, postres fríos." class="form-control" name="descripcion">
        </div>

        <div class="col-12 mb-3 text-start">
            <p class="form-p">Los campos con <span style="color:#f00;">*</span> son obligatorios</p>
        </div>
    </div>
</form>