<?php

require_once("../../../modelo/modeloPrincipal.php"); 
require_once("../../../modelo/proveedor_model.php"); 

$id_proveedor = modeloPrincipal::decryptionId($_POST["id"]);
$id_proveedor = modeloPrincipal::limpiar_cadena($id_proveedor);

if (!isset($_POST['id'])) {
    alert_model::alerta_simple("¡Ocurrio un error!","No se está recibiendo correctamente el identificador del proveedor","error");
    exit();
}


?>
<div class="row">
    
    <div class="text-center col-12 mb-3 col-auto">
        <form target="_blank" action="./reportes/historial_proveedor.php" method="post">
            <input type="hidden" value="<?= modeloPrincipal::encryptionId($id_proveedor); ?>" name="id_proveedor">
            <button type="submit" class="btn bi bi-file-text btn-primary"> Exportar todas las entradas (compras)</button>
        </form>
    </div>
    <hr>
    <div class="text-center col-12 mb-3 col-auto">
        <label class="dropdown-item">Lista de entradas (compras) Por Fecha</label>
        <form action="./reportes/lista_detalles_entradas_por_fechas.php" method="post" class="p-2 row mb-3" id="" target="_blank">
            <label class="control-label">Desde <span class="text-danger">*</span></label>

            <div class="input-group mb-3 justify-content-center">
                <input class="reportDates form-control" type="date" id="fechaReporteInicio" name="fechaReporteInicio">
            </div>
            <label class="control-label">Hasta <span class="text-danger">*</span></label>

            <div class="input-group mb-3 justify-content-center">
                <input class="reportDates form-control" value="<?= date('Y-m-d') ?>" type="date" id="fechaReporteFin" name="fechaReporteFin">
            </div>
            
            <div class="input-group mb-3 justify-content-center">
                <p class="showThis alert alert-danger d-none" id="mensajefechaReporteInicio" style="width: fit-content;">La fecha de inicio no puede ser mayor a la fecha de fin y ninguno puede ser mayor a la fecha actual.</p>
            </div>
            <div class="col-12 col-sm-12 col-md-12 mb-3 text-center">
                <button type="submit" class="d-none btn btn-outline-success bi bi-file-text" id="btnReportesFechas">&nbsp; Generar Reporte</button>
            </div>
        </form>
    </div>

</div>
