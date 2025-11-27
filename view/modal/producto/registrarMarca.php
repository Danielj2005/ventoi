<form id="modalSendForm" action="../controlador/marca.php" method="post" class="SendFormAjax" autocomplete="off" data-type-form="save">
    <h5 class="card-title">Registrar Marca</h5>
    <input type="hidden" name="modulo" value="Guardar">          
    <div class="row mb-3">
        <div class="col-12 mb-3 text-start">
            <label class="col-form-label">Nombre <span style="color:#f00;">*</span> </label>
            <input type="text" pattern="[A-Za-zñÑÁÉÍÚÓáéíóú0-9 ]{3,50}" required="" placeholder="Ejemplo: Coca-Cola" class="form-control" id="nombre_marca" name="nombre_marca">
        </div>

        <div class="col-12 mb-3 text-start">
            <p class="form-p">Los campos con <span style="color:#f00;">*</span> son obligatorios</p>
        </div>
    </div>
</form>