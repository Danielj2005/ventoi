<?php 
session_start();

require_once "../../../model/modeloPrincipal.php";
require_once "../../../model/rol_model.php";
require_once "../../../model/marca_model.php";

?>
<div class="table">
    <table class="table table-striped datatable mb-3 tableTrademarkOfProducts" id="tableTrademarkOfProducts">
        <thead>
            <tr>
                <th class="col text-center" scope="col">#</th>
                <th class="col text-center" scope="col">Nombre</th>
                <?php if (modeloPrincipal::verificar_permisos_requeridos(['m_marca'])) { ?>
                    <th class="col text-center" scope="col">Estado</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php marca_model::lista(); ?>  
        </tbody>
    </table>
</div>